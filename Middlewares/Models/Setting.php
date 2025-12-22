<?php
session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";

class Setting
{
    public function __construct()
    {
    }

    public function all(): array
    {
        $sql = "SELECT setting_key, setting_value FROM settings WHERE deleted_at IS NULL";
        $result = ejecutarConsulta($sql);
        $settings = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
        }
        return $settings;
    }

    public function get(string $key, $default = null)
    {
        $key = limpiarCadena($key);
        $sql = "SELECT setting_value FROM settings WHERE setting_key = '$key' LIMIT 1";
        $row = ejecutarConsultaSimpleFila($sql);
        if ($row && isset($row['setting_value'])) {
            return $row['setting_value'];
        }
        return $default;
    }

    public function set(string $key, $value): bool
    {
        global $conexion;
        $key = limpiarCadena($key);
        $value = mysqli_real_escape_string($conexion, trim((string)$value));

        $sql = "
            INSERT INTO settings (setting_key, setting_value, created_at, updated_at)
            VALUES ('$key', '$value', NOW(), NOW())
            ON DUPLICATE KEY UPDATE
                setting_value = VALUES(setting_value),
                updated_at = NOW(),
                deleted_at = NULL
        ";
        return ejecutarConsulta($sql) ? true : false;
    }

    public function saveMany(array $data): bool
    {
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }
        return true;
    }
}
