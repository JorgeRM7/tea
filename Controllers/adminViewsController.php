<?php 
require_once "../Middlewares/authMiddleware.php";
$userData = verificarToken();
require_once "../Models/View.php";
$View = new View();


switch ($_GET["op"]) {
    case 'store':
        $rspta = $View->store ( $_POST );
        echo $rspta;
    break;
    
    
    case 'show':
        $rspta = $View->show( $_POST );
        echo json_encode($rspta);
    break;
    
    case 'deleteItem':
        $rspta=$View->deleteItem($_POST);
        echo $rspta;
    break;

    case 'index':
        
        $rspta = $View->index();
        
        $data=Array();
        while ($reg=$rspta->fetch_object()) {
            
            $boton_editar = '<button type="button" class="editar btn btn-sm btn-warning" onclick="show('.$reg->id.')"><i class="ti ti-edit"></i></button>';
            $boton_borrar = '<button type="button" class="eliminar btn btn-sm btn-danger" onclick="deleteItem(' . $reg->id . ')"><i class="ti ti-trash"></i></button>';
            
            $data[]=array(
                $boton_editar.' '.$boton_borrar,
                $reg->id,
                $reg->route,
                $reg->module,
                $reg->title,
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
