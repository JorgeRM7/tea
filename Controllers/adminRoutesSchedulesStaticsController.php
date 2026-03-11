<?php 
require_once "../Middlewares/authMiddleware.php";
$userData = verificarToken();
require_once "../Models/RouteScheduleStatic.php";
$RouteScheduleStatic = new RouteScheduleStatic();


switch ($_GET["op"]) {
    case 'store':
        $rspta = $RouteScheduleStatic->store ( $_POST );
        echo $rspta;
    break;
    
    
    case 'show':
        $rspta = $RouteScheduleStatic->show( $_POST );
        echo json_encode($rspta);
    break;
    
    case 'deleteItem':
        $rspta=$RouteScheduleStatic->deleteItem($_POST);
        echo $rspta;
    break;

    case 'index':
        
        $rspta = $RouteScheduleStatic->index();
        
        $data=Array();
        while ($reg=$rspta->fetch_object()) {
            
            $bonton_editar = '<button type="button" class="editar btn btn-sm btn-warning" onclick="show('.$reg->id.')"><i class="ti ti-edit"></i></button>';
            $bonton_borrar = '<button type="button" class="eliminar btn btn-sm btn-danger" onclick="deleteItem(' . $reg->id . ')"><i class="ti ti-trash"></i></button>';
            
            $data[]=array(
                $bonton_editar.' '.$bonton_borrar,
                $reg->id,
                '<i class="ti ti-map-pin"></i>'.$reg->origin,
                '<i class="ti ti-map-pin"></i>'.$reg->destination,
                $reg->role,
                $reg->leaving_time,
            );
         }
        $results=array(
                 "sEcho"=>1,
                 "iTotalRecords"=>count($data),
                 "iTotalDisplayRecords"=>count($data),
                 "aaData"=>$data); 
        echo json_encode($results);
    break;
}
?>
