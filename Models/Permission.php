<?php
session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";

class Permission
{

    public function __construct()
    {
    }

    public function index (){
        $sql ="SELECT * FROM `users_types` WHERE deleted_at is null";
        return ejecutarConsulta($sql);
    }

    public function wies ( $data ){
        $sql ="SELECT * FROM `users_types` WHERE deleted_at is null";
        return ejecutarConsulta($sql);
    }

    public function store($data){
        $user_type_id = $data["user_type_id"] ?? null;
        $view_id = $data["view_id"] ?? null;
        $permission_view = $data["permission_view"] ?? null;
        $permission_create = $data["permission_create"] ?? null;
        $permission_update = $data["permission_update"] ?? null;
        $permission_delete = $data["permission_delete"] ?? null;

        $sql = "
            INSERT INTO `permissions`(
                `user_type_id`,
                `view_id`,
                `permission_create`,
                `permission_view`,
                `permission_update`, 
                `permission_delete`,
                `created_at`, 
                `updated_at`
            ) VALUES (
                '$user_type_id',
                '$view_id',
                '$permission_create',
                '$permission_view',
                '$permission_update',
                '$permission_delete',
                NOW(),
                NOW()
            )
        ";
        return ejecutarConsulta($sql);
    }

   
    


}
?>