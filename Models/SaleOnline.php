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
        $origin      = 'Morelia';
        $destination = 'Angamacutiro';
        $price       = (float)120.00;
        $quantity    = (int)1;

        $client = new PreferenceClient();

        try {
            $pref = $client->create([
                "items" => [[
                    "title"       => "Boleto {$origin} → {$destination}",
                    "quantity"    => $quantity,
                    "unit_price"  => $price,
                    "currency_id" => "MXN",
                ]],
                // Para Bricks puedes omitir back_urls; si quieres dejarlas, déjalas sin auto_return
                // "back_urls" => [
                //   "success" => "https://tu-dominio/Views/success.php?ticket_id={$ticket_id}",
                //   "failure" => "https://tu-dominio/Views/failure.php?ticket_id={$ticket_id}",
                //   "pending" => "https://tu-dominio/Views/pending.php?ticket_id={$ticket_id}",
                // ],
                // SIN auto_return
                "external_reference" => "ticket_{$ticket_id}",
                "notification_url" => "https://tea.digitalenigma.mx/Controllers/salesOnlineController.php?op=webhook"
            ]);

            return [
                "id"        => $pref->id,
                "ticket_id" => $ticket_id,
            ];

        } catch (MPApiException $e) {
            $resp = $e->getApiResponse();
            $status = method_exists($resp,'getStatus') ? $resp->getStatus() : (method_exists($resp,'getStatusCode') ? $resp->getStatusCode() : null);
            $rawBody = method_exists($resp,'getContent') ? $resp->getContent() : (method_exists($resp,'getBody') ? $resp->getBody() : null);
            $bodyArr = is_string($rawBody) ? json_decode($rawBody, true) : (is_array($rawBody) ? $rawBody : null);
            $msg = $bodyArr['message'] ?? $bodyArr['error'] ?? $e->getMessage();
            throw new \RuntimeException("MercadoPago API error (" . ($status ?? 400) . "): " . $msg, (int)($status ?? 400));
        } catch (\Throwable $e) {
            throw new \RuntimeException("Error inesperado: ".$e->getMessage(), 500);
        }
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

    

}
?>