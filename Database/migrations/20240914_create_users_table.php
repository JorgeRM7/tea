<?php
require_once __DIR__ . "/../connection.php";

$sql = "
CREATE TABLE IF NOT EXISTS users (
    id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    email VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
    username VARCHAR(100) COLLATE utf8mb4_unicode_ci NULL,
    email_verified_at TIMESTAMP NULL DEFAULT NULL,
    password VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    two_factor_secret TEXT COLLATE utf8mb4_unicode_ci NULL,
    two_factor_recovery_codes TEXT COLLATE utf8mb4_unicode_ci NULL,
    two_factor_confirmed_at TIMESTAMP NULL DEFAULT NULL,
    remember_token VARCHAR(100) COLLATE utf8mb4_unicode_ci NULL,
    current_team_id BIGINT(20) UNSIGNED NULL,
    profile_photo_path VARCHAR(2048) COLLATE utf8mb4_unicode_ci NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    INDEX (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

if ($conexion->query($sql) === TRUE) {
    echo '✅ Migración ejecutada: users' . PHP_EOL;
} else {
    echo '❌ Error en migración users: ' . $conexion->error . PHP_EOL;
}
