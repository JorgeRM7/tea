<?php
$ticket_id = $_GET['ticket_id'] ?? 0;
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Pago cancelado - Stripe | TEA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../assets/img/logo.png" />
    <style>
        body {
            background: linear-gradient(135deg, #0d1a1e, #4b0000);
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
            max-width: 500px;
            width: 100%;
            backdrop-filter: blur(15px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, .4);
        }

        .status-icon {
            font-size: 4rem;
            color: #f2a71e;
        }
    </style>
</head>

<body>
    <div class="ticket-box text-center">
        <img src="../assets/img/logo.png" alt="TEA" width="80" class="mb-3">
        <h3 class="fw-bold">Pago Cancelado</h3>
        <i class="bi bi-exclamation-triangle-fill status-icon mb-3"></i>
        <p class="mb-3">El proceso con Stripe se canceló. Puedes volver a intentarlo en cualquier momento.</p>
        <p>Boleto temporal #: <?php echo htmlspecialchars($ticket_id); ?></p>
        <div class="mt-4 d-flex gap-2 flex-column flex-md-row justify-content-center">
            <a href="sales-online-stripe.php" class="btn btn-light">Volver al inicio</a>
            <a href="sales-online.php" class="btn btn-outline-light">Comprar con MercadoPago</a>
        </div>
    </div>
</body>

</html>
