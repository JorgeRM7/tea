<?php 
require_once "../Models/SaleOnlineStripe.php";
$SaleOnline = new SaleOnlineStripe();

switch ($_GET["op"]) {
    case 'buy':
        try {
            $rspta = $SaleOnline->buy($_POST);
            echo json_encode([
                "success"    => true,
                "url"        => $rspta['url'],
                "session_id" => $rspta['session_id'],
                "ticket_id"  => $rspta['ticket_id']
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "error"   => $e->getMessage()
            ]);
        }
    break;

    case 'webhook':
        $SaleOnline->handleWebhook();
    break;

    case 'schedules':
        $rspta = $SaleOnline->schedules($_GET);
        $data = [];
        while ($reg = $rspta->fetch_assoc()) {
            $data[] = [
                "schedule_id" => $reg['schedule_id'],
                "price"       => $reg['price'],
                "leaving_time"=> $reg['leaving_time'],
                "tickets_sale"=> $reg['tickets_sale'],
                "capacity"    => $reg['capacity'],
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
                "destination"    => $reg['destination'],
                "price"          => $reg['price'],
            ];
        }
        echo json_encode($data);
    break;

    case 'session-status':
        try {
            $sessionId = $_GET['session_id'] ?? '';
            $session = $SaleOnline->getSessionStatus($sessionId);
            echo json_encode(["success" => true, "session" => $session]);
        } catch (Exception $e) {
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
    break;
}
