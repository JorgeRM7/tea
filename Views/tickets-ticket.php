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
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Boleto</h5>
                                        <small class="text-muted float-end">Default label</small>
                                    </div>
                                    <div class="card-body">
                                        <form>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="nameWithTitle" class="form-label">Taquillero(a)</label>
                                                    <select class="form-select select2-container" id="planta" name="planta" aria-label="Default select example" required>
                                                        <option value="">Selecciona...</option>
                                                        <?php 
                                                            $sql = "SELECT * FROM `employees` WHERE deleted_at is null";
                                                            $query = ejecutarConsulta($sql);
                                                            while($valores = mysqli_fetch_array($query)){
                                                                echo "<option value='".$valores['id']."'>".$valores['name']."</option>";
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="nameWithTitle" class="form-label">Ruta</label>
                                                    <select class="form-select select2-container" id="planta" name="planta" aria-label="Default select example" required>
                                                        <option value="">Selecciona...</option>
                                                        <?php 
                                                            $sql = "SELECT * FROM `routes` WHERE deleted_at is null";
                                                            $query = ejecutarConsulta($sql);
                                                            while($valores = mysqli_fetch_array($query)){
                                                                echo "<option value='".$valores['id']."'>".$valores['name']."</option>";
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-primary">Send</button>
                                        </form>
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
        const menuToggle = document.querySelector('a[href="tickets"]').parentElement;
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
            url: "../Controllers/adminUsersController.php?op=store",
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
                url: '../Controllers/adminUsersController.php?op=index',
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

    const show = ( user_id ) => {
        $('#modal_create').modal('show');
        $.ajax({
            url: "../Controllers/adminUsersController.php?op=show",
            type: "POST",
            dataType: "json",
            data: { user_id: user_id },
            success: function (response) {
                let data = response;
                console.log(data)
                $("#name").val(data?.name);
                $("#user_id").val(data?.id);
                $("#email").val(data?.email);
                $("#username").val(data?.username);
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

    const deleteItem = ( user_id ) => {
        
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
                    url: "../Controllers/adminUsersController.php?op=deleteItem",
                    type: "POST",
                    data: { user_id: user_id },
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