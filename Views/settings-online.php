<!doctype html>
<?php $title = "Configuración Ventas Online"; ?>
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
                        <?php
                        $branchOffices = ejecutarConsulta("SELECT id, name FROM branch_offices WHERE deleted_at IS NULL ORDER BY name");
                        $users = ejecutarConsulta("SELECT id, name FROM users WHERE deleted_at IS NULL ORDER BY name");
                        ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-1">Configuración de Ventas en Línea</h5>
                                            <p class="text-muted mb-0">Selecciona la taquilla, usuario y credenciales de Stripe que se usarán para el checkout.</p>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <form id="settingsForm">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Taquilla (Sucursal)</label>
                                                    <select class="form-select" id="online_branch_office_id" name="online_branch_office_id" required>
                                                        <option value="">Selecciona taquilla</option>
                                                        <?php while ($bo = $branchOffices->fetch_assoc()): ?>
                                                            <option value="<?php echo $bo['id']; ?>"><?php echo htmlspecialchars($bo['name']); ?></option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Usuario responsable</label>
                                                    <select class="form-select" id="online_user_id" name="online_user_id" required>
                                                        <option value="">Selecciona usuario</option>
                                                        <?php while ($user = $users->fetch_assoc()): ?>
                                                            <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['name']); ?></option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Stripe Secret Key</label>
                                                    <input type="text" class="form-control" id="stripe_secret_key" name="stripe_secret_key" placeholder="sk_test_..." required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Stripe Publishable Key</label>
                                                    <input type="text" class="form-control" id="stripe_publishable_key" name="stripe_publishable_key" placeholder="pk_test_..." required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Connect Account ID</label>
                                                    <input type="text" class="form-control" id="stripe_connect_account_id" name="stripe_connect_account_id" placeholder="acct_..." required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Fee plataforma (%)</label>
                                                    <input type="number" step="0.01" class="form-control" id="stripe_platform_fee_percent" name="stripe_platform_fee_percent" placeholder="5">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Fee fijo (MXN)</label>
                                                    <input type="number" step="0.01" class="form-control" id="stripe_platform_fee_fixed" name="stripe_platform_fee_fixed" placeholder="0">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">URL de éxito</label>
                                                    <input type="text" class="form-control" id="stripe_success_url" name="stripe_success_url" placeholder="https://tusitio.com/...">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">URL de cancelación</label>
                                                    <input type="text" class="form-control" id="stripe_cancel_url" name="stripe_cancel_url" placeholder="https://tusitio.com/...">
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label">Webhook Secret</label>
                                                    <input type="text" class="form-control" id="stripe_webhook_secret" name="stripe_webhook_secret" placeholder="whsec_..." required>
                                                </div>
                                            </div>
                                            <div class="mt-4 text-end">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="ti ti-device-floppy"></i> Guardar configuración
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php require_once('footer.php'); ?>
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="layout-overlay layout-menu-toggle"></div>
    <div class="drag-target"></div>
    <script>
        $(document).ready(function () {
            loadSettings();
            $('#settingsForm').on('submit', function (e) {
                e.preventDefault();
                saveSettings();
            });
        });

        const loadSettings = () => {
            $.ajax({
                url: '../Controllers/settingsController.php?op=get-online-config',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        const data = response.data;
                        Object.keys(data).forEach(key => {
                            if (data[key] !== null && data[key] !== undefined) {
                                $('#' + key).val(data[key]);
                            }
                        });
                    }
                }
            });
        };

        const saveSettings = () => {
            $.ajax({
                url: '../Controllers/settingsController.php?op=save-online-config',
                type: 'POST',
                data: $('#settingsForm').serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Configuración guardada',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo guardar la configuración.' });
                    }
                },
                error: function () {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo guardar la configuración.' });
                }
            });
        };
    </script>
</body>
</html>
