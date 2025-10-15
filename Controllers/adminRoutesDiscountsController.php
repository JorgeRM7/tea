<?php 
require_once "../Middlewares/authMiddleware.php";
$userData = verificarToken();
require_once "../Models/Discount.php";
$Discount = new Discount();


switch ($_GET["op"]) {
    case 'store':
        $rspta = $Discount->store ( $_POST );
        echo $rspta;
    break;
    
    
    case 'show':
        $rspta = $Discount->show( $_POST );
        echo json_encode($rspta);
    break;
    
    case 'deleteItem':
        $rspta=$Discount->deleteItem($_POST);
        echo $rspta;
    break;

    case 'index':
        $rspta = $Discount->index();
        
        $data=Array();
        while ($reg=$rspta->fetch_object()) {

            
            if($reg->status == "active" ){
                $status = '<span class="badge bg-success">Activo</span>';
            }else{
                $status = '<span class="badge bg-danger">Inactivo</span>';
            }   
            
            
            
            $bonton_editar = '<button type="button" class="editar btn btn-sm btn-warning" onclick="show('.$reg->id.')"><i class="ti ti-edit"></i></button>';
            $bonton_borrar = '<button type="button" class="eliminar btn btn-sm btn-danger" onclick="deleteItem(' . $reg->id . ')"><i class="ti ti-trash"></i></button>';
            
            $data[]=array(
                $bonton_editar.' '.$bonton_borrar,
                $reg->id,
                $reg->name,
                $reg->percentage,
                $reg->start_date,
                $reg->end_date,
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
