<?php 
require_once "../Models/Ticket.php";
$Ticket = new Ticket();


switch ($_GET["op"]) {
    // case 'store':
    //     $rspta = $Vehicle->store ( $_POST );
    //     echo $rspta;
    // break;

    case 'schedules':
        // return print_r($_GET);
        $rspta = $Ticket->schedules($_GET);
        $data = [];
        while ($reg = $rspta->fetch_assoc()) {
            $data[] = [
                "id" => $reg['id'],
                "text" => $reg['leaving_time']
            ];
        }

        echo json_encode($data);
    break;

    case 'details':
        $data = $Ticket->details($_POST);
        echo json_encode($data);
    break;

    case 'routes':
        $rspta = $Ticket->routes($_POST);
        $data = [];
        while ($reg = $rspta->fetch_assoc()) {
            $data[] = [
                "route_schedule_id" => $reg['route_schedule_id'],
                "origin" => $reg['origin'],
                "destination" => $reg['destination'],
                "cost" => $reg['cost'],
                "leaving_time" => $reg['leaving_time'],
            ];
        }

        echo json_encode($data);
    break;
}
?>
