<?php 
session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";


class ShiftRole {

    public function __construct() {}
    
    
    public function store( $data ) {
        $shift_role_id = $data["shift_role_id"];
        $name = $data["name"];

        if( $shift_role_id ){
            $sql="
                UPDATE `shift_roles` SET 
                    `name`        = '$name',
                    `updated_at`  = NOW()
                WHERE `id` = '$shift_role_id'
            ";
        }else{
            $sql ="
                INSERT INTO 
                `shift_roles`( 
                    `name`,
                    `created_at`,
                    `updated_at`
                ) VALUES (
                    '$name',
                    NOW(),
                    NOW()
                )
            ";
        }
        return ejecutarConsulta($sql);
    }
    
    public function index() {
        $sql = "SELECT * FROM shift_roles WHERE deleted_at IS NULL";
        return ejecutarConsulta($sql);
    }
    
   
    public function show( $data ) {
        $shift_role_id = $data['shift_role_id'];
        $sql = "SELECT * FROM shift_roles WHERE id = '$shift_role_id' ";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function deleteItem ( $data ){
        $shift_role_id = $data['shift_role_id'];
        $sql="
        UPDATE 
        `shift_roles` SET 
            `deleted_at`= NOW()
        WHERE `id`='$shift_role_id'";
        return ejecutarConsulta($sql);
    }
    
 
}   
?>
