<?php 
require_once "../Middlewares/authMiddleware.php";
$userData = verificarToken();
require_once "../Models/SaleOnline.php";
$SaleOnline = new SaleOnline();


switch ($_GET["op"]) {
   
    case 'buy':
        $rspta = $SaleOnline->buy ( $_POST );
        echo json_encode([
            "success" => true,
            "ids" => $rspta
        ]);
    break;

    case 'webhook':
        $body = file_get_contents("php://input");
        $data = json_decode($body, true);

        if (isset($data["type"]) && $data["type"] === "payment") {
            $paymentId = $data["data"]["id"];

            $client = new \MercadoPago\Client\Payment\PaymentClient();
            $payment = $client->get($paymentId);

            if ($payment->status === "approved") {
                $ticketId = $payment->external_reference;
                $amount   = $payment->transaction_amount;
                $payer    = $payment->payer->email;

                $rspta = $SaleOnline->store([
                    "ticket_id"   => $ticketId,
                    "amount"      => $amount,
                    "payer_email" => $payer,
                    "status"      => "approved",
                    "origin"      => $origin ?? null,
                    "destination" => $destination ?? null
                ]);

                echo json_encode(["success" => true, "data" => $rspta]);
            }
        }

        http_response_code(200);
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
