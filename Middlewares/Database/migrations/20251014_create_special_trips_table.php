<?php
require_once __DIR__ . "/../connection.php";

$sql = "
CREATE TABLE special_trips (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NULL,                         -- Cliente opcional
    vehicle_id INT NOT NULL,                    -- Unidad asignada
    trip_type_id INT NULL,                      -- Tipo de viaje opcional
    origin VARCHAR(255) NOT NULL,
    destination VARCHAR(255) NOT NULL,
    days INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    status ENUM('pending','in_progress','completed','cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id),
    FOREIGN KEY (trip_type_id) REFERENCES special_trip_types(id)
);

";

ejecutarConsulta($sql);


