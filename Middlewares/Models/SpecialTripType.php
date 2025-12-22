<?php
session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";

class SpecialTripType {

    public function __construct() {}

    public function index() {
        $sql = "SELECT * FROM special_trip_types WHERE deleted_at IS NULL ORDER BY id DESC";
        return ejecutarConsulta($sql);
    }

    public function show($id) {
        $sql = "SELECT * FROM special_trip_types WHERE id = '$id' AND deleted_at IS NULL";
        return ejecutarConsultaSimpleFila($sql);
    }


    public function store($data){
            $id = $data["trip_type_id"] ?? null;
            $origin = $data["origin"] ?? '';
            $destination = $data["destination"] ?? '';
            $days = $data["days"] ?? '';
            $price = $data["price"] ?? '';
            $valid_from = $data["valid_from"] ?? '';
            $valid_to = $data["valid_to"] ?? '';
            $status = $data["status"] ?? 'active';

        if ($id) {
        $sql = "UPDATE special_trip_types SET 
                        origin='$origin',
                        destination='$destination',
                        days='$days',
                        price='$price',
                        valid_from='$valid_from',
                        valid_to='$valid_to',
                        status='$status',
                        updated_at=NOW()
                    WHERE id='$id'";
            return ejecutarConsulta($sql);
        } else {
            $sql = "INSERT INTO special_trip_types (origin, destination, days, price, valid_from, valid_to, status, created_at, updated_at) 
                    VALUES ('$origin','$destination','$days','$price','$valid_from','$valid_to', '$status',NOW(),NOW())";
            return ejecutarConsulta_retornarID($sql);
        }
    }


    public function update($data) {
        $id = $data["id"];
        $origin = $data["origin"];
        $destination = $data["destination"];
        $days = $data["days"];
        $price = $data["price"];
        $valid_from = $data["valid_from"];
        $valid_to = $data["valid_to"];
        $status = $data["status"] ?? 'active';

        $sql = "UPDATE special_trip_types SET 
                    origin='$origin',
                    destination='$destination',
                    days='$days',
                    price='$price',
                    valid_from='$valid_from',
                    valid_to='$valid_to',
                    status='$status',
                    updated_at = NOW()
                WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    public function delete($id) {
        $sql = "UPDATE special_trip_types SET deleted_at = NOW() WHERE id = '$id'";
        return ejecutarConsulta($sql);
    }
}
