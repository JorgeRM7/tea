<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexión a la BD
require_once __DIR__ . "/conexion.php";

// Buscar todos los archivos dentro de /migrations
$migrations = glob(__DIR__ . "/migrations/*.php");

// Ordenarlos alfabéticamente (para que se ejecuten en orden)
sort($migrations);

echo "<h2>🚀 Ejecutando migraciones...</h2><ul>";

foreach ($migrations as $migration) {
    echo "<li>▶ Ejecutando: <strong>" . basename($migration) . "</strong><br>";
    include $migration;
    echo "</li>";
}

echo "</ul><h3>✅ Todas las migraciones se ejecutaron.</h3>";
