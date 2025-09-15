<?php 
session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";

class UserType {

    public function __construct() {}
    
    
    public function store( $data ) {
        $user_type_id = $data["user_type_id"];
        $name = $data["name"];
        $description = $data["description"];
       
       

        if( $user_type_id ){
            $sql="
                UPDATE `users_types` 
                SET 
                    `name`           = '$name',
                    `description`    = '$description',
                    `updated_at`     = NOW()
                WHERE `id` = '$user_type_id'
            ";
        }else{
            $sql ="
                INSERT INTO 
                `users_types`( 
                    `name`,
                    `description`,
                    `created_at`,
                    `updated_at`
                ) VALUES (
                    '$name',
                    '$description',
                    NOW(),
                    NOW()
                )
            ";
        }
        return ejecutarConsulta($sql);
    }
    
    public function index() {
        $sql = "SELECT * FROM users_types WHERE deleted_at IS NULL";
        return ejecutarConsulta($sql);
    }
    
   
    public function show( $data ) {
        $user_type_id = $data['user_type_id'];
        $sql = "SELECT * FROM users_types WHERE id = '$user_type_id' ";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function deleteItem ( $data ){
        $user_type_id = $data['user_type_id'];
        $sql="
        UPDATE 
        `users_types` SET 
            `deleted_at`= NOW()
        WHERE `id`='$user_type_id'";
        return ejecutarConsulta($sql);
    }
    
 
}   
?>
