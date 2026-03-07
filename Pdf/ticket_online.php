<?php
require_once dirname(__DIR__) . "/Database/conexion.php";
require_once __DIR__ . '/../vendor/autoload.php';

use Mpdf\Mpdf;

$ticketId = isset($_GET['ticket_id']) ? (int)$_GET['ticket_id'] : 0;
if (!$ticketId) {
    die('Ticket no encontrado');
}

$sql = "
    SELECT 
        tickets.id,
        tickets.price,
        tickets.discount,
        tickets.quantity,
        tickets.status,
         CONCAT(tickets.date, tickets.hour) as created_at,
        routes_schedule.date,
        routes_schedule.leaving_time,
        routes_stop.origin,
        routes_stop.destination,
        routes_stop.price AS base_price,
        CONCAT(employees.name,' ', employees.paternal_surname, ' ', employees.maternal_surname) AS employee,
        tickets.vehicle_id
    FROM tickets
    LEFT JOIN routes_schedule ON tickets.route_schedule_id = routes_schedule.id
    INNER JOIN routes_stop ON routes_stop.id = tickets.route_stop_id
    LEFT JOIN employees ON employees.id = tickets.employee_id
    WHERE tickets.sale_id = '$ticketId'
";
$result = ejecutarConsulta($sql);

$tickets = [];

while ($row = mysqli_fetch_assoc($result)) {
    $tickets[] = $row;
}

if (empty($tickets)) {
    die('Boletos no encontrados');
}






$logoPath = dirname(__DIR__) . "/assets/img/set_water.png";

  // Generar QR

$mpdf = new Mpdf([
    'format' => 'A5',
    'margin_left' => 6,
    'margin_right' => 6,
    'margin_top' => 6,
    'margin_bottom' => 10
]);

foreach ($tickets as $ticket) {

    $price = (float)$ticket['price'];
    $discount = (float)($ticket['discount'] ?? 0);
    $total = $price - $discount;
    $date = $ticket['date'] ?? '';
    $time = $ticket['leaving_time'] ?? '';
    $createdAt = $ticket['created_at'] ?? '';
    $origin = strtoupper($ticket['origin'] ?? '');
    $destination = strtoupper($ticket['destination'] ?? '');
    $vehicle = strtoupper($ticket['vehicle_id'] ?? '');
    $employee = strtoupper($ticket['employee'] ?? 'POR ASIGNAR');
    $text = $ticket['id'];
    $qrUrl  = "https://api.qrserver.com/v1/create-qr-code/?size=130x130&data=" . urlencode($text);
    $html .= "
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            color: #1f1f1f;
        }
        .ticket-container {
            padding: 10px;
            font-size: 10px;
        }
        .ticket-header {
            text-align: center;
            margin-bottom: 14px;
        }
        .ticket-header h1 {
            margin: 0;
            font-size: 20px;
            letter-spacing: 0.6px;
        }
        .ticket-header p {
            margin: 2px 0;
            font-size: 11px;
            color: #555;
        }
        .folio {
            text-align: right;
            font-size: 13px;
            font-weight: bold;
            color: #0a3e7e;
            margin-bottom: 6px;
        }
        .details-card {
            border: 1px solid #e3e3e3;
            border-radius: 10px;
            padding: 12px;
            background: #f7f9fc;
        }
        .details-table {
            width: 100%;
            font-size: 10.5px;
        }
        .details-table td {
            padding: 4px 6px;
            vertical-align: top;
        }
        .label {
            color: #0a3e7e;
            font-weight: 700;
            width: 70px;
        }
        .value {
            font-weight: 600;
        }
        .qr-panel {
            border: 1px dashed #9daecb;
            padding: 6px;
            border-radius: 8px;
            text-align: center;
            margin-top: 8px;
        }
        .qr-panel p {
            font-size: 9px;
            margin: 4px 0 0;
            color: #333;
        }
        .section-title {
            margin-top: 14px;
            font-weight: 700;
            color: #0a3e7e;
        }
        .terms {
            font-size: 8px;
            line-height: 1.45;
            margin-top: 6px;
            color: #333;
        }
        .terms ol {
            margin: 0 0 0 10px;
            padding: 0;
        }
        .terms li {
            margin-bottom: 4px;
            text-align: justify;
        }
    </style>

    <div class='ticket-container'>
        <div class='ticket-header'>
            " . (file_exists($logoPath) ? "<img src='{$logoPath}' width='110' style='margin-bottom:6px;'>" : "") . "
            <h1>TRANSPORTES EJECUTIVOS ARIO S.A. DE C.V.</h1>
            <p>RFC: TEA190814LY6 &middot; Servicio de Autotransporte Federal</p>
            <p>www.transportestea.com &middot; 4433975677 &middot; tea.contacto@digitalenigma.mx</p>
        </div>
        <div class='folio'>Folio digital: {$ticket['id']}</div>
        <div class='details-card'>
            <table class='details-table'>
                <tr>
                    <td class='label'>Fecha</td>
                    <td class='value'>{$date}</td>
                    <td class='label'>Salida</td>
                    <td class='value'>{$time}</td>
                    <td rowspan='5' style='text-align:right; width:130px;'>
                        <div class='qr-panel'>
                            <img src='{$qrUrl}' width='115'>
                            <p>Presente este c&oacute;digo y su identificaci&oacute;n oficial al abordar.</p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class='label'>Origen</td>
                    <td class='value'>{$origin}</td>
                    <td class='label'>Destino</td>
                    <td class='value'>{$destination}</td>
                </tr>
                <tr>
                    <td class='label'>Compra</td>
                    <td class='value'>{$createdAt}</td>
                    <td class='label'>Cantidad</td>
                    <td class='value'>{$ticket['quantity']}</td>
                </tr>
                <tr>
                    <td class='label'>Unidad</td>
                    <td class='value'>{$vehicle}</td>   
                    <td class='label'>Estatus</td>
                    <td class='value'>".strtoupper($ticket['status'])."</td>             
                </tr>
                <tr>                
                    <td class='label'>Total</td>
                    <td class='value'>$ " . number_format($total, 2) . "</td>
                </tr>
            </table>
        </div>
        <div class='section-title'>Términos y condiciones</div>
        <div class='terms'>
            <ol>
                <li>Este servicio se rige por la Ley de Caminos, Puentes y Autotransporte Federal y la NOM-012-SCT vigente.</li>
                <li>El pase de abordar es personal e intransferible; el pasajero deberá; presentarse 20 minutos antes de la salida con identificación oficial.</li>
                <li>Las tarifas promocionales aplican solo en rutas participantes y pueden estar sujetas a cambios de disponibilidad.</li>
                <li>Los cambios de horario, origen o destino se gestionan únicamente en taquilla previa validación de disponibilidad y pago de diferencias tarifarias.</li>
                <li>No hay devoluciones para boletos adquiridos como viajero anticipado ni se permiten boletos abiertos.</li>
                <li>Las reclamaciones y devoluciones por situaciones operativas se atienden exclusivamente en taquilla presentando el boleto original, identificación y dentro del plazo previo a la salida programada.</li>
                <li>La empresa no se responsabiliza por objetos olvidados</li>
                <li>No se permite transportar sustancias peligrosas, animales o mercancía restringida sin autorización escrita.</li>            
                <li>Este pase es válido únicamente para la fecha y hora indicadas.</li>
                <li>En caso de retrasos por causas de fuerza mayor, el viaje se reprogramará conforme a la disponibilidad, respetando las disposiciones de la autoridad.</li>            
                <li>El pasajero debe cumplir con los requisitos sanitarios y/o migratorios vigentes para su destino.</li>
                <li>Al utilizar este servicio usted acepta los presentes términos y el reglamento de pasajeros de Transportes Ejecutivos Ario.</li>
            </ol>
        </div>
    </div>
    ";
}

$mpdf->WriteHTML($html);
$mpdf->Output("ticket-{$ticketId}.pdf", "I");
