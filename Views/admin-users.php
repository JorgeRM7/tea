<!doctype html>
<?php ;$title = "Usuarios"; ?>
<html lang="es" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr"
    data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template">
<!--HEADER-->
<?php require_once('header.php'); ?>
<style>
    .plant-card {
        cursor: pointer; /* 👆 manita al pasar */
        transition: all 0.3s ease-in-out;
        border-radius: 15px;
        border: 2px solid transparent;
    }

    .plant-card:hover {
        transform: translateY(-5px); /* efecto flotante */
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        border-color: #38b449; /* verde TEA al hover */
    }

</style>
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
                                        <h5 class="mb-0">Usuarios</h5>
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
                                                            <th>Nombre</th>
                                                            <th>Correo</th>  
                                                            <th>Usuario</th>  
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
                                                <label for="nameWithTitle" class="form-label">Nombre</label>
                                                <input type="text" id="name" name="name" class="form-control" placeholder="Ingresa..." required/>
                                                <input type="hidden" id="user_id" name="user_id" class="form-control"/>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="nameWithTitle" class="form-label">Correo</label>
                                                <input type="email" id="email" name="email" class="form-control" placeholder="Ingresa..." required/>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="nameWithTitle" class="form-label">Usuario</label>
                                                <input type="text" id="username" name="username" class="form-control" placeholder="Ingresa..." required/>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="nameWithTitle" class="form-label">Tipo de usuario</label>
                                                <select class="form-select select2-container" id="user_type_id" name="user_type_id" aria-label="Default select example" required>
                                                    <option value="">Selecciona...</option>
                                                    <?php 
                                                        $sql = "SELECT * FROM `users_types` WHERE deleted_at is null";
                                                        $query = ejecutarConsulta($sql);
                                                        while($valores = mysqli_fetch_array($query)){
                                                            echo "<option value='".$valores['id']."'>".$valores['name']."</option>";
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6" id="div_password">
                                                <label for="nameWithTitle" class="form-label">Contraseña</label>
                                                <input type="text" id="password" name="password" class="form-control" placeholder="Ingresa..." required/>
                                            </div>
                                        </div>
                                        <div class="row mt-3" id="branch_offices"></div>
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

                    <!--Inicio Modal Crear-->
                    <div class="modal animate__animated animate__flipInX" id="modal_password" aria-labelledby="flipInXAnimationModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Cambiar contraseña</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                   
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="nameWithTitle" class="form-label">Nueva cotraseña</label>
                                            <input type="hidden" id="user_password_id" name="user_password_id" class="form-control"/>
                                            <input type="text" id="change_password" name="change_password" class="form-control" placeholder="Ingresa..." required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="crear btn btn-primary me-2" onclick="store_password()">
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
        const menuItem = document.querySelector('a[href="admin-users.php"]').parentElement;
        menuItem.classList.add('active');
        const menuToggle = document.querySelector(`a[href="${module}"]`).parentElement;
        menuToggle.classList.add('open');
        index();
    });
   
    const create = () => {
        $('#modal_create').modal('show');
        document.getElementById("div_password").style.display = "block";
        clean();
        branch_office();
    };

    const show_password = ( user_id ) => {
        $("#user_password_id").val(user_id);
        $('#modal_password').modal('show');
    };

    const store = () => {
        const form = document.getElementById("formulario");
        // Forzar validación nativa de HTML5
        if (!form.checkValidity()) {
            form.reportValidity(); // Muestra los mensajes del navegador
            return;
        }

        if (!validateForm()) {
            return;
        }
        const formData = new FormData(document.getElementById("formulario"));
        $.ajax({
            url: "../Controllers/adminUsersController.php?op=store",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + token
            },
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function(response) {
                console.log(response)

                if (response.status === "error") {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: response.message
                    });
                } else {
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

    const show = ( user_id ) => {
        branch_office();
        $('#modal_create').modal('show');
        document.getElementById("div_password").style.display = "none";
        $.ajax({
            url: "../Controllers/adminUsersController.php?op=show",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + token
            },
            dataType: "json",
            data: { user_id: user_id },
            success: function (response) {
                let data = response;
                let user = response[0];
                $("#name").val(user?.name);
                $("#user_id").val(user?.user_id);
                $("#email").val(user?.email);
                $("#username").val(user?.username);
                $("#user_type_id").val(user?.user_type_id);
                // $("input[name='branch_office_id[]']").prop("checked", false);
                
                setTimeout(() => {
                    $("input[name='branch_office_id[]']").prop("checked", false);
                    response.forEach(item => {
                        $(`#branch_${item?.branch_office_id}`).prop("checked", true);
                        togglePlant(item?.branch_office_id);
                    });
                }, 500);
                
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

    const store_password = () => {
        let change_password = $("#change_password").val();
        let user_password_id = $("#user_password_id").val();
        $.ajax({
            url: "../Controllers/adminUsersController.php?op=store-password",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + token
            },
            data: { change_password: change_password, user_password_id: user_password_id },
            success: function (response) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Contraseña actualizada exitosamente.',
                });
                $('#modal_password').modal('hide');
                $("#change_password").val('');
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
                    headers: {
                        "Authorization": "Bearer " + token
                    },
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
        $("#name").val('');
        $("#user_id").val('');
        $("#email").val('');
        $("#username").val('');
        $("#user_type_id").val('');        
    }

    const branch_office = () => {
        $.ajax({
            url: "../Controllers/adminUsersController.php?op=branch_offices",
            type: "POST",
            dataType: "json",
            headers: {
                "Authorization": "Bearer " + token
            },
            success: function (response) {
                let data = response;
        
                let container = $("#branch_offices");
                container.empty();

                if (!data || data.length === 0) {
                    container.html(`
                        <div class="alert alert-warning text-center" role="alert">
                            <i class="ti ti-alert-triangle"></i> 
                            No tienes permisos asignados para esta vista.
                        </div>
                    `);
                    return;
                }

                data.forEach(item => {
                    let card = `
                    <div class="col-md-4 col-sm-6 mb-4">
                        <div class="plant-card card h-100 shadow-sm text-center" id="plant_${item.branch_office_id}" onclick="togglePlant(${item.branch_office_id})">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                <i class="ti ti-building fs-1 text-primary mb-3"></i>
                                <h5 class="fw-bold mb-2">${item.name}</h5>
                                <input type="checkbox" class="form-check-input d-none" 
                                id="plant_check_${item.branch_office_id}" 
                                name="branch_office_id[]" 
                                value="${item.branch_office_id}">
                            </div>
                        </div>
                    </div>`;
                    container.append(card);
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

    function validateForm() {
        let name = $("#name").val().trim();
        let email = $("#email").val().trim();
        let username = $("#username").val().trim();
        let userType = $("#user_type_id").val();

        if (!name || !email || !username || !userType) {
            Swal.fire({
                icon: "warning",
                title: "Campos incompletos",
                text: "Por favor llena todos los campos obligatorios."
            });
            return false;
        }

        let plantsChecked = $("input[name='branch_office_id[]']:checked").length;
        if (plantsChecked === 0) {
            Swal.fire({
                icon: "warning",
                title: "Planta requerida",
                text: "Debes seleccionar al menos una planta."
            });
            return false;
        }

        return true; 
    }

    
    function togglePlant(id) {
        let checkbox = document.getElementById(`plant_check_${id}`);
        let card = document.getElementById(`plant_${id}`);

        if( checkbox ){
            checkbox.checked = !checkbox.checked;
            if (checkbox.checked) {
                card.classList.add("border-success");
            } else {
                card.classList.remove("border-success");
            }
        }
    }
</script>