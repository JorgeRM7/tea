<?php
require_once dirname(__DIR__) . "/Database/conexion.php";
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Venta de Boletos - TEA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
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
            max-width: 500px;
            backdrop-filter: blur(15px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
        }

        .form-label {
            font-weight: 600;
            color: #f2a71e;
        }

        .btn-buy {
            background: linear-gradient(90deg, #38b449, #f2a71e);
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
    </style>
</head>

<body>
    <div class="ticket-box">
        <div class="text-center mb-4">
            <img src="../assets/img/logo.png" alt="TEA" width="80" class="mb-3">
            <h3 class="fw-bold text-uppercase">Venta de Boletos</h3>
            <p class="text-white">Selecciona tu viaje y compra tu boleto en línea</p>
        </div>

        <!-- <form id="ticketForm"> -->
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-geo-alt-fill"></i> Origen</label>
                <select class="form-select" id="origin" name="origin">
                    <option selected disabled>Selecciona origen</option>
                    <?php 
                        $sql = "SELECT origin FROM `routes_stop` WHERE deleted_at is null GROUP BY origin;";
                        $query = ejecutarConsulta($sql);
                        while($valores = mysqli_fetch_array($query)){
                            echo "<option value='".$valores['origin']."'>".$valores['origin']."</option>";
                        }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="bi bi-flag-fill"></i> Destino</label>
                <select class="form-select" id="destination" name="destination"></select>
            </div>

            <div class="mb-3 row g-2">
                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-calendar-event"></i> Fecha</label>
                    <input type="date" class="form-control" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-clock-fill"></i> Horario</label>
                    <select class="form-select" id="schedule" name="schedule"></select>
                </div>
            </div>
            <div class="mb-3 row g-2">
                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-ticket-detailed-fill"></i> Cantidad</label>
                    <input type="number" class="form-control" id="quantity" min="1" value="1">
                </div>
                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-cash"></i> Costo</label>
                    <input type="text" class="form-control" id="price" name="price" value="120" readonly>
                </div>
            </div>
            <div class="summary-box">
                <h6><i class="bi bi-receipt"></i> Resumen</h6>
                <p id="summary">Total: $120</p>
            </div>

            <div id="paymentBrick_container" class="mt-4"></div>


            <button type="button" class="btn btn-buy w-100 mt-3" onclick="buyTicket()">
                <i class="bi bi-credit-card"></i> Comprar Boleto
            </button>
        <!-- </form> -->
    </div>
</body>

</html>
<script src="https://sdk.mercadopago.com/js/v2"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script>
    let token = localStorage?.token;
    $(document).ready(function() {

        $("#origin").on("change", function() {
            show_subpaths();
        });

        $("#destination").on("change keyup", function() {
            schedules();
        });
       
    });

    const buyTicket = () => { 
        let origin      = $("#origin").val();
        let destination = $("#destination").val();
        let date        = $("#date").val();
        let schedule    = $("#schedule").val();
        let quantity    = $("#quantity").val();
        let price       = $("#price").val();

        $.ajax({
            url: "../Controllers/salesOnlineController.php?op=buy",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + token
            },
            data: { 
                origin: origin,
                destination: destination,
                date: date,
                schedule: schedule,
                quantity: quantity,
                price: price 
            },
            dataType: "json",
            success: function (response) {
                console.log(response)
                if (response.success) {
                    const preferenceId = response.ids.id;
                    // window.location.href = `https://sandbox.mercadopago.com.mx/checkout/v1/redirect?pref_id=${preferenceId}`;
                    renderWalletBrick(preferenceId);
                } else {
                    Swal.fire({
                    icon: "error",
                    title: "Compra",
                    text: "No se pudo crear la preferencia."
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error("Error en la solicitud:", error);
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Hubo un problema al procesar los datos.",
                    confirmButtonColor: "#f07d42"
                });
            }
        });
    };

    const renderWalletBrick = (preferenceId) => {
  const mp = new MercadoPago("TEST-80a3b7a8-e43d-4b7f-8b8d-aee3d96dca7f", { locale: "es-MX" });

  mp.bricks().create("wallet", "paymentBrick_container", {
    initialization: { preferenceId },
    customization: {
      visual: { style: { theme: "default" } },
      texts: { valueProp: 'smart_option' }
    },
    callbacks: {
      onReady: () => console.log("Wallet Brick listo"),
      onError: (error) => console.error("Error en Wallet Brick:", error)
    }
  });
};

    const show_subpaths = () => { 
        let origin = $("#origin").val(); 
        $.ajax({
            url: "../Controllers/salesOnlineController.php?op=show-subpaths",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + token
            },
            data: { origin: origin },
            dataType: "json",
            success: function (response) {
                let data = response;
                let $select = $("#destination");
                $select.empty();
                $select.append('<option value="">Selecciona destino...</option>');
                data.forEach(item => {
                    $select.append(
                        `<option value="${item.destination}">
                            ${item.destination}
                        </option>`
                    );
                });

            },
            error: function (xhr, status, error) {
                console.error("Error en la solicitud:", error);
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Hubo un problema al procesar los datos.",
                    confirmButtonColor: "#f07d42"
                });
            }
        });
    };
    
    const schedules = () => {
        let origin = $("#origin").val();
        let destination = $("#destination").val();
        let date     = $("#date").val();

        $.ajax({
            url: '../Controllers/salesOnlineController.php?op=schedules',
            type: 'GET',
            headers: {
                "Authorization": "Bearer " + token
            },
            dataType: 'json',
            data: { origin: origin, destination: destination, date: date },
            success: function(schedules) {
                console.log(schedules)
                let data = schedules;
                $("#price").val(schedules[0]?.price)
                let $select = $("#schedule");
                $select.empty();
                $select.append('<option value="">Selecciona horario...</option>');
                data.forEach(item => {
                    $select.append(
                        `<option value="${item.schedule_id}">
                            ${item.leaving_time}
                        </option>`
                    );
                });

                
            },
            error: function(e) {
                console.error("Error cargando horarios:", e.responseText);
            }
        });
    };


  </script>