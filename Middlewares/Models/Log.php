<?php
require_once dirname(__DIR__) . "/Database/conexion.php";

class ActivityLog
{
    /**
     * Guarda un registro en activity_log
     *
     * @param int|null    $user_id       ID del usuario (si aplica)
     * @param string|null $reference_id  ID relacionado (ticket_id, payment_id, etc.)
     * @param string      $event_type    Tipo de evento (payment_webhook, ticket_created, login, etc.)
     * @param string|null $message       Mensaje descriptivo
     * @param mixed|null  $payload       Datos adicionales (array, objeto o string JSON)
     * @param string|null $status        Estado del evento (approved, rejected, fail, etc.)
     */
    public static function save($user_id = null, $reference_id = null, $event_type, $message = null, $payload = null, $status = null)
    {
        global $conexion;

        if (is_array($payload) || is_object($payload)) {
            $payload = json_encode($payload, JSON_UNESCAPED_UNICODE);
        }

        $user_id      = $user_id !== null ? (int)$user_id : 'NULL';
        $reference_id = $reference_id !== null ? "'" . mysqli_real_escape_string($conexion, $reference_id) . "'" : 'NULL';
        $event_type   = mysqli_real_escape_string($conexion, $event_type);
        $message      = $message !== null ? "'" . mysqli_real_escape_string($conexion, $message) . "'" : 'NULL';
        $payload      = $payload !== null ? "'" . mysqli_real_escape_string($conexion, $payload) . "'" : 'NULL';
        $status       = $status !== null ? "'" . mysqli_real_escape_string($conexion, $status) . "'" : 'NULL';

        $sql = "
            INSERT INTO activity_log (user_id, reference_id, event_type, message, payload, date, created_at)
            VALUES (
                $user_id,
                $reference_id,
                '$event_type',
                $message,
                $payload,
                CURDATE(),
                NOW()
            )
        ";

        return ejecutarConsulta($sql);
    }
}
