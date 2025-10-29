<?php
session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";
require_once __DIR__ . '/../vendor/autoload.php';

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;


class SaleOnline
{

    private $config;
    public function __construct(){
        $this->config = include dirname(__DIR__) . "/Config/config.php";
        MercadoPagoConfig::setAccessToken($this->config['mp_access_token']);
    }

    public function buy($data) {
        if (!class_exists(MercadoPagoConfig::class)) {
            throw new \RuntimeException("MercadoPago SDK no está cargado (autoload.php).");
        }
        if (empty($this->config['mp_access_token'])) {
            throw new \RuntimeException("mp_access_token vacío en Config/config.php");
        }
        MercadoPagoConfig::setAccessToken($this->config['mp_access_token']);

        $ticket_id   = random_int(1000, 9999);
        $origin      = $data['origin'] ?? 'Morelia';
        $destination = $data['destination'] ?? 'Angamacutiro';
        $price       = (float)($data['price'] ?? 120.00);
        $quantity    = (int)($data['quantity'] ?? 1);
        $date = $data['date'];
        $hour  = date("H:i:s");  
        $expiration_date = date('Y-m-d', strtotime($date . ' +1 day'));
        $route_schedule_id = $data['schedule'];


        $sql_fields ="
            SELECT 
                routes_stop.id AS routes_stop_id,
                routes_schedule.route_id,
                routes_schedule.vehicle_id
            FROM `routes_stop`
            INNER JOIN routes_schedule ON routes_schedule.route_id =routes_stop.route_id
            WHERE routes_stop.origin='$origin' AND routes_stop.destination='$destination' AND routes_schedule.id='$route_schedule_id'
        ";
        $result_fields = ejecutarConsulta($sql_fields);
        if ( $result_fields ) {
            while ($item = mysqli_fetch_assoc($result_fields)) {
                $route_id = $item['route_id'];
                $employee_id = 1;
                $vehicle_id = $item['vehicle_id'];
                $routes_stop_id = $item['routes_stop_id'];
            }
        } 

        $sql = "
            INSERT INTO `tickets`(
                `route_schedule_id`,
                `route_id`,
                `employee_id`,
                `vehicle_id`,
                `route_stop_id`,
                `quantity`, 
                `payment_method`,
                `price`,
                `status`, 
                `date`,
                `hour`,
                `expires_at`,
                `created_at`, 
                `updated_at`
            ) VALUES (
                '$route_schedule_id',
                '$route_id',
                '$employee_id',
                '$vehicle_id',
                '$routes_stop_id',
                '1',
                'TARJETA',
                '$price',
                'PENDIENTE',
                '$date',
                '$hour',
                '$expiration_date',
                NOW(),
                NOW()
            )
        ";
        $result = ejecutarConsulta($sql);

        if ( $result ) {
            global $conexion;
            $ticket_id = mysqli_insert_id($conexion);
        }

        $client = new PreferenceClient();
        $pref = $client->create([
            "items" => [[
                "title"       => "Boleto {$origin} → {$destination}",
                "quantity"    => $quantity,
                "unit_price"  => $price,
                "currency_id" => "MXN",
            ]],
            "back_urls" => [
                "success" => "localhost/tea/Views/sales-online-success.php?ticket_id={$ticket_id}",
                "failure" => "localhost/tea/Views/sales-online-failure.php?ticket_id={$ticket_id}",
                "pending" => "localhost/tea/Views/sales-online-pending.php?ticket_id={$ticket_id}",
            ],
            "auto_return" => "approved",
            "external_reference" => "ticket_{$ticket_id}",
            "notification_url" => "https://tea.digitalenigma.mx/Controllers/salesOnlineController.php?op=webhook"
        ]);

        

        return [
            "url"       => $pref->init_point,
            "ticket_id" => $ticket_id,
        ];
    }



    public function schedules($data) {
        $hour     = date("H:i:s"); 
        $today    = date("Y-m-d");
        $origin   = $data['origin'];
        $destination = $data['destination'];
        $date     = $data['date'] ?? '2025-10-24';

        $sql = "SELECT 
                    routes_schedule.id AS schedule_id,
                    routes_schedule.leaving_time,
                    routes_stop.price
                FROM routes_schedule routes_schedule
                INNER JOIN routes_stop ON routes_stop.route_id = routes_schedule.route_id
                WHERE routes_stop.origin = '$origin' 
                AND routes_stop.destination = '$destination'";

        if ($date == $today) {
            $sql .= " AND routes_schedule.date = '$date' AND routes_schedule.leaving_time >= '$hour'";
        } elseif ($date > $today) {
            $sql .= " AND routes_schedule.date = '$date'";
        } else {
            $sql .= " AND 1=0";
        }

        return ejecutarConsulta($sql);
    }

    public function show_subpaths ( $data ){
        $origin = $data['origin'] ?? null;
        $sql = "SELECT * FROM `routes_stop` WHERE origin='$origin' AND deleted_at is null";
        return ejecutarConsulta($sql);
    }

    public function update_payment(){
        $rawInput = file_get_contents("php://input");
        $body = json_decode($rawInput, true);

        $logFile = __DIR__ . "/mercadopago_webhook.log";
        $logEntry = "[" . date("Y-m-d H:i:s") . "] Webhook recibido:\n" . $rawInput . "\n\n";
        file_put_contents($logFile, $logEntry, FILE_APPEND);

        
        if (isset($body['data']['id'])) {
            try {
                $paymentId = $body['data']['id'];

                $client = new \MercadoPago\Client\Payment\PaymentClient();
                $payment = $client->get($paymentId);

                $status = $payment->status;
                $externalRef = $payment->external_reference;
                $externalRef = $payment->external_reference;
                $ticketId = str_replace("ticket_", "", $externalRef);

                if ($status === 'approved') {
                    $sql = "UPDATE tickets SET status='VENDIDO' WHERE id='$ticketId'";
                    
                } elseif ($status === 'rejected') {
                    $sql = "UPDATE tickets SET status='RECHAZADO' WHERE id='$ticketId'";
                } else {
                    $sql = "UPDATE tickets SET status='PENDIENTE' WHERE id='$ticketId'";
                }

                $logApi = "[" . date("Y-m-d H:i:s") . "] Pago consultado: ID={$paymentId}, Status={$status}, Ref={$externalRef}, sql={$sql}\n" ;
                file_put_contents($logFile, $logApi, FILE_APPEND);

                ejecutarConsulta($sql);

            } catch (\Exception $e) {
                $logError = "[" . date("Y-m-d H:i:s") . "] ERROR al procesar pago: " . $e->getMessage() . "\n";
                file_put_contents($logFile, $logError, FILE_APPEND);
            }
        } else {
            $logEmpty = "[" . date("Y-m-d H:i:s") . "] Webhook sin data válida: " . $rawInput . "\n";
            file_put_contents($logFile, $logEmpty, FILE_APPEND);
        }
        
        http_response_code(200);
        echo "OK";
    }


    

}
?>