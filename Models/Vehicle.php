<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once dirname(__DIR__) . "/Database/conexion.php";

class Vehicle {

    public function __construct() {}
    
    
    public function store( $data ) {
        $vehicle_id = $data["vehicle_id"];
        $unidad_number = $data["unidad_number"];
        $plate_number = $data["plate_number"];
        $brand = $data["brand"];
        $model = $data["model"];
        $year = $data["year"];
        $color = $data["color"];
        $serial_number = $data["serial_number"];
        $type = $data["type"];
        $vehicle_id = $data["vehicle_id"];
        $capacity = $data["capacity"];
        
       

        if( $vehicle_id ){
            $sql="
                UPDATE `vehicles` SET 
                    `unidad_number`  = '$unidad_number',
                    `plate_number`   = '$plate_number',
                    `brand`          = '$brand',
                    `model`          = '$model',
                    `year`           = '$year',
                    `color`          = '$color',
                    `serial_number`  = '$serial_number',
                    `type`           = '$type',
                    `capacity`       = '$capacity',
                    `updated_at`     = NOW()
                WHERE `id` = '$vehicle_id'
            ";
        }else{
            $sql ="
                    INSERT INTO 
                    `vehicles`( 
                        `unidad_number`,
                        `plate_number`,
                        `brand`,
                        `model`,
                        `year`,
                        `color`,
                        `serial_number`,
                        `type`,
                        `capacity`,
                        `status`,
                        `created_at`,
                        `updated_at`
                    ) VALUES (
                        '$unidad_number',
                        '$plate_number',
                        '$brand',
                        '$model',
                        '$year',
                        '$color',
                        '$serial_number',
                        '$type',
                        '$capacity',
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

    public function change_status ( $data ){
        $vehicle_id = $data['vehicle_status_id'];
        $status = $data['status'];
        $sql="
        UPDATE 
        `vehicles` SET
            `updated_at` = NOW(), 
            `status`= '$status'
        WHERE `id`='$vehicle_id'";
        return ejecutarConsulta($sql);
    }

    public function vehicles (){
        $sql = "SELECT id, unidad_number, brand, model FROM vehicles WHERE deleted_at IS NULL";
        return ejecutarConsulta($sql);
    }  
 
    public function isAvailable($vehicle_id, $start_date, $end_date, $excludeTripId = null) {
        $excludeClause = $excludeTripId ? "AND id != '$excludeTripId'" : "";
        $sql = "SELECT COUNT(*) as total 
                FROM special_trips
                WHERE vehicle_id = '$vehicle_id'
                AND status = 'in_progress'
                AND deleted_at IS NULL
                AND (
                    (start_date <= '$end_date' AND end_date >= '$start_date')
                )
                $excludeClause";
        $res = ejecutarConsultaSimpleFila($sql);
        return ($res && $res['total'] == 0); // true si está libre
    }

    public function setStatus($vehicle_id, $status) {
        $sql = "UPDATE vehicles SET status = '$status', updated_at = NOW() WHERE id = '$vehicle_id'";
        return ejecutarConsulta($sql);
    }

    public function refreshStatus($vehicle_id) {
        $sql = "SELECT COUNT(*) as total 
                FROM special_trips 
                WHERE vehicle_id = '$vehicle_id' 
                AND status = 'in_progress' 
                AND deleted_at IS NULL";
        $res = ejecutarConsultaSimpleFila($sql);

        if ($res && $res['total'] > 0) {
            $this->setStatus($vehicle_id, "occupied");
        } else {
            $this->setStatus($vehicle_id, "active");
        }
    }


}   
?>
