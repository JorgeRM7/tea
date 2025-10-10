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

              <div class="col-sm-6 col-lg-3 mb-4">
                  <div class="card card-border-shadow-primary">
                    <div class="card-body">
                      <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                          <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-truck ti-md"></i></span>
                        </div>
                        <h4 class="ms-1 mb-0">42</h4>
                      </div>
                      <p class="mb-1">On route vehicles</p>
                      <p class="mb-0">
                        <span class="fw-medium me-1">+18.2%</span>
                        <small class="text-muted">than last week</small>
                      </p>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6 col-lg-3 mb-4">
                  <div class="card card-border-shadow-warning">
                    <div class="card-body">
                      <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                          <span class="avatar-initial rounded bg-label-warning"
                            ><i class="ti ti-alert-triangle ti-md"></i
                          ></span>
                        </div>
                        <h4 class="ms-1 mb-0">8</h4>
                      </div>
                      <p class="mb-1">Vehicles with errors</p>
                      <p class="mb-0">
                        <span class="fw-medium me-1">-8.7%</span>
                        <small class="text-muted">than last week</small>
                      </p>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6 col-lg-3 mb-4">
                  <div class="card card-border-shadow-danger">
                    <div class="card-body">
                      <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                          <span class="avatar-initial rounded bg-label-danger"
                            ><i class="ti ti-git-fork ti-md"></i
                          ></span>
                        </div>
                        <h4 class="ms-1 mb-0">27</h4>
                      </div>
                      <p class="mb-1">Deviated from route</p>
                      <p class="mb-0">
                        <span class="fw-medium me-1">+4.3%</span>
                        <small class="text-muted">than last week</small>
                      </p>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6 col-lg-3 mb-4">
                  <div class="card card-border-shadow-info">
                    <div class="card-body">
                      <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                          <span class="avatar-initial rounded bg-label-info"><i class="ti ti-clock ti-md"></i></span>
                        </div>
                        <h4 class="ms-1 mb-0">13</h4>
                      </div>
                      <p class="mb-1">Late vehicles</p>
                      <p class="mb-0">
                        <span class="fw-medium me-1">-2.5%</span>
                        <small class="text-muted">than last week</small>
                      </p>
                    </div>
                  </div>
                </div>
              <!-- Perfil -->
              <div class="col-md-4">
                <div class="card shadow-sm border-0 text-center h-100">
                  <div class="card-body">
                    <img src="../assets/img/logo.png" class="rounded-circle mb-3" width="90">
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
              <div class="col-lg-6 col-xxl-4 mb-4 order-2 order-xxl-2 mt-3">
                  <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between mb-2">
                      <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Delivery Performance</h5>
                        <small class="text-muted">12% increase in this month</small>
                      </div>
                      <div class="dropdown">
                        <button
                          class="btn p-0"
                          type="button"
                          id="deliveryPerformance"
                          data-bs-toggle="dropdown"
                          aria-haspopup="true"
                          aria-expanded="false">
                          <i class="ti ti-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="deliveryPerformance">
                          <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                          <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                          <a class="dropdown-item" href="javascript:void(0);">Share</a>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <ul class="p-0 m-0">
                        <li class="d-flex mb-4 pb-1">
                          <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-package"></i></span>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <h6 class="mb-0 fw-normal">Packages in transit</h6>
                              <small class="text-success fw-normal d-block">
                                <i class="ti ti-chevron-up mb-1"></i>
                                25.8%
                              </small>
                            </div>
                            <div class="user-progress">
                              <h6 class="mb-0">10k</h6>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                          <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-info"><i class="ti ti-truck"></i></span>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <h6 class="mb-0 fw-normal">Packages out for delivery</h6>
                              <small class="text-success fw-normal d-block">
                                <i class="ti ti-chevron-up mb-1"></i>
                                4.3%
                              </small>
                            </div>
                            <div class="user-progress">
                              <h6 class="mb-0">5k</h6>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                          <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-success"
                              ><i class="ti ti-circle-check"></i
                            ></span>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <h6 class="mb-0 fw-normal">Packages delivered</h6>
                              <small class="text-danger fw-normal d-block">
                                <i class="ti ti-chevron-down mb-1"></i>
                                12.5%
                              </small>
                            </div>
                            <div class="user-progress">
                              <h6 class="mb-0">15k</h6>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                          <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-warning"
                              ><i class="ti ti-percentage"></i
                            ></span>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <h6 class="mb-0 fw-normal">Delivery success rate</h6>
                              <small class="text-success fw-normal d-block">
                                <i class="ti ti-chevron-up mb-1"></i>
                                35.6%
                              </small>
                            </div>
                            <div class="user-progress">
                              <h6 class="mb-0">95%</h6>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                          <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-secondary"><i class="ti ti-clock"></i></span>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <h6 class="mb-0 fw-normal">Average delivery time</h6>
                              <small class="text-danger fw-normal d-block">
                                <i class="ti ti-chevron-down mb-1"></i>
                                2.15%
                              </small>
                            </div>
                            <div class="user-progress">
                              <h6 class="mb-0">2.5 Days</h6>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex">
                          <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-danger"><i class="ti ti-users"></i></span>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <h6 class="mb-0 fw-normal">Customer satisfaction</h6>
                              <small class="text-success fw-normal d-block">
                                <i class="ti ti-chevron-up mb-1"></i>
                                5.7%
                              </small>
                            </div>
                            <div class="user-progress">
                              <h6 class="mb-0">4.5/5</h6>
                            </div>
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-xxl-4 mb-4 order-1 order-xxl-3 mt-3">
                  <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                      <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Reasons for delivery exceptions</h5>
                      </div>
                      <div class="dropdown">
                        <button
                          class="btn p-0"
                          type="button"
                          id="deliveryExceptions"
                          data-bs-toggle="dropdown"
                          aria-haspopup="true"
                          aria-expanded="false">
                          <i class="ti ti-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="deliveryExceptions">
                          <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                          <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                          <a class="dropdown-item" href="javascript:void(0);">Share</a>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <div id="deliveryExceptionsChart" class="pt-md-4"></div>
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