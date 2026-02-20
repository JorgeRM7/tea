<?php 
session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";


class SocialReason {

    public function __construct() {}
    
    
    public function store( $data ) {
        $social_reason_id = $data["social_reason_id"];
        $name = $data["name"];
        $tax_data = $data["tax_data"];

        

        if( $social_reason_id ){
            $sql="
                UPDATE `social_reasons` SET 
                    `name`         = '$name',
                    `tax_data`     = '$tax_data',
                    `updated_at`   = NOW()
                WHERE `id` = '$social_reason_id'
            ";
        }else{
            $sql ="
                INSERT INTO 
                `social_reasons`( 
                    `name`,
                    `tax_data`,
                    `created_at`,
                    `updated_at`
                ) VALUES (
                    '$name',
                    '$tax_data',
                    NOW(),
                    NOW()
                )
            ";
        }
        return ejecutarConsulta($sql);
    }
    
    public function index() {
        $sql = "SELECT * FROM social_reasons WHERE deleted_at IS NULL";
        return ejecutarConsulta($sql);
    }
    
   
    public function show( $data ) {
        $social_reason_id = $data['social_reason_id'];
        $sql = "SELECT * FROM social_reasons WHERE id = '$social_reason_id' ";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function deleteItem ( $data ){
        $social_reason_id = $data['social_reason_id'];
        $sql="
        UPDATE 
        `social_reasons` SET 
            `deleted_at`= NOW()
        WHERE `id`='$social_reason_id'";
        return ejecutarConsulta($sql);
    }
    
 
}   
?>
