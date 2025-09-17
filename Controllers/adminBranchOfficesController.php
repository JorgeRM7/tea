<?php 
require_once "../Models/BranchOffice.php";
$BranchOffice = new BranchOffice();


switch ($_GET["op"]) {
    case 'store':
        $rspta = $BranchOffice->store ( $_POST );
        echo $rspta;
    break;
    
    
    case 'show':
        $rspta = $BranchOffice->show( $_POST );
        echo json_encode($rspta);
    break;
    
    case 'deleteItem':
        $rspta=$BranchOffice->deleteItem($_POST);
        echo $rspta;
    break;

    case 'index':
        
        $rspta = $BranchOffice->index();
        
        $data=Array();
        while ($reg = $rspta->fetch_object()) {

            $boton_editar = '<button type="button" class="editar btn btn-sm btn-warning" onclick="show(' . $reg->id . ')"><i class="ti ti-edit"></i></button>';
            $boton_borrar = '<button type="button" class="eliminar btn btn-sm btn-danger" onclick="deleteItem(' . $reg->id . ')"><i class="ti ti-trash"></i></button>';

            switch ($reg->status) {
                case 'active':
                    $status = '<span class="badge bg-success">Activo</span>';
                    break;
                case 'inactive':
                    $status = '<span class="badge bg-danger">Inactivo</span>';
                    break;
                default:
                    $status = '<span class="badge bg-warning">Desconocido</span>';
                    break;
            }

            $data[] = array(
                $boton_editar . ' ' . $boton_borrar,
                $reg->id,
                $reg->code,
                $reg->name,
                $reg->description,
                $reg->address,
                $reg->city,
                $reg->state,
                $reg->country,
                $reg->postal_code,
                $reg->phone,
                $reg->email,
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
