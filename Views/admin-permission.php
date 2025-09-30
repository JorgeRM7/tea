<!doctype html>
<?php ;$title = "Empleados"; ?>
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
                            <div class="col-xl-12 col-lg-12 col-md-12 order-0 order-md-1">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Permisos</h5>
                                        <div class="d-flex justify-content-end">
                                            <button class="crear btn btn-primary me-2" onclick="create()">
                                                <i class="ti ti-cloud-up"></i> Crear
                                            </button>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3" id="permission"></div>
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
                                                <label for="nameWithTitle" class="form-label">Rol</label>
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
                                            <div class="col-md-6">
                                                <label for="nameWithTitle" class="form-label">Vista</label>
                                                <select class="form-select select2-container" id="view_id" name="view_id" aria-label="Default select example" required>
                                                    <option value="">Selecciona...</option>
                                                    <?php 
                                                        $sql = "SELECT * FROM `views` WHERE deleted_at is null";
                                                        $query = ejecutarConsulta($sql);
                                                        while($valores = mysqli_fetch_array($query)){
                                                            echo "<option value='".$valores['id']."'>".$valores['title']."</option>";
                                                        }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="col-md-3 mt-3">
                                                <input class="form-check-input" type="checkbox" id="permission_view" name="permission_view" />
                                                <label class="form-check-label" for="userManagementRead"> Ver </label>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <input class="form-check-input" type="checkbox" id="permission_create" name="permission_create" />
                                                <label class="form-check-label" for="userManagementRead"> Crear </label>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <input class="form-check-input" type="checkbox" id="permission_update" name="permission_update" />
                                                <label class="form-check-label" for="userManagementRead"> Editar </label>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <input class="form-check-input" type="checkbox" id="permission_delete" name="permission_delete" />
                                                <label class="form-check-label" for="userManagementRead"> Eliminar </label>
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
        const menuItem = document.querySelector('a[href="admin-permission.php"]').parentElement;
        menuItem.classList.add('active');
        const menuToggle = document.querySelector('a[href="ADMINISTRACION"]').parentElement;
        menuToggle.classList.add('open');
        index();
    });
   
    const create = () => {
        $('#modal_create').modal('show');

        // $.ajax({
        //     url: "../Controllers/permissionsController.php?op=views",
        //     type: "POST",
        //     dataType: "json",
        //     success: function (response) {
        //         let data = response;
        //         console.log(data)
        //         data.forEach(item => {
        //             let card = `
        //             `;
        //             $("#views").append(card);
        //         });
        //     },
        //     error: function (xhr, status, error) {
        //         console.error("Error en la solicitud:", error);
        //         Swal.fire({
        //             icon: "error",
        //             title: "Error",
        //             text: "Hubo un problema al procesar los datos.",
        //             confirmButtonColor: "#f07d42"
        //         });
        //     }
        // });


        // <tr>
        //     <td class="text-nowrap fw-medium">User Management</td>
        //     <td>
        //     <div class="d-flex">
        //         <div class="form-check me-3 me-lg-5">
        //         <input class="form-check-input" type="checkbox" id="userManagementRead" />
        //         <label class="form-check-label" for="userManagementRead"> Read </label>
        //         </div>
        //         <div class="form-check me-3 me-lg-5">
        //         <input class="form-check-input" type="checkbox" id="userManagementWrite" />
        //         <label class="form-check-label" for="userManagementWrite"> Write </label>
        //         </div>
        //         <div class="form-check">
        //         <input class="form-check-input" type="checkbox" id="userManagementCreate" />
        //         <label class="form-check-label" for="userManagementCreate"> Create </label>
        //         </div>
        //     </div>
        //     </td>
        // </tr>


        clean();
    };

    const store = () => {
        const formData = new FormData(document.getElementById("formulario"));
        ["permission_view", "permission_create", "permission_update", "permission_delete"].forEach(id => {
            formData.set(id, document.getElementById(id).checked ? 1 : 0);
        });
        $.ajax({
            url: "../Controllers/permissionsController.php?op=store",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + token
            },
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
        $("#permission").html('');
        $.ajax({
            url: "../Controllers/permissionsController.php?op=index",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + token
            },
            dataType: "json",
            success: function (response) {
                let data = response;
                console.log(data)
                data.forEach(item => {
                    let card = `
                    <div class="col-xl-4 col-lg-6 col-md-6 mb-3">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h6 class="fw-normal mb-2">ID: ${item.id}</h6>
                                </div>
                                <div class="d-flex justify-content-between align-items-end mt-1">
                                    <div class="role-heading">
                                        <h4 class="mb-1 text-primary">${item.name}</h4>
                                        <a href="javascript:;" onclick="show_views(${item.id})" class="text-success">
                                            <i class="ti ti-edit"></i> Editar
                                        </a>
                                    </div>
                                    <a href="javascript:void(0);" class="text-danger" onclick="deleteRole(${item.id})">
                                        <i class="ti ti-trash ti-md"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>`;
                    $("#permission").append(card);
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

    const show = ( employee_id ) => {
        $('#modal_create').modal('show');
        $.ajax({
            url: "../Controllers/permissionsController.php?op=show",
            type: "POST",
            dataType: "json",
            headers: {
                "Authorization": "Bearer " + token
            },
            data: { employee_id: employee_id },
            success: function (response) {
                let data = response;
                $("#name").val(data?.name);
                $("#employee_id").val(data?.id);
                $("#paternal_surname").val(data?.paternal_surname);
                $("#maternal_surname").val(data?.maternal_surname);
                
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

    const deleteItem = ( employee_id ) => {
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
                    url: "../Controllers/permissionsController.php?op=deleteItem",
                    type: "POST",
                    headers: {
                        "Authorization": "Bearer " + token
                    },
                    data: { employee_id: employee_id },
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
        $("#paternal_surname").val('');
        $("#maternal_surname").val('');
        $("#employee_id").val('');
    }
    
    
        
        
        
        
        
    
        
        
        
        
        
        
    
    
    
    
</script>