<!doctype html>
<?php $title = "Escáner Paquetes"; ?>
<html lang="es" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr"
    data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template">
<?php require_once('header.php'); ?>
<body>
    <style>
        .scanner-container #qr-reader {
            border: 1px dashed #cbd5e1;
            border-radius: 12px;
            background-color: #f8f9fb;
        }

        .timeline {
            list-style: none;
            padding-left: 0;
            margin-bottom: 0;
        }

        .timeline li {
            position: relative;
            padding-left: 28px;
            margin-bottom: 18px;
        }

        .timeline li::before {
            content: "";
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #16a34a;
            position: absolute;
            left: 0;
            top: 8px;
        }

        .timeline img {
            max-width: 180px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
        }

        .detail-placeholder {
            color: #94a3b8;
        }
    </style>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php require_once('menu.php'); ?>
            <div class="layout-page">
                <?php require_once('barra_navegacion.php'); ?>
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card scanner-container">
                                    <div class="card-body text-center">
                                        <h4 class="scanner-header">&#128230; Escanear Paquete</h4>
                                        <p class="text-muted mb-3">Pulsa el bot&oacute;n para abrir la c&aacute;mara o ingresa el c&oacute;digo manualmente.</p>
                                        <div class="mt-3">
                                            <input type="text" class="form-control" id="manual_code" placeholder="Escribe c&oacute;digo manual">
                                            <button class="btn btn-primary w-100 mt-2"
                                                onclick="fetchPackage(document.getElementById('manual_code').value)">
                                                Buscar manual
                                            </button>
                                        </div>
                                        <button class="btn btn-primary w-100 mt-3" id="startScanBtn">
                                            <i class="ti ti-qrcode"></i> Escanear QR
                                        </button>
                                        <div id="qr-reader" class="d-none mt-3" style="width:100%; min-height:320px;"></div>
                                        <p class="scanner-instructions mt-3"><i class="ti ti-info-circle"></i> Usa la c&aacute;mara trasera del dispositivo y aseg&uacute;rate de tener buena iluminaci&oacute;n.</p>
                                        <button class="btn btn-label-secondary w-100 mt-2 d-none" id="btnRestart">Escanear otro</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Actualizar estatus</h5>
                                        <span class="badge bg-label-primary" id="statusBadge">Sin escanear</span>
                                    </div>
                                    <div class="card-body">
                                        <form id="statusForm">
                                            <input type="hidden" id="package_id" name="package_id">
                                            <div class="mb-3">
                                                <label class="form-label">Tracking</label>
                                                <input type="text" class="form-control" id="tracking_display" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nuevo estatus</label>
                                                <select class="form-select" id="status" name="status">
                                                    <option value="">Selecciona...</option>
                                                    <option value="EN_TRANSITO">En tránsito</option>
                                                    <option value="EN_DESTINO">En destino</option>
                                                    <option value="ENTREGADO">Entregado</option>
                                                    <option value="INCIDENCIA">Incidencia</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Notas</label>
                                                <textarea class="form-control" id="notes" name="notes"></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Foto evidencia</label>
                                                <input type="file" class="form-control" id="photo" name="photo" accept="image/*" capture="environment">
                                            </div>
                                            <button type="button" class="btn btn-success w-100" onclick="updateStatus()">Actualizar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Detalle y movimientos</h5>
                                        <span class="badge bg-label-secondary" id="detailTracking">Escanea un paquete</span>
                                    </div>
                                    <div class="card-body">
                                        <div id="detailPlaceholder" class="text-center detail-placeholder py-4">
                                            Escanea o busca un paquete para ver su informaci&oacute;n, movimientos y evidencias.
                                        </div>
                                        <div id="packageDetailContent" class="d-none">
                                            <div class="row g-4 mb-3" id="packageSummary"></div>
                                            <hr>
                                            <h6 class="mb-3">Historial de movimientos y evidencias</h6>
                                            <ul class="timeline" id="packageTimeline"></ul>
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

    <script>
        let html5QrCode;
        let scannerActive = false;
        let currentPackage = null;
        const endpoint = '../Controllers/packageDeliveriesController.php';

        $(document).ready(function () {
            resetPackageDetail();
            $('#startScanBtn').on('click', function () {
                startScanner();
            });
            $('#btnRestart').on('click', function () {
                startScanner();
            });
        });

        function startScanner() {
            if (scannerActive) return;

            if (!html5QrCode) {
                html5QrCode = new Html5Qrcode('qr-reader');
            }

            $('#qr-reader').removeClass('d-none');
            $('#startScanBtn').prop('disabled', true).text('Abriendo c&aacute;mara...');
            $('#btnRestart').addClass('d-none');

            html5QrCode.start({ facingMode: 'environment' }, { fps: 10, qrbox: 250 }, onScanSuccess, onScanFailure)
                .then(() => {
                    scannerActive = true;
                    $('#startScanBtn').text('Escaneando...');
                })
                .catch(error => {
                    console.error(error);
                    $('#startScanBtn').prop('disabled', false).text('Escanear QR');
                    Swal.fire('Error', 'No pudimos iniciar la c&aacute;mara. Verifica permisos.', 'error');
                });
        }

        function stopScanner() {
            if (html5QrCode && scannerActive) {
                return html5QrCode.stop().then(() => {
                    scannerActive = false;
                    $('#startScanBtn').prop('disabled', false).text('Escanear QR');
                    $('#qr-reader').addClass('d-none');
                }).catch(error => {
                    console.error(error);
                });
            }
            return Promise.resolve();
        }

        function onScanSuccess(decodedText) {
            if (!decodedText) return;
            stopScanner().then(() => {
                $('#btnRestart').removeClass('d-none');
            });
            fetchPackage(decodedText);
        }

        function onScanFailure(error) {
            console.warn(error);
        }

        function fetchPackage(decodedText) {
            let encryptedData = null;
            let trackingCode = null;

            if (decodedText && decodedText.includes('data=')) {
                const url = new URL(decodedText);
                encryptedData = url.searchParams.get('data');
            } else {
                trackingCode = decodedText;
            }

            $.post(`${endpoint}?op=scan`, {
                encrypted: encryptedData,
                tracking_code: trackingCode
            }, function(response) {
                if (!response.success) {
                    resetPackageDetail();
                    Swal.fire('Error', response.message || 'No se encontr&oacute; el paquete', 'error');
                    return;
                }

                currentPackage = response.package;
                $('#package_id').val(response.package.id);
                $('#tracking_display').val(response.package.tracking_code);
                $('#statusBadge').text(response.package.status || 'Sin estatus');

                renderPackageDetail(currentPackage);
                loadTimeline(response.package.id);

                Swal.fire('Listo', 'Escaneo correcto, actualiza el estatus.', 'success');
            }, 'json');
        }

        function updateStatus() {
            const form = document.getElementById('statusForm');
            const formData = new FormData(form);
            formData.append('branch_office_id', document.getElementById('branch_office_id_selected').value);
            console.log('Updating status with data:', Object.fromEntries(formData.entries()));
            $.ajax({
                url: `${endpoint}?op=update-status`,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Actualizado', 'Se guard&oacute; el avance del paquete', 'success');
                        const newStatus = $('#status').val();
                        $('#statusBadge').text(newStatus || 'Sin estatus');
                        $('#notes').val('');
                        const photoInput = document.getElementById('photo');
                        if (photoInput) {
                            photoInput.value = '';
                        }
                        reloadCurrentPackage();
                        loadTimeline($('#package_id').val());
                    } else {
                        Swal.fire('Error', response.message || 'No se pudo actualizar', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'No se pudo actualizar', 'error');
                }
            });
        }

        function reloadCurrentPackage() {
            const packageId = $('#package_id').val();
            if (!packageId) return;

            $.post(`${endpoint}?op=show`, { package_id: packageId }, function(response) {
                if (!response) return;
                currentPackage = response;
                $('#tracking_display').val(response.tracking_code || '');
                $('#statusBadge').text(response.status || 'Sin estatus');
                renderPackageDetail(response);
            }, 'json');
        }

        function renderPackageDetail(pkg) {
            if (!pkg) {
                resetPackageDetail();
                return;
            }

            $('#detailPlaceholder').addClass('d-none');
            $('#packageDetailContent').removeClass('d-none');
            $('#detailTracking').text(pkg.tracking_code ? pkg.tracking_code : 'Sin tracking');

            const routeFrom = pkg.stop_origin || pkg.route_origin || 'N/D';
            const routeTo = pkg.stop_destination || pkg.route_destination || 'N/D';

            const summaryHtml = `
                <div class="col-md-3 col-sm-6">
                    <small class="text-muted d-block">Tracking</small>
                    <div class="fw-semibold">${escapeHtml(pkg.tracking_code || 'N/D')}</div>
                    <span class="badge bg-label-primary mt-1">${escapeHtml(pkg.status || 'Sin estatus')}</span>
                </div>
                <div class="col-md-3 col-sm-6">
                    <small class="text-muted d-block">Ruta</small>
                    <div class="fw-semibold">${escapeHtml(routeFrom)} &rarr; ${escapeHtml(routeTo)}</div>
                    <small class="text-muted">${pkg.unidad_number ? 'Unidad ' + escapeHtml(pkg.unidad_number) : ''}</small>
                </div>
                <div class="col-md-3 col-sm-6">
                    <small class="text-muted d-block">Salida programada</small>
                    <div class="fw-semibold" id="scheduledDepartureValue">Sin programar</div>
                    <small class="text-muted" id="scheduledDepartureNote"></small>
                </div>
                <div class="col-md-3 col-sm-6">
                    <small class="text-muted d-block">Remitente / Destinatario</small>
                    <div class="fw-semibold">${escapeHtml(pkg.sender_name || 'N/D')}</div>
                    <small class="text-muted">${escapeHtml(pkg.receiver_name || '')}</small>
                </div>
                <div class="col-md-3 col-sm-6">
                    <small class="text-muted d-block">&Uacute;ltima actualizaci&oacute;n</small>
                    <div class="fw-semibold">${escapeHtml(pkg.status_changed_at || pkg.updated_at || 'N/D')}</div>
                    <small class="text-muted">${pkg.driver_name ? 'Operador: ' + escapeHtml(pkg.driver_name) : ''}</small>
                </div>
            `;

            $('#packageSummary').html(summaryHtml);
            loadScheduledDeparture(pkg.route_schedule_id);
        }

        function loadScheduledDeparture(routeScheduleId) {
            const valueEl = $('#scheduledDepartureValue');
            const noteEl = $('#scheduledDepartureNote');

            if (!valueEl.length) {
                return;
            }

            if (!routeScheduleId) {
                valueEl.text('Sin programar');
                noteEl.text('');
                return;
            }

            valueEl.text('Consultando...');
            noteEl.text('');

            const scheduleId = encodeURIComponent(routeScheduleId);

            $.getJSON(`${endpoint}?op=schedule-details&route_schedule_id=${scheduleId}`, function(schedule) {
                if (!schedule) {
                    valueEl.text('Sin datos');
                    return;
                }

                const dateText = schedule.date ? schedule.date : '';
                const timeText = schedule.leaving_time ? schedule.leaving_time : '';
                const label = [dateText, timeText].filter(Boolean).join(' ');

                valueEl.text(label || 'Sin datos');
                noteEl.text(schedule.unidad_number ? `Unidad programada ${schedule.unidad_number}` : '');
            }).fail(function() {
                valueEl.text('Sin datos');
                noteEl.text('');
            });
        }

        function loadTimeline(id) {
            if (!id) {
                $('#packageTimeline').empty();
                return;
            }

            const list = $('#packageTimeline');
            list.html('<li><small class="text-muted">Cargando movimientos...</small></li>');

            $.getJSON(`${endpoint}?op=timeline&package_id=${id}`, function(events) {
                list.empty();

                if (!events || !events.length) {
                    list.append('<li><small class="text-muted">A&uacute;n no hay movimientos registrados.</small></li>');
                    return;
                }

                events.forEach(event => {
                    const photo = event.photo_path ? `
                        <div class="mt-2">
                            <a href="../${escapeHtml(event.photo_path)}" target="_blank" rel="noopener">
                                <img src="../${escapeHtml(event.photo_path)}" alt="Evidencia del paquete">
                            </a>
                        </div>` : '';

                    list.append(`
                        <li>
                            <div class="d-flex justify-content-between flex-wrap">
                                <strong>${escapeHtml(event.status || '')}</strong>
                                <small class="text-muted">${escapeHtml(event.created_at || '')}</small>
                            </div>
                            <small class="text-muted">${escapeHtml(event.branch_name || '')}</small>
                            <p class="mb-1">${escapeHtml(event.notes || '')}</p>
                            ${photo}
                        </li>
                    `);
                });
            }).fail(function() {
                list.html('<li><small class="text-danger">No se pudo cargar el historial.</small></li>');
            });
        }

        function resetPackageDetail() {
            currentPackage = null;
            $('#detailTracking').text('Escanea un paquete');
            $('#packageSummary').empty();
            $('#packageTimeline').empty();
            $('#packageDetailContent').addClass('d-none');
            $('#detailPlaceholder').removeClass('d-none');
        }

        function escapeHtml(value) {
            return $('<div>').text(value || '').html();
        }
    </script>
</body>
</html>
