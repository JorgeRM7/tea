<?php
require_once dirname(__DIR__) . "/Database/conexion.php";
require_once __DIR__ . '/../vendor/autoload.php';
use Mpdf\Mpdf;

$tickets_ids = isset($_GET['tickets_id']) ? explode(",", $_GET['tickets_id']) : []; 

$mpdf = new Mpdf([
    'format' => [80, 70], // 80mm x 70mm
    'margin_left'   => 3,
    'margin_right'  => 3,
    'margin_top'    => 3,
    'margin_bottom' => 3
]);

foreach ($tickets_ids as $ticket_id) {

    $sql = "
        SELECT 
            tickets.id,
            tickets.price,
            tickets.discount,
            routes_schedule.date,
            routes_schedule.leaving_time,
            routes_stop.origin,
            routes_stop.destination,
            CONCAT(employees.name,' ', employees.paternal_surname, ' ', employees.maternal_surname) AS employee,
            vehicles.unidad_number AS vehicle_id
        FROM tickets
        LEFT JOIN routes_schedule ON tickets.route_schedule_id = routes_schedule.id
        LEFT JOIN routes ON routes.id = tickets.route_id
        INNER JOIN routes_stop ON routes_stop.id = tickets.route_stop_id
        LEFT JOIN employees ON employees.id = tickets.employee_id
        LEFT JOIN vehicles ON vehicles.id = tickets.vehicle_id
        WHERE tickets.id = '$ticket_id'
    ";
    $result = ejecutarConsulta($sql);
    $item = mysqli_fetch_assoc($result);

    if (!$item) {
        continue;
    }

    $price    = (float) $item['price'];
    $discount = (float) $item['discount'] ?? 0;
    $total    = $price - $discount;

    // Generar QR
    $text = $item['id'];
    $url  = "https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=" . urlencode($text);

    // HTML del ticket
    $html = "
    <style>
        .ticket {
            font-family: 'Helvetica', sans-serif;
            font-size: 9px;
            border: 1px dashed #000;
            border-radius: 6px;
            padding: 6px;
        }
        .ticket h3 {
            margin: 0;
            font-size: 11px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 0.5px;
        }
        .ticket .folio {
            font-size: 14px;
            font-weight: bold;
            text-align: right;
            margin: 3px 0;
            color: #2c3e50;
        }
        .ticket hr {
            border: none;
            border-top: 1px dashed #000;
            margin: 4px 0;
        }
        table.ticket-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }
        table.ticket-table td {
            padding: 2px 4px;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            color: #000;
        }
        .value {
            font-size: 9px;
        }
        .qr {
            text-align: center;
        }
    </style>

    <div class='ticket'>
        <h3>TRANSPORTES EJECUTIVOS ARIO S.A. DE C.V.</h3>
        <p style='margin:0; text-align:center;'>RFC: TEA190814LY6</p>
        
        <div class='folio'>Folio: {$item['id']}</div>
        <hr>

        <table class='ticket-table'>
            <tr>
                <td class='label'>Fecha:</td>
                <td class='value'>{$item['date']}</td>
                <td rowspan='6' class='qr'><img src='{$url}' width='70'></td>
            </tr>
            <tr>
                <td class='label'>Salida:</td>
                <td class='value'>{$item['leaving_time']}</td>
            </tr>
            <tr>
                <td class='label'>Origen:</td>
                <td class='value'>{$item['origin']}</td>
            </tr>
            <tr>
                <td class='label'>Destino:</td>
                <td class='value'>{$item['destination']}</td>
            </tr>
            <tr>
                <td class='label'>Precio:</td>
                <td class='value'>$ {$total}</td>
            </tr>
            <tr>
                <td class='label'>Unidad:</td>
                <td class='value'>{$item['vehicle_id']}</td>
            </tr>
            <tr>
                <td class='label'>Operador:</td>
                <td class='value'>{$item['employee']}</td>
            </tr>
        </table>

        <hr>
        <p style='font-size:8px; text-align:center;'>¡Es un placer poder servirle!</p>
    </div>
    ";

    $mpdf->WriteHTML($html);
    $mpdf->AddPage();
}

$mpdf->Output("item.pdf", "I");
