<?php
require_once dirname(__DIR__) . "/Database/conexion.php";
require_once __DIR__ . '/../vendor/autoload.php';
use Mpdf\Mpdf;

$id = $_GET['id'];
date_default_timezone_set('America/Mexico_City');

$mpdf = new Mpdf([
    'format' => 'Letter',
    'margin_left' => 15,
    'margin_right' => 15,
    'margin_top' => 18,
    'margin_bottom' => 15,
    'orientation' => 'P',
    'default_font' => 'dejavusans'
]);

$logoPath      = "../assets/img/set_water.jpeg";
$watermarkPath = "../assets/img/set_water.jpeg";

if ($logoPath !== "../assets/img/set_water.jpeg") {
    $mpdf->SetHTMLHeader('
        <div style="text-align:left; padding-bottom:6px;">
            <img src="'.$logoPath.'" style="height:55px;">
        </div>
    ');
}

$mpdf->SetWatermarkImage('../assets/img/set_water.png', 0.15, [200,200], 'F', false, 100);
$mpdf->showWatermarkImage = true;

$sql = "
    SELECT 
        special_trips.id,
        special_trips.origin,
        special_trips.days,
        special_trips.price,
        special_trips.start_date,
        special_trips.end_date,
        clients.name,
        clients.phone,
        clients.address,
        vehicles.unidad_number,
        vehicles.capacity
    FROM `special_trips`
    INNER JOIN clients ON clients.id = special_trips.client_id
    INNER JOIN vehicles ON vehicles.id = special_trips.vehicle_id
    WHERE special_trips.id = '$id'
";
$result       = ejecutarConsulta($sql);
$item         = mysqli_fetch_assoc($result);

$precio       = (float)($item['price'] ?? 0);
$date         = date("Y-m-d");

$address = json_decode($item['address'] ?? '{}', true);
if (!is_array($address)) $address = [];

$street       = $address['street'] ?? '';
$number       = $address['number'] ?? '';
$neighborhood = $address['neighborhood'] ?? '';
$city         = $address['city'] ?? '';
$state        = $address['state'] ?? '';
$zipcode      = $address['zipcode'] ?? '';

$direccion = "$street #$number, $neighborhood, C.P. $zipcode";
$ciudad    = "$city, $state";

function up($txt){
    return mb_strtoupper(trim((string)$txt), 'UTF-8');
}
function money($n){
    return number_format((float)$n, 2, '.', ',');
}

function numeroALetras($numero){
    $formatter = new NumberFormatter("es", NumberFormatter::SPELLOUT);
    return mb_strtoupper($formatter->format($numero), 'UTF-8');
}

$precioLetra = numeroALetras($precio);

// Variables ya procesadas
$nombreUp     = up($item['name'] ?? '');
$unidadUp     = up($item['unidad_number'] ?? '');
$originUp     = up($item['origin'] ?? '');
$capacidadUp  = up($item['capacity'] ?? '');
$telefonoUp   = up($item['phone'] ?? '');
$direccionUp  = up($direccion);
$ciudadUp     = up($ciudad);

$precioFmt    = money($precio);

$html = "

<style>
body{
    font-family: dejavusans;
    font-size: 15px;
    line-height: 1.45;
}

.header{
    text-align:center;
}

.header-title{
    font-size:20px;
    font-weight:bold;
    letter-spacing:0.5px;
}

.header-sub{
    font-size:11px;
}

.contract-title{
    text-align:center;
    font-weight:bold;
    font-size:15px;
    margin-top:8px;
    margin-bottom:10px;
    border-top:1px solid #000;
    border-bottom:1px solid #000;
    padding:3px 0;
}

.label{
    font-weight:bold;
}

.long-line{
    display:inline-block;
    border-bottom:1px solid #000;
    width:300px;
}

.medium-line{
    display:inline-block;
    border-bottom:1px solid #000;
    width:170px;
}

.short-line{
    display:inline-block;
    border-bottom:1px solid #000;
    width:90px;
}

.mini-line{
    display:inline-block;
    border-bottom:1px solid #000;
    width:60px;
}

.section-title{
    text-align:center;
    font-weight:bold;
    margin:8px 0 5px 0;
}

.justify{
    text-align:justify;
}

.observaciones{
    font-size:10px;
    text-align:justify;
    line-height:1.4;
    margin-top:8px;
}

.firmas{
    margin-top:25px;
}

.tabla-firmas{
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
}
.tabla-firmas td{
    width:50%;
    text-align:center;
    vertical-align:bottom;
}
.line-sign{
    display:block;
    border-top:1px solid #000;
    width:220px;
    margin:8px auto 5px auto;
}
</style>

<div class='header'>
    <div class='header-title'>
        COOPERATIVA DE TRANSPORTES EJIDALES S.C.L.
    </div>
    <div class='header-sub'>
        DOMICILIO CALLE GUERRERO NO 65 COL. CENTRO, ARIO DE ROSALES MICH.
    </div>
    <div class='header-sub'>
        RFC: TEA970121-L21 &nbsp;&nbsp; TEL. 01 (422) 52 1-16-08
    </div>
    <div class='header-sub'>
        CORREO ELECTRONICO t_lgar_ade@live.com.mx
    </div>
    <div class='header-sub' style='margin-top:4px;'>
        ARIO DE ROSALES MICHOACÁN A: {$date} <span class='short-line'></span>
    </div>
</div>

<div class='contract-title'>
    CONTRATO DE VIAJE ESPECIAL
</div>

<div class='justify'>

    <span class='label'>NOMBRE DEL CONTRATANTE:</span> 
    <span class='long-line'>{$nombreUp}</span>

    <br><br>

    <span class='label'>UNIDAD CONTRATADA:</span> 
    <span class='medium-line'>{$unidadUp}</span>

    &nbsp;&nbsp;
    <span class='label'>CAPACIDAD:</span> 
    <span class='short-line'>{$capacidadUp}</span>

    &nbsp;&nbsp;
    <span class='label'>PASAJEROS:</span> 
    <span class='short-line'></span>

    &nbsp; CONTRATADA PARA PRESTAR EL SERVICIO DE TRANSPORTACIÓN A 
    <span class='medium-line'>{$originUp}</span>

    <br><br>

    CON UN COSTO TOTAL DEL SERVICIO POR $ 
    <span class='short-line'>{$precioFmt}</span>

    <span class='medium-line'>
        ( {$precioLetra} PESOS )
    </span>

    DEPOSITANDO UN ANTICIPO GARANTÍA POR LA CANTIDAD DE $ 
    <span class='short-line'>{$precioFmt}</span>
    QUEDANDO PENDIENTE DE PAGO LA CANTIDAD DE $ 
    <span class='short-line'>{$precioFmt} ( {$precioLetra} PESOS )</span>
    PARA EL DÍA QUE SE EFECTÚE EL SERVICIO.

</div>

<div class='section-title'>
    DATOS DE LA SALIDA
</div>

<div class='justify'>
    FECHA DE SALIDA: <span class='short-line'>{$item['start_date']}</span>
    &nbsp; A LAS <span class='short-line'></span>
    &nbsp; PRESENTANDO LA UNIDAD CONTRATADA EN EL DOMICILIO:
    <span class='medium-line'>{$direccionUp}</span>
    DE LA CIUDAD DE: <span class='medium-line'>{$ciudadUp}</span>
    &nbsp; A LA ORDEN DEL SR.(A):
    <span class='medium-line'>{$nombreUp}</span>

    <br><br>

    CEL: <span class='short-line'>{$telefonoUp}</span>
    &nbsp; CASA U OFICINA:
    <span class='medium-line'></span>
</div>

<div class='section-title'>
    DATOS DEL REGRESO
</div>

<div class='justify'>
    EL REGRESO SERÁ EL DÍA <span class='short-line'>{$item['end_date']}</span> A LAS <span class='short-line'></span> EN DOMICILIO DESTINO
    <span class='medium-line'>{$direccionUp}</span> DE LA CIUDAD DE <span class='medium-line'>{$ciudadUp}</span>
</div>

<br>

<div class='justify'>
    <strong>RUTA Y PASEOS INCLUIDOS EN EL COSTO DEL SERVICIO:</strong>
    <span class='medium-line'></span>
</div>

<br>

<div class='justify'>
    <strong>CARACTERÍSTICAS DE LA UNIDAD:</strong>
    ASIENTOS: <span class='mini-line'></span>
    &nbsp; C/CALF.: <span class='mini-line'></span>
    &nbsp; STEREO: <span class='mini-line'></span>
    &nbsp; MONITORES: <span class='mini-line'></span>
    &nbsp; DVD: <span class='mini-line'></span>
</div>

<div class='observaciones'>
    <strong>OBSERVACIONES:</strong>
    TODO SERVICIO SOLICITADO POR EL CONTRATANTE O ENCARGADO(A) DE GRUPO NO ESPECIFICADO EN ESTE CONTRATO
    SE CONSIDERA COMO PASEO, TRASLADO O SERVICIO EXTRA, POR LO TANTO TENDRÁ UN COSTO ADICIONAL EL CUAL SE PAGARÁ
    AL MOMENTO DE SOLICITARLO AL OPERADOR. LAS UNIDADES NO CIRCULAN EN TERRACERÍAS O CAMINOS EN MAL ESTADO
    QUE PONGAN EN RIESGO LA SEGURIDAD DE LOS PASAJEROS Y DE LA UNIDAD.
    AMBAS PARTES CONVIENEN SOMETER CUALQUIER CONTROVERSIA A LOS TRIBUNALES COMPETENTES,
    RENUNCIANDO AL FUERO QUE PUDIERA CORRESPONDERLES.
</div>

<div class='firmas'>
    <table class='tabla-firmas'>
        <tr>
            <td>
                {$nombreUp}<br>
                <div class='line-sign'></div>
                CONTRATANTE
            </td>
            <td>
                TRANSPORTES EJIDALES ARIO DE R. SCL<br>
                <div class='line-sign'></div>
                REPRESENTANTE
            </td>
        </tr>
    </table>
</div>

";

$html_condiciones = '
<style>
.condiciones{
    font-family: dejavusans;
    font-size: 12px;
    text-align: justify;
    line-height: 1.4;
}
.condiciones h2{
    text-align:center;
    letter-spacing:6px;
}
</style>

<div class="condiciones">
    <h2>CONDICIONES:</h2>
    <p>1.- ESTE CONTRATO SE EXTIENDE POR DUPLICADO Y DEBERÁ DISTRIBUIRSE DE LA SIGUIENTE MANERA: ORIGINAL PARA LA EMPRESA Y COPIA PARA EL CLIENTE.</p>
    <p>2.- CUANDO POR CUALQUIER CIRCUNSTANCIA NO SE UTILICE EL SERVICIO, TENDRÁ DERECHO A LA DEVOLUCIÓN TOTAL DE SU ANTICIPO SIEMPRE Y CUANDO SE CANCELE EL VIAJE CON 96 HORAS DE ANTICIPACIÓN. TODO SERVICIO QUE SE CANCELE ANTES DE 72 HORAS POR PARTE DE LOS CONTRATANTES DEJARÁ UN 10% PARA GASTOS ADMINISTRATIVOS, CUANDO SE CANCELE EL VIAJE DENTRO DE LAS 24 H. ANTES DE LA HORA DE SALIDA O EN EL LUGAR DE SALIDA, EL CONTRATANTE PAGARÁ EL 50% DEL VIAJE.</p>
    <p>3.- SI UN PASAJERO O EL GRUPO INTERRUMPEN EL VIAJE EN UNA INSTANCIA O LUGAR INTERMEDIO POR CAUSAS AJENAS AL TRANSPORTISTA, NO TENDRÁN DERECHO AL REEMBOLSO DE LA PARTE PROPORCIONAL DEL IMPORTE DEL SERVICIO.</p>
    <p>4.- EL CONTRATANTE SE OBLIGA A PAGAR EL 50% O EL TOTAL DEL COSTO DEL SERVICIO COMO ANTICIPO EN EL MOMENTO DE LA CONTRATACIÓN Y EL SALDO AL INICIAR EL SERVICIO.</p>
    <p>5.- EL CONTRATANTE ES RESPONSABLE DE LOS DAÑOS EN EL INTERIOR O EXTERIOR DE LA UNIDAD QUE CAUSEN LOS PASAJEROS DURANTE EL TRAYECTO DEL VIAJE.</p>
    <p>6.- EL NÚMERO DE PASAJEROS NO PUEDE EXCEDER LA CAPACIDAD AUTORIZADA AL VEHÍCULO QUE SE CONTRATA.</p>
    <p>7.- LA EMPRESA NO SE HACE RESPONSABLE DE LOS ARTÍCULOS PERSONALES, EQUIPAJE, ETC. EN CASO DE ROBOS, ASALTOS, ETC. TOTAL O PARCIAL EN EL TRAYECTO DEL VIAJE Y DURANTE LA ESTANCIA, ASÍ COMO NO SE HACE RESPONSABLE DE OBJETOS OLVIDADOS.</p>
    <p>8.- TODO VEHÍCULO CONTRATADO DEBE PONERSE A DISPOSICIÓN DE LOS USUARIOS EN PERFECTAS CONDICIONES MECÁNICAS, DE HIGIENE Y SEGURIDAD.</p>
    <p>9.- LA EMPRESA ESTÁ OBLIGADA A PONER LA UNIDAD CONTRATADA EN EL DOMICILIO INDICADO EL DÍA Y HORA SEÑALADA EN ESTE CONTRATO CON UNA ANTICIPACIÓN DE 20 MINUTOS.</p>
    <p>10.- EN CASO DE QUE LA UNIDAD NO SE PRESENTARA EN EL LUGAR FECHA Y HORA SEÑALADA, LA EMPRESA ESTÁ OBLIGADA A DEVOLVER EL ANTICIPO O EN SU CASO EL TOTAL DEL COBRO DEL SERVICIO.</p>
    <p>11.- LA EMPRESA ESTÁ OBLIGADA A PRESTAR EL SERVICIO COMO SE ESTIPULA EN EL APARTADO DE RUTA Y PASEOS INCLUIDOS EN EL SERVICIO.</p>
    <p>12.- CUANDO LOS PASAJEROS DECIDAN CAMBIAR EL ITINERARIO ESTABLECIDO UNA VEZ INICIADO EL VIAJE, SERÁ MOTIVO DE CONVENIO POR SEPARADO, TOMANDO EN CUENTA LOS FACTORES DE COBRO EN EL CAMBIO DE LA RUTA Y PASEOS.</p>
    <p>13.- LA TARIFA APLICADA INCLUYE EL SEGURO DE VIAJERO COMO LO ESTABLECE EL ARTÍCULO 127 DE LA LEY DE COMUNICACIONES Y TRANSPORTES, ASÍ COMO LA REGLA 15 DE APLICACIONES DE SEGURO DE VIAJERO, TAMBIÉN HONORARIOS, GASTOS DE ALIMENTACIÓN Y HOSPEDAJE DEL OPERADOR.</p>
    <p>14.- EN CASO DE LA DESCOMPOSTURA DE LA UNIDAD EN EL TRAYECTO DE VIAJE LOS PASAJEROS ESTÁN OBLIGADOS A ESPERAR A QUE SE REALICE LA REPARACIÓN NECESARIA O EN SU CASO EL TIEMPO QUE TARDE EN LLEGAR OTRA UNIDAD ASIGNADA POR LA EMPRESA PARA CONTINUAR CON EL VIAJE.</p>
    <p>15.- ES RESPONSABILIDAD DEL CONTRATANTE Y LOS PASAJEROS SI HACEN MAL USO DEL VEHÍCULO QUE SE ESTIPULA EN ESTE CONTRATO, O SI TRANSPORTAN OBJETOS O SUSTANCIAS PROHIBIDAS POR LA LEY.</p>
</div>
';

$mpdf->WriteHTML($html);
$mpdf->AddPage();
$mpdf->WriteHTML($html_condiciones);
$mpdf->Output('Contrato_Viaje_Especial.pdf', 'I');