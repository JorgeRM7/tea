<?php
require_once dirname(__DIR__) . "/Database/conexion.php";
class Login
{

    public function __construct()
    {
    }

    public function validar($data)
    {
        $user = $data['login_usuario'];
        $password = $data['login_clave'];

        $sql = "
            SELECT  
                users.id, 
                users.name,
                users.email, 
                users.password                
            FROM `users`
            WHERE (users.email = '$user' OR users.username = '$user')";

        $querym = ejecutarConsulta($sql);

        if ($querym->num_rows === 0) {
            return null;
        }

        $user = $querym->fetch_object();

        if (password_verify($password, $user->password)) {
            session_start();

            $_SESSION['user_id'] = $user->id;
            $_SESSION['name'] = $user->name;
            $_SESSION['email'] = $user->email;

            return [
                "status" => "ok",
                "message" => "Login exitoso",
                "data" => [
                    "id" => $user->id,
                    "name" => $user->name,
                    "email" => $user->email,
                ]
            ];
        }

        return null;
    }


}
