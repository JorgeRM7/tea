<?php 
session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";

class View {

    public function __construct() {}
    
    
    public function store( $data ) {
        $view_id = $data["view_id"];
        $route = $data["route"];
        $module = $data["module"];
        $title = $data["title"];
        $icon = $data["icon"];
    
        if( $view_id ){
            $sql="
                UPDATE `views` SET 
                    `route`         = '$route',
                    `module`        = '$module',
                    `title`         = '$title',
                    `icon`          = '$icon',
                    `updated_at`     = NOW()
                WHERE `id` = '$view_id'
            ";
        }else{
            $sql ="
                INSERT INTO 
                `views`( 
                    `route`,
                    `module`,
                    `title`,
                    `icon`, 
                    `created_at`,
                    `updated_at`
                ) VALUES (
                    '$route',
                    '$module',
                    '$title',
                    '$icon',
                    NOW(),
                    NOW()
                )
            ";
        }
        return ejecutarConsulta($sql);
    }
    
    public function index() {
        $sql = "SELECT * FROM views WHERE deleted_at IS NULL";
        return ejecutarConsulta($sql);
    }
    
   
    public function show( $data ) {
        $view_id = $data['view_id'];
        $sql = "SELECT * FROM views WHERE id = '$view_id' ";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function deleteItem ( $data ){
        $view_id = $data['view_id'];
        $sql="
        UPDATE 
        `views` SET 
            `deleted_at`= NOW()
        WHERE `id`='$view_id'";
        return ejecutarConsulta($sql);
    }
 
}   
?>
