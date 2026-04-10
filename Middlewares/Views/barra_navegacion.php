<?php
$archivo_actual = basename($_SERVER['PHP_SELF']);
$user_type_id = $_SESSION['user_type_id'];
$user_id = $_SESSION['user_id'];
$sql ="SELECT 
            users_types.name,
            permissions.permission_create,
            permissions.permission_view,
            permissions.permission_update,
            permissions.permission_delete,
            views.title,
            views.module 
        FROM `permissions`
        INNER JOIN users_types ON users_types.id = permissions.user_type_id
        INNER JOIN views ON views.id = permissions.view_id
        WHERE users_types.id ='$user_type_id' AND views.route='$archivo_actual'";
$resultado_permisos = ejecutarConsulta($sql);
while ($item = mysqli_fetch_array($resultado_permisos)) {
    $permission_create = $item['permission_create'] ?? 0;
    $permission_update = $item['permission_update'] ?? 0;
    $permission_delete = $item['permission_delete'] ?? 0;
    $permission_view = $item['permission_view'] ?? 0;
    $module = $item['module'] ?? 0;
    
}
if($archivo_actual == 'inicio.php'){
    $permission_view = 1;
    $permission_update = 0;
    $permission_delete = 0;
    $permission_create = 0;
}

?>
<input type="hidden" name="permission_create" id="permission_create" value="<?php echo $permission_create  ?>">
<input type="hidden" name="permission_update" id="permission_update" value="<?php echo $permission_update ?>">
<input type="hidden" name="permission_delete" id="permission_delete" value="<?php echo $permission_delete ?>">
<input type="hidden" name="permission_view" id="permission_view" value="<?php echo $permission_view ?>">
<input type="hidden" name="module" id="module" value="<?php echo $module ?>">


<nav class="layout-navbar navbar navbar-expand-md navbar-detached align-items-center bg-navbar-theme p-3" id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="ti ti-menu-2 ti-sm"></i>
        </a>
    </div>
    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <li>

            
                <select id="branch_office_id_selected" name="branch_office_id_selected" class="form-select" onchange="change_branch_office()">
                    <?php
                    $sql = "SELECT 
                            branch_offices.id, 
                            branch_offices.name
                            FROM `branch_offices_user` 
                            INNER JOIN users ON users.id = branch_offices_user.user_id 
                            INNER JOIN branch_offices ON branch_offices.id = branch_offices_user.branch_office_id 
                            WHERE branch_offices_user.user_id = '$user_id' AND branch_offices_user.deleted_at is  null";
                    $query = ejecutarConsulta($sql);
                    while ($valores = mysqli_fetch_array($query)) {
                        echo "<option value='" . $valores['id'] . "'>" . $valores['name'] . "</option>";
                    }            

                    ?>
                </select>
            </li>
            <li class="nav-item dropdown-style-switcher dropdown me-2 me-xl-0">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <i class="ti ti-md"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                            <span class="align-middle"><i class="ti ti-sun me-2"></i>Claro</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
                            <span class="align-middle"><i class="ti ti-moon me-2"></i>Oscuro</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="system">
                            <span class="align-middle"><i class="ti ti-device-desktop me-2"></i>Por defecto del sistema</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- USUARIO -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="../assets/img/user_logo.png" alt class="h-auto rounded-circle" style="width: 40px; height: 40px; object-fit: cover;"/>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="../assets/img/user_logo_2.png" alt class="h-auto rounded-circle" style="width: 40px; height: 40px; object-fit: cover;"/>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-medium d-block"><?php echo $_SESSION['name']  ?></span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="perfil.php">
                            <i class="ti ti-user-check me-2 ti-sm"></i>
                            <span class="align-middle">Mi perfil</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="../Controllers/loginController.php?op=logout">
                            <i class="ti ti-logout me-2 ti-sm"></i>
                            <span class="align-middle">Salir</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ USUARIO -->
        </ul>
    </div>
</nav>

<div id="loader-overlay">
    <div class="loader-content">
        <img src="../assets/img/logo.png" alt="Logo de TEA" class="loader-logo" />
        <div class="loader-text">Cargando sistema...</div>
    </div>
</div>


<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script> -->
<script>
    let token = localStorage?.token;
    document.addEventListener("DOMContentLoaded", function() {        
        window.addEventListener('load', () => {
            gsap.to("#loader-overlay", {
                opacity: 0,
                duration: 3,
                delay: 0,
                ease: "power2.out",
                onComplete: () => {
                    document.getElementById('loader-overlay').style.display = "none";
                }
            });
        });
        permisos();

        let branch_office_id = localStorage.getItem("branch_office_id");
        if (branch_office_id) {
            const select = document.getElementById('branch_office_id_selected');
            select.value = branch_office_id;
        }
    });

    function change_branch_office() {
        let branch_office_id = document.getElementById('branch_office_id_selected').value;
        localStorage.setItem("branch_office_id", branch_office_id);
        location.reload();
    }

    function permisos() {
        console.log('permisos')
        let createPerm = document.getElementById("permission_create").value;
        let updatePerm = document.getElementById("permission_update").value;
        let deletePerm = document.getElementById("permission_delete").value;
        let viewPerm = document.getElementById("permission_view").value;
        // console.log(
        //     'crear: ',createPerm, '\n',
        //     'actualizar: ',updatePerm, '\n',
        //     'borrar: ',deletePerm, '\n',
        //     'ver: ',viewPerm, '\n'
        // );

        if (viewPerm == 0) {
            window.location.href = "unauthorized.php"; 
        }

        if (createPerm == 1) {
            document.querySelectorAll(".crear").forEach(boton => {
                boton.style.removeProperty("display");
                boton.classList.remove("d-none");
            });
        } else if (createPerm == 0) {
            document.querySelectorAll(".crear").forEach(boton => {
                boton.style.setProperty("display", "none", "important");
                boton.classList.add("d-none");
            });
            console.log('sin permiso crear')
        }

        if (updatePerm == 1) {
            document.querySelectorAll(".editar").forEach(boton => {
                boton.style.removeProperty("display");
                boton.classList.remove("d-none");
            });
        } else if (updatePerm == 0) {
            console.log('sin permiso editar')
            document.querySelectorAll(".editar").forEach(boton => {
                boton.style.setProperty("display", "none", "important");
                boton.classList.add("d-none");
            });
        }

        if (deletePerm == 1) {
            document.querySelectorAll(".eliminar").forEach(boton => {
                boton.style.removeProperty("display");
                boton.classList.remove("d-none");
            });
        } else if (deletePerm == 0) {
            document.querySelectorAll(".eliminar").forEach(boton => {
                boton.style.setProperty("display", "none", "important");
                boton.classList.add("d-none");
            });
            console.log('sin permiso eliminar')
        }

        
        
    }

    
    
</script>

<style>
    .swal2-container {
        z-index: 9999 !important;
    }

    .container-xxl {
        max-width: 100%;
    }

    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
        background-color: #38b449;
        color: white;
    }

    .select2-container--default .select2-results__option--selected {
        background-color: #38b449;
        color: white;
    }

    #tbllistado {
        /*table-layout: fixed;*/
        /*width: 100%;*/
    }

    #tbllistado th,
    #tbllistado td {
        /*min-width: 750px;*/
        white-space: nowrap;
        /*text-align: center;*/
    }
    
    #loader-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.9); /* Fondo semitransparente para que se vea el logo */
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        flex-direction: column;
    }

    .loader-content {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .loader-logo {
        width: 150px; /* Ajusta el tamaño del logo */
        height: auto;
        animation: pulse-logo 1.5s infinite ease-in-out; /* Animación de pulsación */
    }

    .loader-text {
        font-family: sans-serif;
        font-size: 1.2rem;
        font-weight: bold;
        color: #38b449; /* El color verde de tu logo */
        margin-top: 20px;
    }

    /* Definición de la animación para el logo */
    @keyframes pulse-logo {
        0% {
            transform: scale(0.9);
            opacity: 0.7;
        }
        50% {
            transform: scale(1);
            opacity: 1;
        }
        100% {
            transform: scale(0.9);
            opacity: 0.7;
        }
    }
</style>