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

        
        if (isset($_SESSION['user_id'])) {
            $_SESSION = [];

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

            session_destroy();

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
