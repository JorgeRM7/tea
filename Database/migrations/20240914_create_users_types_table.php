<?php
require_once __DIR__ . "/../connection.php";

$sql = "
CREATE TABLE IF NOT EXISTS users_types (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) COLLATE utf8mb4_general_ci NULL,
    description VARCHAR(255) COLLATE utf8mb4_general_ci NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
";

if ($conexion->query($sql) === TRUE) {
    echo '✅ Migración ejecutada: users_types' . PHP_EOL;
} else {
    echo '❌ Error en migración users_types: ' . $conexion->error . PHP_EOL;
}
