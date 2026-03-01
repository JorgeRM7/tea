<!doctype html>
<?php $title = "Paquetería"; ?>
<html lang="es" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr"
    data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template">
<?php require_once('header.php'); ?>
<style>
    .time-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:.75rem}
    .time-card{border:1px solid #e5e7eb;border-radius:12px;padding:.85rem;cursor:pointer;transition:.2s}
    .time-card:hover{border-color:#28c76f;box-shadow:0 0 0 2px rgba(40,199,111,.25)}
    .time-card.selected{border-color:#28c76f;background:#e8fff2}
    .time-hour{font-size:1.4rem;font-weight:700;margin-bottom:8px}
    .time-availability{display:inline-block;padding:4px 10px;border-radius:20px;font-weight:600;font-size:.85rem}
    .summary-line{display:flex;justify-content:space-between;font-size:.9rem;margin-bottom:.45rem;gap:.5rem;flex-wrap:wrap}
    .summary-line span:first-child{color:#8c8fa3;min-width:110px}
    .summary-line strong{font-weight:600;text-align:right;flex:1}
    @media (max-width: 768px){
        .time-grid{grid-template-columns:repeat(auto-fill,minmax(150px,1fr));}
        .summary-line span:first-child{min-width:90px}
    }
</style>
<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php require_once('menu.php'); ?>
            <div class="layout-page">
                <?php require_once('barra_navegacion.php'); ?>
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <form id="packageForm" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="card mb-4">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">Envío de paquetes</h5>
                                            <button class="btn btn-label-success" type="button" onclick="cleanForm()">
                                                <i class="ti ti-plus"></i> Limpiar
                                            </button>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3 mb-3">
                                                <div class="col-md-4">
                                                    <label class="form-label section-title">Ruta</label>
                                                    <select id="search_route" name="route_id" class="form-select form-select-lg" required>
                                                        <?php 
                                                            $branch_ids = [];
                                                            $user_id = $_SESSION['user_id'] ?? 1;
                                                            $sql_branch_offices_user = "
                                                                SELECT branch_office_id 
                                                                FROM branch_offices_user 
                                                                WHERE user_id = '$user_id' 
                                                                AND deleted_at IS NULL
                                                            ";
                                                            $resultado = ejecutarConsulta($sql_branch_offices_user);
                                                            while ($item = mysqli_fetch_array($resultado)) {
                                                                $branch_ids[] = $item['branch_office_id'];
                                                            }

                                                            if (!empty($branch_ids)) {
                                                                $branch_ids_str = implode(',', $branch_ids);
                                                                $sql = "
                                                                    SELECT * 
                                                                    FROM routes 
                                                                    WHERE deleted_at IS NULL 
                                                                    AND branch_office_id IN ($branch_ids_str)
                                                                ";
                                                                $query = ejecutarConsulta($sql);
                                                                while ($valores = mysqli_fetch_array($query)) {
                                                                    echo "<option value='".$valores['id']."'>".$valores['origin']." - ".$valores['destination']."</option>";
                                                                }
                                                            }
                                                        ?>
                                                    </select>
                                                    <div id="routeRules" class="rule-note mt-1"></div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label section-title">Destino</label>
                                                    <select id="routes_stop_id" name="routes_stop_id" class="form-select form-select-lg" onchange="cleanSelection()" required></select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label section-title">Fecha</label>
                                                    <input id="search_date" name="search_date" type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required />
                                                </div>
                                            </div>

                                            <h6 class="mb-2">Horarios disponibles</h6>
                                            <div id="times" class="col-md-12"></div>

                                            <hr class="my-4">
                                            <input type="hidden" id="vehicle_id" name="vehicle_id">
                                            <input type="hidden" id="employee_id" name="employee_id">
                                            <input type="hidden" id="route_stop_id" name="route_stop_id">
                                            <input type="hidden" id="route_schedule_id" name="route_schedule_id">

                                            
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Remitente</label>
                                                    <input type="text" class="form-control" id="sender_name" name="sender_name" placeholder="Nombre completo" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Teléfono remitente</label>
                                                    <input type="text" class="form-control" id="sender_phone" name="sender_phone" placeholder="10 dígitos" inputmode="numeric" pattern="[0-9]*" required>
                                                </div>                                               
                                                <div class="col-md-6">
                                                    <label class="form-label">Destinatario</label>
                                                    <input type="text" class="form-control" id="receiver_name" name="receiver_name" placeholder="Nombre completo" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Teléfono destinatario</label>
                                                    <input type="text" class="form-control" id="receiver_phone" name="receiver_phone" placeholder="10 dígitos" inputmode="numeric" pattern="[0-9]*" required>
                                              </div>
                                            </div>

                                            <hr class="my-4">
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label">Precio</label>
                                                    <input type="number" class="form-control" id="price" name="price" min="0" step="0.01" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Peso (kg)</label>
                                                    <input type="number" class="form-control" id="package_weight" name="package_weight" min="0" step="0.1">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Valor declarado</label>
                                                    <input type="number" class="form-control" id="declared_value" name="declared_value" min="0" step="0.01">
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label">Descripción del paquete</label>
                                                    <textarea class="form-control" id="description" name="description" rows="2" required></textarea>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label">Notas adicionales</label>
                                                    <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-end gap-2 mt-4">
                                                <button type="button" class="btn btn-outline-secondary" onclick="cleanForm()">
                                                    <i class="ti ti-eraser"></i> Limpiar
                                                </button>
                                                <button type="button" class="btn btn-success btn-lg" onclick="storePackage()">
                                                    <i class="ti ti-box"></i> Registrar paquete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Resumen</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="text-muted">Total</span>
                                                <h4 class="mb-0" id="summaryTotal">$0.00</h4>
                                            </div>
                                            <div class="mb-3">
                                                <div class="summary-line"><span>Horario</span><strong id="summaryTime">--:--</strong></div>
                                                <div class="summary-line"><span>Unidad</span><strong id="summaryVehicle">--</strong></div>
                                                <div class="summary-line"><span>Operador</span><strong id="summaryDriver">--</strong></div>
                                                <div class="summary-line"><span>Origen</span><strong id="summaryOrigin">--</strong></div>
                                                <div class="summary-line"><span>Destino</span><strong id="summaryDestination">--</strong></div>
                                                <div class="summary-line"><span>Remitente</span><strong id="summarySender">--</strong></div>
                                                <div class="summary-line"><span>Destinatario</span><strong id="summaryReceiver">--</strong></div>
                                            </div>
                                            <div class="border-dashed rounded p-3 text-center" id="trackingPreview" style="display:none">
                                                <p class="fw-semibold mb-1">Código de seguimiento</p>
                                                <h3 id="tracking_code"></h3>
                                                <div id="qr_container"></div>
                                                <p class="mt-2 mb-0">Pin: <strong id="tracking_pin"></strong></p>
                                                <small class="text-muted">Comparte este código con el cliente</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php require_once('footer.php'); ?>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
        <div class="drag-target"></div>
    </div>

    <script>
        const ROUTES_ENDPOINT = "../Controllers/packageDeliveriesController.php";
        $(document).ready(function() {
            $("#search_route, #routes_stop_id").select2({ width: "100%" });

            $("#search_route").on("change", function() {
                show_subpaths();
                routes();
            });

            $("#search_date").on("change", function() {
                routes();
            });

            show_subpaths();
            routes();
            updateTotal();
        });

        const routes = () => {
            let search_date = $("#search_date").val();
            let search_route = $("#search_route").val();

            $.ajax({
                url: `${ROUTES_ENDPOINT}?op=routes_by_schedule`,
                type: "POST",
                headers: { "Authorization": "Bearer " + token },
                dataType: "json",
                data: { 
                    search_date: search_date, 
                    search_route: search_route  
                },
                success: function (response) {
                    renderSchedules(response);
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

        const renderSchedules = (data) => {
            let content = ``;

            if (!data || data.length === 0) {
                content = `
                    <div class="col-12 text-center">
                        <div class="alert alert-warning">
                            No se encontraron rutas para la fecha seleccionada
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
                                <!-- <div class="time-availability ${badgeClass}">${disponibilidad} libres</div>-->
                            </div>
                        </div>
                    `;

                });
            }   
            $("#times").html(`<div class="row">${content}</div>`);
        };

        const selected_route = (route_schedule_id) => {
            let route_stop_id = $("#routes_stop_id").val(); 
            $(".time-card").removeClass("selected");
            $(`.time-card[data-id="${route_schedule_id}"]`).addClass("selected");
            $.ajax({
                url: `${ROUTES_ENDPOINT}?op=details`,
                type: "POST",
                headers: { "Authorization": "Bearer " + token },
                dataType: "json",
                data: { 
                    route_schedule_id: route_schedule_id,
                    route_stop_id : route_stop_id
                },
                success: function (response) {
                    let data = response;
                    console.log(data);
                    $("#vehicle_id").val(data?.vehicle_id);
                    $("#employee_id").val(data?.employee_id);
                    $("#route_stop_id").val(data?.routes_stop_id || '');
                    $("#route_schedule_id").val(data?.route_schedule_id || route_schedule_id);
                    $("#price").val(data?.price || 0);
                    $("#summaryVehicle").text(data?.vehicle_id || '--');
                    $("#summaryDriver").text(data?.name || '--');
                    $("#summaryOrigin").text(data?.origin || '--');
                    $("#summaryDestination").text(data?.destination || '--');
                    $("#summaryTime").text(data?.leaving_time?.substring(0,5) || '--:--');
                    updateTotal();
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

        const show_subpaths = () => { 
            let route_id = $("#search_route").val(); 
            $.ajax({
                url: `${ROUTES_ENDPOINT}?op=show-subpaths`,
                type: "POST",
                headers: { "Authorization": "Bearer " + token },
                data: { route_id: route_id },
                dataType: "json",
                success: function (response) {
                    let data = response;
                    let $select = $("#routes_stop_id");
                    $select.empty();
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

        $("#sender_name, #receiver_name").on("input", function(){
            $("#summarySender").text($("#sender_name").val() || '--');
            $("#summaryReceiver").text($("#receiver_name").val() || '--');
        });

        const updateTotal = () => {
            const price = parseFloat($("#price").val()) || 0;
            $("#summaryTotal").text(`$${price.toFixed(2)}`);
        };
        
        const storePackage = () => {

            const error = validatePackage();

            if (error) {
                Swal.fire({
                    icon: "warning",
                    title: "Validación",
                    text: error
                });
                return;
            }

            const formData = new FormData(document.getElementById("packageForm"));
            formData.append('quantity', 1);

            $.ajax({
                url: `${ROUTES_ENDPOINT}?op=store`,
                type: "POST",
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
                            text: 'Paquete registrado exitosamente.',
                        });
                        console.log('Package ID:', response.id);
                        openPackagePdf(response.id);
                        cleanForm();
                        showTracking(response);
                        return;
                    }

                    Swal.fire({
                        title: 'Error',
                        text: response.message || 'No se pudo guardar el registro.',
                        icon: 'error'
                    });
                }
            });
        };

        const showTracking = (response) => {
            $('#tracking_code').text(response.tracking_code);
            $('#tracking_pin').text(response.tracking_pin);
            $('#trackingPreview').show();
            const container = document.getElementById("qr_container");
            container.innerHTML = '';
            new QRCode(container, {
                text: `https://tea.digitalenigma.mx/Views/packages-tracking.php?code=${response.tracking_code}&pin=${response.tracking_pin}`,
                width: 120,
                height: 120
            });
        };

        const openPackagePdf = (packageId) => {
            if (!packageId) return;
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = `../Pdf/package_delivery.php?package_id=${packageId}`;
            iframe.onload = function () {
                try {
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                } catch (error) {
                    console.error('No se pudo iniciar la impresión del PDF', error);
                } finally {
                    setTimeout(() => iframe.remove(), 4000);
                }
            };
            document.body.appendChild(iframe);
        };

        const cleanForm = () => {
            document.getElementById('packageForm').reset();
            updateTotal();
            $('#trackingPreview').hide();
            $('#qr_container').empty();
            $(".time-card").removeClass("selected");
            show_subpaths();
            routes();
            $("#summaryVehicle, #summaryDriver, #summaryOrigin, #summaryDestination").text('--');
            $("#summaryTime").text('--:--');
            $("#summarySender, #summaryReceiver").text('--');
        };

        const cleanSelection = () => {
            $(".time-card").removeClass("selected");
            $("#route_schedule_id").val('');
            $("#vehicle_label, #employee_label, #branch_origin, #branch_destination").val('');
            $("#vehicle_id, #employee_id, #branch_origin_id, #branch_destination_id").val('');
        };

        const validatePackage = () => {

            const route = $("#search_route").val();
            const stop = $("#routes_stop_id").val();
            const date = $("#search_date").val();
            const schedule = $("#route_schedule_id").val();

            const sender = $("#sender_name").val().trim();
            const senderPhone = $("#sender_phone").val().trim();

            const receiver = $("#receiver_name").val().trim();
            const receiverPhone = $("#receiver_phone").val().trim();

            const price = parseFloat($("#price").val());
            const description = $("#description").val().trim();

            if (!route) return "Selecciona una ruta";
            if (!stop) return "Selecciona un destino";
            if (!date) return "Selecciona una fecha";
            if (!schedule) return "Selecciona un horario";

            if (sender.length < 10) return "El remitente debe tener mínimo 10 caracteres";
            if (!/^\d{10}$/.test(senderPhone)) return "Teléfono remitente debe ser numérico de 10 dígitos";

            if (receiver.length < 10) return "El destinatario debe tener mínimo 10 caracteres";
            if (!/^\d{10}$/.test(receiverPhone)) return "Teléfono destinatario debe ser numérico de 10 dígitos";

            if (!price || price <= 0) return "El precio debe ser mayor a 0";
            if (description.length < 10) return "La descripción debe tener mínimo 10 caracteres";

            return null;
        };
    </script>
</body>
</html>

