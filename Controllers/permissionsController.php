<?php 
require_once "../Middlewares/authMiddleware.php";
$userData = verificarToken();
require_once "../Models/Permission.php";
$Permission = new Permission();


switch ($_GET["op"]) {
    case 'index':
        $rspta = $Permission->index( $_GET );
        $data = [];
        while ($reg = $rspta->fetch_assoc()) {
            $data[] = [
                "id" => $reg['id'],
                "name" => $reg['name']
            ];
        }

        echo json_encode($data);
        
    break;

    case 'views':
        $rspta = $Permission->index( $_GET );
        $data = [];
        while ($reg = $rspta->fetch_assoc()) {
            $data[] = [
                "id" => $reg['id'],
                "name" => $reg['name']
            ];
        }

        echo json_encode($data);
        
    break;


    case 'store':
        $rspta = $Permission->store ( $_POST );
        echo json_encode([
            "success" => true,
            "ids" => $rspta
        ]);
    break;

    
}
?>
