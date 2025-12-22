<!doctype html>
<?php
$title = "Inicio"; ?>
<html lang="es" class="dark-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template">

<!--INICIO HEADER-->
<?php require_once('header.php'); ?>
<!--FIN HEADER-->

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
                        <!-- INICIO CONTENIDO -->
                        <div class="container-xxl flex-grow-1 container-p-y"></div>
                        <!-- FIN CONTENIDO -->

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
  let token = localStorage.getItem("jwt_token");
  $(document).ready(function () {
    const menuItem = document.querySelector('a[href="inicio.php"]').parentElement;
    menuItem.classList.add('active');
  });
</script>