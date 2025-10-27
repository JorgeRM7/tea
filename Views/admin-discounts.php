<!doctype html>
<?php ;$title = "Descuentos"; ?>
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
                                        <h5 class="mb-0">Descuentos</h5>
                                        <div class="d-flex justify-content-end">
                                            
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
                                                            <th>Numero Boletos</th>
                                                            <th>Nombre</th>
                                                            <th>Porcentaje de descuento</th>
                                                            <th>Fecha de inicio</th>
                                                            <th>Fecha de Fin</th>
                                                            <th>Estatus</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
        
                                                    </tbody>
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
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Crear</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form name="formulario" id="formulario" method="POST">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="form-label section-title">Ruta</label>
                                                <select id="route_id" name="route_id" class="form-select form-select-lg">
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
                                            <div class="col-md-6">
                                                <label for="nameWithTitle" class="form-label">Nombre</label>
                                                <input type="text" id="name" name="name" class="form-control" placeholder="Ingresa..." required/>
                                                <input type="hidden" id="routes_discount_id" name="routes_discount_id" class="form-control"/>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="nameWithTitle" class="form-label">% de Descuento</label>
                                                <input type="text" id="percentage" name="percentage" class="form-control" placeholder="Ingresa..." required/>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="nameWithTitle" class="form-label">Cantidad de boletos</label>
                                                <input type="text" id="ticket_amount" name="ticket_amount" class="form-control" placeholder="Ingresa..." required/>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="nameWithTitle" class="form-label">Fecha de inicio</label>
                                                <input type="date" id="start_date" name="start_date" class="form-control" placeholder="Ingresa..." required/>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="nameWithTitle" class="form-label">Fecha de fin</label>
                                                <input type="date" id="end_date" name="end_date" class="form-control" placeholder="Ingresa..." required/>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="nameWithTitle" class="form-label">Estatus</label>
                                                <select class="form-select" id="status" name="status" required>
                                                    <option value="active">Activo</option>
                                                    <option value="inactive">Inactivo</option>
                                                </select>
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
        const menuItem = document.querySelector('a[href="admin-discounts.php"]').parentElement;
        menuItem.classList.add('active');
        const menuToggle = document.querySelector(`a[href="${module}"]`).parentElement;
        menuToggle.classList.add('open');
        index();
    });
   
    const create = () => {
        $('#modal_create').modal('show');
        clean();
    };

    const validateForm = () => {
        let route_id       = $("#route_id").val();
        let name           = $("#name").val().trim();
        let percentage     = $("#percentage").val().trim();
        let ticket_amount  = $("#ticket_amount").val().trim();
        let start_date     = $("#start_date").val();
        let end_date       = $("#end_date").val();
        let status         = $("#status").val();

        // Reglas de validación
        if (!route_id) {
            Swal.fire("Atención", "Debes seleccionar una ruta.", "warning");
            return false;
        }

        if (name === "") {
            Swal.fire("Atención", "El nombre es obligatorio.", "warning");
            return false;
        }

        if (percentage === "" || isNaN(percentage) || percentage <= 0 || percentage > 100) {
            Swal.fire("Atención", "El porcentaje debe ser un número entre 1 y 100.", "warning");
            return false;
        }

        if (ticket_amount === "" || isNaN(ticket_amount) || ticket_amount <= 0) {
            Swal.fire("Atención", "La cantidad de boletos debe ser un número mayor a 0.", "warning");
            return false;
        }

        if (!start_date) {
            Swal.fire("Atención", "La fecha de inicio es obligatoria.", "warning");
            return false;
        }

        if (!end_date) {
            Swal.fire("Atención", "La fecha de fin es obligatoria.", "warning");
            return false;
        }

        if (new Date(start_date) > new Date(end_date)) {
            Swal.fire("Atención", "La fecha de inicio no puede ser mayor que la fecha de fin.", "warning");
            return false;
        }

        if (!status) {
            Swal.fire("Atención", "Debes seleccionar un estatus.", "warning");
            return false;
        }

        return true;
    };


    const store = () => {
        if (!validateForm()) {
            return;
        }
        const formData = new FormData(document.getElementById("formulario"));
        $.ajax({
            url: "../Controllers/adminRoutesDiscountsController.php?op=store",
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
                url: '../Controllers/adminRoutesDiscountsController.php?op=index',
                type: "get",
                headers: {
                    "Authorization": "Bearer " + token
                },
                dataType: "json",
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
        $('#tbllistado').on('draw.dt', function() {
            permisos();
        });
    };

    const show = ( routes_discount_id ) => {
        $('#modal_create').modal('show');
        $.ajax({
            url: "../Controllers/adminRoutesDiscountsController.php?op=show",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + token
            },
            dataType: "json",
            data: { routes_discount_id: routes_discount_id },
            success: function (response) {
                let data = response;
                $("#routes_discount_id").val(data?.id);
                $("#name").val(data?.name);
                $("#percentage").val(data?.percentage);
                $("#start_date").val(data?.start_date); 
                $("#end_date").val(data?.end_date); 
                $("#status").val(data?.status); 
                $("#route_id").val(data?.route_id);
                $("#ticket_amount").val(data?.ticket_amount);            
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

    const deleteItem = ( routes_discount_id ) => {
        
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
                    url: "../Controllers/adminRoutesDiscountsController.php?op=deleteItem",
                    type: "POST",
                    headers: {
                        "Authorization": "Bearer " + token
                    },
                    data: { routes_discount_id: routes_discount_id },
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
        $("#routes_discount_id").val('');
        $("#route_id").val('');
        $("#ticket_amount").val('');
        $("#name").val('');
        $("#percentage").val('');
        $("#start_date").val('');
        $("#end_date").val('');
        $("#status").val('');
    }
    
</script>