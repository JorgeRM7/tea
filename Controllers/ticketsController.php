<?php 
require_once "../Middlewares/authMiddleware.php";
$userData = verificarToken();
require_once "../Models/Ticket.php";
$Ticket = new Ticket();


switch ($_GET["op"]) {
    case 'index':
        $rspta = $Ticket->index( $_GET );
        
        $data = Array();
        while ($reg = $rspta->fetch_object()) {

        
            // $bonton_editar = '<button type="button" class="editar btn btn-sm btn-warning me-1" onclick="show('.$reg->id.')" title="Editar boleto">
            //                     <i class="ti ti-edit"></i>
            //                 </button>';
            $bonton_borrar = '<button type="button" class="eliminar btn btn-sm btn-danger" onclick="deleteItem(' . $reg->id . ')" title="Eliminar boleto">
                                <i class="ti ti-trash"></i>
                            </button>';

            
            $ruta = '<span class="fw-bold text-primary">
                        <i class="ti ti-map-pin"></i> ' . $reg->origin . 
                    '</span> 
                    <span class="text-dark fw-bold"> → </span> 
                    <span class="fw-bold text-success">
                        <i class="ti ti-flag"></i> ' . $reg->destination . 
                    '</span>';

           
            $price ='💲 ' . number_format($reg->price, 2);


            $leaving_time = '<div>
                                <i class="ti ti-clock"></i> ' . $reg->leaving_time . '
                            </div>';


            $date = '<div>
                        <i class="ti ti-calendar"></i> ' . $reg->date . '
                    </div>';

            $vehiculo = '<span class="badge bg-info text-dark">
                            🚍 ' . $reg->vehicle_id . '
                        </span>';


            $pagoClass = ($reg->payment_method == "EFECTIVO") ? "bg-success" : "bg-primary";
            $pago = '<span class="badge '.$pagoClass.'">'.$reg->payment_method.'</span>';

            $statusClass = ($reg->status == "VENDIDO") ? "bg-success" : "bg-danger";
            $status = '<span class="badge '.$statusClass.'">'.$reg->status.'</span>';

            if( $reg->status == "CANCELADO" ){
                $bonton_borrar ='';
            }

            $data[] = array(
                $bonton_borrar,
                '<span class="fw-bold text-dark">#'.$reg->id.'</span>',
                $ruta,
                $price,
                $date,
                $leaving_time,
                $vehiculo,
                $pago,
                $status,
            );
        }

        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
    break;


    case 'store':
        // return print_r($_POST);
        $rspta = $Ticket->store ( $_POST );
        echo json_encode([
            "success" => true,
            "ids" => $rspta
        ]);
    break;

    case 'schedules':
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

    case 'tickets-today':
        $rspta = $Ticket->tickets_today();
        echo json_encode($rspta);
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
                "tickets_sale" => $reg['tickets_sale'],
                "vehicle_capacity" => $reg['vehicle_capacity'],
                "vehicle_id" => $reg['vehicle_id'],
                
            ];
        }

        echo json_encode($data);
    break;

    case 'tickets':
        $rspta = $Ticket->tickets($_GET);
        echo json_encode($rspta);
    break;

    case 'deleteItem':
        $rspta = $Ticket->deleteItem ( $_POST );
        echo $rspta;
    break;
}
?>
