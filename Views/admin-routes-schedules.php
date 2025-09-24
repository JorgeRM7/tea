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
                            <!--Tabla de asistencias-->
                            <div class="col-xl-12 col-lg-12 col-md-12 order-0 order-md-1">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Horarios</h5>
                                        <div class="d-flex justify-content-end">
                                            <button class="crear btn btn-primary me-2" onclick="filters()">
                                                <i class="ti ti-cloud-up"></i> Filtros
                                            </button>

                                            <button class="crear btn btn-primary me-2" onclick="create()">
                                                <i class="ti ti-cloud-up"></i> Crear
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
                                                            <th>Horarios</th>
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
                    <!--Inicio Modal Crear-->
                    <div class="modal animate__animated animate__flipInX" id="modal_create" aria-labelledby="flipInXAnimationModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Crear</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form name="formulario" id="formulario" method="POST">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="nameWithTitle" class="form-label">Ruta</label>
                                                <select class="form-select select2-container" id="route_id" name="route_id" aria-label="Default select example" required>
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
                                            <div class="col-md-6">
                                                <label for="week_number" class="form-label">Semana</label>
                                                <input type="week" id="week_number" name="week_number" class="form-control" required>
                                            </div>

                                            
                                        </div>
                                        <div class="row" id="schedules"></div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button class="crear btn btn-primary me-2" onclick="store()">
                                        <i class="ti ti-device-floppy"></i> Guardar
                                    </button>
                                    <button class="crear btn btn-secondary me-2" onclick="schedules()">
                                        <i class="ti ti-device-floppy"></i> Agregar +
                                    </button>
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="clean()">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Fin Modal Crear-->

                    <!--Inicio Modal Detalles-->
                    <div class="modal animate__animated animate__flipInX" id="modal_schedules" aria-labelledby="flipInXAnimationModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Detalle</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row" id="routes"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="clean()">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Fin Modal Detalles-->

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
                                
                                        $now = new DateTime();
                                        $year = $now->format("o");
                                        $week = $now->format("W");

                                        $weekValue = $year . "-W" . $week;
                                        ?>
                                        <div class="col-md-6">
                                            <label for="week_number" class="form-label">Semana</label>
                                            <input type="week" id="week_number_filter" name="week_number_filter" class="form-control"
                                                value="<?php echo $weekValue; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="crear btn btn-primary me-2" onclick="index()">
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
        const menuItem = document.querySelector('a[href="admin-routes-schedules.php"]').parentElement;
        menuItem.classList.add('active');
        const menuToggle = document.querySelector('a[href="admin"]').parentElement;
        menuToggle.classList.add('open');
        index();
    });
   
    const create = () => {
        $('#modal_create').modal('show');
        schedules('create');
        clean();
    };

    const store = () => {
        let route_id   = $("#route_id").val();
        let week_value = $("#week_number").val();
        let isValid = true;
        let messages = [];

        if (!route_id) {
            isValid = false;
            messages.push("Debes seleccionar una ruta.");
        }
        if (!week_value) {
            isValid = false;
            messages.push("Debes seleccionar una semana.");
        }

        $("[id^='item_schedule_']").each(function () {
            let vehicle = $(this).find("select[name='vehicle_id[]']").val();
            let day     = $(this).find("select[name='day[]']").val();
            let time    = $(this).find("input[name='leaving_time[]']").val();

            if (!vehicle || !day || !time) {
                isValid = false;
                messages.push("Debes llenar Vehículo, Día y Hora en todos los horarios.");
            }
        });

        if (!isValid) {
            Swal.fire({
                icon: "warning",
                title: "Campos incompletos",
                text: messages.join("\n"),
                confirmButtonColor: "#f07d42"
            });
            return;
        }

        const formData = new FormData(document.getElementById("formulario"));
        $.ajax({
            url: "../Controllers/adminRoutesSchedulesController.php?op=store",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response)
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
                $('#modal_create').modal('hide');
                clean();
                index();
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

    const index = () => {
        if ($.fn.DataTable.isDataTable('#tbllistado')) {
            $('#tbllistado').DataTable().ajax.reload();
            return;
        }
    
        tabla = $('#tbllistado').dataTable({
            "aProcessing": true,
            "aServerSide": true,
            // "dom": 'Bfrtip',
            "ajax": {
                url: '../Controllers/adminRoutesSchedulesController.php?op=index',
                type: "get",
                dataType: "json",
                data: function (d) {
                    let weekInput = document.getElementById('week_number_filter').value;
                    if (weekInput) {
                        // weekInput = "2025-W38"
                        let [year, week] = weekInput.split("-W");
                        d.week = week;
                        d.year = year;
                    }
                },
                error: (e) => {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 10,
            "lengthMenu": [5, 10, 25, 50, 100],
            // "order": [7, "asc"],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            },
            "responsive": false,
        }).DataTable();
    };

    const show = ( routes_schedule_id ) => {
        $('#modal_create').modal('show');
        $.ajax({
            url: "../Controllers/adminRoutesSchedulesController.php?op=show",
            type: "POST",
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

    const deleteItem = ( routes_schedule_id ) => {
        
        Swal.fire({
            title: "Alerta",
            text: "¿Estas seguro de realizar esta acción?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "../Controllers/adminRoutesSchedulesController.php?op=deleteItem",
                    type: "POST",
                    data: { routes_schedule_id: routes_schedule_id },
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

    const clean = () => {   
        $("#routes_schedule_id").val('');
        $("#route_id").val('');
        $("#leaving_time").val('');
    }

    const schedules = ( event ) => {
        if( event =='create'){
            $("#schedules").html('');
        }
        
        item_id ++;
        let schedule = '';
        schedule = `
            <div class="col-md-6 mt-2" id="item_schedule_${item_id}">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Horario</h5>
                        <button type="button" class="btn btn-sm btn-danger" onclick="delete_item(${item_id})">
                            <i class="ti ti-trash"></i> Eliminar
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                        <div class="col-md-12">
                                <label for="nameWithTitle" class="form-label">Veihculo</label>
                                    <select class="form-select select2-container" id="vehicle_id_${item_id}" name="vehicle_id[]" aria-label="Default select example" required>
                                    <option value="">Selecciona...</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="nameWithTitle" class="form-label">Dia</label>
                                <select class="form-select select2-container" id="day_${item_id}" name="day[]" aria-label="Selecciona un día" required>
                                    <option value="">Selecciona...</option>
                                    <option value="monday">Lunes</option>
                                    <option value="tuesday">Martes</option>
                                    <option value="wednesday">Miércoles</option>
                                    <option value="thursday">Jueves</option>
                                    <option value="friday">Viernes</option>
                                    <option value="saturday">Sábado</option>
                                    <option value="sunday">Domingo</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="nameWithTitle" class="form-label">Hora de salida</label>
                                <input type="time" id="leaving_time_${item_id}" name="leaving_time[]" class="form-control" placeholder="Ingresa..." required/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $("#schedules").append(schedule);

        $.ajax({
            url: '../Controllers/adminVehiclesController.php?op=vehicles',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
            
                let $select = $(`#vehicle_id_${item_id}`);
                $select.empty().append('<option value="">Selecciona...</option>');
                data.forEach(v => {
                    $select.append(`<option value="${v.id}">${v.text}</option>`);

                    
                });
            },
            error: function(e) {
                console.error("Error cargando vehículos:", e.responseText);
            }
        });
        
    }
    
    const show_routes = ( routes_schedule_id ) => {
        $('#modal_schedules').modal('show');
        $.ajax({
            url: "../Controllers/adminRoutesSchedulesController.php?op=show-route",
            type: "POST",
            dataType: "json",
            data: { routes_schedule_id: routes_schedule_id },
            success: function (response) {
                let data = response;
                
                let schedule = `
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-primary text-white d-flex align-items-center">
                        <i class="ti ti-bus me-2"></i>
                        <h5 class="mb-0 text-white">Detalles de la Ruta</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                    <i class="ti ti-bus fs-4 text-primary me-3"></i>
                                    <div>
                                        <small class="text-muted">Unidad</small>
                                        <div class="fw-bold">(${data?.plate_number}) ${data?.type}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                    <i class="ti ti-user fs-4 text-success me-3"></i>
                                    <div>
                                        <small class="text-muted">Chofer</small>
                                        <div class="fw-bold">${data?.name}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                    <i class="ti ti-map-pin fs-4 text-danger me-3"></i>
                                    <div>
                                        <small class="text-muted">Ruta</small>
                                        <div class="fw-bold">${data?.origin} <i class="ti ti-arrow-narrow-right"></i> ${data?.destination} </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                    <i class="ti ti-clock fs-4 text-warning me-3"></i>
                                    <div>
                                        <small class="text-muted">Horario</small>
                                        <div class="fw-bold">${data?.leaving_time}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                    <i class="ti ti-calendar fs-4 text-info me-3"></i>
                                    <div>
                                        <small class="text-muted">Día</small>
                                        <div class="fw-bold">${data?.day}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                `;
                $("#routes").html(schedule);       
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

    const show_schedules = (route_id, week, year) => {
        $('#modal_create').modal('show');

        let weekFormatted = week.toString().padStart(2, "0"); 
        let weekValue = `${year}-W${weekFormatted}`;
        $("#week_number").val(weekValue);

        $("#route_id").val(route_id);
        $("#schedules").empty();

        $.ajax({
            url: "../Controllers/adminRoutesSchedulesController.php?op=show-schedules",
            type: "POST",
            dataType: "json",
            data: { route_id: route_id, week: week, year: year },
            success: function (response) {
                console.log(response)
                response.forEach(item => {

                    item_id++; 
                    let schedule = `
                        <div class="col-md-6 mt-2" id="item_schedule_${item_id}">
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Horario</h5>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="delete_item_db(${item_id}, ${item.id})">
                                        <i class="ti ti-trash"></i> Eliminar
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="form-label">Vehículo</label>
                                            <select class="form-select" id="vehicle_id_${item.id}" name="vehicle_id[]" required>
                                                <option value="">Cargando...</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Día</label>
                                            <select class="form-select" id="day_${item.id}" name="day[]" required>
                                                <option value="">Selecciona...</option>
                                                <option value="monday" ${item.day === "monday" ? "selected" : ""}>Lunes</option>
                                                <option value="tuesday" ${item.day === "tuesday" ? "selected" : ""}>Martes</option>
                                                <option value="wednesday" ${item.day === "wednesday" ? "selected" : ""}>Miércoles</option>
                                                <option value="thursday" ${item.day === "thursday" ? "selected" : ""}>Jueves</option>
                                                <option value="friday" ${item.day === "friday" ? "selected" : ""}>Viernes</option>
                                                <option value="saturday" ${item.day === "saturday" ? "selected" : ""}>Sábado</option>
                                                <option value="sunday" ${item.day === "sunday" ? "selected" : ""}>Domingo</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Hora de salida</label>
                                            <input type="time" id="leaving_time_${item.id}" 
                                                name="leaving_time[]" 
                                                class="form-control" 
                                                value="${item.leaving_time}" required/>
                                            <input type="hidden" id="routes_schedule_id_${item.id}" name="routes_schedule_id[]" value="${item.id ?? ''}"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    $("#schedules").append(schedule);
                    $.ajax({
                        url: '../Controllers/adminVehiclesController.php?op=vehicles',
                        type: 'GET',
                        dataType: 'json',
                        success: function(vehicles) {
                            let $select = $(`#vehicle_id_${item.id}`);
                            $select.empty().append('<option value="">Selecciona...</option>');
                            vehicles.forEach(v => {
                                $select.append(`<option value="${v.id}" ${v.id == item.vehicle_id ? "selected" : ""}>${v.text}</option>`);
                            });
                        },
                        error: function(e) {
                            console.error("Error cargando vehículos:", e.responseText);
                        }
                    });
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

    const delete_item = ( item_id )  => {
        $(`#item_schedule_${item_id}`).remove();
    }

    const delete_item_db = (item_id, item_id_db) => {
        Swal.fire({
            title: "¿Estás seguro?",
            text: "Este horario se eliminará permanentemente.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "../Controllers/adminRoutesSchedulesController.php?op=deleted-item",
                    type: "POST",
                    dataType: "json",
                    data: { item_id_db: item_id_db },
                    success: function (response) {
                        if (response) {
                            $(`#item_schedule_${item_id}`).remove();

                            Swal.fire({
                                icon: "success",
                                title: "Eliminado",
                                text: "El horario fue eliminado correctamente.",
                                confirmButtonColor: "#28c76f"
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: response.message || "No se pudo eliminar el horario.",
                                confirmButtonColor: "#f07d42"
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error en la solicitud:", error);
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "Hubo un problema al eliminar el horario.",
                            confirmButtonColor: "#f07d42"
                        });
                    }
                });
            }
        });
    };

    const filters = () => {
         $('#modal_filters').modal('show');
    }

</script>