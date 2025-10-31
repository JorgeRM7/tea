<?php
require_once dirname(__DIR__) . "/Database/conexion.php";
$config = include dirname(__DIR__) . "/Config/config.php";

$ticket_id = $_GET['ticket_id'] ?? 0;

$sql = "
        SELECT 
            tickets.id,
            tickets.price,
            tickets.discount,
            tickets.quantity,
            tickets.status,
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
        WHERE tickets.id = '$ticket_id'";
$query = ejecutarConsulta($sql);
$ticket = mysqli_fetch_assoc($query);
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Boleto #<?php echo $ticket_id; ?> - TEA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../assets/img/logo.png" />
    <style>
        body {
            background: linear-gradient(135deg, #0d1a1e, #0a4d0e);
            color: #fff;
            font-family: 'Public Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ticket-box {
            background: rgba(255, 255, 255, .1);
            border-radius: 20px;
            padding: 2rem;
            max-width: 600px;
            backdrop-filter: blur(15px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, .4);
        }

        .status-icon {
            font-size: 4rem;
            color: #38b449;
        }

        .ticket-data {
            text-align: left;
            margin-top: 1rem;
        }

        .ticket-data p {
            margin: 0.3rem 0;
            font-size: 1rem;
        }

        .btn-download {
            background: #f2a71e;
            color: #000;
            font-weight: bold;
            border: none;
        }
    </style>
</head>

<body>
    <div class="ticket-box text-center">
        <img src="../assets/img/logo.png" alt="TEA" width="80" class="mb-3">
        <h3 class="fw-bold">¡Pago Exitoso!</h3>
        <i class="bi bi-check-circle-fill status-icon mb-3"></i>
        <p class="mb-3">Tu compra fue procesada correctamente 🎉</p>

        <div class="ticket-data">
            <p><strong>Boleto:</strong> #<?php echo $ticket['id']; ?></p>
            <p><strong>Origen:</strong> <?php echo $ticket['origin']; ?></p>
            <p><strong>Destino:</strong> <?php echo $ticket['destination']; ?></p>
            <p><strong>Fecha:</strong> <?php echo $ticket['date']; ?></p>
            <p><strong>Horario:</strong> <?php echo $ticket['leaving_time']; ?></p>
            <p><strong>Vehículo:</strong> <?php echo $ticket['vehicle_id']; ?></p>
            <p><strong>Cantidad:</strong> <?php echo $ticket['quantity']; ?></p>
            <p><strong>Precio:</strong> $<?php echo number_format($ticket['price'], 2); ?></p>
            <p><strong>Estatus:</strong> <?php echo $ticket['status']; ?></p>
        </div>

        <div class="mt-4">
            <a href="../Pdf/ticket.php?tickets_id=<?php echo $ticket_id; ?>" class="btn btn-download">
                <i class="bi bi-file-earmark-pdf-fill"></i> Descargar Boleto
            </a>
            <a href="sales-online.php" class="btn btn-light">Volver a comprar</a>
        </div>
    </div>
</body>

</html>