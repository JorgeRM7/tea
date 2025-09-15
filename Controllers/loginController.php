<?php 
require_once "../Models/Login.php";
$login = new Login();

switch ($_GET["op"]) {
    

    case 'verificar':
        $usuario_data = $login->validar($_POST); 
        echo json_encode($usuario_data);
    break;

    
    case 'logout':
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Si existe la sesión, la limpiamos
        if (isset($_SESSION['id_usuario'])) {
            // Vaciar todas las variables de sesión
            $_SESSION = [];

            // Borrar la cookie de sesión en el navegador
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $params["path"],
                    $params["domain"],
                    $params["secure"],
                    $params["httponly"]
                );
            }

            // Finalmente destruir la sesión
            session_destroy();

            // Redirigir al login
            header("Location: ../Views/login.php");
            exit;
        } else {
            http_response_code(400);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                "error" => "No hay sesión activa",
            ]);
        }
    break;

    

    default:
        http_response_code(404);
        echo json_encode([
            "error" => "Operación no encontrada",
        ]);
        break;
}
