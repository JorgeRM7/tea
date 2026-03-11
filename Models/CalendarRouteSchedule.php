<?php 
// session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";

class Calendar {

    public function __construct() {}
    

    public function store( $data ) {
        $route_id   = $data["route_id"] ?? null;
        $date       = $data["date"] ?? null;
        $vehicle_id = $data["vehicle_id"] ?? null;;

        $dateObj = new DateTime($date);

        $year      = (int)$dateObj->format("o");
        $week      = (int)$dateObj->format("W");
        $dayNameEn = strtolower($dateObj->format("l"));
        $day       = $dayNameEn;

        $results = [];
        $contador = 0;
        $existentes = 0;

        foreach ($data["time"] as $time) {

            $sql_check = "
                SELECT id
                FROM routes_schedule
                WHERE route_id = '$route_id'   
                AND date = '$date'
                AND leaving_time = '$time'
                LIMIT 1
            ";

            $check = ejecutarConsultaSimpleFila($sql_check);

            if ($check) {
                $existentes++;
                continue;
            }

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
            "total_records" => $contador, 
            "action"        => "inserted_or_updated",
            "horarios_existentes" => $existentes
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
        
        $search_route        = $data['search_route'] ?? NULL;
        $search_shift_role   = $data["search_shift_role"] ?? null;
        $sql = "
            SELECT 
                routes_static.*
            FROM `routes_static` 
            WHERE routes_static.route_id ='$search_route' AND shift_role_id='$search_shift_role' AND routes_static.deleted_at IS NULL ORDER BY routes_static.leaving_time ASC
        ";

        return ejecutarConsulta($sql);
    }

    

   


    
 
}   
?>
