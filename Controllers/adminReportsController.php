<?php 
// require_once "../Middlewares/authMiddleware.php";
// $userData = verificarToken();
require_once "../Models/Report.php";
$Report = new Report();


switch ($_GET["op"]) {

    case 'show':
        $data = $Report->index($_POST);
        echo json_encode($data);
    break;

    case 'xls':
        header('Content-Type: application/json; charset=utf-8');
        $data = $Report->index($_POST);
        echo json_encode($data);
    break;

    
}
?>
