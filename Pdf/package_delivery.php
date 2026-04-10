<?php
require_once dirname(__DIR__) . "/Database/conexion.php";
require_once __DIR__ . "/../vendor/autoload.php";

use Mpdf\Mpdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

/* ==========================================================
   CONFIGURACIÓN DE SEGURIDAD
   ========================================================== */

$secretKey = 'TEA_SUPER_SECRET_2026'; // MUEVE ESTO A .env EN PRODUCCIÓN
$method = 'AES-256-CBC';

function encryptData($data, $key, $method) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
    $encrypted = openssl_encrypt($data, $method, $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}

/* ==========================================================
   VALIDAR ID
   ========================================================== */

$package_id = isset($_GET['package_id']) ? (int) $_GET['package_id'] : 0;

if ($package_id <= 0) {
    exit('ID de paquete no válido');
}

/* ==========================================================
   CONSULTA
   ========================================================== */

$sql = "
    SELECT 
        td.*,
        routes.origin AS route_origin,
        routes.destination AS route_destination,
        routes_stop.origin AS stop_origin,
        routes_stop.destination AS stop_destination,
        rs.date AS schedule_date,
        rs.leaving_time,
        vehicles.unidad_number,
        CONCAT(employees.name,' ', employees.paternal_surname,' ', employees.maternal_surname) AS driver_name
    FROM tickets_delivery td
    LEFT JOIN routes ON routes.id = td.route_id
    LEFT JOIN routes_stop ON routes_stop.id = td.route_stop_id
    LEFT JOIN routes_schedule rs ON rs.id = td.route_schedule_id
    LEFT JOIN vehicles ON vehicles.id = td.vehicle_id
    LEFT JOIN employees ON employees.id = vehicles.employee_id
    WHERE td.id = '$package_id'
";

$result = ejecutarConsulta($sql);
$package = mysqli_fetch_assoc($result);

if (!$package) {
    exit('Paquete no encontrado');
}

/* ==========================================================
   FORMATEOS
   ========================================================== */

$origin      = $package['stop_origin'] ?? $package['route_origin'] ?? 'N/D';
$destination = $package['stop_destination'] ?? $package['route_destination'] ?? 'N/D';

$shipDate = $package['schedule_date'] ?? $package['date'] ?? $package['created_at'];
$shipDateFormatted = $shipDate ? date('d/m/Y', strtotime($shipDate)) : '--';
$shipTimeFormatted = $package['leaving_time'] 
    ? substr($package['leaving_time'], 0, 5) 
    : date('H:i', strtotime($package['created_at'] ?? 'now'));

$generatedAt = date('d/m/Y H:i', strtotime($package['created_at'] ?? 'now'));

$price = number_format((float) ($package['price'] ?? 0), 2);

$trackingCode = $package['tracking_code'] ?? ('PKG' . str_pad($package_id, 8, '0', STR_PAD_LEFT));
$trackingPin  = $package['tracking_pin'] ?? '0000';

/* ==========================================================
   CIFRAR DATA PARA QR
   ========================================================== */

$payload = json_encode([
    'code' => $trackingCode,
    'pin'  => $trackingPin
]);

$encryptedPayload = encryptData($payload, $secretKey, $method);

$trackingLink = "https://transportestea.com/Views/packages-tracking.php?data=" . urlencode($encryptedPayload);

// $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=" . urlencode($trackingLink);

$qrCode = new QrCode((string) $trackingLink);
$writer = new PngWriter();
$result = $writer->write($qrCode);
$qrUrl = 'data:' . $result->getMimeType() . ';base64,' . base64_encode($result->getString());



/* ==========================================================
   MPDF CONFIG (MISMAS DIMENSIONES QUE PASAJERO)
   ========================================================== */

$mpdf = new Mpdf([
    'format' => [70, 80],
    'margin_left'   => 3,
    'margin_right'  => 3,
    'margin_top'    => 3,
    'margin_bottom' => 3,
    'orientation'   => 'L',
]);

$mpdf->SetWatermarkImage('../assets/img/set_water.png', 0.25, [90, 90], 'F', false, 203);
$mpdf->showWatermarkImage = true;

$esc = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

/* ==========================================================
   ESTILOS
   ========================================================== */

$styles = "
<style>
.ticket {
    font-family: 'Helvetica', sans-serif;
    font-size: 9px;
}
.ticket h3 {
    margin: 0;
    font-size: 9px;
    font-weight: bold;
    text-align: center;
}
.folio {
    font-size: 10px;
    font-weight: bold;
    text-align: right;
}
table {
    width: 100%;
    border-collapse: collapse;
    font-size: 9px;
}
td {
    padding: 2px;
    vertical-align: top;
}
.label {
    font-weight: bold;
}
.qr {
    text-align: center;
}
.divider {
    text-align:center;
    font-size:8px;
    margin:6px 0;
}
</style>
";

/* ==========================================================
   CONSTRUCTOR DE TICKET
   ========================================================== */

$buildTicket = function ($title) use (
    $esc, $package, $origin, $destination,
    $shipDateFormatted, $shipTimeFormatted,
    $generatedAt, $price, $qrUrl,
    $trackingCode, $trackingPin
) {

    $html = "<div class='ticket'>";
    $html .= "<h3>TRANSPORTES EJECUTIVOS ARIO S.A. DE C.V.</h3>";
    $html .= "<div style='text-align:center; font-size:8px;'>COMPROBANTE $title</div>";
    $html .= "<div class='folio'>ID: " . $esc($package['id']) . "</div><hr>";

    $html .= "<table>
        <tr>
            <td class='label'>Ruta:</td>
            <td>" . $esc($origin) . " → " . $esc($destination) . "</td>
            <td rowspan='6' class='qr'><img src='" . $esc($qrUrl) . "' width='65'></td>
        </tr>
        <tr>
            <td class='label'>Envio:</td>
            <td>$shipDateFormatted $shipTimeFormatted</td>
        </tr>
        <tr>
            <td class='label'>Remite:</td>
            <td>" . $esc($package['sender_name']) . "</td>
        </tr>
        <tr>
            <td class='label'>Recibe:</td>
            <td>" . $esc($package['receiver_name']) . "</td>
        </tr>
        <tr>
            <td class='label'>Precio:</td>
            <td>$ $price</td>
        </tr>
        <tr>
            <td class='label'>PIN:</td>
            <td style='font-weight:bold;'>$trackingPin</td>
        </tr>
        <tr>
            <td class='label'>Generado:</td>
            <td>$generatedAt</td>
        </tr>
    </table>";

    $html .= "<hr><div style='text-align:center; font-size:8px;'>¡Gracias por confiar en TEA!</div>";
    $html .= "</div>";

    return $html;
};

/* ==========================================================
   GENERAR DOBLE COPIA
   ========================================================== */

$html = $styles;
$html .= $buildTicket('CLIENTE');
//$html .= "<div class='divider'>-----------------------------------</div>";
$html .= "<pagebreak />";
$html .= $buildTicket('OPERADOR');

$mpdf->WriteHTML($html);
$mpdf->Output("package_$package_id.pdf", "I");