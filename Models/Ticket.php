<?php
session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";

class Ticket
{

    public function __construct()
    {
    }

    public function index ( $data ){
        $date = $data['date'] ?? null;
        $sql ="
            SELECT 
                tickets.id,
                tickets.cost,
                tickets.payment_method,
                tickets.status,
                routes_schedule.date,
                routes_schedule.leaving_time,
                routes.origin,
                routes.destination,
                CONCAT(employees.name,' ', employees.paternal_surname, ' ', employees.maternal_surname) AS employee,
                vehicles.id AS vehicle_id
            FROM `tickets`
            LEFT JOIN routes_schedule ON tickets.route_schedule_id=routes_schedule.id
            LEFT JOIN routes ON routes.id = tickets.route_id
            LEFT JOIN employees ON employees.id = tickets.employee_id
            LEFT JOIN vehicles ON vehicles.id = tickets.vehicle_id

        ";
        if (!empty($date)) {
            $sql .= " WHERE tickets.date = '$date'";
        }

        $sql .= " ORDER BY tickets.date DESC";
        return ejecutarConsulta($sql);
    }

    public function store($data){
        $route_schedule_id = $data["route_schedule_id"] ?? null;
        $employee_id = $data["employee_id"] ?? null;
        $route_id = $data["route_id"] ?? null;
        $vehicle_id = $data["vehicle_id"] ?? null;
        $cost = $data["cost"] ?? null;
        $quantity = $data["quantity"] ?? null;
        $cost = $data["cost"] ?? null;
        $cost = $data["cost"] ?? null;
        $cost = $data["cost"] ?? null;
        $tickets_ids = [];
        $date = date("Y-m-d");
        $hour  = date("H:i:s");  

        for ($i = 1; $i <= $quantity; $i++) {
            $sql = "
                INSERT INTO `tickets`(
                    `route_schedule_id`,
                    `route_id`,
                    `employee_id`,
                    `vehicle_id`,
                    `quantity`, 
                    `payment_method`,
                    `cost`,
                    `status`, 
                    `date`,
                    `hour`,
                    `created_at`, 
                    `updated_at`
                ) VALUES (
                    '$route_schedule_id',
                    '$route_id',
                    '$employee_id',
                    '$vehicle_id',
                    '1',
                    'EFECTIVO',
                    '$cost',
                    'VENDIDO',
                    '$date',
                    '$hour',
                    NOW(),
                    NOW()
                )
            ";
            $result = ejecutarConsulta($sql);

            if ( $result ) {
                global $conexion;
                $tickets_ids[] = mysqli_insert_id($conexion);
            }
        }
        return $tickets_ids;
    }

    public function schedules($data) {
        $hour     = date("H:i:s"); 
        $today    = date("Y-m-d");
        $route_id = $data['route_id'];
        $date     = $data['date'];

        $sql = "SELECT * 
                FROM `routes_schedule` 
                WHERE route_id = '$route_id'";

        if ($date == $today) {
            $sql .= " AND date = '$date' AND leaving_time >= '$hour'";
        } elseif ($date > $today) {
            $sql .= " AND date = '$date'";
        } else {
            $sql .= " AND 1=0";
        }

        return ejecutarConsulta($sql);
    }


    public function routes($data) {
        $hour  = date("H:i:s"); 
        $today = date("Y-m-d");
        $search_date     = $data['search_date'] ?? NULL;
        $search_schedule = $data['search_schedule'] ?? NULL;
        $search_route    = $data['search_route'] ?? NULL;

        $sql = "SELECT 
                    routes.*,
                    routes_schedule.leaving_time,
                    routes_schedule.id AS route_schedule_id
                FROM `routes_schedule` 
                INNER JOIN routes ON routes.id = routes_schedule.route_id
                WHERE 1=1";

        if (!empty($search_date)) {
            if ($search_date == $today) {
                $sql .= " AND routes_schedule.date = '$search_date' 
                        AND routes_schedule.leaving_time >= '$hour'";
            } elseif ($search_date > $today) {
                $sql .= " AND routes_schedule.date = '$search_date'";
            } else {
                $sql .= " AND 1=0";
            }
        }

        if (!empty($search_schedule)) {
            $sql .= " AND routes_schedule.id = '$search_schedule'";
        }

        if (!empty($search_route)) {
            $sql .= " AND routes_schedule.route_id = '$search_route'";
        }

        $sql .= " ORDER BY routes_schedule.leaving_time ASC";

        return ejecutarConsulta($sql);
    }


    public function details( $data ){
        $route_schedule_id = $data['route_schedule_id'];
        $sql = "SELECT 
                    routes.id AS route_id,
                    routes_schedule.id AS route_schedule_id,
                    vehicles.id AS vehicle_id,
                    employees.id AS employee_id,
                    routes_schedule.leaving_time,
                    routes_schedule.date,
                    routes.origin,
                    routes.destination,
                    routes.cost,
                    vehicles.type,
                    vehicles.model,
                    employees.name
                FROM `routes_schedule`
                LEFT JOIN routes ON routes.id = routes_schedule.route_id
                LEFT JOIN vehicles ON vehicles.id = routes_schedule.vehicle_id
                LEFT JOIN employees ON employees.id = vehicles.employee_id
                WHERE routes_schedule.id='$route_schedule_id'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function tickets ( $data ){
        $date = $data['date'];
        $sql = "
            SELECT 
                SUM(CASE WHEN status = 'VENDIDO' THEN 1 ELSE 0 END) AS vendidos,
                SUM(CASE WHEN status = 'CANCELADO' THEN 1 ELSE 0 END) AS cancelados,
                COUNT(*) AS total
            FROM tickets
        ";
        if (!empty($date)) {
            $sql .= " WHERE tickets.date = '$date'";
        }

        $sql .= " ORDER BY tickets.date DESC";

        return ejecutarConsultaSimpleFila($sql);
    }

    public function deleteItem ( $data ){
        $ticket_id = $data['ticket_id'];
        $sql = "UPDATE `tickets` SET `status`='CANCELADO',`updated_at`= NOW() WHERE `id`='$ticket_id'";
        return ejecutarConsulta($sql);
    }
    


}
?>