<?php 
require_once "../Middlewares/authMiddleware.php";
$userData = verificarToken();
require_once "../Models/RouteSubpath.php";
$Route = new RouteSubpath();


switch ($_GET["op"]) {
    case 'store':
        $rspta = $Route->store ( $_POST );
        echo $rspta;
    break;
    
    case 'show-origin':
        $rspta = $Route->show_origin( $_POST );
        echo json_encode($rspta);
    break;
    
    case 'show':
        $rspta = $Route->show( $_POST );
        echo json_encode($rspta);
    break;
    
    case 'deleteItem':
        $rspta=$Route->deleteItem($_POST);
        echo $rspta;
    break;

    case 'index':
        
        $rspta = $Route->index();
        
        $data=Array();
        while ($reg=$rspta->fetch_object()) {
            
            $bonton_editar = '<button type="button" class="editar btn btn-sm btn-warning" onclick="show('.$reg->id.')"><i class="ti ti-edit"></i></button>';
            $bonton_borrar = '<button type="button" class="eliminar btn btn-sm btn-danger" onclick="deleteItem(' . $reg->id . ')"><i class="ti ti-trash"></i></button>';
            
            if( $reg->active_online == 1 ){
                $status = '<span class="badge bg-success">Activo</span>';
            }else{
                $status = '<span class="badge bg-danger">Inactivo</span>';
            }   

            $data[]=array(
                $bonton_editar.' '.$bonton_borrar,
                $reg->id,
                $reg->route,
                '<i class="ti ti-map-pin"></i>'.$reg->origin,
                '<i class="ti ti-map-pin"></i>'.$reg->destination,
                '$ '.$reg->price,
                '$ '.$reg->price_online ?? 0,
                $status
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
