<?php
session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";

class User
{

    public function __construct()
    {
    }


    public function store($data){
        $user_id = $data["user_id"];
        $name = $data["name"];
        $email = $data["email"];
        $username = $data["username"];
        $branch_office_id = $data["branch_office_id"];
        $user_type_id = $data["user_type_id"];
        $password = $data["password"];
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $branch_office_ids = $data["branch_office_id"];


        if ($user_id) {
            $sql = "
                UPDATE `users` SET 
                    `user_type_id`      = '$user_type_id',
                    `name`              = '$name',
                    `email`             = '$email',
                    `username`          = '$username',
                    `updated_at`        =  NOW()
                WHERE `id`              = '$user_id'
            ";

            $sql_delete = "UPDATE branch_offices_user SET deleted_at = NOW()  WHERE user_id = '$user_id'";
            ejecutarConsulta($sql_delete);

            foreach ($branch_office_ids as $branch_id) {
                $sql_branch = "
                    INSERT INTO `branch_offices_user`(
                        `branch_office_id`,
                        `user_id`,
                        `create_at`,
                        `update_at`
                    ) VALUES (
                        '$branch_id',
                        '$user_id',
                        NOW(),
                        NOW()
                    )
                ";
                ejecutarConsulta($sql_branch);
            }
        } else {
            $sql = "
                INSERT INTO `users`(
                `user_type_id`,
                `branch_office_id`,
                `name`,
                `email`,
                `username`,
                `email_verified_at`,
                `password`,
                `two_factor_secret`,
                `two_factor_recovery_codes`,
                `two_factor_confirmed_at`,
                `remember_token`,
                `current_team_id`,
                `profile_photo_path`,
                `created_at`,
                `updated_at`
                ) VALUES (
                    '$user_type_id',
                    '$branch_office_id',
                    '$name',
                    '$email',
                    '$username',
                    NULL,
                    '$hashed_password',
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    NOW(),
                    NOW()
                )
            ";

            global $conexion;
            $new_user_id = mysqli_insert_id($conexion);

            foreach ($branch_office_ids as $branch_id) {
                $sql_branch = "
                    INSERT INTO `branch_offices_user`(
                        `branch_office_id`,
                        `user_id`,
                        `create_at`,
                        `update_at`
                    ) VALUES (
                        '$branch_id',
                        '$new_user_id',
                        NOW(),
                        NOW()
                    )
                ";
                ejecutarConsulta($sql_branch);
            }
        }
        return ejecutarConsulta($sql);
    }

    public function index()
    {
        $sql = "SELECT * FROM users WHERE deleted_at IS NULL";
        return ejecutarConsulta($sql);
    }


    public function show($data){
        $user_id = $data['user_id'];
        $sql = "
            SELECT 
                users.id,
                users.name,
                users.email,
                users.username,
                users.user_type_id,
                branch_offices_user.branch_office_id
            FROM users
            LEFT JOIN branch_offices_user ON branch_offices_user.user_id = users.id
            WHERE users.id = '$user_id' AND branch_offices_user.deleted_at is null";
        return ejecutarConsulta($sql);
    }

    public function deleteItem($data){
        $user_id = $data['user_id'];
        $sql = "
        UPDATE 
        `users` SET 
            `deleted_at`= NOW()
        WHERE `id`='$user_id'";
        return ejecutarConsulta($sql);
    }

    public function store_password( $data ){
        $password = $data["change_password"];
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $user_id = $data['user_password_id'];
        $sql = "
        UPDATE 
        `users` SET 
            `password` = '$hashed_password',
            `updated_at`= NOW()
        WHERE `id`='$user_id'";
        return ejecutarConsulta($sql);
    }

    public function branch_offices(){
        $sql = "SELECT * FROM `branch_offices` WHERE deleted_at is null";
        return ejecutarConsulta($sql);
    }


}
?>