<!doctype html>
<?php ;$title = "Vehiculos"; ?>
<html lang="es" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr"
    data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template">
<!--HEADER-->
<?php require_once('header.php'); ?>
<!--HEADER-->

<style>
    .ticket-card {
        border: 2px solid #e0e0e0;
        border-radius: 16px;
        /* background: #fff; */
        position: relative;
        overflow: hidden;
    }

    .ticket-body {
        padding: 16px;
    }

    .ticket-divider {
        border-top: 2px dashed #ccc;
        margin: 8px 0;
        position: relative;
    }

    .ticket-divider::before,
    .ticket-divider::after {
        content: "";
        width: 16px;
        height: 16px;
        background: #fff;
        border: 2px solid #ccc;
        border-radius: 50%;
        position: absolute;
        top: -9px;
    }

    .ticket-divider::before {
        left: -18px;
    }

    .ticket-divider::after {
        right: -18px;
    }

    .selectable-card {
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }

    .selectable-card:hover {
        transform: scale(1.02);
        border: 2px solid #28c76f;
    }

    .selectable-card.active {
        border: 2px solid #28c76f;
        background: #e6f8f0;
        box-shadow: 0 0 10px rgba(40, 199, 111, 0.4);
    }

    .detail-box .value.text-danger {
        font-weight: 800;
    }

    .detail-box .value.text-warning {
        font-weight: 700;
    }

    .detail-box .value.text-success {
        font-weight: 700;
    }
    .ticket-divider {
    border-top: 2px dashed #ccc;
    position: relative;
    margin: 16px 0;
    }

    .ticket-divider::before,
    .ticket-divider::after {
    content: "";
    width: 16px;
    height: 16px;
    background: #fff;
    border: 2px solid #ccc;
    border-radius: 50%;
    position: absolute;
    top: -10px;
    }

    .ticket-divider::before {
    left: -10px;
    }

    .ticket-divider::after {
    right: -10px;
    }




</style>
<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!--MENU-->
            <?php require_once('menu.php'); ?>
            <!--MENU-->
            <div class="layout-page">
                <!--BARRA DE NAVEGACION-->
                <?php require_once('barra_navegacion.php'); ?>
                <!--BARRA DE NAVEGACION-->
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Busqueda</h5>
                                        <small class="text-muted float-end">Default label</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="row"> 
                                            <div class="col-md-4">
                                                <label for="nameWithTitle" class="form-label">Fecha</label>
                                                <input type="date" id="search_date" name="search_date" class="form-control" value="<?php echo date('Y-m-d'); ?>"  min="<?php echo date('Y-m-d'); ?>" onchange="schedules()" />
                                            </div>
                                            <div class="col-md-4">
                                                <label for="nameWithTitle" class="form-label">Ruta</label>
                                                <select class="form-select select2-container" id="search_route" name="search_route" aria-label="Default select example" onchange="schedules()">
                                                    <option value="">Selecciona...</option>
                                                    <?php 
                                                        $sql = "SELECT * FROM `routes` WHERE deleted_at is null";
                                                        $query = ejecutarConsulta($sql);
                                                        while($valores = mysqli_fetch_array($query)){
                                                            echo "<option value='".$valores['id']."'>".$valores['origin']." - ".$valores['destination']."</option>";
                                                        }
                                                    ?>
                                                </select>
                                            </div>  
                                            <div class="col-md-4">
                                                <label for="nameWithTitle" class="form-label">Horario</label>
                                                <select class="form-select select2-container" id="search_schedule" name="search_schedule" aria-label="Default select example">
                                                </select>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card mb-4 100 h-100">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Rutas</h5>
                                        <small class="text-muted float-end">Default label</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12" id="routes"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 ">
                                <div class="card mb-4 shadow-lg border-0 100 h-100" id="detailsCard" style="display:none;">
                                    <div class="card-header bg-gradient text-white d-flex justify-content-between align-items-center" style="background: linear-gradient(45deg, #007bff, #0056b3);">
                                        <h5 class="mb-0"><i class="bi bi-ticket-perforated"></i> Detalles del Boleto</h5>
                                        <small class="text-light">Información</small>
                                    </div>
                                    <div class="card-body" id="detailsBody"></div>
                                    <div class="card-body border-top">
                                        <form name="formulario" id="formulario" method="POST">
                                            <div class="row">
                                                
                                                <input type="hidden" id="route_schedule_id" name="route_schedule_id" class="form-control">
                                                <input type="hidden" id="employee_id" name="employee_id" class="form-control">
                                                <input type="hidden" id="route_id" name="route_id" class="form-control">
                                                <input type="hidden" id="cost" name="cost" class="form-control">
                                                <input type="hidden" id="vehicle_id" name="vehicle_id" class="form-control">
                                                <!-- <input type="text" id="route_schedule_id" name="route_schedule_id" class="form-control"> -->
                                                <div class="col-md-12">
                                                    <h4 class="fw-bold text-uppercase text-primary" id="total_label">Total a pagar: $</h4>
                                                </div>
                                                <div class="col-md-12">
                                                    <h4 class="fw-bold text-uppercase text-primary" id="amount_received_label">Recibi: $</h4>
                                                </div>
                                                <div class="col-md-12">
                                                    <h4 class="fw-bold text-uppercase text-primary" id="change_label">Cambio: $</h4>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <label class="form-label">Cantidad de boletos</label>
                                                    <input type="number" id="quantity" name="quantity" class="form-control" min="1" value="1">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Monto recibido</label>
                                                    <input type="number" id="amount_received" name="amount_received" class="form-control" min="0" >
                                                </div>
                                            </div>
                                            
                                        </form>
                                    </div>
                                    <div class="card-footer text-end bg-light">
                                        <button class="btn btn-success fw-bold" id="confirmBtn" type="button" onclick="store()">
                                            <i class="bi bi-check-circle me-1"></i> Confirmar boleto
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- FOOTER -->
                    <?php require_once('footer.php'); ?>
                    <!-- FOOTER -->
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
        <div class="drag-target"></div>
    </div>
</body>

</html>

<script>
    var tabla;
    $(document).ready(function() {
        const menuItem = document.querySelector('a[href="tickets-ticket.php"]').parentElement;
        menuItem.classList.add('active');
        const menuToggle = document.querySelector('a[href="BOLETOS"]').parentElement;
        menuToggle.classList.add('open');
        $("#search_route").select2({ width:"100%"});
        $("#search_schedule").select2({ width:"100%"});

        $("#search_date, #search_schedule, #search_route").on("change keyup", function() {
            routes();
        });

        $("#quantity, #amount_received").on("change keyup", function() {
            total();
        });
        routes();
    });
   
    
    const store = () => {
        const formData = new FormData(document.getElementById("formulario"));
        $.ajax({
            url: "../Controllers/ticketsController.php?op=store",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + token
            },
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",   // 👈 importante
            success: function(response) {
                console.log(response);

                if (response.success) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Registro creado exitosamente.',
                    });

                    let tickets_id = response.ids; // ahora sí es array
                    console.log("Tickets:", tickets_id);

                    // Pasar los IDs al PDF
                    let url = `../Pdf/ticket.php?tickets_id=${tickets_id.join(",")}`;
                    console.log("URL PDF:", url);

                    var iframe = document.createElement('iframe');
                    iframe.className = 'pdfIframe';
                    document.body.appendChild(iframe);
                    iframe.style.display = 'none';

                    iframe.onload = function () {
                        Swal.close();
                        setTimeout(function () {
                            iframe.focus();
                            iframe.contentWindow.print();
                            URL.revokeObjectURL(url);
                        }, 1);
                    };
                    iframe.src = url;
                }
            },
            error: function(error) {
                Swal.fire({
                    title: "Error",
                    text: "No se pudo guardar el registro.",
                    icon: "error"
                });
            }
        });
    };

    const schedules = () => {
        let route_id = $("#search_route").val();
        let date     = $("#search_date").val();

        $.ajax({
            url: '../Controllers/ticketsController.php?op=schedules',
            type: 'GET',
            headers: {
                "Authorization": "Bearer " + token
            },
            dataType: 'json',
            data: { route_id: route_id, date: date },
            success: function(schedules) {
                console.log(schedules);
                let $select = $("#search_schedule");
                $select.empty().append('<option value="">Selecciona...</option>');
                schedules.forEach(s => {
                    $select.append(`<option value="${s.id}">${s.text}</option>`);
                });
            },
            error: function(e) {
                console.error("Error cargando horarios:", e.responseText);
            }
        });
    };

    const show = ( route_id ) => {
        $.ajax({
            url: "../Controllers/adminRoutesSchedulesController.php?op=show-route",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + token
            },
            dataType: "json",
            data: { route_id: route_id },
            success: function (response) {
                let data = response;
                
       
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

    const routes = () => {
        let search_date     = $("#search_date").val();
        let search_schedule = $("#search_schedule").val(); 
        let search_route    = $("#search_route").val();    

        $.ajax({
            url: "../Controllers/ticketsController.php?op=routes",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + token
            },
            dataType: "json",
            data: { 
                search_date: search_date, 
                search_schedule: search_schedule, 
                search_route: search_route  
            },
            success: function (response) {
                let data = response;
                console.log(data);
                let content = ``;

                if (data.length === 0) {
                    content = `
                        <div class="col-12 text-center">
                            <div class="alert alert-warning">
                                🚍 No se encontraron rutas para la fecha seleccionada
                            </div>
                        </div>`;
                } else {
                    data.forEach(item => {
                    
                        let boletos = 10; 
                        let boletosClass = "text-success"; 
                        if (boletos <= 3) {
                            boletosClass = "text-danger";
                        } else if (boletos <= 7) {
                            boletosClass = "text-warning";
                        }

                        content += `
                            <div class="col-md-4 col-sm-6 mb-4">
                                <div class="ticket-card shadow-sm h-100 selectable-card" 
                                    onclick="selected_route(this,'${item?.route_schedule_id}')">

                                    <div class="ticket-body d-flex flex-column justify-content-between">
                                        <div class="text-center mb-3">
                                            <h6 class="fw-bold text-uppercase text-primary mb-1">
                                                ${item?.origin}
                                            </h6>
                                            <span class="fs-3 fw-bold text-dark">→</span>
                                            <h6 class="fw-bold text-uppercase text-primary mt-1">
                                                ${item?.destination}
                                            </h6>
                                        </div>
                                        <div class="ticket-divider my-3"></div>
                                        <div class="px-2 text-center">
                                            <div class="detail-box mb-2">
                                                <i class="bi bi-cash-coin text-success me-1"></i>
                                                <span class="label">Costo</span>
                                                <span class="value">$${item?.cost}</span>
                                            </div>
                                            <div class="detail-box mb-2">
                                                <i class="bi bi-clock-history text-primary me-1"></i>
                                                <span class="label">Salida</span>
                                                <span class="value">${item?.leaving_time}</span>
                                            </div>
                                            <div class="detail-box">
                                                <i class="bi bi-ticket-detailed me-1 ${boletosClass}"></i>
                                                <span class="label">Boletos</span>
                                                <span class="value ${boletosClass}">${boletos}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        });

                    }
                    $("#routes").html(`<div class="row">${content}</div>`);
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

   const selected_route = ( card, route_schedule_id ) => {
        document.querySelectorAll('.selectable-card').forEach(c => c.classList.remove('active'));
        card.classList.add('active');
        console.log("Seleccionaste:", route_schedule_id);

        $.ajax({
            url: "../Controllers/ticketsController.php?op=details",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + token
            },
            dataType: "json",
            data: { 
                route_schedule_id: route_schedule_id,
            },
            success: function (response) {
                let data = response;
                console.log(data);
                costoUnitario = parseFloat(data.cost); 
                let content = `
                <div class="text-center mb-4">
                    <h4 class="fw-bold text-uppercase text-primary">${data?.origin}</h4>
                    <span class="fs-3 fw-bold text-dark">→</span>
                    <h4 class="fw-bold text-uppercase text-primary">${data?.destination}</h4>
                    <p class="text-muted mb-0"><i class="bi bi-calendar-event"></i> ${data.date} | 
                    <i class="bi bi-clock-history"></i> ${data?.leaving_time}</p>
                </div>

                <div class="ticket-divider my-3"></div>

                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="p-3 border rounded bg-light">
                            <i class="bi bi-cash-coin text-success fs-4"></i>
                            <p class="mb-1 fw-semibold">Costo</p>
                            <h5 class="mb-0">$${data?.cost}</h5>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="p-3 border rounded bg-light">
                            <i class="bi bi-bus-front text-info fs-4"></i>
                            <p class="mb-1 fw-semibold">Tipo</p>
                            <h5 class="mb-0">${data?.type}</h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 border rounded bg-light">
                            <i class="bi bi-car-front text-warning fs-4"></i>
                            <p class="mb-1 fw-semibold">Modelo</p>
                            <h5 class="mb-0">${data?.model}</h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 border rounded bg-light">
                            <i class="bi bi-person-circle text-danger fs-4"></i>
                            <p class="mb-1 fw-semibold">Chofer</p>
                            <h5 class="mb-0">${data?.name}</h5>
                        </div>
                    </div>
                </div>
                `;
                $("#detailsBody").html(content);
                $("#detailsCard").fadeIn();
                $("#route_schedule_id").val(data?.route_schedule_id);
                $("#route_id").val(data?.route_id);
                $("#vehicle_id").val(data?.vehicle_id);
                $("#employee_id").val(data?.employee_id);
                $("#cost").val(data?.cost);
                $("#quantity").val(1);
                $("#amount_received").val(0);
                total()
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
       
    }

    const total = () => {
        let cost = $("#cost").val();
        let quantity = $("#quantity").val();
        let amount_received = $("#amount_received").val();

        let total = parseFloat(cost) * parseFloat(quantity);
        let change = parseFloat(amount_received) - parseFloat(total);

        let total_label = document.querySelector("#total_label");
        let amount_received_label = document.querySelector("#amount_received_label");
        let change_label = document.querySelector("#change_label");

        total_label.innerText = `Total a pagar: $${total.toFixed(2)}`;
        amount_received_label.innerText = `Monto recibido: $${amount_received}`;
        change_label.innerText = `Cambio: $${change.toFixed(2)}`;
    }
    
    const clean = () => {   
        $("#brand").val('');
        $("#user_id").val('');
        $("#color").val('');
        $("#model").val('');
        $("#plate_number").val('');
        $("#serial_number").val('');
        $("#type").val('');
        $("#year").val('');         
    }
    
</script>