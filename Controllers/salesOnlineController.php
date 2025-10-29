<?php 
// require_once "../Middlewares/authMiddleware.php";
// $userData = verificarToken();
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
        $SaleOnline->update_payment();
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
