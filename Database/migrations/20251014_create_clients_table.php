<?php
require_once __DIR__ . "/../connection.php";

$sql = "
CREATE TABLE `clients` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `email` VARCHAR(255) DEFAULT NULL,
  `address` JSON DEFAULT NULL, -- Dirección completa en JSON (street, number, neighborhood, city, state, zipcode)
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active', -- Activo / Inactivo
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
";

ejecutarConsulta($sql);
