<?php 
require_once "../Models/Vehicle.php";
$Vehicle = new Vehicle();


switch ($_GET["op"]) {
    case 'store':
        $rspta = $Vehicle->store ( $_POST );
        echo $rspta;
    break;
    
    
    case 'show':
        $rspta = $Vehicle->show( $_POST );
        echo json_encode($rspta);
    break;
    
    case 'deleteItem':
        $rspta=$Vehicle->deleteItem($_POST);
        echo $rspta;
    break;

    case 'index':
        
        $rspta = $Vehicle->index();
        
        $data=Array();
        while ($reg=$rspta->fetch_object()) {
            
            $bonton_editar = '<button type="button" class="editar btn btn-sm btn-warning" onclick="show('.$reg->id.')"><i class="ti ti-edit"></i></button>';
            $bonton_borrar = '<button type="button" class="eliminar btn btn-sm btn-danger" onclick="deleteItem(' . $reg->id . ')"><i class="ti ti-trash"></i></button>';
            
            $data[]=array(
                $bonton_editar.' '.$bonton_borrar,
                $reg->id,
                $reg->plate_number,
                $reg->brand,
                $reg->model,
                $reg->year,
                $reg->color,
                $reg->serial_number,
                $reg->type,
                $reg->status,
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
