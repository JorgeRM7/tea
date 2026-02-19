<?php 
session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";


class TicketDelivery {

    public function __construct() {}
    
    
    public function store( $data ) {
        date_default_timezone_set('America/Mexico_City');
        $today = date("Y-m-d H:i:s");
        $route_id           = $data["search_route"];
        $route_stop_id      = $data["routes_stop_id"];
        $price              = $data["price"];
        $quantity           = $data["quantity"];
        $description        = $data["description"];
        $description        = $data["description"];

        $sql ="
            INSERT INTO `tickets_delivery`(
                `route_id`,
                `route_stop_id`,
                `price`,
                `quantity`,
                `date`,
                `description`,
                `created_at`,
                `updated_at`
            ) VALUES (
                '$route_id',
                '$route_stop_id',
                '$price',
                '$quantity',
                '$today',
                '$description',
                '$today',
                '$today'
            )
        ";
        $result = ejecutarConsulta($sql);
        
        if ( $result ) {
            global $conexion;
            $tickets_ids[] = mysqli_insert_id($conexion);
        }

        return $tickets_ids;
    }
    
    public function index() {
        $sql = "SELECT * FROM routes WHERE deleted_at IS NULL";
        return ejecutarConsulta($sql);
    }
    
   
    public function show( $data ) {
        $route_id = $data['route_id'];
        $sql = "SELECT * FROM routes WHERE id = '$route_id' ";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function deleteItem ( $data ){
        $route_id = $data['route_id'];
        $sql="
        UPDATE 
        `routes` SET 
            `deleted_at`= NOW()
        WHERE `id`='$route_id'";
        return ejecutarConsulta($sql);
    }
    
 
}   
?>
