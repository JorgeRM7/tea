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
                                        <h5 class="mb-0">Sucurales</h5>
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
                                                            <th>Codigo</th>
                                                            <th>Nombre</th>
                                                            <th>Descripción</th>
                                                            <th>Dirección</th>
                                                            <th>Ciudad</th>
                                                            <th>Estado Serial</th>
                                                            <th>Pais</th>
                                                            <th>Codigo Postal</th> 
                                                            <th>Telefono</th>    
                                                            <th>Cprreo</th>    
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
                                            <div class="col-md-6">
                                                <label for="nameWithTitle" class="form-label">Codigo</label>
                                                <input type="text" id="code" name="code" class="form-control" placeholder="Ingresa..." required/>
                                                <input type="hidden" id="branch_office_id" name="branch_office_id" class="form-control"/>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="nameWithTitle" class="form-label">Nombre</label>
                                                <input type="text" id="name" name="name" class="form-control" placeholder="Ingresa..." required/>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="nameWithTitle" class="form-label">Descripción</label>
                                                <input type="text" id="description" name="description" class="form-control" placeholder="Ingresa..." required/>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="nameWithTitle" class="form-label">Dirección</label>
                                                <input type="text" id="address" name="address" class="form-control" placeholder="Ingresa..." required/>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="nameWithTitle" class="form-label">Ciudad</label>
                                                <input type="text" id="city" name="city" class="form-control" placeholder="Ingresa..." required/>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="nameWithTitle" class="form-label">Estado</label>
                                                <input type="text" id="state" name="state" class="form-control" placeholder="Ingresa..." required/>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="nameWithTitle" class="form-label">Pais</label>
                                                <input type="text" id="country" name="country" class="form-control" placeholder="Ingresa..." required/>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="nameWithTitle" class="form-label">Codigo Postal</label>
                                                <input type="text" id="postal_code" name="postal_code" class="form-control" placeholder="Ingresa..." required/>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="nameWithTitle" class="form-label">Telefono</label>
                                                <input type="text" id="phone" name="phone" class="form-control" placeholder="Ingresa..." required/>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="nameWithTitle" class="form-label">Correo</label>
                                                <input type="text" id="email" name="email" class="form-control" placeholder="Ingresa..." required/>
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
        const menuItem = document.querySelector('a[href="admin-branch-offices.php"]').parentElement;
        menuItem.classList.add('active');
        const menuToggle = document.querySelector('a[href="admin"]').parentElement;
        menuToggle.classList.add('open');
        index();
    });
   
    const create = () => {
        $('#modal_create').modal('show');
        clean();
    };

    const store = () => {
        const formData = new FormData(document.getElementById("formulario"));
        $.ajax({
            url: "../Controllers/adminBranchOfficesController.php?op=store",
            type: "POST",
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
                url: '../Controllers/adminBranchOfficesController.php?op=index',
                type: "get",
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
    };

    const show = ( branch_office_id ) => {
        $('#modal_create').modal('show');
        $.ajax({
            url: "../Controllers/adminBranchOfficesController.php?op=show",
            type: "POST",
            dataType: "json",
            data: { branch_office_id: branch_office_id },
            success: function (response) {
                let data = response;
                $("#name").val(data?.name);
                $("#branch_office_id").val(data?.id);
                $("#code").val(data?.code);
                $("#description").val(data?.description);
                $("#address").val(data?.address);
                $("#city").val(data?.city);
                $("#state").val(data?.state);
                $("#country").val(data?.country);
                $("#postal_code").val(data?.postal_code);
                $("#phone").val(data?.phone);
                $("#email").val(data?.email);            
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

    const deleteItem = ( branch_office_id ) => {
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
                    url: "../Controllers/adminBranchOfficesController.php?op=deleteItem",
                    type: "POST",
                    data: { branch_office_id: branch_office_id },
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
        $("#name").val('');
        $("#branch_office_id").val('');
        $("#code").val('');
        $("#description").val('');
        $("#address").val('');
        $("#city").val('');
        $("#state").val('');
        $("#country").val('');
        $("#postal_code").val('');
        $("#phone").val('');
        $("#email").val('');    
    }
    
</script>