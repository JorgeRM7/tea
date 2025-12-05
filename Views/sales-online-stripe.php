<?php
require_once dirname(__DIR__) . "/Database/conexion.php";
$config = include dirname(__DIR__) . "/Config/config.php";
$stripeConfig = include dirname(__DIR__) . "/Config/stripe.php";
$publicKey = $stripeConfig['publishable_key'] ?? '';
$token = $config['token'];
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Venta de Boletos - Stripe | TEA</title>
    <link rel="icon" type="image/x-icon" href="../assets/img/logo.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://js.stripe.com/v3/"></script>
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
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 2rem;
            width: 100%;
            max-width: 520px;
            backdrop-filter: blur(15px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
        }

        .form-label {
            font-weight: 600;
            color: #f2a71e;
        }

        .btn-buy {
            background: linear-gradient(90deg, #635bff, #00d4ff);
            border: none;
            padding: 0.9rem;
            font-weight: 700;
            font-size: 1.1rem;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .btn-buy:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
        }

        .summary-box {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .summary-box h6 {
            margin: 0;
            font-size: 0.9rem;
            color: #a9b5bd;
        }

        .summary-box p {
            font-size: 1rem;
            font-weight: bold;
            margin: 0;
            color: #fff;
        }

        .is-invalid {
            border: 2px solid #dc3545 !important;
        }
    </style>
</head>

<body>
    <div class="ticket-box">
        <div class="text-center mb-4">
            <img src="../assets/img/logo.png" alt="TEA" width="80" class="mb-3">
            <h3 class="fw-bold text-uppercase">Venta de Boletos</h3>
            <p class="text-white">Genera tu compra y seras enviado a Stripe Checkout</p>
        </div>

        <div class="mb-3">
            <label class="form-label"><i class="bi bi-geo-alt-fill"></i> Origen</label>
            <select class="form-select" id="origin" name="origin">
                <option selected disabled>Selecciona origen</option>
                <?php
                $sql = "SELECT origin FROM `routes_stop` WHERE deleted_at is null GROUP BY origin;";
                $query = ejecutarConsulta($sql);
                while ($valores = mysqli_fetch_array($query)) {
                    echo "<option value='" . $valores['origin'] . "'>" . $valores['origin'] . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label"><i class="bi bi-flag-fill"></i> Destino</label>
            <select class="form-select" id="destination" name="destination">
                <option selected disabled>Selecciona destino...</option>
            </select>
        </div>

        <div class="mb-3 row g-2">
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-calendar-event"></i> Fecha</label>
                <input type="date" class="form-control" id="date" name="date" value="<?php echo date('Y-m-d'); ?>"
                    min="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-clock-fill"></i> Horario</label>
                <select class="form-select" id="schedule" name="schedule">
                    <option selected disabled>Selecciona horario...</option>
                </select>
            </div>
        </div>

        <div class="mb-3 row g-2">
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-ticket-detailed-fill"></i> Cantidad</label>
                <input type="number" class="form-control" id="quantity" min="1" value="1">
            </div>
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-cash"></i> Costo</label>
                <input type="text" class="form-control" id="price" name="price" value="0" readonly>
            </div>
        </div>

        <div class="summary-box">
            <h6><i class="bi bi-receipt"></i> Resumen</h6>
            <p id="summary">Total: $0</p>
        </div>

        <div class="d-grid gap-2 mt-3">
            <button type="button" class="btn btn-outline-light" onclick="window.location.href='landing_public_2.php'">
                <i class="bi bi-arrow-left"></i> Volver al inicio
            </button>
            <button type="button" class="btn btn-buy" onclick="buyTicket()">
                <i class="bi bi-credit-card"></i> Pagar con Stripe
            </button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script>
        const STRIPE_PUBLIC_KEY = "<?php echo $publicKey; ?>";
        const token = "<?php echo $token; ?>";
        const stripeInstance = STRIPE_PUBLIC_KEY && STRIPE_PUBLIC_KEY !== "pk_test_your_key"
            ? Stripe(STRIPE_PUBLIC_KEY)
            : null;

        $(document).ready(function () {
            $("#origin").on("change", show_subpaths);
            $("#destination, #date").on("change", schedules);
            $("#quantity").on("input", updateSummary);
        });

        const show_subpaths = () => {
            let origin = $("#origin").val();
            $.ajax({
                url: "../Controllers/salesOnlineStripeController.php?op=show-subpaths",
                type: "POST",
                headers: { "Authorization": "Bearer " + token },
                data: { origin },
                dataType: "json",
                success: function (data) {
                    let $select = $("#destination");
                    $select.empty().append('<option selected disabled>Selecciona destino...</option>');
                    data.forEach(item => {
                        $select.append(`<option value="${item.destination}">${item.destination}</option>`);
                    });
                }
            });
        };

        const schedules = () => {
            let origin = $("#origin").val();
            let destination = $("#destination").val();
            let date = $("#date").val();

            $.ajax({
                url: '../Controllers/salesOnlineStripeController.php?op=schedules',
                type: 'GET',
                headers: { "Authorization": "Bearer " + token },
                dataType: 'json',
                data: { origin, destination, date },
                success: function (data) {
                    $("#price").val(data[0]?.price || 0);
                    updateSummary();

                    let $select = $("#schedule");
                    $select.empty().append('<option selected disabled>Selecciona horario...</option>');
                    data.forEach(item => {
                        $select.append(`<option value="${item.schedule_id}">${item.leaving_time}</option>`);
                    });
                }
            });
        };

        const updateSummary = () => {
            let qty = parseInt($("#quantity").val()) || 1;
            let price = parseFloat($("#price").val()) || 0;
            $("#summary").text(`Total: $${(qty * price).toFixed(2)}`);
        };

        const buyTicket = () => {
            let origin      = $("#origin").val();
            let destination = $("#destination").val();
            let date        = $("#date").val();
            let schedule    = $("#schedule").val();
            let quantity    = $("#quantity").val();
            let price       = $("#price").val();

            if (!origin || origin === "Selecciona origen") {
                Swal.fire({ icon: "warning", title: "Campo faltante", text: "Por favor selecciona un origen." });
                return;
            }
            if (!destination || destination === "Selecciona destino...") {
                Swal.fire({ icon: "warning", title: "Campo faltante", text: "Por favor selecciona un destino." });
                return;
            }
            if (!date) {
                Swal.fire({ icon: "warning", title: "Campo faltante", text: "Por favor selecciona una fecha valida." });
                return;
            }
            if (!schedule || schedule === "Selecciona horario...") {
                Swal.fire({ icon: "warning", title: "Campo faltante", text: "Por favor selecciona un horario." });
                return;
            }
            if (!quantity || quantity <= 0) {
                Swal.fire({ icon: "warning", title: "Campo faltante", text: "Por favor ingresa una cantidad valida." });
                return;
            }
            if (!price || parseFloat(price) <= 0) {
                Swal.fire({ icon: "warning", title: "Campo faltante", text: "El costo del boleto no puede ser $0." });
                return;
            }

            $.ajax({
                url: "../Controllers/salesOnlineStripeController.php?op=buy",
                type: "POST",
                headers: { "Authorization": "Bearer " + token },
                data: { origin, destination, date, schedule, quantity, price },
                dataType: "json",
                beforeSend: () => {
                    Swal.fire({ title: "Creando checkout...", didOpen: () => Swal.showLoading(), allowOutsideClick: false });
                },
                success: function (response) {
                    Swal.close();
                    if (response.success) {
                        redirectToStripe(response.session_id, response.url);
                    } else {
                        Swal.fire({ icon: "error", title: "Compra", text: response.error || "No se pudo crear la sesion." });
                    }
                },
                error: function () {
                    Swal.close();
                    Swal.fire({ icon: "error", title: "Error", text: "Hubo un problema al procesar los datos." });
                }
            });
        };

        const redirectToStripe = (sessionId, fallbackUrl) => {
            if (stripeInstance && sessionId) {
                stripeInstance.redirectToCheckout({ sessionId }).then(function (result) {
                    if (result.error && fallbackUrl) {
                        window.location.href = fallbackUrl;
                    }
                });
            } else if (fallbackUrl) {
                window.location.href = fallbackUrl;
            } else {
                Swal.fire({ icon: "error", title: "Stripe", text: "No fue posible iniciar el checkout." });
            }
        };
    </script>
</body>

</html>
