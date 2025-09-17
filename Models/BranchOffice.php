<?php 
session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";

class BranchOffice {

    public function __construct() {}
    
    
    public function store($data){
        $branch_office_id = $data["branch_office_id"] ?? null;
        $code        = $data["code"] ?? null;
        $name        = $data["name"] ?? null;
        $description = $data["description"] ?? null;
        $address     = $data["address"] ?? null;
        $city        = $data["city"] ?? null;
        $state       = $data["state"] ?? null;
        $country     = $data["country"] ?? null;
        $postal_code = $data["postal_code"] ?? null;
        $phone       = $data["phone"] ?? null;
        $email       = $data["email"] ?? null;
        $status      = $data["status"] ?? 'active';

        if ($branch_office_id) {
            $sql = "
                UPDATE `branch_offices` SET 
                    `code`        = '$code',
                    `name`        = '$name',
                    `description` = '$description',
                    `address`     = '$address',
                    `city`        = '$city',
                    `state`       = '$state',
                    `country`     = '$country',
                    `postal_code` = '$postal_code',
                    `phone`       = '$phone',
                    `email`       = '$email',
                    `status`      = '$status',
                    `updated_at`  = NOW()
                WHERE `id` = '$branch_office_id'
            ";
        } else {
            $sql = "
                INSERT INTO `branch_offices` (
                    `code`,
                    `name`,
                    `description`,
                    `address`,
                    `city`,
                    `state`,
                    `country`,
                    `postal_code`,
                    `phone`,
                    `email`,
                    `status`,
                    `created_at`,
                    `updated_at`
                ) VALUES (
                    '$code',
                    '$name',
                    '$description',
                    '$address',
                    '$city',
                    '$state',
                    '$country',
                    '$postal_code',
                    '$phone',
                    '$email',
                    '$status',
                    NOW(),
                    NOW()
                )
            ";
        }

        return ejecutarConsulta($sql);
    }

    
    public function index() {
        $sql = "SELECT * FROM branch_offices WHERE deleted_at IS NULL";
        return ejecutarConsulta($sql);
    }
    
   
    public function show( $data ) {
        $branch_office_id = $data['branch_office_id'];
        $sql = "SELECT * FROM branch_offices WHERE id = '$branch_office_id' ";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function deleteItem ( $data ){
        $branch_office_id = $data['branch_office_id'];
        $sql="
        UPDATE 
        `branch_offices` SET 
            `deleted_at`= NOW()
        WHERE `id`='$branch_office_id'";
        return ejecutarConsulta($sql);
    }
}   
?>
