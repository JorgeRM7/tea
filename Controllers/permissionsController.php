<?php 
require_once "../Middlewares/authMiddleware.php";
$userData = verificarToken();
require_once "../Models/Permission.php";
$Permission = new Permission();


switch ($_GET["op"]) {
    case 'index':
        $rspta = $Permission->index();
        $data = [];
        while ($reg = $rspta->fetch_assoc()) {
            $data[] = [
                "id" => $reg['id'],
                "name" => $reg['name']
            ];
        }

        echo json_encode($data);
        
    break;

    case 'permissions':
        $rspta = $Permission->permissions( $_POST );
        $data = [];
        while ($reg = $rspta->fetch_assoc()) {
            $data[] = [
                "permission_id" => $reg['permission_id'],
                "view" => $reg['view'],
                "view_id" => $reg['view_id'],
                "permission_create" => $reg['permission_create'],
                "permission_view" => $reg['permission_view'],
                "permission_update" => $reg['permission_update'],
                "permission_delete" => $reg['permission_delete'],
            ];
        }

        echo json_encode($data);
        
    break;

    case 'update':
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        if ($data === null) {
            echo json_encode([
                "success" => false,
                "message" => "No se recibieron datos válidos"
            ]);
            exit;
        }
        $rspta = $Permission->update($data);

        echo json_encode([
            "success" => true,
            "ids" => $rspta
        ]);
    break;


    case 'store':
        $rspta = $Permission->store ( $_POST );
        echo json_encode([
            "success" => true,
            "ids" => $rspta
        ]);
    break;

    case 'deleteItem':
        $rspta = $Permission->deleteItem( $_POST );
        echo $rspta;
    break;
    
}
?>
