<?php 
require_once "../Middlewares/authMiddleware.php";
$userData = verificarToken();
require_once "../Models/SystemModule.php";
$SystemModule = new SystemModule();


switch ($_GET["op"]) {
    case 'store':
        $rspta = $SystemModule->store ( $_POST );
        echo $rspta;
    break;
    
    
    case 'show':
        $rspta = $SystemModule->show( $_POST );
        echo json_encode($rspta);
    break;
    
    case 'deleteItem':
        $rspta=$SystemModule->deleteItem($_POST);
        echo $rspta;
    break;

    case 'index':
        
        $rspta = $SystemModule->index();
        
        $data=Array();
        while ($reg=$rspta->fetch_object()) {
            
            $bonton_editar = '<button type="button" class="editar btn btn-sm btn-warning" onclick="show('.$reg->id.')"><i class="ti ti-edit"></i></button>';
            $bonton_borrar = '<button type="button" class="eliminar btn btn-sm btn-danger" onclick="deleteItem(' . $reg->id . ')"><i class="ti ti-trash"></i></button>';
            
            $data[]=array(
                $bonton_editar.' '.$bonton_borrar,
                $reg->id,
                $reg->name,
                $reg->description,
                $reg->icon,
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
