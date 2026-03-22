<?php
require_once dirname(__DIR__) . "/Database/conexion.php";
require_once __DIR__ . '/../vendor/autoload.php';
use Mpdf\Mpdf;

$tickets_ids = isset($_GET['tickets_id']) ? explode(",", $_GET['tickets_id']) : []; 

$mpdf = new Mpdf([
    'format' => [70, 80], // 80mm x 70mm
    'margin_left'   => 3,
    'margin_right'  => 3,
    'margin_top'    => 3,
    'margin_bottom' => 3,
    'orientation' => 'L',
]);

// $mpdf->SetWatermarkImage('../assets/img/logo.png', 0.12, [40, 40]);
// $mpdf->showWatermarkImage = true;

$mpdf->SetWatermarkImage('../assets/img/set_water.png', 0.15, [60,60], 'F', false, 203);
$mpdf->showWatermarkImage = true;


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
            tickets.vehicle_id,
            tickets.created_at,
            social_reasons.name AS social_reason,
            social_reasons.tax_data AS RFC
        FROM tickets
        LEFT JOIN routes_schedule ON tickets.route_schedule_id = routes_schedule.id
        LEFT JOIN routes ON routes.id = tickets.route_id
        INNER JOIN routes_stop ON routes_stop.id = tickets.route_stop_id
        LEFT JOIN employees ON employees.id = tickets.employee_id
        LEFT JOIN branch_offices ON branch_offices.id = tickets.branch_office_id
        LEFT JOIN social_reasons ON social_reasons.id = branch_offices.social_reason_id
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
        * {
            color: #000000 !important;
            text-shadow: 0 0 0 #000000; /* truco para hacerlo más sólido */
        }
        .ticket {
            font-family: Arial, sans-serif;
            font-size: 9px;
        }
        .ticket h3 {
            margin: 0;
            font-size: 9px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 0.5px;
        }
        .ticket .folio {
            font-size: 11px;
            font-weight: bold;
            text-align: right;
            color: #2c3e50;
        }
        .ticket hr {
            border: none;
            margin: 0px 0;
        }
        table.ticket-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        table.ticket-table td {
            padding: 1px 1px;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            color: #000;
        }
        .value {
            font-weight: bold;
            font-size: 10px;
        }
        .qr {
            text-align: center;
        }
    </style>

    <div class='ticket'>
    
        <h3>{$item['social_reason']}</h3>
        <p style='margin:0; text-align:center;'>RFC: {$item['RFC']}</p>
        
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
            <tr>
                <td class='label'>Hora venta:</td>
                <td class='value'>{$item['created_at']}</td>
            </tr>
        </table>

        <hr>
        <p style='font-size:8px; text-align:center;'>¡Es un placer poder servirle!</p>
    </div>
    <div class='ticket'>
        <h3>OPERADOR</h3>
        <h3>{$item['social_reason']}</h3>
        <p style='margin:0; text-align:center;'>RFC: {$item['RFC']}</p>
        
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
            <tr>
                <td class='label'>Hora venta:</td>
                <td class='value'>{$item['created_at']}</td>
            </tr>
        </table>

        <hr>
        <p style='font-size:8px; text-align:center;'>¡Es un placer poder servirle!</p>
    </div>
    ";

    $mpdf->WriteHTML($html);
}

$mpdf->Output("item.pdf", "I");
