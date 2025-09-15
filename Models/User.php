<?php
session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";

class User
{

    public function __construct()
    {
    }


    public function store($data)
    {
        $user_id = $data["user_id"];
        $name = $data["name"];
        $email = $data["email"];
        $username = $data["username"];
        $user_type = $data["user_type"];
        $password = $data["password"];
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        if ($user_id) {
            $sql = "
            UPDATE `users` SET 
                `name`   = '$name',
                `email`          = '$email',
                `username`          = '$username',
                `password`           = '$hashed_password',
                `updated_at`     = NOW()
            WHERE `id` = '$user_id'
    ";
        } else {
            $sql = "
                INSERT INTO `users`(
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
                `updated_at`,
                ) VALUES (
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
                    NULL,
                    NOW(),
                    NOW()
                )
            ";
        }
        return ejecutarConsulta($sql);
    }

    public function index()
    {
        $sql = "SELECT * FROM users WHERE deleted_at IS NULL";
        return ejecutarConsulta($sql);
    }


    public function show($data)
    {
        $user_id = $data['user_id'];
        $sql = "SELECT * FROM users WHERE id = '$user_id' ";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function deleteItem($data)
    {
        $user_id = $data['user_id'];
        $sql = "
        UPDATE 
        `Users` SET 
            `deleted_at`= NOW()
        WHERE `id`='$user_id'";
        return ejecutarConsulta($sql);
    }


}
?>