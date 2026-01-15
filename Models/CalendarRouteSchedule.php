<?php 
// session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";

class Calendar {

    public function __construct() {}
    

    public function store($data) {
        $route_id    = $data["route_id"] ?? null;
        $date = $data["date"] ?? null;
        $time = $data["time"] ?? null;
        $vehicle_id =20;


        $dateObj = new DateTime($date);

        $year = (int)$dateObj->format("o");
        $week = (int)$dateObj->format("W");
        $dayNumber = (int)$dateObj->format("N");
        $dayNameEn = strtolower($dateObj->format("l"));

        $day = $dayNameEn;

        

        $results = [];

        foreach ($data["time"] as $time) {
            $sql = "
                INSERT INTO `routes_schedule` (
                    `route_id`,
                    `vehicle_id`,
                    `leaving_time`,
                    `day`,
                    `week`,
                    `year`,
                    `date`,
                    `created_at`,
                    `updated_at`
                ) VALUES (
                    '$route_id',
                    '$vehicle_id',
                    '$time',
                    '$day',
                    '$week',
                    '$year',
                    '$date',
                    NOW(),
                    NOW()
                )
            ";
            $new_id = ejecutarConsulta_retornarID($sql);
            $results[] = [
                "id"           => $new_id,
                "vehicle_id"   => $vehicle_id,
                "day"          => $day,
                "leaving_time" => $time,
                "week"         => $week,
                "year"         => $year,
                "action"       => "inserted"
            ];


        }

        return $results;
    }

    public function routes( $data ) {
        
        $search_route    = $data['search_route'] ?? NULL;
        // $search_date = $data['search_date'];
        $contador = 0;

        $sql_check = "SELECT COUNT(id) AS contador FROM `routes_schedule` WHERE date='2026-01-15' AND deleted_at IS NULL AND route_id='$search_route'";
        $result_check = ejecutarConsulta( $sql_check );
        while ($row = $result_check->fetch_assoc()) {
            $contador = $row['contador'];
        }

        if ($contador == 0) {
            $search_date = '2026-01-15';
        } else {
            $search_date = $data['search_date'] ?? '2026-01-15';
        }


        $sql = "SELECT * FROM `routes_schedule` WHERE date='2026-01-15' AND deleted_at IS NULL AND route_id='$search_route'";
        $sql .= " ORDER BY routes_schedule.leaving_time ASC";

        return ejecutarConsulta($sql);
    }

    // public function index() {
    //     $sql = "SELECT * FROM routes WHERE deleted_at IS NULL";
    //     return ejecutarConsulta($sql);
    // }
    
    // public function show( $data ) {
    //     $routes_schedule_id = $data['routes_schedule_id'];
    //     $sql = "SELECT * FROM routes_schedule WHERE id = '$routes_schedule_id' ";
    //     return ejecutarConsultaSimpleFila($sql);
    // }

    // public function deleteItem ( $data ){
    //     $routes_schedule_id = $data['routes_schedule_id'];
    //     $sql="
    //     UPDATE 
    //     `routes_schedule` SET 
    //         `deleted_at`= NOW()
    //     WHERE `id`='$routes_schedule_id'";
    //     return ejecutarConsulta($sql);
    // }

    // public function show_route( $data ) {
    //     $routes_schedule_id = $data['routes_schedule_id'];
    //     $sql = "
    //         SELECT 
    //             routes_schedule.leaving_time,
    //             routes_schedule.day,
    //             routes.origin,
    //             routes.destination,
    //             routes.cost,
    //             vehicles.plate_number,
    //             vehicles.type,
    //             employees.name
                
    //         FROM `routes_schedule`
    //         INNER JOIN routes ON routes.id = routes_schedule.route_id
    //         INNER JOIN vehicles ON vehicles.id = routes_schedule.vehicle_id
    //         LEFT JOIN employees ON employees.id = vehicles.employee_id
    //         WHERE routes_schedule.id = '$routes_schedule_id' ";
    //     return ejecutarConsultaSimpleFila($sql);
    // }


    // public function show_schedules( $data ) {
    //     $route_id = $data['route_id'];
    //     $week = $data['week'];
    //     $year = $data['year'];

    //     $sql = "
    //         SELECT 
    //             routes_schedule.*,
    //             vehicles.unidad_number
    //         FROM routes_schedule 
    //         INNER JOIN vehicles ON vehicles.id= routes_schedule.vehicle_id
    //         WHERE route_id='$route_id' 
    //         AND routes_schedule.week=$week AND routes_schedule.year=$year 
    //         AND routes_schedule.deleted_at IS NULL";
    //     return ejecutarConsulta($sql);
    // }

    // public function deleted_item ( $data ){
    //     $item_id_db = $data['item_id_db'];
    //     $sql="UPDATE `routes_schedule` SET `deleted_at`= NOW() WHERE `id`='$item_id_db'";
    //     return ejecutarConsulta($sql);
    // }

    // public function deleted_schedules ( $data ){
    //     $route_id = $data['route_id'];
    //     $week = $data['week'];
    //     $year = $data['year'];

    //     $sql="UPDATE `routes_schedule` SET `deleted_at`= NOW() WHERE `route_id`='$route_id' AND week = '$week' AND year='$year'";
    //     return ejecutarConsulta($sql);
    // }

    // public function deleted_schedules_by_vehicle ( $data ){
    //     $vehicle_id = $data['vehicle_id'];
    //     $week = $data['week'];
    //     $year = $data['year'];

    //     $sql="UPDATE `routes_schedule` SET `deleted_at`= NOW() WHERE `vehicle_id`='$vehicle_id' AND week = '$week' AND year='$year'";
    //     return ejecutarConsulta($sql);
    // }
      
    
 
}   
?>
