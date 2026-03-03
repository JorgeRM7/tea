<?php
require_once dirname(__DIR__) . "/Database/conexion.php";
require_once __DIR__ . '/../vendor/autoload.php';
use Mpdf\Mpdf;

$mpdf = new Mpdf([
    'format' => 'Letter',
    'margin_left' => 15,
    'margin_right' => 15,
    'margin_top' => 15,
    'margin_bottom' => 15,
    'orientation' => 'P',
    'default_font' => 'times'
]);

$sql = '
    SELECT 
        special_trips.id,
        special_trips.origin,
        special_trips.days,
        special_trips.price,
        special_trips.start_date,
        special_trips.end_date,
        clients.name,
        clients.phone,
        vehicles.unidad_number,
        vehicles.capacity
    FROM `special_trips`
    INNER JOIN clients ON clients.id = special_trips.client_id
    INNER JOIN vehicles ON vehicles.id = special_trips.vehicle_id;
';
$result = ejecutarConsulta($sql);
$item = mysqli_fetch_assoc($result);
$precio = $item['price'];
$date = date("Y-m-d");

function numeroALetras($numero){

    $formatter = new NumberFormatter("es", NumberFormatter::SPELLOUT);

    return strtoupper($formatter->format($numero));
}

$precioLetra = numeroALetras($precio);
$html = "

<style>
body{
    font-family: times;
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

.firma{
    width:45%;
    display:inline-block;
    text-align:center;
}

.line-sign{
    border-top:1px solid #000;
    width:220px;
    margin:30px auto 5px auto;
}
</style>

<div class='header'>
    <div class='header-title'>
        COOPERATIVA DE TRANSPORTES EJIDALES S.C.L.
    </div>
    <div class='header-sub'>
        Domicilio Calle Guerrero No 65 Col. Centro, Ario de Rosales Mich.
    </div>
    <div class='header-sub'>
        RFC: TEA970121-L21 &nbsp;&nbsp; TEL. 01 (422) 52 1-16-08
    </div>
    <div class='header-sub'>
        CORREO ELECTRONICO t_lgar_ade@live.com.mx
    </div>
    <div class='header-sub' style='margin-top:4px;'>
        Ario de Rosales Michoacán A: {$date} <span class='short-line'></span>
    </div>
</div>

<div class='contract-title'>
    CONTRATO DE VIAJE ESPECIAL
</div>

<div class='justify'>

    <span class='label'>NOMBRE DEL CONTRATANTE:</span> 
    <span class='long-line'>{$item['name']}</span>

    <br><br>

    <span class='label'>UNIDAD CONTRATADA:</span> 
    <span class='medium-line'>{$item['unidad_number']}</span>

    &nbsp;&nbsp;
    <span class='label'>CAPACIDAD:</span> 
    <span class='short-line'>{$item['capacity']}</span>

    &nbsp;&nbsp;
    <span class='label'>PASAJEROS:</span> 
    <span class='short-line'></span>

    &nbsp; Contratada para prestar el Servicio de Transportación a 
    <span class='medium-line'>{$item['origin']}</span>

    <br><br>

    con un costo total del Servicio por $ 
    <span class='short-line'>{$item['price']}</span>

    <span class='medium-line'>
        ( {$precioLetra} PESOS )
    </span>

    depositando un anticipo Garantía por la cantidad de $ 
    <span class='short-line'>{$item['price']}</span>

    <br><br>

    Quedando pendiente de pago la cantidad de $ 
    <span class='short-line'></span>
    para el día que se efectúe el servicio.

</div>

<div class='section-title'>
    DATOS DE LA SALIDA
</div>

<div class='justify'>
    Fecha de Salida: <span class='short-line'>{$item['start_date']}</span>
    &nbsp; a las <span class='short-line'></span>
    &nbsp; Presentando la unidad en:
    <span class='medium-line'>{$item['origin']}</span>

    <br><br>

    Ciudad de: <span class='medium-line'></span>
    &nbsp; a la Orden del Sr.(a):
    <span class='medium-line'>{$item['name']}</span>

    <br><br>

    Cel: <span class='short-line'>{$item['phone']}</span>
    &nbsp; Casa u oficina:
    <span class='medium-line'></span>
    </div>

    <div class='section-title'>
        DATOS DEL REGRESO
    </div>

    <div class='justify'>
        El regreso será el día <span class='short-line'>{$item['end_date']}</span> a las <span class='short-line'></span> en domicilio destino
        <span class='medium-line'>{$item['origin']}</span> de la ciudad de <span class='medium-line'></span>
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
        Todo servicio solicitado por el Contratante o Encargado(a) de grupo no especificado en este contrato
        se considera como Paseo, Traslado o Servicio Extra, por lo tanto tendrá un costo adicional el cual se pagará
        al momento de solicitarlo al operador. Las Unidades no circulan en terracerías o caminos en mal estado
        que pongan en riesgo la seguridad de los pasajeros y de la unidad.
        Ambas partes convienen someter cualquier controversia a los Tribunales competentes,
        renunciando al fuero que pudiera corresponderles.
    </div>

    <div class='firmas'>
        <div class='firma'>
            <div class='line-sign'></div>
            CONTRATANTE
        </div>

        <div class='firma' style='float:right;'>
            <div class='line-sign'></div>
            TRANSPORTES EJIDALES ARIO DE R. SCL
        </div>
    </div>

";

$html_condiciones='
<style>
.condiciones{
    font-family: "Times New Roman", serif;
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

<p>
1.- ESTE CONTRATO SE EXTIENDE POR DUPLICADO Y DEBERÁ DISTRIBUIRSE DE LA SIGUIENTE MANERA: ORIGINAL PARA LA EMPRESA Y COPIA PARA EL CLIENTE.
</p>

<p>
2.- CUANDO POR CUALQUIER CIRCUNSTANCIA NO SE UTILICE EL SERVICIO, TENDRÁ DERECHO A LA DEVOLUCIÓN TOTAL DE SU ANTICIPO SIEMPRE Y CUANDO SE CANCELE EL VIAJE CON 96 HORAS DE ANTICIPACIÓN. TODO SERVICIO QUE SE CANCELE ANTES DE 72 HORAS POR PARTE DE LOS CONTRATANTES DEJARÁ UN 10% PARA GASTOS ADMINISTRATIVOS, CUANDO SE CANCELE EL VIAJE DENTRO DE LAS 24 H. ANTES DE LA HORA DE SALIDA O EN EL LUGAR DE SALIDA, EL CONTRATANTE PAGARÁ EL 50% DEL VIAJE.
</p>

<p>
3.- SI UN PASAJERO O EL GRUPO INTERRUMPEN EL VIAJE EN UNA INSTANCIA O LUGAR INTERMEDIO POR CAUSAS AJENAS AL TRANSPORTISTA, NO TENDRÁN DERECHO AL REEMBOLSO DE LA PARTE PROPORCIONAL DEL IMPORTE DEL SERVICIO.
</p>

<p>
4.- EL CONTRATANTE SE OBLIGA A PAGAR EL 50% O EL TOTAL DEL COSTO DEL SERVICIO COMO ANTICIPO EN EL MOMENTO DE LA CONTRATACIÓN Y EL SALDO AL INICIAR EL SERVICIO.
</p>

<p>
5.- EL CONTRATANTE ES RESPONSABLE DE LOS DAÑOS EN EL INTERIOR O EXTERIOR DE LA UNIDAD QUE CAUSEN LOS PASAJEROS DURANTE EL TRAYECTO DEL VIAJE.
</p>

<p>
6.- EL NÚMERO DE PASAJEROS NO PUEDE EXCEDER LA CAPACIDAD AUTORIZADA AL VEHÍCULO QUE SE CONTRATA.
</p>

<p>
7.- LA EMPRESA NO SE HACE RESPONSABLE DE LOS ARTÍCULOS PERSONALES, EQUIPAJE, ETC. EN CASO DE ROBOS, ASALTOS, ETC. TOTAL O PARCIAL EN EL TRAYECTO DEL VIAJE Y DURANTE LA ESTANCIA, ASÍ COMO NO SE HACE RESPONSABLE DE OBJETOS OLVIDADOS.
</p>

<p>
8.- TODO VEHÍCULO CONTRATADO DEBE PONERSE A DISPOSICIÓN DE LOS USUARIOS EN PERFECTAS CONDICIONES MECÁNICAS, DE HIGIENE Y SEGURIDAD.
</p>

<p>
9.- LA EMPRESA ESTÁ OBLIGADA A PONER LA UNIDAD CONTRATADA EN EL DOMICILIO INDICADO EL DÍA Y HORA SEÑALADA EN ESTE CONTRATO CON UNA ANTICIPACIÓN DE 20 MINUTOS.
</p>

<p>
10.- EN CASO DE QUE LA UNIDAD NO SE PRESENTARA EN EL LUGAR FECHA Y HORA SEÑALADA, LA EMPRESA ESTÁ OBLIGADA A DEVOLVER EL ANTICIPO O EN SU CASO EL TOTAL DEL COBRO DEL SERVICIO.
</p>

<p>
11.- LA EMPRESA ESTÁ OBLIGADA A PRESTAR EL SERVICIO COMO SE ESTIPULA EN EL APARTADO DE RUTA Y PASEOS INCLUIDOS EN EL SERVICIO.
</p>

<p>
12.- CUANDO LOS PASAJEROS DECIDAN CAMBIAR EL ITINERARIO ESTABLECIDO UNA VEZ INICIADO EL VIAJE, SERÁ MOTIVO DE CONVENIO POR SEPARADO, TOMANDO EN CUENTA LOS FACTORES DE COBRO EN EL CAMBIO DE LA RUTA Y PASEOS.
</p>

<p>
13.- LA TARIFA APLICADA INCLUYE EL SEGURO DE VIAJERO COMO LO ESTABLECE EL ARTÍCULO 127 DE LA LEY DE COMUNICACIONES Y TRANSPORTES, ASÍ COMO LA REGLA 15 DE APLICACIONES DE SEGURO DE VIAJERO, TAMBIÉN HONORARIOS, GASTOS DE ALIMENTACIÓN Y HOSPEDAJE DEL OPERADOR.
</p>

<p>
14.- EN CASO DE LA DESCOMPOSTURA DE LA UNIDAD EN EL TRAYECTO DE VIAJE LOS PASAJEROS ESTÁN OBLIGADOS A ESPERAR A QUE SE REALICE LA REPARACIÓN NECESARIA O EN SU CASO EL TIEMPO QUE TARDE EN LLEGAR OTRA UNIDAD ASIGNADA POR LA EMPRESA PARA CONTINUAR CON EL VIAJE.
</p>

<p>
15.- ES RESPONSABILIDAD DEL CONTRATANTE Y LOS PASAJEROS SI HACEN MAL USO DEL VEHÍCULO QUE SE ESTIPULA EN ESTE CONTRATO, O SI TRANSPORTAN OBJETOS O SUSTANCIAS PROHIBIDAS POR LA LEY.
</p>

</div>
';

$mpdf->WriteHTML($html);
$mpdf->AddPage();
$mpdf->WriteHTML($html_condiciones);
$mpdf->Output('Contrato_Viaje_Especial.pdf', 'I');