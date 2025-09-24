<?php 
// session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";

class RouteSchedule {

    public function __construct() {}
    
    
        public function store($data) {
            $route_id    = $data["route_id"] ?? null;
            $week_number = $data["week_number"] ?? null;
            $vehicles    = $data["vehicle_id"] ?? [];
            $days        = $data["day"] ?? [];
            $times       = $data["leaving_time"] ?? [];
            $ids         = $data["routes_schedule_id"] ?? [];
            list($year, $week) = explode("-W", $week_number);

            $day_map = [
                "monday"    => 1,
                "tuesday"   => 2,
                "wednesday" => 3,
                "thursday"  => 4,
                "friday"    => 5,
                "saturday"  => 6,
                "sunday"    => 7
            ];

            $results = [];

            foreach ($days as $index => $day) {
                $time        = $times[$index]    ?? null;
                $vehicle_id  = $vehicles[$index] ?? null;
                $schedule_id = $ids[$index]      ?? null;

                if ($day && $time && $vehicle_id) {
                    $dateObj = new DateTime();
                    $dateObj->setISODate((int)$year, (int)$week, $day_map[$day] ?? 1);
                    $date = $dateObj->format("Y-m-d");
                    if ($schedule_id) {
                        $sql = "
                            UPDATE `routes_schedule` SET
                                `route_id`     = '$route_id',
                                `vehicle_id`   = '$vehicle_id',
                                `leaving_time` = '$time',
                                `day`          = '$day',
                                `week`         = '$week',
                                `year`         = '$year',
                                `date`         = '$date',
                                `updated_at`   = NOW()
                            WHERE id = '$schedule_id'
                        ";
                        ejecutarConsulta($sql);
                        $results[] = [
                            "id"          => $schedule_id,
                            "vehicle_id"  => $vehicle_id,
                            "day"         => $day,
                            "leaving_time"=> $time,
                            "week"        => $week,
                            "year"        => $year,
                            "action"      => "updated"
                        ];
                    } else {
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
                            "id"          => $new_id,
                            "vehicle_id"  => $vehicle_id,
                            "day"         => $day,
                            "leaving_time"=> $time,
                            "week"        => $week,
                            "year"        => $year,
                            "action"      => "inserted"
                        ];
                    }
                }
            }

            return $results;
        }


    
    public function index( $route_id, $week, $year ) {
        $sql = "SELECT * FROM routes_schedule WHERE route_id='$route_id' AND week=$week AND year=$year AND deleted_at IS NULL";
        return ejecutarConsulta($sql);
    }
    
   

    public function show( $data ) {
        $routes_schedule_id = $data['routes_schedule_id'];
        $sql = "SELECT * FROM routes_schedule WHERE id = '$routes_schedule_id' ";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function deleteItem ( $data ){
        $routes_schedule_id = $data['routes_schedule_id'];
        $sql="
        UPDATE 
        `routes_schedule` SET 
            `deleted_at`= NOW()
        WHERE `id`='$   '";
        return ejecutarConsulta($sql);
    }

    public function show_route( $data ) {
        $routes_schedule_id = $data['routes_schedule_id'];
        $sql = "
            SELECT 
                routes_schedule.leaving_time,
                routes_schedule.day,
                routes.origin,
                routes.destination,
                routes.cost,
                vehicles.plate_number,
                vehicles.type,
                employees.name
                
            FROM `routes_schedule`
            INNER JOIN routes ON routes.id = routes_schedule.route_id
            INNER JOIN vehicles ON vehicles.id = routes_schedule.vehicle_id
            LEFT JOIN employees ON employees.id = vehicles.employee_id
            WHERE routes_schedule.id = '$routes_schedule_id' ";
        return ejecutarConsultaSimpleFila($sql);
    }


    public function show_schedules( $data ) {
        $route_id = $data['route_id'];
        $week = $data['week'];
        $year = $data['year'];

        $sql = "SELECT * FROM routes_schedule WHERE route_id='$route_id' AND week=$week AND year=$year AND deleted_at IS NULL";
        return ejecutarConsulta($sql);
    }

    public function deleted_item ( $data ){
        $item_id_db = $data['item_id_db'];
        $sql="UPDATE `routes_schedule` SET `deleted_at`= NOW() WHERE `id`='$item_id_db'";
        return ejecutarConsulta($sql);
    }
      
    
 
}   
?>
