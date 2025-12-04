<?php
$basePath = dirname(__DIR__);
$config = include $basePath . "/Config/config.php";
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Transporte Ejecutivo Tea</title>
<link rel="icon" type="image/x-icon" href="../assets/img/logo.png" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { font-family: 'Public Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; color: #0d1a1e; scroll-behavior: smooth; }
.hero { min-height: 100vh; background: radial-gradient(circle at top, rgba(13, 26, 30, 0.85), rgba(10, 77, 14, 0.95)), url("../assets/img/unidades.png") center/cover; color: #fff; }
.badge-soft { background: rgba(56, 180, 73, 0.15); color: #38b449; }
.feature-card { border: 0; border-radius: 18px; box-shadow: 0 15px 35px rgba(13, 26, 30, 0.08); transition: transform 0.25s ease; }
.feature-card:hover { transform: translateY(-6px); }
.steps-line { position: relative; }
.steps-line::before { content: ""; position: absolute; top: 1.5rem; left: 10px; width: 4px; height: calc(100% - 3rem); background: linear-gradient(180deg, #38b449, #f2a71e); border-radius: 999px; }
.rent-banner { border-radius: 24px; background: linear-gradient(120deg, #0a4d0e, #38b449); color: #fff; }
footer { background: #0d1a1e; }
</style>
</head>
<body>
<header class="hero d-flex flex-column justify-content-center">
<div class="container text-center">
<img src="../assets/img/logo.png" alt="TEA" width="90" class="mb-4">
<span class="badge badge-soft px-3 py-2 mb-3">Servicio integral de transporte</span>
<h1 class="display-4 fw-bold mb-3">Compra tus boletos o renta unidades para viajes especiales</h1>
<p class="lead text-white-50 mb-4">Conectamos México con rutas confiables.</p>
<div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
<a href="sales-online-stripe.php" class="btn btn-success btn-lg px-4"><i class="bi bi-ticket-perforated me-2"></i>Comprar boleto en línea</a>
<a href="#renta" class="btn btn-outline-light btn-lg px-4"><i class="bi bi-bus-front me-2"></i>Planes de renta</a>
</div>
</div>
</header>
<main>
<section class="py-5 bg-light">
<div class="container">
<div class="row g-4">
<div class="col-md-4"><div class="card feature-card h-100 p-4"><div class="icon mb-3 text-success fs-1"><i class="bi bi-shield-check"></i></div><h5 class="fw-bold">Seguro y puntual</h5><p class="text-secondary">Operadores certificados y monitoreo continuo.</p></div></div>
<div class="col-md-4"><div class="card feature-card h-100 p-4"><div class="icon mb-3 text-warning fs-1"><i class="bi bi-credit-card-2-front"></i></div><h5 class="fw-bold">Compra en línea</h5><p class="text-secondary">Reserva desde cualquier dispositivo y paga con Mercado Pago.</p></div></div>
<div class="col-md-4"><div class="card feature-card h-100 p-4"><div class="icon mb-3 text-primary fs-1"><i class="bi bi-people"></i></div><h5 class="fw-bold">Viajes privados</h5><p class="text-secondary">Planes corporativos y turísticos bajo demanda.</p></div></div>
</div>
</div>
</section>
<section class="py-5">
<div class="container">
<div class="row align-items-center gy-4">
<div class="col-lg-5">
<span class="text-uppercase text-success fw-semibold">¿Cómo funciona?</span>
<h2 class="fw-bold mb-4">Compra tu boleto en 3 pasos</h2>
<div class="steps-line ps-4">
<div class="mb-4"><h5 class="fw-bold">1. Elige ruta</h5><p class="text-secondary mb-0">Origen, destino y fecha.</p></div>
<div class="mb-4"><h5 class="fw-bold">2. Paga en línea</h5><p class="text-secondary mb-0">Mercado Pago Checkout Pro.</p></div>
<div><h5 class="fw-bold">3. Viaja con tu QR</h5><p class="text-secondary mb-0">Recibe confirmación inmediata.</p></div>
</div>
</div>
<div class="col-lg-7">
<div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-lg">
<img src="../assets/img/flujo.png" alt="Compra tu boleto" class="w-100 h-100 object-fit-cover"> 
</div>
</div>
</div>
</div>
</section>
<section id="renta" class="py-5 bg-light">
<div class="container">
<div class="row gy-4 align-items-center">
<div class="col-lg-6">
<div class="rent-banner p-5 h-100">
<span class="text-uppercase fw-semibold text-warning">Renta corporativa y turística</span>
<h2 class="fw-bold mt-3 mb-4">Unidades listas para tu evento</h2>
<p class="mb-4">Combis, vans y autobuses equipados con A/C, rastreo satelital y seguro.</p>
<ul class="list-unstyled mb-4">
<li class="mb-2"><i class="bi bi-check2-circle me-2"></i>Traslados empresariales</li>
<li class="mb-2"><i class="bi bi-check2-circle me-2"></i>Tours personalizados</li>
<li><i class="bi bi-check2-circle me-2"></i>Soporte 24/7</li>
</ul>
<a href="https://wa.me/5215555555555" target="_blank" class="btn btn-light btn-lg text-success fw-bold"><i class="bi bi-whatsapp me-2"></i>Solicitar cotización</a>
</div>
</div>
<div class="col-lg-6">
<div class="card shadow-lg border-0 p-4 h-100">
<h4 class="fw-bold mb-3">Cuéntanos tu viaje</h4>
<p class="text-secondary mb-4">Déjanos tus datos y un asesor te contactará.</p>
<form>
<div class="mb-3"><label class="form-label">Nombre completo</label><input type="text" class="form-control" placeholder="Tu nombre"></div>
<div class="mb-3"><label class="form-label">Correo electrónico</label><input type="email" class="form-control" placeholder="ejemplo@correo.com"></div>
<div class="mb-3"><label class="form-label">Fecha</label><input type="date" class="form-control"></div>
<div class="mb-3"><label class="form-label">Detalle</label><textarea rows="3" class="form-control" placeholder="Origen, destino, pasajeros..."></textarea></div>
<button type="button" class="btn btn-success w-100">Enviar solicitud</button>
</form>
<small class="text-muted d-block mt-3">* Próximamente habilitaremos el envío automático.</small>
</div>
</div>
</div>
</div>
</section>
<section class="py-5">
<div class="container text-center">
<p class="text-uppercase text-success fw-semibold mb-1">Consulta horarios y rutas</p>
<h2 class="fw-bold mb-3">¿Listo para viajar?</h2>
<p class="mb-4 text-secondary">Explora la disponibilidad y compra tu boleto ahora mismo.</p>
<a href="sales-online-stripe.php" class="btn btn-dark btn-lg px-5">Ver horarios y comprar</a>
</div>
</section>
</main>
<footer class="py-4 text-white">
<div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
<p class="mb-2 mb-md-0">&copy; <?php echo date('Y'); ?> Transportes Ejecutivos Tea</p>
<div class="d-flex gap-4">
<a href="mailto:ventas@tea.com" class="text-white text-decoration-none"><i class="bi bi-envelope me-1"></i>ventas@tea.com</a>
<a href="tel:+524431234567" class="text-white text-decoration-none"><i class="bi bi-telephone me-1"></i>4444444444</a>
<a href="login.php" class="text-white text-decoration-none"><i class="bi bi-person-lock me-1"></i>Iniciar sesión</a>
</div>
</div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
