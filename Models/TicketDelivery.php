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
    
    public function index( $data ) {
        $date = $data['date'] ?? null;
        $date_filter_end = $data['date_filter_end'] ?? null;
        $branch_office_id =$data['branch_office'];
        $user_id = $_SESSION['user_id'];
        $user_type_id = $_SESSION['user_type_id'];

        $sql = "
        SELECT 
            tickets_delivery.id,
            tickets_delivery.price,
            tickets_delivery.quantity,
            tickets_delivery.description,
            routes_stop.origin,
            routes_stop.destination
        FROM `tickets_delivery`
        INNER JOIN routes_stop ON routes_stop.id = tickets_delivery.route_stop_id";

        if ( $user_type_id != 1 ) {
            $sql .= " AND tickets_delivery.user_id = '".$user_id. "'";
        }

        if (!empty($date)) {
            $sql .= " AND tickets_delivery.date >= '$date' AND tickets_delivery.date <='$date_filter_end'";
        }

        $sql .= " ORDER BY tickets_delivery.date DESC";

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

    public function tickets ( $data ){
        $date = $data['date'];
        $date_filter_end = $data['date_filter_end'] ?? null;
        $user_id = $_SESSION['user_id'];
        $user_type_id = $_SESSION['user_type_id'];
        $branch_office_id = $data['branch_office_id'];
        $sql = "
            SELECT 
                SUM(CASE WHEN status = 'VENDIDO' THEN 1 ELSE 0 END) AS vendidos,
                SUM(CASE WHEN status = 'CANCELADO' THEN 1 ELSE 0 END) AS cancelados,
                SUM(CASE WHEN status = 'VENDIDO' THEN price ELSE 0 END) AS importe_total,
                COUNT(*) AS total
            FROM tickets_delivery
            WHERE branch_office_id = $branch_office_id
        ";

        if (!empty($date)) {
            $sql .= " AND tickets_delivery.date >= '$date' AND tickets_delivery.date <='$date_filter_end'";
        }

        if ( $user_type_id != 1 ) {
            $sql .= " AND tickets_delivery.user_id = '".$user_id. "'";
        }

        $sql .= " ORDER BY tickets_delivery.date DESC";

        return ejecutarConsultaSimpleFila($sql);
    }

    public function xls ( $data ){
        $date_start         = $data['date_start'] ?? null;
        $date_end           = $data['date_end'] ?? null;
        $branch_office_id   = $data['branch_office_id'];

        $sql ="
            SELECT 
                tickets_delivery.id,
                tickets_delivery.price,
                tickets_delivery.quantity,
                tickets_delivery.description,
                routes_stop.origin,
                routes_stop.destination
            FROM `tickets_delivery`
            INNER JOIN routes_stop ON routes_stop.id = tickets_delivery.route_stop_id
            WHERE branch_office_id = '$branch_office_id' AND tickets_delivery.date >= '$date_start' AND tickets_delivery.date <='$date_end'
        ";
    
        $sql .= " ORDER BY tickets_delivery.date DESC";
        $resultado = ejecutarConsulta($sql);
        $data = array();
        while ( $item = $resultado->fetch_object()) {
            $data[] = $item;
        }
        return $data;
    }

    
 
}   
?>
