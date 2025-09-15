<?php
require_once __DIR__ . "/../connection.php";

$sql = "
CREATE TABLE IF NOT EXISTS employees (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NULL,
    paternal_surname VARCHAR(255) NULL,
    maternal_surname VARCHAR(255) NULL,
    status ENUM('active','termination') NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

if ($conexion->query($sql) === TRUE) {
    echo '✅ Migración ejecutada: employees' . PHP_EOL;
} else {
    echo '❌ Error en migración employees: ' . $conexion->error . PHP_EOL;
}
