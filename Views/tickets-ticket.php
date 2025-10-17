<!doctype html>
<?php ;$title = "Taqilla"; ?>
<html lang="es" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr"
    data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template">
<!--HEADER-->
<?php require_once('header.php'); ?>
<!--HEADER-->

<style>
    :root{--naranja:#f07d42;--verde:#28c76f;--rojo:#ef4444;--amarillo:#f59e0b;--azul:#1f2a44;}
    /* body{background:linear-gradient(180deg,#f8fafc 0%,#eef2f6 100%)} */
    .card-main{border:0;border-radius:18px;box-shadow:0 10px 30px rgba(2,8,20,.05)}
    .time-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:.75rem}
    .time-card{border:1px solid #e5e7eb;border-radius:12px;padding:.85rem;cursor:pointer;transition:.2s}
    .time-card:hover{border-color:var(--naranja);box-shadow:0 0 0 2px rgba(240,125,66,.15)}
    .time-card.disabled{opacity:.55;pointer-events:none}
    .time-card.active{border-color:var(--verde);background:#ecfdf3}
    .badge-seats{border:1px solid #e5e7eb}
    .badge-green{color:#065f46;border-color:#a7f3d0}
    .badge-yellow{background:#fff7ed;color:#92400e;border-color:#fed7aa}
    .badge-red{background:#fee2e2;color:#991b1b;border-color:#fecaca}
    .stub{border:1px dashed #cbd5e1;border-radius:12px;padding:.75rem;color:#64748b}
    .section-title{font-weight:700}
    .kpi{border:1px solid #e5e7eb;border-radius:16px;padding:.75rem 1rem}
    .rule-note{font-size:.85rem;color:#6b7280}

    .time-card {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 18px 12px;
        text-align: center;
        cursor: pointer;
        transition: all 0.25s ease;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .time-card:hover {
        border-color: #28c76f;
        transform: translateY(-4px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }
    .time-hour {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 8px;
    }
    .time-availability {
        font-size: 0.85rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-block;
    }
    .time-availability.bg-success {
        background: #d1fae5;
        color: #065f46;
    }
    .time-availability.bg-warning {
        background: #fef9c3;
        color: #92400e;
    }
    .time-availability.bg-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .time-card.selected {
        border-color: #28c76f;
        box-shadow: 0 0 8px rgba(40,199,111,0.4);
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
                        <form name="formulario" id="formulario" method="POST">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="card mb-4">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">Venta de boletos</h5>
                                            <small class="text-muted float-end">TEA</small>
                                        </div>
                                        <div class="card-body">

                                            <div class="row g-3 mb-3">
                                                <div class="col-md-4">
                                                    <label class="form-label section-title">Ruta</label>
                                                    <select id="search_route" name="search_route" class="form-select form-select-lg">
                                                        <?php 
                                                            $sql = "SELECT * FROM `routes` WHERE deleted_at is null";
                                                            $query = ejecutarConsulta($sql);
                                                            while($valores = mysqli_fetch_array($query)){
                                                                echo "<option value='".$valores['id']."'>".$valores['origin']." - ".$valores['destination']."</option>";
                                                            }
                                                        ?>

                                                    </select>
                                                    <div id="routeRules" class="rule-note mt-1"></div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label section-title">Destino</label>
                                                    <select id="routes_stop_id" name="routes_stop_id" class="form-select form-select-lg" onchange="clean()"></select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label section-title">Fecha</label>
                                                    <input id="search_date" name="search_date" type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>"/>
                                                </div>
                                            </div>
                                            <h6 class="mb-2">Horarios disponibles</h6>
                                            <div id="times" class="col-md-12"></div>
                                            
                                            <hr class="my-4">
                                            <div class="row g-3 align-items-end">
                                                
                                                <div class="col-md-3">
                                                    <label class="form-label section-title">Cantidad</label>
                                                    <input id="quantity" name="quantity" type="number" class="form-control" min="1" max="5" value="1" />
                                                </div>
                                            
                                            </div>
                                            <hr class="my-4">
                                            <div class="row g-3 mb-2">
                                                <div class="col-md-4">
                                                    <label class="form-label section-title">Precio base</label>
                                                    <input id="price" name="price" type="number" class="form-control" readonly>
                                                </div>
                                                <div class="col-md-8">
                                                    <h6>Descuentos</h6>
                                                    <div class="row" id="discounts"></div>
                                                </div>
                                            </div>
                                            <div class="row g-3 align-items-end">
                                                <div class="col-md-4">
                                                    <label class="form-label section-title">Total</label>
                                                    <input id="total" name="total" type="number" class="form-control" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label section-title">Recibido</label>
                                                    <input id="amount_received" name="amount_received" type="number" class="form-control">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label section-title">Cambio</label>
                                                    <input id="change_amount" name="change_amount" type="number" class="form-control" readonly>
                                                    <input id="employee_id" name="employee_id" type="hidden" class="form-control" readonly>
                                                    <input id="route_schedule_id" name="route_schedule_id" type="hidden" class="form-control" readonly>
                                                    <input id="route_id" name="route_id" type="hidden" class="form-control" readonly>
                                                    <input id="vehicle_id" name="vehicle_id" type="hidden" class="form-control" readonly>
                                                    <input id="branch_office_id" name="branch_office_id" type="hidden" class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="row g-3 mt-2">
                                                <div class="col-md-12 text-end">
                                                    <button id="btnClear" class="btn btn-outline-secondary" type="button" onclick="clean()">
                                                        <i class="bi bi-x-circle"></i> Limpiar
                                                    </button>
                                                    <button id="btnGenerate" class="btn btn-success btn-lg" type="button" onclick="store()"> 
                                                        <i class="bi bi-receipt"></i> Generar boleto
                                                    </button>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card kpi mb-3 d-flex justify-content-between">
                                        <div>
                                            <div class="small text-muted">Taquillero</div>
                                            <div class="fw-bold" id="sessionAgent"><?= $_SESSION['name'] ?></div>
                                        </div>
                                        <div >
                                            <div class="small text-muted">Boletos hoy</div>
                                            <div class="fw-bold" id="kpiCount">1000</div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <i class="bi bi-ticket-perforated"></i> Vista previa
                                        </div>
                                        <div class="card-body">
                                            <div class="row mt-2">
                                                <div class="col-6">
                                                    <small>Origen</small>
                                                    <div id="pvOrigin">—</div>
                                                </div>
                                                <div class="col-6">
                                                    <small>Destino</small>
                                                    <div id="pvDestination">—</div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-6">
                                                    <small>Fecha</small>
                                                    <div id="pvDate">—</div>
                                                </div>
                                                <div class="col-6"><small>Hora</small><div id="pvTime">—</div></div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-6">
                                                    <small>Unidad</small>
                                                    <div id="pvUnit">—</div>
                                                </div>
                                                <div class="col-6">
                                                    <small>Chofer</small>
                                                    <div id="pvDriver">—</div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row small">
                                                <div class="col-6">Cantidad<br>
                                                    <span id="pvQty">—</span>
                                                </div>
                                                <div class="col-6">Total<br>
                                                    <span id="pvTotal">—</span>
                                                </div>
                                            </div>
                                            <!-- <div class="mt-2 small">Descuento: 
                                                <span id="pvDisc">—</span>
                                            </div> -->
                                            <div class="mt-2 small">Pago: 
                                                <span id="pvPay">Efectivo</span>
                                            </div>
                                            <div class="mt-3 stub" id="qrStub">QR/Folio se generará al emitir.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
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
        $("#routes_stop_id").select2({ width:"100%"});

        $("#search_date, #search_schedule, #search_route").on("change keyup", function() {
            routes();
        });

        $("#search_date").on("change", function() {
            discounts();
        });

        $("#search_route").on("change", function() {
            show_subpaths();
        });

        $("#quantity, #amount_received").on("change keyup", function() {
            total();
        });
        let branch_office_id = document.getElementById('branch_office_id_selected').value;
        $("#branch_office_id").val(branch_office_id);
        routes();
        tickets_today();
        show_subpaths();
        discounts();
    });
   
    const store = () => {
        let route_schedule_id = $("#route_schedule_id").val();

        if( route_schedule_id == '' ){
            Swal.fire({
                title: "Ups...",
                text: "Debes seleccionar un horario.",
                icon: "warning"
            });
            return;
        }

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
            dataType: "json",
            success: function(response) {
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
                    let tickets_id = response.ids;
                    let url = `../Pdf/ticket.php?tickets_id=${tickets_id.join(",")}`;
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
                    
                    clean();
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

                let container = $("#times");
                container.empty();

                if (schedules.length === 0) {
                    container.html("<span class='text-muted'>No hay horarios disponibles</span>");
                    return;
                }

                schedules.forEach(item => {
                    let badge = `
                        <span class="badge bg-primary m-1 schedule-badge" 
                            data-id="${item.route_schedule_id}" 
                            style="cursor:pointer;">
                            <i class="bi bi-clock"></i> ${item.leaving_time}
                        </span>
                    `;
                    container.append(badge);
                });

                $(".schedule-badge").off("click").on("click", function() {
                    $(".schedule-badge").removeClass("bg-success").addClass("bg-primary");
                    $(this).removeClass("bg-primary").addClass("bg-success");
                    let selectedId = $(this).data("id");
                    $("#route_schedule_id").val(selectedId);
                });
                
            },
            error: function(e) {
                console.error("Error cargando horarios:", e.responseText);
            }
        });
    };

    const show_subpaths = () => { 
        let route_id = $("#search_route").val(); 
        $.ajax({
            url: "../Controllers/ticketsController.php?op=show-subpaths",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + token
            },
            data: { route_id: route_id },
            dataType: "json",
            success: function (response) {
                let data = response;
                let $select = $("#routes_stop_id");
                $select.empty();
                // $select.append('<option value="">Selecciona destino...</option>');
                data.forEach(item => {
                    $select.append(
                        `<option value="${item.routes_stop_id}">
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

    const tickets_today = () => {  
        $.ajax({
            url: "../Controllers/ticketsController.php?op=tickets-today",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + token
            },
            dataType: "json",
            success: function (response) {
                let data = response;
                $("#kpiCount").text(data?.tickets_today ?? 0);
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
                        let disponibilidad = parseInt(item.vehicle_capacity) - parseInt(item.tickets_sale);

                        let badgeClass = "bg-success text-white";
                        if (disponibilidad === 0) badgeClass = "bg-danger text-white";
                        else if (disponibilidad < 10) badgeClass = "bg-warning text-dark";

                        content += `
                            <div class="col-6 col-md-3 mb-3">
                                <div class="time-card" data-id="${item.route_schedule_id}" onclick="selected_route(${item.route_schedule_id})">
                                    <div class="time-hour">${item.leaving_time.substring(0,5)}</div>
                                    <div class="time-availability ${badgeClass}">${disponibilidad} libres</div>
                                </div>
                            </div>
                        `;

                    });
                }   
                $("#times").html(`<div class="row">${content}</div>`);
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

    const selected_route = ( route_schedule_id ) => {
        let route_stop_id = $("#routes_stop_id").val(); 
        
        $(".time-card").removeClass("selected");
        $(`.time-card[data-id="${route_schedule_id}"]`).addClass("selected");
        $.ajax({
            url: "../Controllers/ticketsController.php?op=details",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + token
            },
            dataType: "json",
            data: { 
                route_schedule_id: route_schedule_id,
                route_stop_id : route_stop_id
            },
            success: function (response) {
                let data = response;
                let route = `${data?.origin} ➝ ${data?.destination}`;
                $("#pvDate").text(data?.date);
                $("#pvDriver").text(data?.name);
                $("#pvOrigin").text(data?.origin);
                $("#pvDestination").text(data?.destination);
                $("#pvTime").text(data?.leaving_time);
                $("#pvUnit").text(data?.vehicle_id);
                $("#price").val(data?.price);
                $("#route_id").val(data?.route_id);
                $("#route_schedule_id").val(data?.route_schedule_id);
                $("#vehicle_id").val(data?.vehicle_id);
                $("#employee_id").val(data?.employee_id);
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
        let price = parseFloat($("#price").val()) || 0;
        let quantity = parseFloat($("#quantity").val()) || 0;
        let amount_received = parseFloat($("#amount_received").val()) || 0;

        let total = price * quantity;
        let change = amount_received - total;

        if (change < 0) change = 0;

        $("#pvQty").text(quantity);
        $("#total").val(total);
        $("#change_amount").val(change);
        $("#pvTotal").text(total);
        
    };

    const clean = () => {  
        routes(); 
        $("#pvDate").text('-');
        $("#pvDriver").text('-');
        $("#pvRoute").text('-');
        $("#pvTime").text('-');
        $("#pvUnit").text('-');
        $("#price").val('');
        $("#route_id").val('');
        $("#route_schedule_id").val('');
        $("#vehicle_id").val('');
        $("#employee_id").val('');  
        $("#pvQty").text('-');
        $("#total").val(0);
        $("#change_amount").val(0);
        $("#pvTotal").text(0);     
    }
    
    const discounts = () => {
        let search_date = $("#search_date").val(); 
        $.ajax({
            url: "../Controllers/ticketsController.php?op=discounts",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + token
            },
            data: { search_date: search_date },
            dataType: "json",
            success: function (response) {
                let data = response;
                $("#discounts").empty();
                if (data.length === 0) {
                    $("#discounts").html("<span class='text-muted'>No hay descuentos disponibles</span>");
                    return;
                }

                data.forEach(item => {
                    let content = `
                    <div class="col-md-6 col-sm-6">
                        <label class="switch switch-primary">
                            <input type="radio" 
                                class="switch-input discount-radio" 
                                name="discount" 
                                id="discount_${item.id}" 
                                value="${item.id}"
                                onchange="discount_selected()"/>
                            <span class="switch-toggle-slider">
                                <span class="switch-on"><i class="ti ti-check"></i></span>
                                <span class="switch-off"><i class="ti ti-x"></i></span>
                            </span>
                            <span class="switch-label">${item?.name}</span>
                        </label>
                    </div>`;
                    $("#discounts").append(content);
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
    }

    const discount_selected = () => {
        let activo = $("input.discount-radio:checked").val();
    }
    

    document.addEventListener("keydown", function(e) {
        if (e.key === "Enter") {
            store();
        }
    });

    setInterval(tickets_today, 60000);
</script>