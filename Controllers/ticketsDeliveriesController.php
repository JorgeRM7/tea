<?php 
require_once "../Middlewares/authMiddleware.php";
$userData = verificarToken();
require_once "../Models/TicketDelivery.php";
$Ticket = new TicketDelivery();


switch ($_GET["op"]) {
    case 'store':
        $rspta = $Ticket->store ( $_POST );
        echo json_encode([
            "success" => true,
            "ids" => $rspta
        ]);
    break;
    
    
    case 'show':
        $rspta = $Ticket->show( $_POST );
        echo json_encode($rspta);
    break;
    
    case 'deleteItem':
        $rspta=$Ticket->deleteItem($_POST);
        echo $rspta;
    break;

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


           

            $data[] = array(
                $bonton_borrar,
                '<span class="fw-bold text-dark">'.$reg->id.'</span>',
                $ruta,
                $price,
               
                $reg->quantity,
                $reg->description,
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

    case 'xls':
        $data = $Ticket->xls( $_POST );
        echo json_encode($data);
    break;

    case 'tickets-today':
        $rspta = $Ticket->tickets_today();
        echo json_encode($rspta);
    break;

    case 'tickets':
        $rspta = $Ticket->tickets($_GET);
        echo json_encode($rspta);
    break;
}
?>
