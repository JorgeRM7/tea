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
                                                <input class="form-check-input" type="checkbox" id="permission_view_check" name="permission_view_check" />
                                                <label class="form-check-label" for="userManagementRead"> Ver </label>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <input class="form-check-input" type="checkbox" id="permission_create_check" name="permission_create_check" />
                                                <label class="form-check-label" for="userManagementRead"> Crear </label>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <input class="form-check-input" type="checkbox" id="permission_update_check" name="permission_update_check" />
                                                <label class="form-check-label" for="userManagementRead"> Editar </label>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <input class="form-check-input" type="checkbox" id="permission_delete_check" name="permission_delete_check" />
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

                    <!--Inicio Modal Editar-->
                    <div class="modal animate__animated animate__flipInX" id="modal_edit" aria-labelledby="flipInXAnimationModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">
                                        <span id="roleName" class="text-primary"></span>                                    
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form name="formulario_edit" id="formulario_edit" method="POST">
                                        <div class="row" id="permissions"></div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button class="crear btn btn-primary me-2" onclick="update()">
                                        <i class="ti ti-device-floppy"></i> Actualizar
                                    </button>
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="clean()">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Fin Modal Editar-->
                    
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
        const menuItem = document.querySelector('a[href="admin-permission.php"]').parentElement;
        menuItem.classList.add('active');
        const menuToggle = document.querySelector(`a[href="${module}"]`).parentElement;
        menuToggle.classList.add('open');
        index();
    });
   
    const create = () => {
        $('#modal_create').modal('show');
        clean();
    };

    const store = () => {
        let user_type_id = $("#user_type_id").val();
        let view_id = $("#view_id").val();
        if( user_type_id == ""){
             Swal.fire({
                title: "Error",
                text: "Selecciona un rol",
                icon: "error"
            });
            return;
        }
        if( view_id == ""){
             Swal.fire({
                title: "Error",
                text: "Selecciona una vista",
                icon: "error"
            });
            return;
        }
    
        const formData = new FormData(document.getElementById("formulario"));
        ["permission_view_check", "permission_create_check", "permission_update_check", "permission_delete_check"].forEach(id => {
            const el = document.getElementById(id);
            formData.set(id, el && el.checked ? 1 : 0);
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
                                        <a href="javascript:;" onclick="show_permissions(${item.id}, '${item.name}')" class="text-success">
                                            <i class="ti ti-edit"></i> Editar
                                        </a>
                                    </div>
                                    <a href="javascript:void(0);" class="text-danger" onclick="deleteItem(${item.id})">
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

const show_permissions = ( permission_id, roleName ) => {
    $('#modal_edit').modal('show');
    $('#roleName').text(roleName);
    
    $.ajax({
        url: "../Controllers/permissionsController.php?op=permissions",
        type: "POST",
        dataType: "json",
        headers: {
            "Authorization": "Bearer " + token
        },
        data: { permission_id: permission_id },
        success: function (response) {
            let data = response;
            let container = $("#permissions");
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
                <div class="col-12 mb-3" id="perm_${item.permission_id}">
                    <div class="card shadow-sm border">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <h5 class="card-title text-primary">
                                    <i class="ti ti-lock"></i> ${item.view}
                                </h5>
                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                        onclick="deleteSinglePermission(${item.permission_id})">
                                    <i class="ti ti-trash"></i> Eliminar
                                </button>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-3">
                                    <input type="checkbox" class="form-check-input" 
                                        id="view_${item.permission_id}" 
                                        name="permission_view[]" 
                                        ${item.permission_view == "1" ? "checked" : ""}>
                                    <label class="form-check-label">Ver</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="checkbox" class="form-check-input" 
                                        id="create_${item.permission_id}" 
                                        name="permission_create[]" 
                                        ${item.permission_create == "1" ? "checked" : ""}>
                                    <label class="form-check-label">Crear</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="checkbox" class="form-check-input" 
                                        id="update_${item.permission_id}" 
                                        name="permission_update[]" 
                                        ${item.permission_update == "1" ? "checked" : ""}>
                                    <label class="form-check-label">Editar</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="checkbox" class="form-check-input" 
                                        id="delete_${item.permission_id}" 
                                        name="permission_delete[]" 
                                        ${item.permission_delete == "1" ? "checked" : ""}>
                                    <label class="form-check-label">Eliminar</label>
                                </div>
                            </div>
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

function deleteSinglePermission(permission_id) {
    Swal.fire({
        title: "¿Eliminar esta vista?",
        text: "Se quitarán los permisos de esta vista para el rol.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar"
    }).then(result => {
        if (!result.isConfirmed) return;

        $.ajax({
            url: "../Controllers/permissionsController.php?op=deleteSingle",
            type: "POST",
            headers: { "Authorization": "Bearer " + token },
            data: { permission_id },
            success: function () {
                // Quitar la card del DOM
                $("#perm_" + permission_id).remove();

                // Si ya no hay permisos, mostrar aviso
                if ($("#permissions .card").length === 0) {
                    $("#permissions").html(`
                        <div class="alert alert-warning text-center" role="alert">
                            <i class="ti ti-alert-triangle"></i> 
                            No tienes permisos asignados para esta vista.
                        </div>
                    `);
                }

                Swal.fire({
                    toast: true, position: "top-end", timer: 2500,
                    showConfirmButton: false, icon: "success",
                    title: "Vista eliminada"
                });
            },
            error: function () {
                Swal.fire({ icon: "error", title: "Error", text: "No se pudo eliminar la vista." });
            }
        });
    });
}


    const update = () => {
        let permisos = [];

        $("#permissions .card").each(function () {
            let permiso = {};
            let viewTitle = $(this).find(".card-title").text().trim();
            let permission_id = $(this).find("input[type=checkbox]").first().attr("id").split("_")[1];
            permiso.permission_id = permission_id;
            permiso.view = viewTitle;
            permiso.permission_view = $(this).find(`input[id=view_${permission_id}]`).is(":checked") ? 1 : 0;
            permiso.permission_create = $(this).find(`input[id=create_${permission_id}]`).is(":checked") ? 1 : 0;
            permiso.permission_update = $(this).find(`input[id=update_${permission_id}]`).is(":checked") ? 1 : 0;
            permiso.permission_delete = $(this).find(`input[id=delete_${permission_id}]`).is(":checked") ? 1 : 0;

            permisos.push(permiso);
        });
        $.ajax({
            url: "../Controllers/permissionsController.php?op=update",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + token
            },
            dataType: "json",
            contentType: "application/json",
            data: JSON.stringify(permisos),
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
                $('#modal_edit').modal('hide');
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

    const deleteItem = ( permission_id ) => {
        Swal.fire({
            title: "¿Estas seguro de realizar esta acción?",
            text: "Si realizas esta accion todos los permisos del rol se eliminaran",
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
                    data: { permission_id: permission_id },
                    success: function(data, status) {
                        console.log(data)
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
        $("#permission_id").val('');
    }
    
    
</script>