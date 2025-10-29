<?php 
require_once "../Middlewares/authMiddleware.php";
$userData = verificarToken();
require_once "../Models/SaleOnline.php";
$SaleOnline = new SaleOnline();


switch ($_GET["op"]) {
   
    // case 'buy':
    //     $rspta = $SaleOnline->buy ( $_POST );
    //     echo json_encode([
    //         "success" => true,
    //         "ids" => $rspta
    //     ]);
    // break;

    case 'buy':
        try {
            $rspta = $SaleOnline->buy($_POST);
            echo json_encode([
                "success" => true,
                "url" => $rspta['url'],
                // "ticket_id" => $rspta['ticket_id']
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "error" => $e->getMessage()
            ]);
        }
    break;



    case 'webhook':
        // 1. Capturar el cuerpo crudo
        $rawInput = file_get_contents("php://input");
        $body = json_decode($rawInput, true);

        // 2. Guardar log en archivo
        $logFile = __DIR__ . "/../logs/mercadopago_webhook.log";
        $logEntry = "[" . date("Y-m-d H:i:s") . "] Webhook recibido:\n" . $rawInput . "\n\n";
        file_put_contents($logFile, $logEntry, FILE_APPEND);

        // 3. Validar y procesar
        if (isset($body['data']['id'])) {
            try {
                $paymentId = $body['data']['id'];

                // Consultar el pago con el SDK
                $client = new \MercadoPago\Client\Payment\PaymentClient();
                $payment = $client->get($paymentId);

                $status = $payment->status;                // approved, rejected, pending
                $externalRef = $payment->external_reference; // tu ticket_id

                // Registrar también en el log lo que se obtuvo del API
                $logApi = "[" . date("Y-m-d H:i:s") . "] Pago consultado: ID={$paymentId}, Status={$status}, Ref={$externalRef}\n";
                file_put_contents($logFile, $logApi, FILE_APPEND);

                if ($status === 'approved') {
                    $sql = "UPDATE tickets SET status='VENDIDO' WHERE id='$externalRef'";
                    ejecutarConsulta($sql);
                } elseif ($status === 'rejected') {
                    $sql = "UPDATE tickets SET status='RECHAZADO' WHERE id='$externalRef'";
                    ejecutarConsulta($sql);
                } else {
                    // Si quieres manejar pendiente explícitamente
                    $sql = "UPDATE tickets SET status='PENDIENTE' WHERE id='$externalRef'";
                    ejecutarConsulta($sql);
                }
            } catch (Exception $e) {
                $logError = "[" . date("Y-m-d H:i:s") . "] ERROR al procesar pago: " . $e->getMessage() . "\n";
                file_put_contents($logFile, $logError, FILE_APPEND);
            }
        } else {
            $logEmpty = "[" . date("Y-m-d H:i:s") . "] Webhook sin data válida: " . $rawInput . "\n";
            file_put_contents($logFile, $logEmpty, FILE_APPEND);
        }

        // 4. Responder a MP
        http_response_code(200);
        echo "OK";
    break;




    case 'schedules':
        $rspta = $SaleOnline->schedules($_GET);
        $data = [];
        while ($reg = $rspta->fetch_assoc()) {
            $data[] = [
                "schedule_id" => $reg['schedule_id'],
                "price" => $reg['price'],
                "leaving_time" => $reg['leaving_time'],
            ];
        }

        echo json_encode($data);
    break;


    case 'show-subpaths':
        $rspta = $SaleOnline->show_subpaths($_POST);
        $data = [];
        while ($reg = $rspta->fetch_assoc()) {
            $data[] = [
                "routes_stop_id" => $reg['id'],
                "destination" => $reg['destination'],
                "price" => $reg['price'],
            ];
        }
        echo json_encode($data);
    break;

}
?>
