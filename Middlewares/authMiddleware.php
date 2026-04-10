<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function verificarToken() {
    $headers = apache_request_headers();

    if (!isset($headers['Authorization'])) {
        http_response_code(401);
        echo json_encode(["error" => "Token no proporcionado"]);
        exit;
    }

    $token = str_replace("Bearer ", "", $headers['Authorization']);
    $config = require __DIR__ . "/../Config/config.php";
    $key = $config['jwt_secret'];

    try {
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        return $decoded->data; // datos del usuario
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(["error" => "Token inválido o expirado"]);
        header("Location: ../Views/login.php");
        exit;
    }
}
