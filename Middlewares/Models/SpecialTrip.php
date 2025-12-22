<?php
session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";

class SpecialTrip {

    public function __construct() {}

    public function index() {
        $sql = "SELECT st.*, 
                       c.name AS client_name, 
                       v.unidad_number AS vehicle_plate
                FROM special_trips st
                LEFT JOIN clients c ON c.id = st.client_id
                INNER JOIN vehicles v ON v.id = st.vehicle_id
                WHERE st.deleted_at IS NULL
                ORDER BY st.id DESC";
        return ejecutarConsulta($sql);
    }

    public function show($id) {
        $sql = "SELECT * FROM special_trips WHERE id = '$id' AND deleted_at IS NULL";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function options() {
        
        $clients = ejecutarConsulta("SELECT id, name FROM clients WHERE deleted_at IS NULL AND status='active' ORDER BY name ASC");
   
        $vehicles = ejecutarConsulta("SELECT id, unidad_number FROM vehicles WHERE deleted_at IS NULL ORDER BY unidad_number ASC");

        $today = date('Y-m-d');
        $types = ejecutarConsulta("
            SELECT id, origin, destination, days, price, valid_from, valid_to
            FROM special_trip_types
            WHERE deleted_at IS NULL
              AND status='active'
              AND (valid_from IS NULL OR valid_from <= '$today')
              AND (valid_to IS NULL OR valid_to >= '$today')
            ORDER BY origin, destination
        ");

        $r_clients = []; while ($row = $clients->fetch_assoc()) $r_clients[] = $row;
        $r_vehicles = []; while ($row = $vehicles->fetch_assoc()) $r_vehicles[] = $row;
        $r_types = []; while ($row = $types->fetch_assoc()) $r_types[] = $row;

        return [ "clients"=>$r_clients, "vehicles"=>$r_vehicles, "types"=>$r_types ];
    }

    public function store($data) {
        $id          = $data["id"] ?? null;
        $client_id   = !empty($data["client_id"]) ? $data["client_id"] : "NULL";
        $vehicle_id  = $data["vehicle_id"];
        $trip_type_id= $data["trip_type_id"] ?? "NULL";
        $origin      = $data["origin"];
        $destination = $data["destination"];
        $days        = $data["days"];
        $price       = $data["price"];
        $start_date  = $data["start_date"];
        $end_date    = $data["end_date"];
        $status      = $data["status"] ?? 'pending';

        require_once "Vehicle.php";
        $veh = new Vehicle();

        if ($status === "in_progress" && !$veh->isAvailable($vehicle_id, $start_date, $end_date, $id ?? null)) {
            return ["error" => true, "message" => "El vehículo seleccionado ya está ocupado en otro viaje en curso en esas fechas."];
        }


        if ($id) {

            $sql = "UPDATE special_trips SET 
                        client_id = $client_id,
                        vehicle_id = '$vehicle_id',
                        trip_type_id = '$trip_type_id',
                        origin = '$origin',
                        destination = '$destination',
                        days = '$days',
                        price = '$price',
                        start_date = '$start_date',
                        end_date = '$end_date',
                        status = '$status',
                        updated_at = NOW()
                    WHERE id = '$id'";
                ejecutarConsulta($sql);


            // Recalcular estado del vehículo en base a todos los viajes
            $veh->refreshStatus($vehicle_id);

            return ["success" => true, "id" => $id];

        } else {
            $sql = "INSERT INTO special_trips 
                        (client_id, vehicle_id, trip_type_id, origin, destination, days, price, start_date, end_date, status, created_at, updated_at)
                    VALUES ($client_id, '$vehicle_id', '$trip_type_id', '$origin', '$destination', '$days', '$price', '$start_date', '$end_date', '$status', NOW(), NOW())";
            $trip_id = ejecutarConsulta_retornarID($sql);

            // Recalcular estado del vehículo en base a todos los viajes
            $veh->refreshStatus($vehicle_id);

        return ["success" => true, "id" => $trip_id];
        }
    }

    public function delete($id) {
        $sql = "SELECT vehicle_id, status FROM special_trips WHERE id = '$id' AND deleted_at IS NULL";
        $trip = ejecutarConsultaSimpleFila($sql);

        if (!$trip) {
            return ["error" => true, "message" => "Viaje no encontrado o ya eliminado."];
        }

        $vehicle_id = $trip['vehicle_id'];

        // Eliminar lógico
        $sql = "UPDATE special_trips SET deleted_at = NOW(), updated_at = NOW() WHERE id = '$id'";
        ejecutarConsulta($sql);

        require_once "Vehicle.php";
        $veh = new Vehicle();

        // Siempre recalcular estatus del vehículo
        $veh->refreshStatus($vehicle_id);

        return ["success" => true];
    }
}
