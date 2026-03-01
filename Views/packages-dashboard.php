<!doctype html>
<?php $title = "Tracking Paquetes"; ?>
<html lang="es" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr"
    data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template">
<?php require_once('header.php'); ?>
<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php require_once('menu.php'); ?>
            <div class="layout-page">
                <?php require_once('barra_navegacion.php'); ?>
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row g-4">
                            <div class="col-xl-3 col-sm-6">
                                <div class="card card-border-shadow-success">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2"><span class="avatar-initial rounded bg-label-success"><i class="ti ti-route"></i></span></div>
                                            <div>
                                                <h4 class="mb-1" id="countOrigin">0</h4>
                                                <small class="text-muted">Enviados desde mi sucursal</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <div class="card card-border-shadow-primary">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2"><span class="avatar-initial rounded bg-label-primary"><i class="ti ti-building"></i></span></div>
                                            <div>
                                                <h4 class="mb-1" id="countDestination">0</h4>
                                                <small class="text-muted">Llegando a mi sucursal</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-4">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="mb-0">Paquetes</h5>
                                <div class="d-flex gap-2">
                                    <select id="statusFilter" class="form-select">
                                        <option value="">Todos</option>
                                        <option value="CREADO">Creado</option>
                                        <option value="EN_TRANSITO">En tránsito</option>
                                        <option value="EN_DESTINO">En destino</option>
                                        <option value="ENTREGADO">Entregado</option>
                                        <option value="INCIDENCIA">Incidencia</option>
                                    </select>
                                    <button class="btn btn-label-primary" onclick="indexPackages()">
                                        <i class="ti ti-refresh"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-origin" type="button" role="tab" aria-selected="true" onclick="setView('origin')">Salidas</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-destination" type="button" role="tab" aria-selected="false" onclick="setView('destination')">Entradas</button>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="tab-origin" role="tabpanel">
                                        <div class="table-responsive">
                                            <table class="table" id="packagesTable">
                                                <thead>
                                                    <tr>
                                                        <th>Acciones</th>
                                                        <th>#</th>
                                                        <th>Ruta</th>
                                                        <th>Precio</th>
                                                        <th>Estatus</th>
                                                        <th>Operador</th>
                                                        <th>Unidad</th>
                                                        <th>Tracking</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="tab-destination" role="tabpanel">
                                        <div class="table-responsive">
                                            <table class="table" id="packagesTableDestination">
                                                <thead>
                                                    <tr>
                                                        <th>Acciones</th>
                                                        <th>#</th>
                                                        <th>Ruta</th>
                                                        <th>Precio</th>
                                                        <th>Estatus</th>
                                                        <th>Operador</th>
                                                        <th>Unidad</th>
                                                        <th>Tracking</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php require_once('footer.php'); ?>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
        <div class="drag-target"></div>
    </div>

    <div class="modal fade" id="packageModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalle del paquete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="packageDetail"></div>
                    <hr>
                    <h6>Timeline</h6>
                    <ul class="timeline" id="timeline"></ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentView = 'origin';
        const endpoint = '../Controllers/packageDeliveriesController.php';

        $(document).ready(function() {
            initTables();
            indexPackages();
        });

        const initTables = () => {
            window.tableOrigin = $('#packagesTable').DataTable({
                language: { url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json" }
            });
            window.tableDest = $('#packagesTableDestination').DataTable({
                language: { url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json" }
            });
        };

        const setView = (view) => {
            currentView = view;
            indexPackages();
        };

        const indexPackages = () => {
            const branch = document.getElementById('branch_office_id_selected').value;            
            const status = $('#statusFilter').val();
            $.getJSON(`${endpoint}?op=index&branch_office_id=${branch}&view=${currentView}&status=${status}`, function(data) {
                const rows = data.aaData || [];
                  if (currentView === 'origin') {
                    tableOrigin.clear().rows.add(rows).draw();
                    $('#countOrigin').text(rows.length);
                } else {
                    tableDest.clear().rows.add(rows).draw();
                    $('#countDestination').text(rows.length);
                }
            });
        };

        const showPackage = (id) => {
            $.post(`${endpoint}?op=show`, { package_id: id }, function(data) {
                $('#packageDetail').html(`
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Tracking:</strong> ${data.tracking_code}</p>
                            <p><strong>Estatus:</strong> ${data.status}</p>
                            <p><strong>Ruta:</strong> ${data.stop_origin} → ${data.stop_destination}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Unidad:</strong> ${data.unidad_number || 'N/A'}</p>
                            <p><strong>Operador:</strong> ${data.driver_name || 'N/A'}</p>
                            <p><strong>Peso:</strong> ${data.package_weight || 0} kg</p>
                        </div>
                    </div>
                `);
                loadTimeline(id);
                new bootstrap.Modal(document.getElementById('packageModal')).show();
            }, 'json');
        };

        const loadTimeline = (id) => {
            $.getJSON(`${endpoint}?op=timeline&package_id=${id}`, function(events) {
                const list = $('#timeline');
                list.empty();
                events.forEach(event => {
                    list.append(`<li><strong>${event.status}</strong> - ${event.created_at}<br><small>${event.notes || ''}</small></li>`);
                });
            });
        };
    </script>
</body>
</html>
