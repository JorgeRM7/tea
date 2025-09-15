<nav class="layout-navbar navbar navbar-expand-md navbar-detached align-items-center bg-navbar-theme p-3" id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="ti ti-menu-2 ti-sm"></i>
        </a>
    </div>
    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            

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
                        <!-- <img src="https://nominas.grupo-ortiz.site/Librerias/img/Fotos/<?= $_SESSION['nombre'] ?>.jpg" alt class="h-auto rounded-circle" style="width: 30px; height: 30px; object-fit: cover;"/> -->
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <!-- <img src="https://nominas.grupo-ortiz.site/Librerias/img/Fotos/<?= $_SESSION['nombre'] ?>.jpg" alt class="h-auto rounded-circle" style="width: 30px; height: 30px; object-fit: cover;"/> -->
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
        <img src="https://tea.systemsorion.com/Librerias/img/logo" alt="Logo de TEA" class="loader-logo" />
        <div class="loader-text">Cargando sistema...</div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script>
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
    });


    
</script>

<style>
    .swal2-container {
        z-index: 9999 !important;
    }

    .container-xxl {
        max-width: 100%;
    }

    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
        background-color: #f07d42;
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