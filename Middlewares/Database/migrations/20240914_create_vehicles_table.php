<?php
require_once __DIR__ . "/../connection.php";

$sql = "
CREATE TABLE IF NOT EXISTS vehicles (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    plate_number VARCHAR(15) COLLATE utf8mb4_general_ci NULL,
    brand VARCHAR(50) COLLATE utf8mb4_general_ci NULL,
    model VARCHAR(50) COLLATE utf8mb4_general_ci NULL,
    year INT(11) NULL,
    color VARCHAR(30) COLLATE utf8mb4_general_ci NULL,
    serial_number VARCHAR(50) COLLATE utf8mb4_general_ci NULL,
    type VARCHAR(30) COLLATE utf8mb4_general_ci NULL,
    status ENUM('active','inactive','maintenance') COLLATE utf8mb4_general_ci NULL,
    capacity INT(11) NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
";

if ($conexion->query($sql) === TRUE) {
    echo '✅ Migración ejecutada: vehicles' . PHP_EOL;
} else {
    echo '❌ Error en migración vehicles: ' . $conexion->error . PHP_EOL;
}
