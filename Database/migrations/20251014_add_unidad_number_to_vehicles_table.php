<?php
require_once __DIR__ . "/../conexion.php";  // ojo aquí: conexion.php, no connection.php

$sql = "
ALTER TABLE vehicles 
ADD COLUMN unidad_number INT(11) NULL AFTER id;
";

if ($conexion->query($sql) === TRUE) {
    echo "✅ Migración ejecutada: se agregó columna unidad_number en vehicles" . PHP_EOL;
} else {
    echo "❌ Error en migración vehicles: " . $conexion->error . PHP_EOL;
}
