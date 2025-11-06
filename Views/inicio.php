<!doctype html>
<?php
$title = "Inicio"; ?>
<html lang="es" class="dark-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template">

<!--INICIO HEADER-->
<?php require_once('header.php'); ?>
<style>
.cart-static {
    background: linear-gradient(135deg, #0d1a1e, #0a4d0e);
    color: #fff;
    padding: 2.5rem 1rem;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
    animation: fadeIn 1.2s ease-in-out;
  }

  .cart-static i {
    font-size: 5rem;
    color: #38b449;
    text-shadow: 0 0 12px rgba(56, 180, 73, 0.6);
    animation: pulse 2s ease-in-out infinite;
  }

  .cart-static h2 {
    font-weight: 700;
    color: #f2a71e;
    margin-top: 10px;
  }

  .cart-static p {
    font-size: 1.1rem;
    color: #ccc;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }

  @keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.05); opacity: 0.85; }
  }
</style>

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
  <div class="row justify-content-center text-center my-5">
    <div class="col-md-6">
      <div class="cart-static">
        <i class="bi bi-cart-check-fill"></i>
        <h2 class="mt-3">Sistema de Venta de Boletos</h2>
        <p class="text-muted">Listo para procesar tus ventas de manera rápida y segura</p>
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
  document.addEventListener("DOMContentLoaded", function() {
    // Mostrar la pantalla 2 segundos antes de pasar al contenido
    setTimeout(() => {
      // document.getElementById("loadingScreen").style.display = "none";
      document.getElementById("mainContent").style.display = "block";
    }, 2000);
  });
</script>