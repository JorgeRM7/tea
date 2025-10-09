<?php 
require_once "../Middlewares/authMiddleware.php";
$userData = verificarToken();
require_once "../Models/User.php";
$User = new User();


switch ($_GET["op"]) {
    case 'store':
        $rspta = $User->store ( $_POST );
        echo $rspta;
    break;

    case 'store-password':
        $rspta = $User->store_password ( $_POST );
        echo $rspta;
    break;

    case 'show':
        $rspta = $User->show( $_POST );
        $data = [];
        while ($reg = $rspta->fetch_assoc()) {
            $data[] = [
                "branch_office_id" => $reg['branch_office_id'],
                "name" => $reg['name'],
                "email" => $reg['email'],
                "user_type_id" => $reg['user_type_id'],
                "username" => $reg['username'],
                "user_id" => $reg['id'],
            ];
        }
        echo json_encode($data);
    break;

    case 'branch_offices':
        $rspta = $User->branch_offices();
        $data = [];
        while ($reg = $rspta->fetch_assoc()) {
            $data[] = [
                "branch_office_id" => $reg['id'],
                "code" => $reg['code'],
                "name" => $reg['name'],
            ];
        }
        echo json_encode($data);
    break;
    
    case 'deleteItem':
        $rspta=$User->deleteItem($_POST);
        echo $rspta;
    break;

    case 'index':
        
        $rspta = $User->index();
        
        $data=Array();
        while ($reg=$rspta->fetch_object()) {
            
            $bonton_editar = '<button type="button" class="editar btn btn-sm btn-warning" onclick="show('.$reg->id.')"><i class="ti ti-edit"></i></button>';
            $bonton_borrar = '<button type="button" class="eliminar btn btn-sm btn-danger" onclick="deleteItem(' . $reg->id . ')"><i class="ti ti-trash"></i></button>';
            $bonton_contraseña = '<button type="button" class="btn btn-sm btn-success" onclick="show_password(' . $reg->id . ')"><i class="ti ti-cloud-lock-open"></i></button>';
            
            $data[]=array(
                $bonton_editar.' '.$bonton_borrar.' '.$bonton_contraseña,
                $reg->id,
                $reg->name,
                $reg->email,
                $reg->username
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
