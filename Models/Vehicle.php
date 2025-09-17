<?php 
session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";

class Vehicle {

    public function __construct() {}
    
    
    public function store( $data ) {
        $vehicle_id = $data["vehicle_id"];
        $plate_number = $data["plate_number"];
        $brand = $data["brand"];
        $model = $data["model"];
        $year = $data["year"];
        $color = $data["color"];
        $serial_number = $data["serial_number"];
        $type = $data["type"];
        $vehicle_id = $data["vehicle_id"];
        $vehicle_id = $data["vehicle_id"];
       

        if( $vehicle_id ){
            $sql="
                UPDATE `vehicles` SET 
                    `plate_number`   = '$plate_number',
                    `brand`          = '$brand',
                    `model`          = '$model',
                    `year`           = '$year',
                    `color`          = '$color',
                    `serial_number`  = '$serial_number',
                    `type`           = '$type',
                    `updated_at`     = NOW()
                WHERE `id` = '$vehicle_id'
            ";
        }else{
            $sql ="
                INSERT INTO 
                `vehicles`( 
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
        $sql = "
            SELECT 
                vehicles.*,
                CONCAT(employees.name, ' ', employees.paternal_surname, ' ', employees.maternal_surname) AS employee_name
            FROM vehicles
            LEFT JOIN employees ON employees.id = vehicles.employee_id
            WHERE vehicles.deleted_at IS NULL
        ";
        return ejecutarConsulta($sql);
    }
    
   
    public function show( $data ) {
        $vehicle_id = $data['vehicle_id'];
        $sql = "SELECT * FROM vehicles WHERE id = '$vehicle_id' ";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function deleteItem ( $data ){
        $vehicle_id = $data['vehicle_id'];
        $sql="
        UPDATE 
        `vehicles` SET 
            `deleted_at`= NOW()
        WHERE `id`='$vehicle_id'";
        return ejecutarConsulta($sql);
    }
    

    public function assign ( $data ){
        $vehicle_id = $data['vehicle_id'];
        $employee_id = $data['employee_id'];
        $sql="
        UPDATE 
        `vehicles` SET
            `updated_at` = NOW(), 
            `employee_id`= '$employee_id'
        WHERE `id`='$vehicle_id'";
        return ejecutarConsulta($sql);
    }
 
}   
?>
