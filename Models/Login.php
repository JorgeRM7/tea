<?php
require_once dirname(__DIR__) . "/Database/conexion.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class Login
{

    public function __construct() {}

    public function validar( $data ){
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
            $tiempo_sesion = 60 * 60 * 24;
            ini_set('session.gc_maxlifetime', $tiempo_sesion);
            ini_set('session.cookie_lifetime', $tiempo_sesion);
            session_set_cookie_params($tiempo_sesion);

            $_SESSION['nombre']     = $user->name;
            $_SESSION['id_usuario'] = $user->id;

            return [
                "status"  => "ok",
                "message" => "Login exitoso",
                "data"    => [
                    "id"    => $user->id,
                    "name"  => $user->name,
                    "email" => $user->email
                ]
            ];
        }

        return null;
    }


}
