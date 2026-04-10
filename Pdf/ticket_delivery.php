<?php
require_once dirname(__DIR__) . "/Database/conexion.php";
require_once __DIR__ . '/../vendor/autoload.php';
use Mpdf\Mpdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

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

$mpdf->SetWatermarkImage('../assets/img/set_water.png', 0.30, [100,100], 'F', false, 203);
$mpdf->showWatermarkImage = true;


foreach ($tickets_ids as $ticket_id) {

    $sql = "
        SELECT 
            tickets_delivery.id,
            tickets_delivery.price,
            tickets_delivery.quantity,
            tickets_delivery.date,
            tickets_delivery.description,
            routes_stop.origin,
            routes_stop.destination
        FROM `tickets_delivery`
        INNER JOIN routes ON routes.id = tickets_delivery.route_id
        INNER JOIN routes_stop ON routes_stop.id = tickets_delivery.route_stop_id
        WHERE tickets_delivery.id = '$ticket_id'
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

    $qrCode = new QrCode((string) $item['id']);
    $writer = new PngWriter();

    $result = $writer->write($qrCode);

    $url = 'data:' . $result->getMimeType() . ';base64,' . base64_encode($result->getString());
    // $url  = "https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=" . urlencode($text);

    // HTML del ticket
    $html = "
        <style>
            .ticket {
                font-family: 'Helvetica', sans-serif;
                font-size: 9px;
            }
            .ticket h3 {
                margin: 0;
                font-size: 11px;
                font-weight: bold;
                text-align: center;
            }
            .ticket .folio {
                font-size: 11px;
                font-weight: bold;
                text-align: right;
            }
            .ticket hr {
                border: none;
                margin: 2px 0;
            }
            table.ticket-table {
                width: 100%;
                border-collapse: collapse;
                font-size: 13px;
            }
            table.ticket-table td {
                padding: 2px 2px;
            }
            .label {
                font-weight: bold;
            }
            .value {
                font-weight: bold;
                font-size: 13px;
            }
            .qr {
                text-align: center;
            }
        </style>

        <div class='ticket'>
            <h3>TRANSPORTES EJECUTIVOS ARIO S.A. DE C.V.</h3>

            <div class='folio'>Folio: {$item['id']}</div>
            <hr>

            <table class='ticket-table'>
                <tr>
                    <td class='label'>Fecha:</td>
                    <td class='value'>{$item['date']}</td>
                    <td rowspan='5' class='qr'><img src='{$url}' width='70'></td>
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
                    <td class='label'>Cantidad:</td>
                    <td class='value'>{$item['quantity']}</td>
                </tr>
                <tr>
                    <td class='label'>Precio:</td>
                    <td class='value'>$ {$item['price']}</td>
                </tr>
                <tr>
                    <td class='label'>Paquete:</td>
                    <td class='value'>{$item['description']}</td>
                </tr>
            </table>

            <hr>
            <p style='font-size:8px; text-align:center;'>¡Es un placer poder servirle!</p>
        </div>
        <div class='ticket' style='margin-top:40px';>
            <h3>Operador</h3>
            <h3>TRANSPORTES EJECUTIVOS ARIO S.A. DE C.V.</h3>

            <div class='folio'>Folio: {$item['id']}</div>
            <hr>

            <table class='ticket-table'>
                <tr>
                    <td class='label'>Fecha:</td>
                    <td class='value'>{$item['date']}</td>
                    <td rowspan='5' class='qr'><img src='{$url}' width='70'></td>
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
                    <td class='label'>Cantidad:</td>
                    <td class='value'>{$item['quantity']}</td>
                </tr>
                <tr>
                    <td class='label'>Precio:</td>
                    <td class='value'>$ {$item['price']}</td>
                </tr>
                <tr>
                    <td class='label'>Paquete:</td>
                    <td class='value'>{$item['description']}</td>
                </tr>
            </table>

            <hr>
            <p style='font-size:8px; text-align:center;'>¡Es un placer poder servirle!</p>
        </div>
    ";

    $mpdf->WriteHTML($html);
}

$mpdf->Output("item.pdf", "I");
