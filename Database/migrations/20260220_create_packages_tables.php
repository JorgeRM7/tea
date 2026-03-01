<?php
require_once __DIR__ . '/../conexion.php';

echo '<strong>Migración módulo de paquetería</strong><br>';

$alterTicketsDelivery = "
ALTER TABLE `tickets_delivery`
    ADD COLUMN IF NOT EXISTS `branch_office_destination_id` INT NULL AFTER `branch_office_id`,
    ADD COLUMN IF NOT EXISTS `vehicle_id` INT NULL AFTER `route_schedule_id`,
    ADD COLUMN IF NOT EXISTS `employee_id` INT NULL AFTER `vehicle_id`,
    ADD COLUMN IF NOT EXISTS `tracking_code` VARCHAR(40) NULL AFTER `employee_id`,
    ADD COLUMN IF NOT EXISTS `tracking_pin` CHAR(4) NULL AFTER `tracking_code`,
    ADD COLUMN IF NOT EXISTS `status` VARCHAR(40) NOT NULL DEFAULT 'CREADO' AFTER `tracking_pin`,
    ADD COLUMN IF NOT EXISTS `status_changed_at` DATETIME NULL AFTER `status`,
    ADD COLUMN IF NOT EXISTS `user_id` INT NULL AFTER `status_changed_at`,
    ADD COLUMN IF NOT EXISTS `sender_name` VARCHAR(150) NULL AFTER `user_id`,
    ADD COLUMN IF NOT EXISTS `sender_phone` VARCHAR(25) NULL AFTER `sender_name`,
    ADD COLUMN IF NOT EXISTS `receiver_name` VARCHAR(150) NULL AFTER `sender_phone`,
    ADD COLUMN IF NOT EXISTS `receiver_phone` VARCHAR(25) NULL AFTER `receiver_name`,
    ADD COLUMN IF NOT EXISTS `package_weight` DECIMAL(8,2) NULL AFTER `receiver_phone`,
    ADD COLUMN IF NOT EXISTS `declared_value` DECIMAL(10,2) NULL AFTER `package_weight`,
    ADD COLUMN IF NOT EXISTS `notes` TEXT NULL AFTER `declared_value`,
    ADD COLUMN IF NOT EXISTS `photo_path` VARCHAR(255) NULL AFTER `notes`;
";

if ($conexion->query($alterTicketsDelivery) === TRUE) {
    echo '✔️ Columnas agregadas/actualizadas en tickets_delivery<br>';
} else {
    echo '❌ Error al modificar tickets_delivery: ' . $conexion->error . '<br>';
}

$createEventsTable = "
CREATE TABLE IF NOT EXISTS `tickets_delivery_events` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `tickets_delivery_id` INT NOT NULL,
    `status` VARCHAR(40) NOT NULL,
    `notes` TEXT NULL,
    `branch_office_id` INT NULL,
    `user_id` INT NULL,
    `photo_path` VARCHAR(255) NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_delivery_event_ticket`
        FOREIGN KEY (`tickets_delivery_id`) REFERENCES `tickets_delivery` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

if ($conexion->query($createEventsTable) === TRUE) {
    echo '✔️ Tabla tickets_delivery_events creada<br>';
} else {
    echo '❌ Error al crear tickets_delivery_events: ' . $conexion->error . '<br>';
}

$createUploadsTable = "
CREATE TABLE IF NOT EXISTS `tickets_delivery_media` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `tickets_delivery_id` INT NOT NULL,
    `event_id` INT NULL,
    `file_path` VARCHAR(255) NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_delivery_media_ticket`
        FOREIGN KEY (`tickets_delivery_id`) REFERENCES `tickets_delivery` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `fk_delivery_media_event`
        FOREIGN KEY (`event_id`) REFERENCES `tickets_delivery_events` (`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

if ($conexion->query($createUploadsTable) === TRUE) {
    echo '✔️ Tabla tickets_delivery_media creada<br>';
} else {
    echo '❌ Error al crear tickets_delivery_media: ' . $conexion->error . '<br>';
}

$setDefaults = "UPDATE `tickets_delivery` SET `status` = 'CREADO' WHERE `status` IS NULL OR `status` = ''";
$conexion->query($setDefaults);

$seedTrackingCodes = "
UPDATE `tickets_delivery`
SET `tracking_code` = CONCAT('PKG', LPAD(`id`, 8, '0'))
WHERE `tracking_code` IS NULL OR `tracking_code` = '';
";
$conexion->query($seedTrackingCodes);

$seedPins = "
UPDATE `tickets_delivery`
SET `tracking_pin` = LPAD(FLOOR(RAND() * 10000), 4, '0')
WHERE `tracking_pin` IS NULL OR `tracking_pin` = '';
";
$conexion->query($seedPins);

$setStatusDate = "
UPDATE `tickets_delivery`
SET `status_changed_at` = IFNULL(`status_changed_at`, NOW())
WHERE `status_changed_at` IS NULL;
";
$conexion->query($setStatusDate);

$addTrackingIndex = "
ALTER TABLE `tickets_delivery`
    ADD UNIQUE KEY `idx_tracking_code` (`tracking_code`);
";
$conexion->query($addTrackingIndex);

echo 'Migración completada.';
?>

