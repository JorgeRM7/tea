<!doctype html>
<?php ;$title = "Calendario horarios"; ?>
<html lang="es" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr"
    data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template">
<!--HEADER-->
<?php require_once('header.php'); ?>
<!--HEADER-->

<style>
    .schedule-row {
        display: flex;
        align-items: center;
    }

    .schedule-group {
        width: 100%;
    }

    .schedule-time {
        height: 40px;
        font-size: 1.1rem;
        font-weight: 600;
        text-align: center;
        border-radius: 10px 0 0 10px;
    }
    #calendar {
        cursor: pointer;
    }

    .fc-daygrid-day,
    .fc-timegrid-slot,
    .fc-timegrid-col {
        cursor: pointer;
    }

    .fc-event {
        cursor: pointer;
    }

    .fc-toolbar button {
        cursor: pointer;
    }

    .btn-mini {
        width: 38px;
        height: 40px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .btn-mini i {
        pointer-events: none;
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
                            <!--Tabla de asistencias-->
                            <div class="col-xl-12 col-lg-12 col-md-12 order-0 order-md-1">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Calendario de horarios</h5>
                                        <div class="d-flex justify-content-end">      
                                           
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-xl-12 col-lg-12 col-md-12 order-0 order-md-1">
                                                <div id="calendar"></div>
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
                                    <h5 class="modal-title" id="exampleModalLabel">Horarios</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form name="formulario" id="formulario" method="POST">
                                        <div class="row">

                                            <div class="col-md-3">
                                                <label class="form-label section-title">Ruta</label>
                                                <select id="route_id" name="route_id" class="form-select">
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
                                            <div class="col-md-3">
                                                <label class="form-label section-title">Vehiculo</label>
                                                <select id="vehicle_id" name="vehicle_id" class="form-select">
                                                    <?php 
                                                        $sql = "SELECT * FROM `vehicles` WHERE deleted_at is null";
                                                        $query = ejecutarConsulta($sql);
                                                        while($valores = mysqli_fetch_array($query)){
                                                            echo "<option value='".$valores['id']."'>(".$valores['unidad_number'].") ".$valores['brand']."</option>";
                                                        }
                                                    ?>

                                                </select>
                                                
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label section-title">Rol de horiario</label>
                                                <select id="shift_role_id" name="shift_role_id" class="form-select">
                                                    <?php 
                                                        $sql = "SELECT * FROM `shift_roles` WHERE deleted_at is null";
                                                        $query = ejecutarConsulta($sql);
                                                        while($valores = mysqli_fetch_array($query)){
                                                            echo "<option value='".$valores['id']."'>".$valores['name']."</option>";
                                                        }
                                                    ?>

                                                </select>
                                                
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label section-title">Fecha</label>
                                                <input type="date" class="form-control" id="date" name="date" readonly>
                                            
                                            </div>
                                            <h6 class="mb-2">Horarios disponibles</h6>
                                            <div id="times" class="row"></div>
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
        let module = $("#module").val();
        const menuItem = document.querySelector('a[href="admin-calendar-schedules.php"]').parentElement;
        menuItem.classList.add('active');
        const menuToggle = document.querySelector(`a[href="${module}"]`).parentElement;
        menuToggle.classList.add('open');
        // index();


  

        $("#route_id").on("change", function() {
            routes();
        });

        $("#shift_role_id").on("change", function() {
            routes();
        });

    });

    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            themeSystem: 'bootstrap5',
            locale: 'es',
            initialView: 'dayGridMonth',
            height: 'auto',

            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },

            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                day: 'Día'
            },

            selectable: true,

            dateClick: function (info) {
                $('#modal_create').modal('show');
                $('#date').val(info.dateStr)
                routes();
                // loadSchedules();

                // console.log('Fecha seleccionada:', info.dateStr);
            },

            eventClick: function (info) {
                console.log('Evento:', info.event);
            },

            events: []
        });

        calendar.render();
    });
   
    const create = () => {
        $('#modal_create').modal('show');
    };

    const store = () => {
        const formData = new FormData(document.getElementById("formulario"));
        $.ajax({
            url: "../Controllers/adminCalendarRoutesSchedulesController.php?op=store",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + token
            },
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(response) {

                if (response.total_records > 0) {

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        icon: 'success',
                        title: 'Éxito',
                        text: `Se insertaron ${response.total_records} horarios correctamente.`,
                    });

                } else {

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        icon: 'warning',
                        title: 'Aviso',
                        text: `Los ${response.horarios_existentes} horarios ya existían.`,
                    });

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

    const show = ( route_id ) => {
        $('#modal_create').modal('show');
        $.ajax({
            url: "../Controllers/adminCalendarRoutesSchedulesController.php?op=show",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + token
            },
            dataType: "json",
            data: { route_id: route_id },
            success: function (response) {
                let data = response;
                $("#route_id").val(data?.id);
                $("#destination").val(data?.destination);
                $("#origin").val(data?.origin);
                $("#cost").val(data?.cost);             
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

    const deleteItem = ( route_id ) => {
        
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
                    url: "../Controllers/adminCalendarRoutesSchedulesController.php?op=deleteItem",
                    type: "POST",
                    headers: {
                        "Authorization": "Bearer " + token
                    },
                    data: { route_id: route_id },
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
        // $("#route_id").val('');
        // $("#origin").val('');
        // $("#destination").val('');
        // $("#cost").val('');
    }
    
    const routes = () => {    
        let search_route        = $("#route_id").val(); 
        let search_shift_role   = $("#shift_role_id").val(); 
        $.ajax({
            url: "../Controllers/adminCalendarRoutesSchedulesController.php?op=routes",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + token
            },
            dataType: "json",
            data: { search_route, search_shift_role },
            success: function (data) {
                let content = ``;

                if (!data.length) {
                    content = `
                        <div class="col-12 text-center">
                            <div class="alert alert-warning">
                                🚍 No se encontraron rutas
                            </div>
                        </div>`;
                } else {

                    data.forEach(item => {
                        content += `
                            <div class="col-12 col-sm-6 col-md-4 mb-3 schedule-item" data-schedule-id="${item.route_schedule_id}">

                                <div class="input-group input-group-sm schedule-group">
                        
                                    <input 
                                        type="time"
                                        name="time[]"
                                        class="form-control schedule-time"
                                        value="${item.leaving_time.substring(0,5)}"
                                        data-id="${item.route_schedule_id}"
                                        onchange="updateTime(${item.route_schedule_id}, this.value)"
                                    >
                                </div>
                            </div>
                        `;
                    });
                }

                $("#times").html(content);
            }
        });
    };

    const loadSchedules = () => {    
        let search_route = $("#route_id").val(); 
        let search_date  = $("#date").val(); 

        Swal.fire({
            icon: "info",
            title: "Cargando horarios...",
            text: `Ruta: ${search_route} | Fecha: ${search_date}`,
            showConfirmButton: false,
            timer: 1000
        });

        $.ajax({
            url: "../Controllers/adminCalendarRoutesSchedulesController.php?op=load-schedules",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + token
            },
            dataType: "json",
            data: { search_route, search_date },
            success: function (data) {

                Swal.fire({
                    icon: data.success ? "success" : "warning",
                    title: "Aviso",
                    text: data.message || "Proceso finalizado",
                    confirmButtonText: "OK"
                });

                routes();
            },
            error: function (xhr) {
                console.error(xhr.responseText);

                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Ocurrió un error al cargar los horarios",
                    confirmButtonText: "OK"
                });
            }
        });
    };  

</script>