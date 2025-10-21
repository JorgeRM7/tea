<?php
session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";

class Client {

    public function __construct() {}

    public function index() {
        $sql = "SELECT * FROM clients WHERE deleted_at IS NULL ORDER BY id DESC";
        return ejecutarConsulta($sql);
    }

    public function show($id) {
        $sql = "SELECT * FROM clients WHERE id = '$id' AND deleted_at IS NULL";
        return ejecutarConsultaSimpleFila($sql);
    }

public function store($data){
    $id = $data["client_id"] ?? null;
    $name = $data["name"] ?? '';
    $phone = $data["phone"] ?? '';
    $email = $data["email"] ?? '';
    $address = $data["address"] ?? '{}';
    $status  = $data["status"] ?? 'active';

    if ($id) {
        $sql = "UPDATE clients SET 
                    name='$name',
                    phone='$phone',
                    email='$email',
                    address='$address',
                    status='$status',
                    updated_at=NOW()
                WHERE id='$id'";
        return ejecutarConsulta($sql);
    } else {
        $sql = "INSERT INTO clients (name, phone, email, address, status, created_at, updated_at)
                VALUES ('$name','$phone','$email','$address','$status',NOW(),NOW())";
        return ejecutarConsulta_retornarID($sql);
    }
}


    public function update($data) {
        $id     = $data["id"];
        $name   = $data["name"];
        $phone  = $data["phone"] ?? null;
        $email  = $data["email"] ?? null;
        $address = $data["address"] ?? null;
        $status  = $data["status"] ?? 'active';

        $sql = "UPDATE clients SET 
                    name='$name',
                    phone='$phone',
                    email='$email',
                    address='$address',
                    status='$status',
                    updated_at = NOW()
                WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    public function delete($id) {
        $sql = "UPDATE clients SET deleted_at = NOW() WHERE id = '$id'";
        return ejecutarConsulta($sql);
    }
}
