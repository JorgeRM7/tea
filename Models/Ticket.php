<?php
session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";

class Ticket
{

    public function __construct()
    {
    }


    public function store($data)
    {
        $user_id = $data["user_id"];
        $name = $data["name"];
        $email = $data["email"];
        $username = $data["username"];
        $user_type = $data["user_type"];
        $password = $data["password"];
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        if ($user_id) {
            $sql = "
            UPDATE `users` SET 
                `name`   = '$name',
                `email`          = '$email',
                `username`          = '$username',
                `password`           = '$hashed_password',
                `updated_at`     = NOW()
            WHERE `id` = '$user_id'
    ";
        } else {
            $sql = "
                INSERT INTO `users`(
                `name`,
                `email`,
                `username`,
                `email_verified_at`,
                `password`,
                `two_factor_secret`,
                `two_factor_recovery_codes`,
                `two_factor_confirmed_at`,
                `remember_token`,
                `current_team_id`,
                `profile_photo_path`,
                `created_at`,
                `updated_at`,
                ) VALUES (
                    '$name',
                    '$email',
                    '$username',
                    NULL,
                    '$hashed_password',
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    NOW(),
                    NOW()
                )
            ";
        }
        return ejecutarConsulta($sql);
    }

    


    public function schedules( $data ){
        $route_id = $data['route_id'];
        $date = $data['date'];
        $sql = "SELECT * FROM `routes_schedule` WHERE date ='$date' AND route_id='$route_id' ";
        return ejecutarConsulta($sql);
    }

    public function routes( $data ){
        $search_date = $data['search_date'] ?? NULL;
        $search_schedule = $data['search_schedule'] ?? NULL;
        $search_route = $data['search_route'] ?? NULL;
        $sql = "SELECT 
                    routes.*,
                    routes_schedule.leaving_time,
                    routes_schedule.id AS route_schedule_id
                FROM `routes_schedule` 
                INNER JOIN routes ON routes.id = routes_schedule.route_id
                ";

        if (!empty($search_date)) {
            $sql .= " AND routes_schedule.date = '$search_date'";
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

    


}
?>