<?php 
session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";

class RouteSubpath {

    public function __construct() {}
    
    
    public function store( $data ) {
        $route_id = $data["route_id"];
        $origin = $data["origin"];
        $destination = $data["destination"];
        $price = $data["price"];
        $route_subpaths_id = $data["route_subpaths_id"];
        
    

        if( $route_subpaths_id ){
            $sql="
                UPDATE `routes_stop` SET
                    `route_id`      = '$route_id',
                    `origin`        = '$origin',
                    `destination`   = '$destination',
                    `price`         = '$price',
                    `updated_at`     = NOW()
                WHERE `id` = '$route_subpaths_id'
            ";
        }else{
            $sql ="
                INSERT INTO 
                `routes_stop`( 
                    `route_id`,
                    `origin`,
                    `destination`,
                    `price`,
                    `created_at`,
                    `updated_at`
                ) VALUES (
                    '$route_id',
                    '$origin',
                    '$destination',
                    '$price',
                    NOW(),
                    NOW()
                )
            ";
        }
        // echo $sql;
        return ejecutarConsulta($sql);
    }
    
    public function index() {
        $sql = "
        SELECT routes_stop.*,
            CONCAT(routes.origin,' - ',routes.destination) AS route
        FROM routes_stop 
        INNER JOIN routes ON routes.id = routes_stop.route_id 
        WHERE routes_stop.deleted_at IS NULL";
        return ejecutarConsulta($sql);
    }
    
   
    public function show( $data ) {
        $route_id = $data['route_id'];
        $sql = "SELECT * FROM routes_stop WHERE id = '$route_id' ";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function show_origin( $data ) {
        $route_id = $data['route_id'];
        $sql = "SELECT * FROM routes WHERE id = '$route_id' ";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function deleteItem ( $data ){
        $route_id = $data['route_id'];
        $sql="
        UPDATE 
        `routes_stop` SET 
            `deleted_at`= NOW()
        WHERE `id`='$route_id'";
        return ejecutarConsulta($sql);
    }
    
 
}   
?>
