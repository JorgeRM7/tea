<?php 
session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";

class Report {

    public function __construct() {}
    
   

    public function index($data) {

        $start_date  = $data['date_start'] ?? null;
        $end_date    = $data['date_end'] ?? null;
        $report_type = $data['report_type'];

        if (!$start_date || !$end_date) {
            return ["error" => "Rango de fechas inválido"];
        }

        switch ($report_type) {

            case "RUTA":
                $sql = "
                    SELECT 
                        CONCAT(routes.origin,' - ',routes.destination) AS Ruta,
                        SUM((tickets.price - COALESCE(CAST(REPLACE(REPLACE(TRIM(tickets.discount),',',''),' ','') AS DECIMAL(10,2)),0))) AS Importe,
                        COUNT(tickets.id) AS Boletos
                    FROM tickets
                    INNER JOIN routes ON routes.id = tickets.route_id
                    WHERE tickets.status='VENDIDO'
                    AND tickets.date BETWEEN '$start_date' AND '$end_date'
                    GROUP BY tickets.route_id;
                ";
                break;

            case "FECHA":
                $sql = "
                    SELECT 
                        tickets.date AS unidad,
                        SUM((tickets.price - COALESCE(CAST(REPLACE(REPLACE(TRIM(tickets.discount),',',''),' ','') AS DECIMAL(10,2)),0))) AS total_sales,
                        COUNT(tickets.id) AS total_tickets
                    FROM tickets
                    WHERE tickets.status='VENDIDO'
                    AND tickets.date BETWEEN '$start_date' AND '$end_date'
                    GROUP BY tickets.date
                    ORDER BY tickets.date ASC;
                ";
                break;

            case "USUARIO":
                $sql = "
                    SELECT 
                        users.name AS unidad,
                        SUM((tickets.price - COALESCE(CAST(REPLACE(REPLACE(TRIM(tickets.discount),',',''),' ','') AS DECIMAL(10,2)),0))) AS total_sales,
                        COUNT(tickets.id) AS total_tickets
                    FROM tickets
                    INNER JOIN users ON users.id = tickets.user_id
                    WHERE tickets.status='VENDIDO'
                    AND tickets.date BETWEEN '$start_date' AND '$end_date'
                    GROUP BY tickets.user_id;
                ";
                break;

            case "UNIDAD":
                $sql = "
                    SELECT 
                        vehicles.unidad_number AS unidad,
                        SUM((tickets.price - COALESCE(CAST(REPLACE(REPLACE(TRIM(tickets.discount),',',''),' ','') AS DECIMAL(10,2)),0))) AS total_sales,
                        COUNT(tickets.id) AS total_tickets
                    FROM tickets
                    INNER JOIN vehicles ON vehicles.id = tickets.vehicle_id
                    WHERE tickets.status='VENDIDO'
                    AND tickets.date BETWEEN '$start_date' AND '$end_date'
                    GROUP BY tickets.vehicle_id;
                ";
                break;

            default:
                $sql = "
                    SELECT 
                        COUNT(id) AS total_tickets,
                        SUM((price - COALESCE(CAST(REPLACE(REPLACE(TRIM(discount),',',''),' ','') AS DECIMAL(10,2)),0))) AS total_sales,
                        SUM(CASE WHEN status='CANCELADO' THEN 1 ELSE 0 END) AS cancelados
                    FROM tickets
                    WHERE status='VENDIDO'
                    AND date BETWEEN '$start_date' AND '$end_date';
                ";
                break;
        }

        $query   = ejecutarConsulta($sql);
        $result  = [];

        while ($row = $query->fetch_assoc()) {
            $result[] = $row;
        }

        return [
            "report_type"   => $report_type,
            "fecha_inicio"  => $start_date,
            "fecha_fin"     => $end_date,
            "resultados"    => $result,
        ];
    }


    

}   
?>
