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
          <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
    <!-- Perfil -->
              <div class="col-md-4">
                <div class="card shadow-sm border-0 text-center h-100">
                  <div class="card-body">
                    <img src="https://tea.systemsorion.com/Librerias/img/logo" class="rounded-circle mb-3" width="90">
                    <h5 class="fw-bold"><?php echo $_SESSION['name']  ?></h5>
                    <p class="text-muted mb-1">Administrador</p>
                    <small class="text-muted">Último acceso: <?php echo date("d/m/Y h:i a"); ?></small>
                  </div>
                </div>
              </div>

              <!-- Noticias -->
              <div class="col-md-8">
                <div class="card shadow-sm border-0 h-100">
                  <div class="card-body">
                    <h5 class="fw-bold mb-3"><i class="ti ti-bell"></i> Noticias</h5>
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item">📢 El sistema tendrá mantenimiento este sábado a las 10 p.m.</li>
                      <li class="list-group-item">🚍 Nueva ruta: Morelia → Pátzcuaro disponible desde hoy.</li>
                      <li class="list-group-item">🧾 Recuerda revisar tu historial de boletos semanalmente.</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
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
  $(document).ready(function () {
    
      const menuItem = document.querySelector('a[href="inicio.php"]').parentElement;
        menuItem.classList.add('active');
        console.log(menuItem)
  });
</script>