<?php
require_once __DIR__ . "/../connection.php";

$sql = "
CREATE TABLE IF NOT EXISTS settings (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL,
    setting_value TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY unique_setting_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

if ($conexion->query($sql) === TRUE) {
    echo 'Configuraciòn: tabla settings creada' . PHP_EOL;
} else {
    echo 'Error en migración settings: ' . $conexion->error . PHP_EOL;
}
