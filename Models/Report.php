<?php 
session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";

class Report {

    public function __construct() {}
    
   

    public function index($data) {

        $start_date  = $data['date_start'] ?? '2026-02-01';
        $end_date    = $data['date_end'] ?? '2026-02-28';
        $user_id = $_SESSION['user_id'];

      

        
        $sql = "
            SELECT 
                tickets.id,
                tickets.payment_method AS 'TIPO PAGO',
                tickets.price AS PRECIO,
                tickets.status AS ESTATUS,
                tickets.hour AS HORA,
                tickets.date AS FECHA,
                tickets.discount AS DESCUENTO,
                routes_stop.origin AS ORIGEN,
                routes_stop.destination AS DESTINO,
                CONCAT(employees.name,' ', employees.paternal_surname, ' ', employees.maternal_surname) AS CHOFER,
                vehicles.unidad_number AS 'NUMERO UNIDAD',
                routes_schedule.leaving_time AS 'HORA SALIDA',
                users.name AS VENDEDOR
            FROM `tickets`
            INNER JOIN routes_stop ON routes_stop.id = tickets.route_stop_id
            LEFT JOIN routes_schedule ON tickets.route_schedule_id=routes_schedule.id
            LEFT JOIN employees ON employees.id = tickets.employee_id
            LEFT JOIN vehicles ON vehicles.id = tickets.vehicle_id
            INNER JOIN users ON users.id = tickets.user_id
            WHERE 
                tickets.status='VENDIDO' 
                AND tickets.date >='$start_date' 
                AND tickets.date <='$end_date'
                AND tickets.user_id ='$user_id'
        ";

        $query   = ejecutarConsulta($sql);
        $result  = [];

        while ($row = $query->fetch_assoc()) {
            $result[] = $row;
        }

        $sql_paquetes ="
            SELECT 
                tickets_delivery.id,
                tickets_delivery.price AS PRECIO,
                tickets_delivery.quantity AS CANTIDAD,
                tickets_delivery.description AS DESCRIPCION,
                routes_stop.origin AS ORIGEN,
                routes_stop.destination AS DESTINO,
                CONCAT(employees.name,' ', employees.paternal_surname, ' ', employees.maternal_surname) AS CHOFER,
                vehicles.unidad_number AS 'NUMERO UNIDAD',
                routes_schedule.leaving_time AS 'HORA SALIDA',
                users.name AS VENDEDOR,
                tickets_delivery.date AS FECHA
            FROM `tickets_delivery`
            INNER JOIN routes_stop ON routes_stop.id = tickets_delivery.route_stop_id
            LEFT JOIN routes_schedule ON tickets_delivery.route_schedule_id=routes_schedule.id
            LEFT JOIN employees ON employees.id = tickets_delivery.employee_id
            LEFT JOIN vehicles ON vehicles.id = tickets_delivery.vehicle_id
            INNER JOIN users ON users.id = tickets_delivery.user_id
            WHERE 
                tickets_delivery.date >='$start_date' 
                AND tickets_delivery.date <='$end_date'
                AND tickets_delivery.user_id ='$user_id'
        ";

        $query_paquetes   = ejecutarConsulta($sql_paquetes);
        $result_paquetes  = [];

        while ($row = $query_paquetes->fetch_assoc()) {
            $result_paquetes[] = $row;
        }

        return [
            "boletos"   => $result,
            "paquetes"  => $result_paquetes
        ];
    }


    

}   
?>
