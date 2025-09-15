<?php 
session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";

class User {

    public function __construct() {}
    
    
    public function store( $data ) {
        $User_id = $data["User_id"];
        $plate_number = $data["plate_number"];
        $brand = $data["brand"];
        $model = $data["model"];
        $year = $data["year"];
        $color = $data["color"];
        $serial_number = $data["serial_number"];
        $type = $data["type"];
        $User_id = $data["User_id"];
        $User_id = $data["User_id"];
       

        if( $User_id ){
            $sql="
            UPDATE `Users` SET 
                `plate_number`   = '$plate_number',
                `brand`          = '$brand',
                `model`          = '$model',
                `year`           = '$year',
                `color`          = '$color',
                `serial_number`  = '$serial_number',
                `type`           = '$type',
                `updated_at`     = NOW()
            WHERE `id` = '$User_id'
    ";
        }else{
            $sql ="
                INSERT INTO 
                `Users`( 
                    `plate_number`,
                    `brand`,
                    `model`,
                    `year`,
                    `color`,
                    `serial_number`,
                    `type`,
                    `status`,
                    `created_at`,
                    `updated_at`
                ) VALUES (
                    '$plate_number',
                    '$brand',
                    '$model',
                    '$year',
                    '$color',
                    '$serial_number',
                    '$type',
                    'active',
                    NOW(),
                    NOW()
                )
            ";
        }
        return ejecutarConsulta($sql);
    }
    
    public function index() {
        $sql = "SELECT * FROM users WHERE deleted_at IS NULL";
        return ejecutarConsulta($sql);
    }
    
   
    public function show( $data ) {
        $user_id = $data['user_id'];
        $sql = "SELECT * FROM users WHERE id = '$user_id' ";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function deleteItem ( $data ){
        $user_id = $data['user_id'];
        $sql="
        UPDATE 
        `Users` SET 
            `deleted_at`= NOW()
        WHERE `id`='$user_id'";
        return ejecutarConsulta($sql);
    }
    
 
}   
?>
