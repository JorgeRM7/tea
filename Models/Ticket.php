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
        $date_filter_end = $data['date_filter_end'] ?? null;
        $branch_office_id =$data['branch_office'];
        $user_id = $_SESSION['user_id'];
        $user_type_id = $_SESSION['user_type_id'];

        $sql ="
            SELECT
                tickets.id,
                tickets.payment_method,
                tickets.price,
                tickets.status,
                tickets.hour,
                tickets.date,
                tickets.discount,
                routes_stop.origin,
                routes_stop.destination,
                CONCAT(employees.name,' ', employees.paternal_surname, ' ', employees.maternal_surname) AS employee,
                vehicles.unidad_number,
                routes_schedule.leaving_time
                
            FROM `tickets`
            INNER JOIN routes_stop ON routes_stop.id = tickets.route_stop_id
            LEFT JOIN routes_schedule ON tickets.route_schedule_id=routes_schedule.id
            LEFT JOIN employees ON employees.id = tickets.employee_id
            LEFT JOIN vehicles ON vehicles.id = tickets.vehicle_id
            WHERE branch_office_id = '$branch_office_id'
        ";

        if ( $user_type_id != 1 ) {
            $sql .= " AND tickets.user_id = '".$user_id. "'";
        }

        if (!empty($date)) {
            $sql .= " AND tickets.date >= '$date' AND tickets.date <='$date_filter_end'";
        }

        $sql .= " ORDER BY tickets.date DESC";
        return ejecutarConsulta($sql);
    }

    public function store($data){
        date_default_timezone_set('America/Mexico_City');
        $today = date("Y-m-d H:i:s");
        $route_schedule_id = $data["route_schedule_id"] ?? null;
        $employee_id = $data["employee_id"] ?? null;
        $route_id = $data["route_id"] ?? null;
        $vehicle_id = $data["vehicle_id"] ?? null;
        $price = $data["price"] ?? null;
        $quantity = $data["quantity"] ?? null;
        $user_id = $_SESSION['user_id'];
        $branch_office_id = $data['branch_office_id'];
        $routes_stop_id = $data['routes_stop_id'];
        $discount = $data['discount'];
        $tickets_ids = [];
        $date = date("Y-m-d");
        $hour  = date("H:i:s");  
        $expiration_date = date('Y-m-d', strtotime($date . ' +1 day'));
        $route_discount_id = $data['route_discount_id'];

        $sql_sale = "SELECT IFNULL(MAX(sale_id), 0) + 1 AS next_sale_id FROM tickets";
        $rs = ejecutarConsulta($sql_sale);
        $row = mysqli_fetch_assoc($rs);

        $sale_id = (int)$row['next_sale_id'] ?? 1;

        for ($i = 1; $i <= $quantity; $i++) {
            $sql = "
                INSERT INTO `tickets`(
                    `route_schedule_id`,
                    `route_id`,
                    `employee_id`,
                    `vehicle_id`,
                    `branch_office_id`,
                    `user_id`,
                    `route_stop_id`,
                    `route_discount_id`,
                    `quantity`, 
                    `payment_method`,
                    `price`,
                    `status`, 
                    `date`,
                    `hour`,
                    `discount`,
                    `expires_at`,
                    `sale_id`,
                    `created_at`, 
                    `updated_at`
                ) VALUES (
                    '$route_schedule_id',
                    '$route_id',
                    '$employee_id',
                    '$vehicle_id',
                    '$branch_office_id',
                    '$user_id',
                    '$routes_stop_id',
                    '$route_discount_id',
                    '1',
                    'EFECTIVO',
                    '$price',
                    'VENDIDO',
                    '$date',
                    '$hour',
                    '$discount',
                    '$expiration_date',
                    '$sale_id',
                    '$today',
                    '$today'
                )
            ";
            $result = ejecutarConsulta(sql: $sql);

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
                    routes_schedule.id AS route_schedule_id,
                    vehicles.capacity AS vehicle_capacity,
                    vehicles.id AS vehicle_id,
                    (SELECT COUNT(id) FROM tickets WHERE route_schedule_id = routes_schedule.id AND status ='VENDIDO') AS tickets_sale
                FROM `routes_schedule` 
                INNER JOIN routes ON routes.id = routes_schedule.route_id
                LEFT JOIN vehicles ON vehicles.id = routes_schedule.vehicle_id
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
        $route_schedule_id = $data['route_schedule_id'] ?? 0;
        $route_stop_id = $data['route_stop_id'] ?? 0;
        
        $sql = "SELECT 
                    routes.id AS route_id,
                    routes_schedule.id AS route_schedule_id,
                    vehicles.unidad_number AS vehicle_id,
                    vehicles.capacity AS vehicle_capacity,
                    employees.id AS employee_id,
                    routes_schedule.leaving_time,
                    routes_schedule.date,
                    routes_stop.origin,
                    routes_stop.destination,
                    routes_stop.price,
                    vehicles.type,
                    vehicles.model,
                    employees.name,
                    (SELECT COUNT(id) FROM tickets WHERE route_schedule_id ='$route_schedule_id' AND status ='VENDIDO') AS tickets_sale
                FROM `routes_schedule`
                LEFT JOIN routes ON routes.id = routes_schedule.route_id
                INNER JOIN routes_stop ON routes_stop.route_id = routes.id
                LEFT JOIN vehicles ON vehicles.id = routes_schedule.vehicle_id
                LEFT JOIN employees ON employees.id = vehicles.employee_id
                WHERE routes_schedule.id='$route_schedule_id' AND routes_stop.id ='$route_stop_id'";
                // echo $sql;
        return ejecutarConsultaSimpleFila($sql);
    }

    public function tickets ( $data ){
        $date = $data['date'];
        $date_filter_end = $data['date_filter_end'] ?? null;
        $user_id = $_SESSION['user_id'];
        $user_type_id = $_SESSION['user_type_id'];
        $branch_office_id = $data['branch_office_id'];
        $sql = "
            SELECT 
                SUM(CASE WHEN status = 'VENDIDO' THEN 1 ELSE 0 END) AS vendidos,
                SUM(CASE WHEN status = 'CANCELADO' THEN 1 ELSE 0 END) AS cancelados,
                SUM(CASE WHEN status = 'VENDIDO' THEN price - discount ELSE 0 END) AS importe_total,
                COUNT(*) AS total
            FROM tickets
            WHERE branch_office_id = $branch_office_id
        ";

        if (!empty($date)) {
            $sql .= " AND tickets.date >= '$date' AND tickets.date <='$date_filter_end'";
        }

        if ( $user_type_id != 1 ) {
            $sql .= " AND tickets.user_id = '".$user_id. "'";
        }

        $sql .= " ORDER BY tickets.date DESC";

        return ejecutarConsultaSimpleFila($sql);
    }

    public function tickets_today (){
        $today    = date("Y-m-d");
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT COUNT( id ) AS tickets_today FROM tickets WHERE date ='$today' AND user_id ='$user_id'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function discounts ( $data ){
        $date = $data['search_date'] ?? null;
        $search_route = $data['search_route'] ?? null;
        $sql = "
            SELECT 
                routes_discounts.*,
                COUNT(tickets.id) AS tickets
            FROM routes_discounts 
            LEFT JOIN tickets ON tickets.route_discount_id = routes_discounts.id
            WHERE routes_discounts.start_date<='$date'
            AND routes_discounts.end_date>='$date' 
            AND routes_discounts.status ='active' 
            AND routes_discounts.deleted_at is null AND routes_discounts.route_id='$search_route'
            GROUP BY routes_discounts.id
            HAVING COUNT(tickets.id) < routes_discounts.ticket_amount;";
        return ejecutarConsulta($sql);
    }

    public function deleteItem ( $data ){
        $ticket_id = $data['ticket_id'];
        $sql = "UPDATE `tickets` SET `status`='CANCELADO',`updated_at`= NOW() WHERE `id`='$ticket_id'";
        return ejecutarConsulta($sql);
    }

    public function show_subpaths ( $data ){
        $route_id = $data['route_id'] ?? null;
        $sql = "SELECT * FROM `routes_stop` WHERE route_id='$route_id' AND deleted_at is null";
        return ejecutarConsulta($sql);
    }

    public function show ( $data ){
        $ticket_id = $data['ticket_id'] ?? null;
        $sql = "SELECT * FROM `tickets` WHERE id='$ticket_id'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function check_ticket ( $data ){
        $ticket_id = $data['ticket_id'];
        $today = date("Y-m-d H:i:s");
        $sql = "UPDATE `tickets` SET `status_check`='VALIDADO', `date_check`='$today', `updated_at`= NOW() WHERE `id`='$ticket_id'";
        return ejecutarConsulta($sql);
    }

    public function xls ( $data ){
        $date_start = $data['date_start'] ?? null;
        $date_end = $data['date_end'] ?? null;
        $branch_office_id =$data['branch_office_id'];

        $sql ="
            SELECT
                tickets.id,
                tickets.payment_method,
                tickets.price,
                tickets.status,
                tickets.hour,
                tickets.date,
                tickets.discount,
                routes_stop.origin,
                routes_stop.destination,
                CONCAT(employees.name,' ', employees.paternal_surname, ' ', employees.maternal_surname) AS employee,
                vehicles.unidad_number,
                routes_schedule.leaving_time
                
            FROM `tickets`
            INNER JOIN routes_stop ON routes_stop.id = tickets.route_stop_id
            LEFT JOIN routes_schedule ON tickets.route_schedule_id=routes_schedule.id
            LEFT JOIN employees ON employees.id = tickets.employee_id
            LEFT JOIN vehicles ON vehicles.id = tickets.vehicle_id
            WHERE branch_office_id = '$branch_office_id' AND tickets.date >='$date_start' AND tickets.date <='$date_end'
        ";
    
        $sql .= " ORDER BY tickets.date DESC";
        $resultado = ejecutarConsulta($sql);
        $data = array();
        while ( $item = $resultado->fetch_object()) {
            $data[] = $item;
        }
        return $data;
    }
}
?>