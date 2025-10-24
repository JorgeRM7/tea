<?php
session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";

use MercadoPago\SDK;
use MercadoPago\Preference;
use MercadoPago\Item;

class SaleOnline
{

    // private $config;
    public function __construct(){
        // $this->config = include dirname(__DIR__) . "/Config/config.php";
        // SDK::setAccessToken($this->config['mp_access_token']);
    }

    // public function buy($data) {
    //     $ticket_id =600;
    //     $origin      = $data['origin'];
    //     $destination = $data['destination'];
    //     $schedule_id = $data['schedule_id'];
    //     $price       = (float)$data['price'];
    //     $quantity    = (int)$data['quantity'];


    //     $preference = new Preference();

    //     $item = new Item();
    //     $item->title = "Boleto $origin → $destination";
    //     $item->quantity = $quantity;
    //     $item->unit_price = $price;
    //     $preference->items = [$item];

    //     $preference->back_urls = [
    //         "success" => "http://localhost/Views/success.php?ticket_id=$ticket_id",
    //         "failure" => "http://localhost/Views/failure.php?ticket_id=$ticket_id",
    //         "pending" => "http://localhost/Views/pending.php?ticket_id=$ticket_id"
    //     ];
    //     $preference->auto_return = "approved";
    //     $preference->save();

    //     return [
    //         "id" => $preference->id,
    //         "ticket_id" => $ticket_id
    //     ];

    // }

    public function schedules($data) {
        $hour     = date("H:i:s"); 
        $today    = date("Y-m-d");
        $origin   = $data['origin'];
        $destination = $data['destination'];
        $date     = $data['date'] ?? '2025-10-24';

        $sql = "SELECT 
                    routes_schedule.id AS schedule_id,
                    routes_schedule.leaving_time,
                    routes_stop.price
                FROM routes_schedule routes_schedule
                INNER JOIN routes_stop ON routes_stop.route_id = routes_schedule.route_id
                WHERE routes_stop.origin = '$origin' 
                AND routes_stop.destination = '$destination'";

        if ($date == $today) {
            $sql .= " AND routes_schedule.date = '$date' AND routes_schedule.leaving_time >= '$hour'";
        } elseif ($date > $today) {
            $sql .= " AND routes_schedule.date = '$date'";
        } else {
            $sql .= " AND 1=0";
        }

        return ejecutarConsulta($sql);
    }

    public function show_subpaths ( $data ){
        $origin = $data['origin'] ?? null;
        $sql = "SELECT * FROM `routes_stop` WHERE origin='$origin' AND deleted_at is null";
        return ejecutarConsulta($sql);
    }

    

}
?>