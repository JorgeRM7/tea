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
                "route_static_id"   => $reg['id'],
                "leaving_time"      => $reg['leaving_time'],
                "shift_role_id"     => $reg['shift_role_id'],  
                "route_id"          => $reg['route_id'],  
            ];
        }

        echo json_encode($data);
    break;
    

    case 'shift-roles':
        $rspta=$Calendar->shiftRole($_POST);
        echo json_encode($rspta);
    break;
    

}
?>
