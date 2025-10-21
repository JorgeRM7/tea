<?php
require_once __DIR__ . "/../connection.php";

$sql = "
CREATE TABLE `special_trip_types` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `origin` VARCHAR(255) NOT NULL,      -- Ciudad origen
  `destination` VARCHAR(255) NOT NULL, -- Ciudad destino
  `days` INT(11) NOT NULL,             -- Cantidad de días del viaje
  `price` DECIMAL(10,2) NOT NULL,      -- Precio del viaje
  `valid_from` DATE DEFAULT NULL,      -- Vigencia desde
  `valid_to` DATE DEFAULT NULL,        -- Vigencia hasta
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active', -- Activo / Inactivo
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
";

ejecutarConsulta($sql);
