<?php 
session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";


class RouteScheduleStatic {

    public function __construct() {}
    
    
    public function store( $data ) {
        $route_static_id    = $data["route_static_id"];
        $shift_role_id      = $data["shift_role_id"];
        $route_id           = $data["route_id"];
        $leaving_time       = $data["leaving_time"];

        if( $route_static_id ){
            $sql="
                UPDATE `routes_static` SET 
                    `route_id`         = '$route_id',
                    `shift_role_id`    = '$shift_role_id',
                    `leaving_time`     = '$leaving_time',
                    `updated_at`       = NOW()
                WHERE `id` = '$route_static_id'
            ";
        }else{
            $sql ="
                INSERT INTO 
                `routes_static`( 
                    `route_id`,
                    `shift_role_id`,
                    `leaving_time`,
                    `created_at`,
                    `updated_at`
                ) VALUES (
                    '$route_id',
                    '$shift_role_id',
                    '$leaving_time',
                    NOW(),
                    NOW()
                )
            ";
        }
        return ejecutarConsulta($sql);
    }
    
    public function index() {
        $sql = "SELECT 
                routes_static.*,
                routes.origin,
                routes.destination,
                shift_roles.name AS role
            FROM `routes_static`
            INNER JOIN shift_roles ON shift_roles.id = routes_static.shift_role_id
            INNER JOIN routes ON routes.id = routes_static.route_id
            WHERE routes_static.deleted_at IS NULL
            ORDER BY routes_static.leaving_time ASC";
        return ejecutarConsulta($sql);
    }
    
   
    public function show( $data ) {
        $route_static_id = $data['route_static_id'];
        $sql = "SELECT * FROM routes_static WHERE id = '$route_static_id' ";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function deleteItem ( $data ){
        $route_static_id = $data['route_static_id'];
        $sql="
        UPDATE 
        `routes_static` SET 
            `deleted_at`= NOW()
        WHERE `id`='$route_static_id'";
        return ejecutarConsulta($sql);
    }
    
 
}   
?>
