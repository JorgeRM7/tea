<?php 
session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";

class Route {

    public function __construct() {}
    
    
    public function store( $data ) {
        $route_id = $data["route_id"];
        $origin = $data["origin"];
        $destination = $data["destination"];
        $cost = $data["cost"];
    

        if( $route_id ){
            $sql="
                UPDATE `routes` SET 
                    `origin`         = '$origin',
                    `destination`    = '$destination',
                    `cost`           = '$cost',
                    `updated_at`     = NOW()
                WHERE `id` = '$route_id'
            ";
        }else{
            $sql ="
                INSERT INTO 
                `routes`( 
                    `origin`,
                    `destination`,
                    `cost`,
                    `created_at`,
                    `updated_at`
                ) VALUES (
                    '$origin',
                    '$destination',
                    '$cost',
                    NOW(),
                    NOW()
                )
            ";
        }
        return ejecutarConsulta($sql);
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
        WHERE `id`='$   '";
        return ejecutarConsulta($sql);
    }
    
 
}   
?>
