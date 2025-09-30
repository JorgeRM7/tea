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

    public function permissions ( $data ){
        $permission_id = $data['permission_id'];
        $sql ="SELECT 
                permissions.id AS permission_id,
                permissions.permission_create,
                permissions.permission_view,
                permissions.permission_update,
                permissions.permission_delete,
                views.title AS view,
                views.id AS view_id
            FROM `permissions`
            INNER JOIN views ON views.id = permissions.view_id
            WHERE permissions.user_type_id='$permission_id' AND permissions.deleted_at is null";
        return ejecutarConsulta($sql);
    }

    public function update($data) {
        $ids = [];

        foreach ($data as $permiso) {
            $id = $permiso['permission_id'];
            $view = $permiso['view'];
            $create = $permiso['permission_create'];
            $read = $permiso['permission_view'];
            $update = $permiso['permission_update'];
            $delete = $permiso['permission_delete'];

            $sql = "
                UPDATE permissions 
                SET permission_create = '$create',
                    permission_view = '$read',
                    permission_update = '$update',
                    permission_delete = '$delete'
                WHERE id = '$id'
            ";
            $result = ejecutarConsulta($sql);
            if ($result) {
                $ids[] = $id;
            }
        }

        return $ids;
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

    public function deleteItem ( $data ){
        $permission_id = $data['permission_id'];
        $sql = "UPDATE `tickets` SET `deleted_at`= NOW() WHERE `user_type_id`='$permission_id'";
        return ejecutarConsulta($sql);
    }
   
    


}
?>