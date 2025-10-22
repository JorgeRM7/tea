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
                                                    <label class="form-label section-title">Ruta</label>
                                                    <select id="search_route" name="search_route" class="form-select">
                                                        <?php 
                                                            $sql = "SELECT * FROM `routes` WHERE deleted_at is null";
                                                            $query = ejecutarConsulta($sql);
                                                            while($valores = mysqli_fetch_array($query)){
                                                                echo "<option value='".$valores['id']."'>".$valores['origin']." - ".$valores['destination']."</option>";
                                                            }
                                                        ?>

                                                    </select>
                                                    <div id="routeRules" class="rule-note mt-1"></div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label section-title">Fecha</label>
                                                    <input id="search_date" name="search_date" type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>"/>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label section-title">Fecha</label>
                                                    <input id="search_date" name="search_date" type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>"/>
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
                                <div class="col-sm-6 col-lg-6">
                                    <div class="card mt-4">
                                        <div class="card-header bg-white">
                                            <h5 class="mb-0"><i class="bi bi-graph-up-arrow me-1 text-primary"></i> Ventas por Fecha</h5>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="salesByDateChart" height="100%"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2" id="salesByRoute"></div>
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
        const menuItem = document.querySelector('a[href="dashboard-general.php"]').parentElement;
        menuItem.classList.add('active');
        const menuToggle = document.querySelector('a[href="DASHBOARD"]').parentElement;
        menuToggle.classList.add('open');
        index()
    });

    const index = () => {
        // let route_id = $("#search_route").val();
        // let date     = $("#search_date").val();

        $.ajax({
            url: '../Controllers/dashboardGeneralController.php?op=index',
            type: 'GET',
            headers: {
                "Authorization": "Bearer " + token
            },
            dataType: 'json',
            // data: { route_id: route_id, date: date },
            success: function(data) {

                console.log(data)

                if (data.kpis && data.kpis.length > 0) {
                    let kpi = data.kpis[0];
                    $("#kpi_total_tickets").text(kpi.total_tickets);
                    $("#kpi_total_sales").text(`${parseInt(kpi.total_sales).toLocaleString()}`);
                }

                $("#salesByRoute").empty();
                if (data.sales_by_route && data.sales_by_route.length > 0) {
                    data.sales_by_route.forEach(item => {
                        let card = `
                            <div class="col-md-4 mb-3">
                                <div class="card shadow-sm p-3 h-100">
                                    <h6 class="text-muted">${item.route}</h6>
                                    <h4 class="fw-bold text-success">$${parseInt(item.total_sales).toLocaleString()}</h4>
                                </div>
                            </div>
                        `;
                        $("#salesByRoute").append(card);
                    });
                }

                if (data.sales_by_date) {
                    renderSalesByDateChart(data.sales_by_date);
                }
                
            },
            error: function(e) {
                console.error("Error cargando horarios:", e.responseText);
            }
        });
    };


    function renderSalesByDateChart(salesByDate) {
        // Preparamos los datos
        const labels = salesByDate.map(item => item.date);
        const values = salesByDate.map(item => parseInt(item.total_sales));

        const ctx = document.getElementById('salesByDateChart').getContext('2d');

        new Chart(ctx, {
            type: 'line', // puedes cambiar a 'bar' o 'area' (con fill:true)
            data: {
                labels: labels,
                datasets: [{
                    label: 'Ventas ($)',
                    data: values,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.2)',
                    borderWidth: 3,
                    tension: 0.3, // suaviza las líneas
                    fill: true,
                    pointBackgroundColor: '#007bff',
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '$' + context.raw.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: '#6c757d' }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => '$' + value.toLocaleString(),
                            color: '#6c757d'
                        }
                    }
                }
            }
        });
    }
</script>