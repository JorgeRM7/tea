<!doctype html>
<?php ;$title = "Vehiculos"; ?>
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
                            <div class="col-md-4">
                                <div class="card shadow-sm border-0 rounded-3 h-100">
                                    <div class="card-body text-center">
                                        <h5 class="card-title text-success d-flex align-items-center justify-content-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-ticket" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M15 5l0 2" />
                                                <path d="M15 11l0 2" />
                                                <path d="M15 17l0 2" />
                                                <path d="M5 5h14a2 2 0 0 1 2 2v2a2 2 0 0 0 0 4v2a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-2a2 2 0 0 0 0 -4v-2a2 2 0 0 1 2 -2" />
                                            </svg>
                                            Boletos Vendidos
                                        </h5>
                                        <h2 class="fw-bold text-success mt-2" id="totalVendidos">0</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card shadow-sm border-0 rounded-3 h-100">
                                    <div class="card-body text-center">
                                        <h5 class="card-title text-danger d-flex align-items-center justify-content-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M18 6l-12 12" />
                                                <path d="M6 6l12 12" />
                                            </svg>
                                            Boletos Cancelados
                                        </h5>
                                        <h2 class="fw-bold text-danger mt-2" id="totalCancelados">0</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card shadow-sm border-0 rounded-3 h-100">
                                    <div class="card-body text-center">
                                        <h5 class="card-title text-primary d-flex align-items-center justify-content-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-database" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <ellipse cx="12" cy="6" rx="8" ry="3" />
                                                <path d="M4 6v12a8 3 0 0 0 16 0v-12" />
                                                <path d="M4 12a8 3 0 0 0 16 0" />
                                            </svg>
                                            Total Boletos
                                        </h5>
                                        <h2 class="fw-bold text-primary mt-2" id="totalBoletos">0</h2>
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
                                                <i class="ti ti-cloud-up"></i> Filtros
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
                                                            <th>Descuento</th>
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
                        <div class="modal-dialog" role="document">
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
                                            <label for="week_number" class="form-label">Fecha</label>
                                            <input type="date" id="date" name="date" class="form-control"
                                                value="<?php echo $date; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="crear btn btn-primary me-2" onclick="filter()">
                                        <i class="ti ti-device-floppy"></i> Filtrar
                                    </button>
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="clean()">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Fin Modal Filtros-->
                    
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
        const menuItem = document.querySelector('a[href="tickets-ticket-history.php"]').parentElement;
        menuItem.classList.add('active');
        const menuToggle = document.querySelector(`a[href="${module}"]`).parentElement;
        menuToggle.classList.add('open');
        index();
        tickets();
    });
   
    const index = () => {
        if ($.fn.DataTable.isDataTable('#tbllistado')) {
            $('#tbllistado').DataTable().ajax.reload(null, false);
            return;
        }
        tabla = $('#tbllistado').dataTable({
            "aProcessing": true,
            "aServerSide": true,
            // "dom": 'Bfrtip',
            "ajax": {
                url: '../Controllers/ticketsController.php?op=index',
                type: "get",
                headers: {
                    "Authorization": "Bearer " + token
                },
                dataType: "json",
                data: function (d) {
                    d.date = $("#date").val();
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

    const deleteItem = ( ticket_id ) => {
        
        Swal.fire({
            title: "Alerta",
            html: `¿ Estas seguro(a) de realizar la cancelación del boleto con folio: <b>${ticket_id}</b> ?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "../Controllers/ticketsController.php?op=deleteItem",
                    type: "POST",
                    headers: {
                        "Authorization": "Bearer " + token
                    },
                    data: { ticket_id: ticket_id },
                    success: function(data, status) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            icon: 'success',
                            title: 'Éxito',
                            text: 'Acción realizada exitosamente.',
                        });
                        index();
                        tickets();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", error);
                        Swal.fire({
                            title: "Error",
                            text: "No se pudo obtener la información del registro.",
                            icon: "error"
                        });
                    }
                });
            }
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
        let date = $("#date").val();

        $.ajax({
            url: "../Controllers/ticketsController.php?op=tickets",
            type: "GET",
            headers: {
                "Authorization": "Bearer " + token
            },
            dataType: "json",
            data: { 
                date: date,
            },
            success: function (data) {
                $("#totalVendidos").text(data.vendidos);
                $("#totalCancelados").text(data.cancelados);
                $("#totalBoletos").text(data.total);
            },
            error: function (xhr, status, error) {
                console.error("Error cargando resumen:", error);
            }
        });
    };

</script>