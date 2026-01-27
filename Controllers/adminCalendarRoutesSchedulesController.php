<?php 
require_once "../Middlewares/authMiddleware.php";
$userData = verificarToken();
require_once "../Models/CalendarRouteSchedule.php";
$Calendar = new Calendar();



switch ($_GET["op"]) {
    case 'store': 
        $rspta = $Calendar->store($_POST);
        echo json_encode($rspta);
    break;

    case 'load-schedules': 
        $rspta = $Calendar->load_schedules($_POST);
        echo json_encode($rspta);
    break;

    case 'routes':
        $rspta = $Calendar->routes($_POST);
        $data = [];
        while ($reg = $rspta->fetch_assoc()) {
            $data[] = [
                "route_schedule_id" => $reg['id'],
                "leaving_time" => $reg['leaving_time'],
                "unidad_number" => $reg['unidad_number'],  
            ];
        }

        echo json_encode($data);
    break;
    
    case 'deleteItem':
        $rspta=$Calendar->deleteItem($_POST);
        echo $rspta;
    break;

    case 'store-unit':
        $rspta=$Calendar->storeUnit($_POST);
        echo $rspta;
    break;
    

}
?>
