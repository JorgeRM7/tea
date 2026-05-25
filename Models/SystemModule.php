<?php 
session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";


class SystemModule {

    public function __construct() {}
    
    
    public function store( $data ) {
        $system_module_id   = $data["system_module_id"];
        $name               = $data["name"];
        $description        = $data["description"];
        $icon               = $data["icon"];

        

        if( $system_module_id ){
            $sql="
                UPDATE `system_modules` SET 
                    `name`         = '$name',
                    `description`  = '$description',
                    `icon`         = '$icon',
                    `updated_at`   = NOW()
                WHERE `id` = '$system_module_id'
            ";
        }else{
            $sql ="
                INSERT INTO 
                `system_modules`( 
                    `name`,
                    `description`,
                    `icon`,
                    `created_at`,
                    `updated_at`
                ) VALUES (
                    '$name',
                    '$description',
                    '$icon',
                    NOW(),
                    NOW()
                )
            ";
        }
        return ejecutarConsulta($sql);
    }
    
    public function index() {
        $sql = "SELECT * FROM system_modules WHERE deleted_at IS NULL";
        return ejecutarConsulta($sql);
    }
    
   
    public function show( $data ) {
        $system_module_id = $data['system_module_id'];
        $sql = "SELECT * FROM system_modules WHERE id = '$system_module_id' ";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function deleteItem ( $data ){
        $system_module_id = $data['system_module_id'];
        $sql="
        UPDATE 
        `system_modules` SET 
            `deleted_at`= NOW()
        WHERE `id`='$system_module_id'";
        return ejecutarConsulta($sql);
    }
    
 
}   
?>
