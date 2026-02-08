<?php
session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";
require_once __DIR__ . '/../vendor/autoload.php';

use Stripe\Checkout\Session as CheckoutSession;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Stripe\Webhook;

class SaleOnlineStripe
{
    private array $config;

    public function __construct()
    {
        $this->config = include dirname(__DIR__) . "/Config/stripe.php";
        if (empty($this->config['secret_key']) || $this->config['secret_key'] === 'sk_test_your_key') {
            throw new RuntimeException("Configura STRIPE_SECRET en Config/stripe.php o variables de entorno.");
        }
        Stripe::setApiKey($this->config['secret_key']);
    }

    public function buy(array $data): array
    {
        $ticketMeta = $this->prepareTicketData($data);
        $ticketId = $this->insertTicket($ticketMeta);

        $session = $this->createCheckoutSession($data, $ticketId, $ticketMeta);

        return [
            "url" => $session->url,
            "ticket_id" => $ticketId,
            "session_id" => $session->id
        ];
    }

    private function prepareTicketData(array $data): array
    {
        $origin      = $data['origin'] ?? '';
        $destination = $data['destination'] ?? '';
        $quantity    = max(1, (int)($data['quantity'] ?? 1));
        $date        = $data['date'] ?? date('Y-m-d');
        $scheduleId  = $data['schedule'] ?? null;
        $price       = (float)($data['price'] ?? 0);

        if (!$origin || !$destination || !$scheduleId || $price <= 0) {
            throw new InvalidArgumentException("Datos insuficientes para generar el boleto.");
        }

        $branchOfficeId = $this->config['online_branch_office_id'] ?? null;
        $userId         = $this->config['online_user_id'] ?? null;

        if (!$branchOfficeId || !$userId) {
            throw new RuntimeException("Configura la taquilla y usuario para ventas en línea desde el panel de configuración.");
        }

        $hour            = date("H:i:s");
        $expiration_date = date('Y-m-d', strtotime($date . ' +1 day'));

        $sql = "SELECT routes_stop.id AS routes_stop_id, routes_schedule.route_id, routes_schedule.vehicle_id
                FROM routes_stop
                INNER JOIN routes_schedule ON routes_schedule.route_id = routes_stop.route_id
                WHERE routes_stop.origin='$origin'
                AND routes_stop.destination='$destination'
                AND routes_schedule.id='$scheduleId'";
        $result = ejecutarConsulta($sql);

        if (!$result || !$result->num_rows) {
            throw new RuntimeException("No se encontro informacion de ruta para el horario seleccionado.");
        }

        $routeData = $result->fetch_assoc();

        return [
            'route_schedule_id' => $scheduleId,
            'route_id'         => $routeData['route_id'],
            'employee_id'      => 1,
            'vehicle_id'       => $routeData['vehicle_id'],
            'routes_stop_id'   => $routeData['routes_stop_id'],
            'branch_office_id' => $branchOfficeId,
            'user_id'          => $userId,
            'quantity'         => $quantity,
            'payment_method'   => 'STRIPE',
            'price'            => $price,
            'status'           => 'PENDIENTE',
            'date'             => $date,
            'hour'             => $hour,
            'expires_at'       => $expiration_date
        ];
    }

    private function insertTicket(array $ticketMeta): int
    {
        $sql = "INSERT INTO tickets (
                    route_schedule_id,
                    route_id,
                    employee_id,
                    vehicle_id,
                    branch_office_id,
                    user_id,
                    route_stop_id,
                    quantity,
                    payment_method,
                    price,
                    status,
                    date,
                    hour,
                    expires_at,
                    created_at,
                    updated_at
                ) VALUES (
                    '{$ticketMeta['route_schedule_id']}',
                    '{$ticketMeta['route_id']}',
                    '{$ticketMeta['employee_id']}',
                    '{$ticketMeta['vehicle_id']}',
                    '{$ticketMeta['branch_office_id']}',
                    '{$ticketMeta['user_id']}',
                    '{$ticketMeta['routes_stop_id']}',
                    '{$ticketMeta['quantity']}',
                    '{$ticketMeta['payment_method']}',
                    '{$ticketMeta['price']}',
                    '{$ticketMeta['status']}',
                    '{$ticketMeta['date']}',
                    '{$ticketMeta['hour']}',
                    '{$ticketMeta['expires_at']}',
                    NOW(),
                    NOW()
                )";

        $result = ejecutarConsulta($sql);
        if (!$result) {
            throw new RuntimeException("No fue posible registrar el boleto.");
        }

        global $conexion;
        return (int)mysqli_insert_id($conexion);
    }

    private function createCheckoutSession(array $data, int $ticketId, array $ticketMeta)
    {
        $origin      = $data['origin'];
        $destination = $data['destination'];
        $quantity    = max(1, (int)$ticketMeta['quantity']);
        $unitAmount  = (int)round($ticketMeta['price'] * 100);
        $amountTotal = $unitAmount * $quantity;

        $successUrl = $this->buildUrl($this->config['success_url'] ?? '', $ticketId);
        $cancelUrl  = $this->buildUrl($this->config['cancel_url'] ?? '', $ticketId);

        $params = [
            'mode' => 'payment',
            'client_reference_id' => (string)$ticketId,
            'metadata' => ['ticket_id' => $ticketId],
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'line_items' => [[
                'quantity' => $quantity,
                'price_data' => [
                    'currency' => 'mxn',
                    'unit_amount' => $unitAmount,
                    'product_data' => [
                        'name' => "Boleto {$origin} -> {$destination}",
                        'metadata' => [
                            'origin' => $origin,
                            'destination' => $destination,
                            'ticket_id' => $ticketId
                        ]
                    ]
                ]
            ]]
        ];

        $paymentIntentData = [
            'metadata' => ['ticket_id' => $ticketId]
        ];

        if (!empty($this->config['connect_account_id'])) {
            $paymentIntentData['transfer_data'] = [
                'destination' => $this->config['connect_account_id']
            ];
        }

        $fee = $this->calculateFeeAmount($amountTotal);
        if ($fee > 0 && !empty($this->config['connect_account_id'])) {
            $paymentIntentData['application_fee_amount'] = $fee;
        }

        if (!empty($paymentIntentData['transfer_data']) || !empty($paymentIntentData['application_fee_amount'])) {
            $params['payment_intent_data'] = $paymentIntentData;
        }

        try {
            return CheckoutSession::create($params);
        } catch (ApiErrorException $e) {
            throw new RuntimeException('Error al crear sesion de Checkout: ' . $e->getMessage());
        }
    }

    private function buildUrl(string $template, int $ticketId): string
    {
        if (!$template) {
            $base = $this->detectBaseUrl();
            return rtrim($base, '/') . '/Views/stripe-sales-success.php?ticket_id=' . $ticketId;
        }

        return str_replace('{TICKET_ID}', $ticketId, $template);
    }

    private function detectBaseUrl(): string
    {
        $scheme = 'http';
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            $scheme = 'https';
        }
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '/');
        $basePath = dirname($scriptDir);
        if ($basePath === '.' || $basePath === DIRECTORY_SEPARATOR) {
            $basePath = '';
        }
        return $scheme . '://' . $host . $basePath;
    }

    private function calculateFeeAmount(int $amountTotal): int
    {
        $percent = (float)($this->config['platform_fee_percent'] ?? 0);
        $fixed   = (float)($this->config['platform_fee_fixed'] ?? 0);

        $fee = 0;
        if ($percent > 0) {
            $fee += ($amountTotal * ($percent / 100));
        }
        if ($fixed > 0) {
            $fee += ($fixed * 100);
        }

        return (int)round($fee);
    }

    public function schedules($data)
    {
        $hour        = date("H:i:s");
        $today       = date("Y-m-d");
        $origin      = $data['origin'];
        $destination = $data['destination'];
        $date        = $data['date'] ?? $today;

        $sql = "SELECT 
                    routes_schedule.id AS schedule_id,
                    routes_schedule.leaving_time,
                    routes_stop.price_online AS price,
                    (SELECT COUNT(id) FROM tickets WHERE tickets.route_schedule_id = routes_schedule.id AND tickets.status IN ('VENDIDO')) AS tickets_sale,
                    (SELECT capacity FROM vehicles WHERE vehicles.id = routes_schedule.vehicle_id) AS capacity
                FROM routes_schedule
                INNER JOIN routes_stop ON routes_stop.route_id = routes_schedule.route_id
                WHERE routes_stop.origin = '$origin'
                AND routes_stop.destination = '$destination'
                AND routes_stop.active_online = 1 ";

        if ($date == $today) {
            $sql .= " AND routes_schedule.date = '$date' AND routes_schedule.leaving_time >= '$hour'";
        } elseif ($date > $today) {
            $sql .= " AND routes_schedule.date = '$date'";
        } else {
            $sql .= " AND 1=0";
        }

        $sql .= " HAVING tickets_sale < capacity";

        return ejecutarConsulta($sql);
    }

    public function show_subpaths($data)
    {
        $origin = $data['origin'] ?? null;
        $sql = "SELECT * FROM routes_stop WHERE origin='$origin' AND deleted_at IS NULL";
        return ejecutarConsulta($sql);
    }

    public function handleWebhook(): void
    {
        $payload   = file_get_contents('php://input');
        $signature = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
        $secret    = $this->config['webhook_secret'] ?? '';
        $logFile   = __DIR__ . '/stripe_webhook.log';

        file_put_contents($logFile, '[' . date('Y-m-d H:i:s') . "] Payload:\n" . $payload . "\n", FILE_APPEND);

        if (empty($signature) && function_exists('getallheaders')) {
            $headers = getallheaders();
            foreach ($headers as $key => $value) {
                if (strtolower($key) === 'stripe-signature') {
                    $signature = $value;
                    break;
                }
            }
        }

        try {
            if ($secret) {
                $event = Webhook::constructEvent($payload, $signature, $secret);
            } else {
                $event = json_decode($payload);
            }
        } catch (Throwable $e) {
            file_put_contents($logFile, '[' . date('Y-m-d H:i:s') . '] Error de firma: ' . $e->getMessage() . "\n", FILE_APPEND);
            http_response_code(400);
            echo 'Invalid payload';
            return;
        }

        $type = $event->type ?? ($event['type'] ?? null);
        $object = $event->data->object ?? $event['data']['object'] ?? null;

        if ($type === 'checkout.session.completed') {
            $ticketId = $object->client_reference_id ?? ($object->metadata->ticket_id ?? null);
            if ($ticketId) {
                $status = ($object->payment_status === 'paid') ? 'VENDIDO' : 'PENDIENTE';
                $this->updateTicketStatus($ticketId, $status);
            }
        } elseif ($type === 'checkout.session.expired') {
            $ticketId = $object->client_reference_id ?? null;
            if ($ticketId) {
                $this->updateTicketStatus($ticketId, 'CANCELADO');
            }
        } elseif ($type === 'payment_intent.payment_failed') {
            $ticketId = $object->metadata->ticket_id ?? null;
            if ($ticketId) {
                $this->updateTicketStatus($ticketId, 'RECHAZADO');
            }
        }

        http_response_code(200);
        echo 'OK';
    }

    private function updateTicketStatus(int $ticketId, string $status): void
    {
        $sql = "UPDATE tickets SET status='$status', updated_at = NOW() WHERE id='$ticketId'";
        ejecutarConsulta($sql);
    }

    public function getSessionStatus(string $sessionId): array
    {
        try {
            $session = CheckoutSession::retrieve($sessionId, []);
            return [
                'status' => $session->payment_status,
                'amount_total' => $session->amount_total / 100,
                'currency' => strtoupper($session->currency ?? 'MXN'),
                'ticket_id' => $session->client_reference_id,
                'url' => $session->url
            ];
        } catch (ApiErrorException $e) {
            throw new RuntimeException('No se pudo obtener la sesion: ' . $e->getMessage());
        }
    }
}
