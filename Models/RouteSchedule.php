<?php 
// session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";

class RouteSchedule {

    public function __construct() {}
    
    
    public function store( $data ) {
        $routes_schedule_id = $data["routes_schedule_id"];
        $route_id = $data["route_id"];
        $leaving_time = $data["leaving_time"];
        $day = $data["day"];
    
        if( $routes_schedule_id ){
            $sql="
                UPDATE `routes_schedule` SET 
                    `route_id`       = '$route_id',
                    `leaving_time`   = '$leaving_time',
                    `day`            = '$day',
                    `updated_at`     = NOW()
                WHERE `id` = '$routes_schedule_id'
            ";
        }else{
            $sql ="
                INSERT INTO 
                `routes_schedule`( 
                    `route_id`,
                    `leaving_time`,
                    `day`,
                    `created_at`,
                    `updated_at`
                ) VALUES (
                    '$route_id',
                    '$leaving_time',
                    '$day',
                    NOW(),
                    NOW()
                )
            ";
        }
        return ejecutarConsulta($sql);
    }
    
    public function index($route_id ) {
        $sql = "SELECT * FROM routes_schedule WHERE route_id='$route_id' AND deleted_at IS NULL";
        return ejecutarConsulta($sql);
    }
    
   
    public function show( $data ) {
        $routes_schedule_id = $data['routes_schedule_id'];
        $sql = "SELECT * FROM routes_schedule WHERE id = '$routes_schedule_id' ";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function deleteItem ( $data ){
        $routes_schedule_id = $data['routes_schedule_id'];
        $sql="
        UPDATE 
        `routes_schedule` SET 
            `deleted_at`= NOW()
        WHERE `id`='$   '";
        return ejecutarConsulta($sql);
    }
    
 
}   
?>
