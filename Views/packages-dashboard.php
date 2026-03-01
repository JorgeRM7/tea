<!doctype html>
<?php $title = "Tracking Paquetes"; ?>
<html lang="es" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr"
    data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template">
<?php require_once('header.php'); ?>
<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php require_once('menu.php'); ?>
            <div class="layout-page">
                <?php require_once('barra_navegacion.php'); ?>
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row g-4">
                            <div class="col-xl-3 col-sm-6">
                                <div class="card card-border-shadow-success">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2"><span class="avatar-initial rounded bg-label-success"><i class="ti ti-route"></i></span></div>
                                            <div>
                                                <h4 class="mb-1" id="countOrigin">0</h4>
                                                <small class="text-muted">Enviados desde mi sucursal</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <div class="card card-border-shadow-primary">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2"><span class="avatar-initial rounded bg-label-primary"><i class="ti ti-building"></i></span></div>
                                            <div>
                                                <h4 class="mb-1" id="countDestination">0</h4>
                                                <small class="text-muted">Llegando a mi sucursal</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-4">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="mb-0">Paquetes</h5>
                                <div class="d-flex gap-2">

                                    <?php
                                        $date = date("Y-m-d");
                                    ?>
                                    
                                    <input type="date" id="date" name="date" class="form-control" value="<?php echo $date; ?>">
                                    <input type="date" id="date_filter_end" name="date_filter_end" class="form-control" value="<?php echo $date; ?>">


                                    <select id="statusFilter" class="form-select">
                                        <option value="">Todos</option>
                                        <option value="CREADO">Creado</option>
                                        <option value="EN_TRANSITO">En tránsito</option>
                                        <option value="EN_DESTINO">En destino</option>
                                        <option value="ENTREGADO">Entregado</option>
                                        <option value="INCIDENCIA">Incidencia</option>
                                        <option value="CANCELADO">Cancelado</option>
                                    </select>
                                    <button class="btn btn-label-primary" onclick="indexPackages()">
                                        <i class="ti ti-refresh"></i>
                                    </button>
                                    <button class="crear btn btn-success me-2" onclick="showXLS()">
                                        <i class="ti ti-file-spreadsheet"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-origin" type="button" role="tab" aria-selected="true" onclick="setView('origin')">Salidas</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-destination" type="button" role="tab" aria-selected="false" onclick="setView('destination')">Entradas</button>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="tab-origin" role="tabpanel">
                                        <div class="table-responsive">
                                            <table class="table" id="packagesTable">
                                                <thead>
                                                    <tr>
                                                        <th>Acciones</th>
                                                        <th>#</th>
                                                        <th>Ruta</th>
                                                        <th>Precio</th>
                                                        <th>Estatus</th>
                                                        <th>Operador</th>
                                                        <th>Unidad</th>
                                                        <th>Tracking</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="tab-destination" role="tabpanel">
                                        <div class="table-responsive">
                                            <table class="table" id="packagesTableDestination">
                                                <thead>
                                                    <tr>
                                                        <th>Acciones</th>
                                                        <th>#</th>
                                                        <th>Ruta</th>
                                                        <th>Precio</th>
                                                        <th>Estatus</th>
                                                        <th>Operador</th>
                                                        <th>Unidad</th>
                                                        <th>Tracking</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php require_once('footer.php'); ?>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
        <div class="drag-target"></div>
    </div>

    <div class="modal fade" id="packageModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalle del paquete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="packageDetail"></div>
                    <hr>
                    <h6>Timeline</h6>
                    <ul class="timeline" id="timeline"></ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!--Inicio Modal Crear-->
    <div class="modal animate__animated animate__flipInX" id="modal_create" aria-labelledby="flipInXAnimationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Actualizar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form name="formulario" id="formulario" method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="nameWithTitle" class="form-label">Nombre remitente</label>
                                <input type="text" id="sender_name" name="sender_name" class="form-control" placeholder="Ingresa..." required/>
                                <input type="hidden" id="package_id" name="package_id" class="form-control"/>
                            </div>
                            <div class="col-md-6">
                                <label for="nameWithTitle" class="form-label">Telefono remitente</label>
                                <input type="text" id="sender_phone" name="sender_phone" class="form-control" placeholder="Ingresa..." required/>
                            </div>
                            <div class="col-md-6">
                                <label for="nameWithTitle" class="form-label">Nombre receptor</label>
                                <input type="text" id="receiver_name" name="receiver_name" class="form-control" placeholder="Ingresa..." required/>
                            </div>
                             <div class="col-md-6">
                                <label for="nameWithTitle" class="form-label">Telefono receptor</label>
                                <input type="text" id="receiver_phone" name="receiver_phone" class="form-control" placeholder="Ingresa..." required/>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="crear btn btn-primary me-2" onclick="store()">
                        <i class="ti ti-device-floppy"></i> Guardar
                    </button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="clean()">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Modal Crear-->
    <!-- Modal XLS -->
    <div class="modal animate__animated animate__flipInX" id="modalXLS" aria-labelledby="flipInXAnimationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Generar Reporte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="nameWithTitle" class="form-label">Desde</label>
                            <input type="date" id="fecha_inicial" name="fecha_inicial" class="form-control" placeholder="Ingresa..." required/>
                        </div>
                        <div class="col-md-6">
                            <label for="nameWithTitle" class="form-label">Hasta</label>
                            <input type="date" id="fecha_final" name="fecha_final" class="form-control" placeholder="Ingresa..." />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="exportCSV()">Generar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal XLS -->

    <script>
        let currentView = 'origin';
        const endpoint = '../Controllers/packageDeliveriesController.php';

        $(document).ready(function() {
            initTables();
            indexPackages();
        });

        const initTables = () => {
            window.tableOrigin = $('#packagesTable').DataTable({
                language: { url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json" }
            });
            window.tableDest = $('#packagesTableDestination').DataTable({
                language: { url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json" }
            });
        };

        const setView = (view) => {
            currentView = view;
            indexPackages();
        };

        const indexPackages = () => {
            const branch = document.getElementById('branch_office_id_selected').value; 
            const date_filter_end = document.getElementById('date_filter_end').value; 
            const date = document.getElementById('date').value;            
            const status = $('#statusFilter').val();
            $.getJSON(`${endpoint}?op=index&branch_office_id=${branch}&view=${currentView}&status=${status}&date=${date}&date_filter_end=${date_filter_end}`, function(data) {
                const rows = data.aaData || [];
                  if (currentView === 'origin') {
                    tableOrigin.clear().rows.add(rows).draw();
                    $('#countOrigin').text(rows.length);
                } else {
                    tableDest.clear().rows.add(rows).draw();
                    $('#countDestination').text(rows.length);
                }
            });
        };

        const showPackage = (id) => {
            $.post(`${endpoint}?op=show`, { package_id: id }, function(data) {
                $('#packageDetail').html(`
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Tracking:</strong> ${data.tracking_code}</p>
                            <p><strong>Estatus:</strong> ${data.status}</p>
                            <p><strong>Ruta:</strong> ${data.stop_origin} → ${data.stop_destination}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Unidad:</strong> ${data.unidad_number || 'N/A'}</p>
                            <p><strong>Operador:</strong> ${data.driver_name || 'N/A'}</p>
                            <p><strong>Peso:</strong> ${data.package_weight || 0} kg</p>
                        </div>
                    </div>
                `);
                loadTimeline(id);
                new bootstrap.Modal(document.getElementById('packageModal')).show();
            }, 'json');
        };

        const loadTimeline = (id) => {
            $.getJSON(`${endpoint}?op=timeline&package_id=${id}`, function(events) {
                const list = $('#timeline');
                list.empty();
                events.forEach(event => {
                    list.append(`<li><strong>${event.status}</strong> - ${event.created_at}<br><small>${event.notes || ''}</small></li>`);
                });
            });
        };

        const deleteItem = (package_id) => {

            Swal.fire({
                title: "Cancelar boleto",
                html: `¿Estás seguro(a) de cancelar el boleto con folio: <b>${package_id}</b>?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí"
            }).then((result) => {

                if (!result.isConfirmed) return;

                Swal.fire({
                    title: "Autorización requerida",
                    html: `
                        <input 
                            type="password"
                            name="auth_code"
                            class="swal2-input"
                            placeholder="Contraseña..."
                            autocomplete="off"
                        >
                        <textarea
                            name="cancel_comment"
                            class="swal2-textarea"
                            placeholder="Motivo de cancelación..."
                            style="resize:none"
                        ></textarea>
                        <p class="text-muted mt-2" style="font-size:13px;">
                            Contraseña requerida para autorizar la cancelación
                        </p>
                    `,
                    focusConfirm: false,
                    showCancelButton: true,
                    confirmButtonText: "Validar",
                    preConfirm: () => {
                        const password = document.querySelector("input[name='auth_code']").value;
                        const comment  = document.querySelector("textarea[name='cancel_comment']").value;

                        if (!password) {
                            Swal.showValidationMessage("Ingresa la contraseña");
                            return false;
                        }

                        if (!comment.trim()) {
                            Swal.showValidationMessage("Ingresa el motivo de cancelación");
                            return false;
                        }

                        return { password, comment };
                    }
                }).then((passResult) => {

                    if (!passResult.isConfirmed) return;

                    const PASSWORD_CORRECTA = "TEA2025";

                    if (passResult.value.password !== PASSWORD_CORRECTA) {
                        Swal.fire("Acceso denegado", "Contraseña incorrecta.", "error");
                        return;
                    }

                    $.ajax({
                        url: `${endpoint}?op=deleteItem`,
                        type: "POST",
                        headers: { "Authorization": "Bearer " + token },
                        data: {
                            package_id,
                            comment: passResult.value.comment
                        },
                        success: () => {
                            Swal.fire({
                                toast: true,
                                icon: "success",
                                title: "Boleto cancelado",
                                position: "top-end",
                                showConfirmButton: false,
                                timer: 2500
                            });
                            indexPackages();
                        },
                        error: () => {
                            Swal.fire("Error", "No se pudo cancelar el boleto.", "error");
                        }
                    });

                });
            });
        };

        const show = ( package_id ) => {
            $('#modal_create').modal('show');
            $.ajax({
                url: `${endpoint}?op=show`,
                type: "POST",
                headers: {
                    "Authorization": "Bearer " + token
                },
                dataType: "json",
                data: { package_id: package_id },
                success: function (response) {
                    let data = response;
                    $("#package_id").val(data?.id);
                    $("#receiver_name").val(data?.receiver_name);
                    $("#receiver_phone").val(data?.receiver_phone);
                    $("#sender_name").val(data?.sender_name);
                    $("#sender_phone").val(data?.sender_phone);
                    
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

        const store = () => {
            let receiver_name = $("#receiver_name").val().trim();
            let receiver_phone = $("#receiver_phone").val().trim();
            let sender_name = $("#sender_name").val().trim();
            let sender_phone = $("#sender_phone").val().trim();

            if (!receiver_name) {
                Swal.fire({
                    icon: "warning",
                    title: "Campo requerido",
                    text: "El campo nombre receptor es obligatorio."
                });
                return;
            }

            if (!receiver_phone) {
                Swal.fire({
                    icon: "warning",
                    title: "Campo requerido",
                    text: "El campo telefono receptor es obligatorio."
                });
                return;
            }

             if (!sender_name) {
                Swal.fire({
                    icon: "warning",
                    title: "Campo requerido",
                    text: "El campo nombre remitente es obligatorio."
                });
                return;
            }
             if (!sender_phone) {
                Swal.fire({
                    icon: "warning",
                    title: "Campo requerido",
                    text: "El campo telefono remitente es obligatorio."
                });
                return;
            }


            const formData = new FormData(document.getElementById("formulario"));
            $.ajax({
                url: `${endpoint}?op=update`,
                type: "POST",
                headers: {
                    "Authorization": "Bearer " + token
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Registro actualizado exitosamente.',
                    });
                    $('#modal_create').modal('hide');
                    // clean();
                    indexPackages();
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

        const clean = () => {
            $("#package_id").val('');
            $("#receiver_name").val('');
            $("#receiver_phone").val('');
            $("#sender_name").val('');
            $("#sender_phone").val('');
        }

        const showXLS = () => {
            $('#modalXLS').modal('show');
        };


        const  exportCSV = () => {
            const date_start = document.getElementById("fecha_inicial").value;
            const date_end = document.getElementById("fecha_final").value;
            let branch_office_id = document.getElementById('branch_office_id_selected').value;
            $('#modalXLS').modal('hide');
            Swal.fire({
                title: 'Exportando...',
                text: 'Por favor, espera mientras se genera el archivo Excel.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        
            $.ajax({
                url:`${endpoint}?op=xls`,
                type: "POST",
                dataType: "json",
                headers: {
                    Authorization: `Bearer ${token}`,
                },
                data: { date_start: date_start, date_end:date_end, branch_office_id:branch_office_id },
                success: function (response) {
                    const formattedData = response.map(item => ({
                        "Descripcion" : item.description,
                        "Horario" : item.leaving_time,
                        "Origen": item.origin,
                        "Destino": item.destination,
                        "Remitente" : item.sender_name,
                        "Receptor" : item.receiver_name,
                        "Chofer": item.employee,
                        "Unidad": item.unidad_number,
                        "Precio": item.price,   
                        "Estatus" : item.status,
                        "Valor declarado" : item.declared_value,
                        "Fecha" : item.date
                        
                    }));
                    const worksheet = XLSX.utils.json_to_sheet(formattedData);
                    const workbook  = XLSX.utils.book_new();

                    XLSX.utils.book_append_sheet(workbook, worksheet, "Boletos por sucursal");
                    XLSX.writeFile(workbook, `reporte_paquetes_${branch_office_id}.xlsx`);

                    Swal.fire({
                        icon: "success",
                        title: "Exportación completada",
                        text: "El archivo Excel ha sido generado con éxito.",
                        confirmButtonColor: "#28c76f"
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
    </script>
</body>
</html>
