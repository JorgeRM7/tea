<?php 
// session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";

class Calendar {

    public function __construct() {}
    

    public function store($data) {
        $route_id   = $data["route_id"] ?? null;
        $date       = $data["date"] ?? null;
        $vehicle_id = 0;

        $dateObj = new DateTime($date);

        $year      = (int)$dateObj->format("o");
        $week      = (int)$dateObj->format("W");
        $dayNameEn = strtolower($dateObj->format("l"));
        $day       = $dayNameEn;

        $results = [];
        $contador = 0;

        $delete = "DELETE FROM routes_schedule WHERE route_id = '$route_id' AND vehicle_id = 0 AND day='$day' AND week='$week' AND year='$year' AND date='$date'";
        ejecutarConsulta($delete);

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

            ejecutarConsulta($sql);
            $contador ++;
        }

        $results = [
            "total records" => $contador, 
            "action"        => "inserted_or_updated"
        ];

        return $results;
    }

    public function load_schedules ( $data ) {
        $route_id   = $data["search_route"] ?? null;
        $date       = $data["search_date"] ?? null;
        $vehicle_id = 0;

        $dateObj = new DateTime($date);

        $year      = (int)$dateObj->format("o");
        $week      = (int)$dateObj->format("W");
        $dayNameEn = strtolower($dateObj->format("l"));
        $day       = $dayNameEn;

        $sql_check = "SELECT COUNT(*) AS total FROM routes_schedule WHERE route_id = '$route_id' AND date = '$date' ";
        $result_check = ejecutarConsulta($sql_check);
        $row_check = $result_check->fetch_assoc();

        if (($row_check["total"] ?? 0) > 0) {
            return [
                "success" => true,
                "message" => "Ya existen horarios para esta ruta y esta fecha."
            ];
        }

        $sql ="SELECT * FROM `routes_static` WHERE route_id ='$route_id'";
        $result = ejecutarConsulta($sql);
        $contador = 0;
        while( $row = $result->fetch_assoc() ){
            $leaving_time = $row['leaving_time'];
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
                    '$leaving_time',
                    '$day',
                    '$week',
                    '$year',
                    '$date',
                    NOW(),
                    NOW()
                )
            ";

            ejecutarConsulta($sql);
            $contador ++;
        }

        return [
            "success" => true,
            "message" => "Horarios cargados: ".$contador
        ];

    }

    public function routes( $data ) {
        
        $search_route    = $data['search_route'] ?? NULL;
        $search_date     = $data["search_date"] ?? null;
        $sql = "SELECT * FROM `routes_schedule` WHERE route_id ='$search_route' AND date='$search_date' AND deleted_at IS NULL ORDER BY leaving_time ASC";

        return ejecutarConsulta($sql);
    }

    public function deleteItem ( $data ){
        $routes_schedule_id = $data['id'];
        $sql=" UPDATE `routes_schedule` SET `deleted_at`= NOW() WHERE `id`='$routes_schedule_id'";
        return ejecutarConsulta($sql);
    }
 
}   
?>
