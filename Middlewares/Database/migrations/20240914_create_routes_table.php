<?php
require_once __DIR__ . "/../connection.php";

$sql = "
CREATE TABLE IF NOT EXISTS routes (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    origin VARCHAR(255) NULL,
    destination VARCHAR(255) NULL,
    cost VARCHAR(10) NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

if ($conexion->query($sql) === TRUE) {
    echo '✅ Migración ejecutada: routes' . PHP_EOL;
} else {
    echo '❌ Error en migración routes: ' . $conexion->error . PHP_EOL;
}
