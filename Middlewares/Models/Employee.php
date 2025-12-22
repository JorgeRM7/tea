<?php 
session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";

class Employee {

    public function __construct() {}
    
    
    public function store( $data ) {
        $employee_id = $data["employee_id"];
        $name = $data["name"];
        $paternal_surname = $data["paternal_surname"];
        $maternal_surname = $data["maternal_surname"];

        if( $employee_id ){
            $sql="
                UPDATE 
                `employees` SET 
                    `name`='$name',
                    `paternal_surname`='$paternal_surname',
                    `maternal_surname`='$maternal_surname',
                    `updated_at`= NOW()
                WHERE `id`='$employee_id'";
        }else{
            $sql ="
                INSERT INTO 
                `employees`(
                    `name`,
                    `paternal_surname`,
                    `maternal_surname`,
                    `status`,
                    `created_at`,
                    `updated_at`
                ) VALUES (
                    '$name',
                    '$paternal_surname',
                    '$maternal_surname',
                    'active',
                    NOW(),
                    NOW()
                )
            ";
        }
        return ejecutarConsulta($sql);
    }
    
    public function index() {
        $sql = "SELECT * FROM employees WHERE deleted_at IS NULL";
        return ejecutarConsulta($sql);
    }
    
   
    public function show( $data ) {
        $employee_id = $data['employee_id'];
        $sql = "SELECT * FROM employees WHERE id = '$employee_id' ";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function deleteItem ( $data ){
        $employee_id = $data['employee_id'];
        $sql="
        UPDATE 
        `employees` SET 
            `deleted_at`= NOW()
        WHERE `id`='$employee_id'";
        return ejecutarConsulta($sql);
    }
    
 
}   
?>
