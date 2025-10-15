<?php 
session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";

class Discount {

    public function __construct() {}
    
    
    public function store( $data ) {
        $routes_discount_id = $data["routes_discount_id"] ?? null;
        $name = $data["name"] ?? null;
        $percentage = $data["percentage"] ?? null;
        $start_date = $data["start_date"] ?? null;
        $end_date = $data["end_date"] ?? null;
        $status = $data["status"] ?? null;
    

        if( $routes_discount_id ){
            $sql="
                UPDATE `routes_discounts` SET 
                    `name`          = '$name',
                    `percentage`    = '$percentage',
                    `start_date`    = '$start_date',
                    `end_date`      = '$end_date',
                    `status`        = '$status',
                    `updated_at`    = NOW()
                WHERE `id` = '$routes_discount_id'
            ";
        }else{
            $sql ="
                INSERT INTO 
                `routes_discounts`( 
                    `name`,
                    `percentage`,
                    `start_date`,
                    `end_date`,
                    `status`,
                    `created_at`, 
                    `updated_at`
                ) VALUES (
                    '$name',
                    '$percentage',
                    '$start_date',
                    '$end_date',
                    '$status',
                    NOW(),
                    NOW()
                )
            ";
        }
        return ejecutarConsulta($sql);
    }
    
    public function index() {
        $sql = "SELECT * FROM routes_discounts WHERE deleted_at IS NULL";
        return ejecutarConsulta($sql);
    }
    
   
    public function show( $data ) {
        $routes_discount_id = $data['routes_discount_id'];
        $sql = "SELECT * FROM routes_discounts WHERE id = '$routes_discount_id' ";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function deleteItem ( $data ){
        $routes_discount_id = $data['routes_discount_id'];
        $sql="
        UPDATE 
        `routes_discounts` SET 
            `deleted_at`= NOW()
        WHERE `id`='$routes_discount_id'";
        return ejecutarConsulta($sql);
    }
    
 
}   
?>
