<?php
require_once dirname(__DIR__) . "/Database/conexion.php";
require_once __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
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
                users.password,
                users.branch_office_id,
                users.user_type_id               
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
            $_SESSION['branch_office_id'] = $user->branch_office_id;
            $_SESSION['user_type_id'] = $user->user_type_id;


            $config = require __DIR__ . "/../Config/config.php";
            $key = $config['jwt_secret'];

            $payload = [
                "iss" => "http://tu-sistema.com", // quién emite
                "aud" => "http://tu-sistema.com", // quién recibe
                "iat" => time(),                  // emitido en
                "exp" => time() + (60 * 60),      // expira en 1 hora
                "data" => [
                    "id"    => $user->id,
                    "name"  => $user->name,
                    "email" => $user->email,
                    "branch_office_id" => $user->branch_office_id,
                    "user_type_id" => $user->user_type_id,
                ]
            ];

            $jwt = JWT::encode($payload, $key, 'HS256');

            return [
                "status"  => "ok",
                "message" => "Login exitoso",
                "token"   => $jwt,
                "user"    => [
                    "id"    => $user->id,
                    "name"  => $user->name,
                    "email" => $user->email,
                    "branch_office_id" => $user->branch_office_id,
                    "user_type_id" => $user->user_type_id,

                ]
            ];
        }

        return null;
    }


}
