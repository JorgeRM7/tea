<!doctype html>
<?php ;$title = "Boletos en linea"; ?>
<html lang="es" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr"
    data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template">
<!--HEADER-->
<?php require_once('header.php'); ?>
<!--HEADER-->
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
                            <div class="col-sm-6 col-lg-6 mt-2">
                                <div class="card card-border-shadow-success">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-2 pb-1">
                                            <div class="avatar me-2">
                                                <span class="avatar-initial rounded bg-label-success">
                                                    <i class="ti ti-ticket ti-md"></i>
                                                </span>
                                            </div>
                                            <h4 class="ms-1 mb-0" id="totalVendidos">0</h4>
                                        </div>
                                        <p class="mb-1">Boletos Vendidos En Linea</p>  
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="col-sm-3 col-lg-3 mt-2">
                                <div class="card card-border-shadow-danger">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-2 pb-1">
                                            <div class="avatar me-2">
                                                <span class="avatar-initial rounded bg-label-danger">
                                                    <i class="ti ti-ticket-off ti-md"></i>
                                                </span>
                                            </div>
                                            <h4 class="ms-1 mb-0" id="totalCancelados">0</h4>
                                        </div>
                                        <p class="mb-1">Boletos Cancelados</p>  
                                    </div>
                                </div>
                            </div> -->

                            <!-- <div class="col-sm-3 col-lg-3 mt-2">
                                <div class="card card-border-shadow-success">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-2 pb-1">
                                            <div class="avatar me-2">
                                                <span class="avatar-initial rounded bg-label-success">
                                                    <i class="ti ti-database ti-md"></i>
                                                </span>
                                            </div>
                                            <h4 class="ms-1 mb-0" id="totalBoletos">0</h4>
                                        </div>
                                        <p class="mb-1">Total Boletos</p>  
                                    </div>
                                </div>
                            </div> -->

                            <div class="col-sm-6 col-lg-6 mt-2">
                                <div class="card card-border-shadow-success">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-2 pb-1">
                                            <div class="avatar me-2">
                                                <span class="avatar-initial rounded bg-label-success">
                                                    <i class="ti ti-coin ti-md"></i>
                                                </span>
                                            </div>
                                            <h4 class="ms-1 mb-0" id="importeTotal">0</h4>
                                        </div>
                                        <p class="mb-1">Importe Total</p>  
                                    </div>
                                </div>
                            </div>

                            <!--Tabla de asistencias-->
                            <div class="col-xl-12 col-lg-12 col-md-12 order-0 order-md-1 mt-3">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Historial de boletos</h5>
                                        <div class="d-flex justify-content-end">
                                            <button class="crear btn btn-primary me-2" onclick="filters()">
                                                <i class="ti ti-filter"></i> Filtros
                                            </button>
                                            <button class="crear btn btn-success me-2" onclick="showXLS()">
                                                <i class="ti ti-file-spreadsheet"></i> Exportar
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-datatable table-responsive">
                                        <div class="row">
                                            <div class="col-xl-12 col-lg-12 col-md-12 order-0 order-md-1">
                                                <table class="dt-responsive table table-striped" id="tbllistado">
                                                    <thead>
                                                        <tr>
                                                            <th>Acciones</th>
                                                            <th>#</th>
                                                            <th>Ruta</th>
                                                            <th>Precio</th>
                                                            <th>Total</th>
                                                            <th>Fecha</th>
                                                            <th>Hora de salida</th>
                                                            <th>Vehiculo</th>
                                                            <th>Tipo de pago</th>
                                                            <th>Estatus</th>
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
                    </div>
                    
                    <!--Inicio Modal Filtros-->
                    <div class="modal animate__animated animate__flipInX" id="modal_filters" aria-labelledby="flipInXAnimationModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Filtrar</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <?php
                                            $date = date("Y-m-d");
                                        ?>
                                        <div class="col-md-6">
                                            <label for="week_number" class="form-label">Desde</label>
                                            <input type="date" id="date" name="date" class="form-control" value="<?php echo $date; ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="week_number" class="form-label">Hasta</label>
                                            <input type="date" id="date_filter_end" name="date_filter_end" class="form-control" value="<?php echo $date; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="crear btn btn-primary me-2" onclick="filter()">
                                        <i class="ti ti-filter"></i> Filtrar
                                    </button>
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="clean()">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Fin Modal Filtros-->

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
    let tabla;
    let item_id = 1;
    $(document).ready(function() {
        let module = $("#module").val();
        const menuItem = document.querySelector('a[href="tickets-ticket-history-online.php"]').parentElement;
        menuItem.classList.add('active');
        const menuToggle = document.querySelector(`a[href="${module}"]`).parentElement;
        menuToggle.classList.add('open');
        index();
        tickets();
    });
   
    const index = () => {
        let branch_office_id = document.getElementById('branch_office_id_selected').value;
        if ($.fn.DataTable.isDataTable('#tbllistado')) {
            $('#tbllistado').DataTable().ajax.reload(null, false);
            return;
        }
        tabla = $('#tbllistado').dataTable({
            "aProcessing": true,
            "aServerSide": true,
            // "dom": 'Bfrtip',
            "ajax": {
                url: '../Controllers/ticketsController.php?op=index-online',
                type: "get",
                headers: {
                    "Authorization": "Bearer " + token
                },
                dataType: "json",
                data: function (d) {
                    d.date = $("#date").val();
                    d.date_filter_end = $("#date_filter_end").val();
                    d.branch_office  = branch_office_id;
                },
                error: (e) => {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 10,
            "lengthMenu": [5, 10, 25, 50, 100],
            "order": [1, "desc"],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            },
            "responsive": false,
        }).DataTable();
        $('#tbllistado').on('draw.dt', function() {
            permisos();
        });
    };

    const show = ( routes_schedule_id ) => {
        $('#modal_create').modal('show');
        $.ajax({
            url: "../Controllers/ticketsController.php?op=show",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + token
            },
            dataType: "json",
            data: { routes_schedule_id: routes_schedule_id },
            success: function (response) {
                let data = response;
                $("#routes_schedule_id").val(data?.id);
                $("#route_id").val(data?.route_id);
                $("#leaving_time").val(data?.leaving_time);          
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

    const deleteItem = (ticket_id) => {

        Swal.fire({
            title: "Cancelar boleto",
            html: `¿Estás seguro(a) de cancelar el boleto con folio: <b>${ticket_id}</b>?`,
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
                    url: "../Controllers/ticketsController.php?op=deleteItem",
                    type: "POST",
                    headers: { "Authorization": "Bearer " + token },
                    data: {
                        ticket_id,
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
                        index();
                        tickets();
                    },
                    error: () => {
                        Swal.fire("Error", "No se pudo cancelar el boleto.", "error");
                    }
                });

            });
        });
    };

    const filters = () => {
        $('#modal_filters').modal('show');
    }

    const filter = () => {
        $('#modal_filters').modal('hide');
        index();
        tickets();
    }

    const tickets = () => {
        let branch_office_id = document.getElementById('branch_office_id_selected').value;
        let date = $("#date").val();
        let date_filter_end = $("#date_filter_end").val();
        
        $.ajax({
            url: "../Controllers/ticketsController.php?op=tickets-online",
            type: "GET",
            headers: {
                "Authorization": "Bearer " + token
            },
            dataType: "json",
            data: { 
                date: date, branch_office_id: branch_office_id, date_filter_end:date_filter_end,
            },
            success: function (data) {
                console.log(data)
                $("#totalVendidos").text(
                    isNaN(parseFloat(data.vendidos)) 
                        ? 0 
                        : parseFloat(data.vendidos).toLocaleString()
                );

                $("#totalCancelados").text(
                    isNaN(parseFloat(data.cancelados)) 
                        ? 0 
                        : parseFloat(data.cancelados).toLocaleString()
                );

                $("#totalBoletos").text(
                    isNaN(parseFloat(data.total)) 
                        ? 0 
                        : parseFloat(data.total).toLocaleString()
                );

                $("#importeTotal").text(
                    isNaN(parseFloat(data.importe_total)) 
                        ? 0 
                        : parseFloat(data.importe_total).toLocaleString()
                );

            },
            error: function (xhr, status, error) {
                console.error("Error cargando resumen:", error);
            }
        });
    };

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
            url: "../Controllers/ticketsController.php?op=xls-online",
            type: "POST",
            dataType: "json",
            headers: {
                Authorization: `Bearer ${token}`,
            },
            data: { date_start: date_start, date_end:date_end, branch_office_id:branch_office_id },
            success: function (response) {
                console.log(response)
                const formattedData = response.map(item => ({
                    "Fecha" : item.date,
                    "Horario" : item.leaving_time,
                    "Origen": item.origin,
                    "Destino": item.destination,
                    "Chofer": item.employee,
                    "Unidad": item.unidad_number,
                    "Precio": item.price,   
                    "Estatus" : item.status,
                }));
                const worksheet = XLSX.utils.json_to_sheet(formattedData);
                const workbook  = XLSX.utils.book_new();

                XLSX.utils.book_append_sheet(workbook, worksheet, "Boletos por sucursal");
                XLSX.writeFile(workbook, `reporte_boletos_${branch_office_id}.xlsx`);

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