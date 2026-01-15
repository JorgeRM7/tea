<?php 
// require_once "../Middlewares/authMiddleware.php";
// $userData = verificarToken();
require_once "../Models/CalendarRouteSchedule.php";
$Calendar = new Calendar();



switch ($_GET["op"]) {
    case 'store': 
        $rspta = $Calendar->store($_POST);
        echo json_encode($rspta);
    break;

    case 'routes':
        $rspta = $Calendar->routes($_POST);
        $data = [];
        while ($reg = $rspta->fetch_assoc()) {
            $data[] = [
                "route_schedule_id" => $reg['route_id'],
                "leaving_time" => $reg['leaving_time'],
                "vehicle_id" => $reg['vehicle_id'],  
            ];
        }

        echo json_encode($data);
    break;

    // case 'show':
    //     $rspta = $RouteSchedule->show( $_POST );
    //     echo json_encode($rspta);
    // break;
    // case 'show-route':
    //     $rspta = $RouteSchedule->show_route( $_POST );
    //     echo json_encode($rspta);
    // break;
    
    // case 'deleteItem':
    //     return print_r($_POST);
    //     $rspta=$RouteSchedule->deleteItem($_POST);
    //     echo $rspta;
    // break;
    
    // case 'index':
    //     $rspta = $RouteSchedule->index();
    //     $data = [];
    //     while ($reg = $rspta->fetch_assoc()) {
    //         $data[] = [
    //             "id" => $reg['id'],
    //             "origin" => $reg['origin'],
    //             "destination" => $reg['destination'],
    //             "cost" => $reg['cost'],
    //         ];
    //     }

    //     echo json_encode($data);
    // break;


    // case 'show-schedules':
    //     $rspta = $RouteSchedule->show_schedules( $_POST );
    //     $data = [];
    //     while ($reg = $rspta->fetch_assoc()) {
    //         $data[] = [
    //             "id" => $reg['id'],
    //             "vehicle_id" => $reg['vehicle_id'],
    //             "leaving_time" => $reg['leaving_time'],
    //             "day" => $reg['day'],
    //             "unidad_number" => $reg['unidad_number'],
                
    //         ];
    //     }

    //     echo json_encode($data);
    // break;

    // case 'deleted-item':
    //     $rspta = $RouteSchedule->deleted_item ( $_POST );
    //     echo $rspta;
    // break;

    // case 'deleted-schedules':
    //     $rspta = $RouteSchedule->deleted_schedules ( $_POST );
    //     echo $rspta;
    // break;

    // case 'deleted-schedules-by-vehicle':
    //     $rspta = $RouteSchedule->deleted_schedules_by_vehicle ( $_POST );
    //     echo $rspta;
    // break;

    




}
?>
