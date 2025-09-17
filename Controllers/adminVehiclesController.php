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
            
            $boton_editar = '<button type="button" class="editar btn btn-sm btn-warning" onclick="show('.$reg->id.')"><i class="ti ti-edit"></i></button>';
            $boton_borrar = '<button type="button" class="eliminar btn btn-sm btn-danger" onclick="deleteItem(' . $reg->id . ')"><i class="ti ti-trash"></i></button>';
            $boton_asignar = '<button type="button" class="eliminar btn btn-sm btn-info" onclick="show_assign(' . $reg->id . ')"><i class="ti ti-users-plus"></i></button>';

            $employee = $reg->employee_name 
                ? '<i class="ti ti-user-check text-success"></i> ' . $reg->employee_name
                : '<span class="badge bg-secondary"><i class="ti ti-user-off"></i> Sin asignar</span>';


        
            switch ($reg->status) {
                case 'active':
                    $status = '<span class="badge bg-success">Activo</span>';
                    break;
                case 'inactive':
                    $status = '<span class="badge bg-danger">Inactivo</span>';
                    break;
                case 'maintenance':
                    $status = '<span class="badge bg-danger">Mantenimiento</span>';
                    break;
                default:
                    $status = '<span class="badge bg-warning">Desconocido</span>';
                    break;
            }
            
            $data[]=array(
                $boton_editar.' '.$boton_borrar.' '.$boton_asignar,
                $reg->id,
                $reg->plate_number,
                $reg->brand,
                $reg->model,
                $reg->year,
                $reg->color,
                $reg->serial_number,
                $reg->type,
                $status,
                $employee

            );
         }
        $results=array(
                 "sEcho"=>1,
                 "iTotalRecords"=>count($data),
                 "iTotalDisplayRecords"=>count($data),
                 "aaData"=>$data); 
        echo json_encode($results);
    break;

    case 'assign':
        $rspta = $Vehicle->assign ( $_POST );
        echo $rspta;
    break;
}
?>
