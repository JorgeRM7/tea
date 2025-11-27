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
                                <div class="col-md-12">
                                    <div class="card shadow-sm border-0 h-100">
                                        <div class="card-body">
                                            <h5 class="fw-bold mb-3"><i class="ti ti-bell"></i> Filtros</h5>
                                            <div class="row g-3 mb-3">
                                                <div class="col-md-4">
                                                    <label class="form-label section-title">Desde</label>
                                                    <input id="start_date" name="start_date" type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" onchange="index()"/>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label section-title">Hasta</label>
                                                    <input id="end_date" name="end_date" type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" onchange="index()"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-6 mt-2">
                                    <div class="card card-border-shadow-primary">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                <div class="avatar me-2">
                                                    <span class="avatar-initial rounded bg-label-primary">
                                                        <i class="ti ti-coin ti-md"></i>
                                                    </span>
                                                </div>
                                                <h4 class="ms-1 mb-0" id="kpi_total_sales">0</h4>
                                            </div>
                                            <p class="mb-1">Total de ventas</p>  
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-6 mt-2">
                                    <div class="card card-border-shadow-primary">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                <div class="avatar me-2">
                                                    <span class="avatar-initial rounded bg-label-primary">
                                                        <i class="ti ti-ticket ti-md"></i>
                                                    </span>
                                                </div>
                                                <h4 class="ms-1 mb-0" id="kpi_total_tickets"></h4>
                                            </div>
                                            <p class="mb-1">Total de boletos vendidos</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2" id="salesByRoute"></div>
                            <div class="row" id="salesByBranchOffice"></div>
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
        let module = $("#module").val();
        const menuItem = document.querySelector('a[href="dashboard-general.php"]').parentElement;
        menuItem.classList.add('active');
        const menuToggle = document.querySelector(`a[href="${module}"]`).parentElement;
        menuToggle.classList.add('open');
        index()
    });

    const index = () => {
        let start_date = $("#start_date").val();
        let end_date   = $("#end_date").val();

        $.ajax({
            url: '../Controllers/dashboardGeneralController.php?op=index',
            type: 'GET',
            headers: {
                "Authorization": "Bearer " + token
            },
            dataType: 'json',
            data: { start_date: start_date, end_date: end_date },
            success: function(data) {

                console.log(data)

                if (data.kpis && data.kpis.length > 0) {
                    let kpi = data.kpis[0];
                    $("#kpi_total_tickets").text(kpi.total_tickets);
                    $("#kpi_total_sales").text(`${parseInt(kpi.total_sales).toLocaleString()}`);
                }

                $("#salesByBranchOffice").empty();

                if (data.sales_by_branch_office && data.sales_by_branch_office.length > 0) {

                    let listItems = "";

                    data.sales_by_branch_office.forEach(item => {
                        listItems += `
                        <li class="d-flex mb-3 align-items-center">
                            <div class="avatar flex-shrink-0 me-2">
                                <span class="rounded-circle bg-success text-white p-2 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-ticket"></i>
                                </span>
                            </div>
                            <div class="w-100 d-flex justify-content-between align-items-center">
                                <p class="mb-0 fw-medium">${item.branch_office}</p>
                                <span class="badge bg-label-success text-success">$${parseInt(item.total_sales).toLocaleString()}.00</span>
                            </div>
                        </li>`;
                    });

                    $("#salesByBranchOffice").append(`
                        <div class="col-md-4 col-xl-4 mb-4">
                            <div class="card card-ticket shadow-sm border-0 h-100">
                                <div class="card-header text-center">
                                    <h5 class="card-title m-0">Ventas por sucursal</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled mb-0">
                                        ${listItems}
                                    </ul>
                                </div>

                            </div>
                        </div>
                    `);
                } else {
                    $("#salesByBranchOffice").append(`
                        <div class="col-12 text-center mt-3">
                            <p class="text-muted fw-bold">Sin ventas registradas</p>
                        </div>
                    `);
                }

                if (data.sales_by_date && data.sales_by_date.length > 0) {
                    let listItems = "";
                    data.sales_by_date.forEach(item => {
                        listItems += `
                        <li class="d-flex mb-3 align-items-center">
                            <div class="avatar flex-shrink-0 me-2">
                                <span class="rounded-circle bg-info text-white p-2 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-calendar-due"></i>
                                </span>
                            </div>
                            <div class="w-100 d-flex justify-content-between align-items-center">
                                <p class="mb-0 fw-medium">${item.date}</p>
                                <span class="badge bg-label-info text-info">$${parseInt(item.total_sales).toLocaleString()}.00</span>
                            </div>
                        </li>`;
                    });

                    $("#salesByBranchOffice").append(`
                        <div class="col-md-4 col-xl-4 mb-4">
                            <div class="card card-ticket shadow-sm border-0 h-100">
                                <div class="card-header text-center">
                                    <h5 class="card-title m-0">Ventas por dia</h5>
                                </div>

                                <div class="card-body">
                                    <ul class="list-unstyled mb-0">
                                        ${listItems}
                                    </ul>
                                </div>

                            </div>
                        </div>
                    `);
                } 

                if (data.sales_by_route && data.sales_by_route.length > 0) {

                    let listItems = "";

                    data.sales_by_route.forEach(item => {
                        listItems += `
                        <li class="d-flex mb-3 align-items-center">
                            <div class="avatar flex-shrink-0 me-2">
                                <span class="rounded-circle bg-warning text-white p-2 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-road"></i>
                                </span>
                            </div>
                            <div class="w-100 d-flex justify-content-between align-items-center">
                                <p class="mb-0 fw-medium">${item.route}</p>
                                <span class="badge bg-label-warning text-warning">$${parseInt(item.total_sales).toLocaleString()}.00</span>
                            </div>
                        </li>`;
                    });

                    $("#salesByBranchOffice").append(`
                        <div class="col-md-4 col-xl-4 mb-4">
                            <div class="card card-ticket shadow-sm border-0 h-100">
                                <div class="card-header text-center">
                                    <h5 class="card-title m-0">Ventas por ruta</h5>
                                </div>

                                <div class="card-body">
                                    <ul class="list-unstyled mb-0">
                                        ${listItems}
                                    </ul>
                                </div>

                            </div>
                        </div>
                    `);
                } 

                if (data.sales_by_users && data.sales_by_users.length > 0) {

                    let listItems = "";

                    data.sales_by_users.forEach(item => {
                        listItems += `
                        <li class="d-flex mb-3 align-items-center">
                            <div class="avatar flex-shrink-0 me-2">
                                <span class="rounded-circle bg-danger text-white p-2 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-user"></i>
                                </span>
                            </div>
                            <div class="w-100 d-flex justify-content-between align-items-center">
                                <p class="mb-0 fw-medium">${item.user}</p>
                                <span class="badge bg-label-danger text-danger">$${parseInt(item.total_sales).toLocaleString()}.00</span>
                            </div>
                        </li>`;
                    });

                    $("#salesByBranchOffice").append(`
                        <div class="col-md-4 col-xl-4 mb-4">
                            <div class="card card-ticket shadow-sm border-0 h-100">
                                <div class="card-header text-center">
                                    <h5 class="card-title m-0">Ventas por usuarios</h5>
                                </div>

                                <div class="card-body">
                                    <ul class="list-unstyled mb-0">
                                        ${listItems}
                                    </ul>
                                </div>

                            </div>
                        </div>
                    `);
                } 

            },
            error: function(e) {
                console.error("Error cargando horarios:", e.responseText);
            }
        });
    };

</script>