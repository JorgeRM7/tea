<?php
// require "../config/Conexion.php";
// require_once "../Controllers/token.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}


?>

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">

        <a href="" class="app-brand-link">
            <center><img src="https://tea.systemsorion.com/Librerias/img/logo" alt="TEA" width="50"
                    height="50" style="padding:2px;"></center>
            <span class="app-brand-text demo menu-text fw-bold">TEA</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">
        <li class="menu-item">
            <a href="inicio.php" class="menu-link">
                <i class="menu-icon tf-icons ti ti-home"></i>
                <div data-i18n="Inicio">Inicio</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="admin" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-settings"></i>
                <div data-i18n="Administración">Administración</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="admin-employees.php" class="menu-link">
                        <div data-i18n="Empleados">Empleados</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="admin-vehicles.php" class="menu-link">
                        <div data-i18n="Vehiculos">Vehiculos</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="admin-routes.php" class="menu-link">
                        <div data-i18n="Rutas">Rutas</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="admin-users.php" class="menu-link">
                        <div data-i18n="Usuarios">Usuarios</div>
                    </a>
                </li>
            </ul>
        </li>

    </ul>

    
</aside>