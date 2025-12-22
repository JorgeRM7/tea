<?php 
require_once "../Middlewares/authMiddleware.php";
$userData = verificarToken();
require_once "../Models/Dashboard.php";
$Dashboard = new Dashboard();


switch ($_GET["op"]) {

    case 'index':
        $rspta = $Dashboard->index($_GET );
        echo json_encode($rspta);
    break;
}
?>
