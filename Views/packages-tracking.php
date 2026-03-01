<?php
$secretKey = 'TEA_SUPER_SECRET_2026'; // MISMA CLAVE DEL PDF
$method = 'AES-256-CBC';

function decryptData($data, $key, $method) {
    $data = base64_decode($data);
    $ivLength = openssl_cipher_iv_length($method);
    $iv = substr($data, 0, $ivLength);
    $encrypted = substr($data, $ivLength);
    return openssl_decrypt($encrypted, $method, $key, 0, $iv);
}

$autoCode = '';
$autoPin  = '';

if (isset($_GET['data']) && !empty($_GET['data'])) {

    $decrypted = decryptData($_GET['data'], $secretKey, $method);

    if ($decrypted) {
        $payload = json_decode($decrypted, true);
        $autoCode = $payload['code'] ?? '';
        $autoPin  = $payload['pin'] ?? '';
    }
}
?>

<!doctype html>
<?php $title = "Seguimiento"; ?>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Seguimiento de Paquetes - TEA</title>
    <link rel="icon" type="image/x-icon" href="../assets/img/logo.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background: #0f172a; color: #fff; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem; }
        .tracking-card { background: rgba(255,255,255,0.08); border-radius: 24px; padding: 2rem; max-width: 520px; width: 100%; box-shadow: 0 20px 50px rgba(0,0,0,0.4); backdrop-filter: blur(12px); }
        .timeline { list-style: none; padding-left: 0; }
        .timeline li { position: relative; padding-left: 24px; margin-bottom: 18px; }
        .timeline li::before { content: ""; width: 10px; height: 10px; border-radius: 50%; background: #38b449; position: absolute; left: 0; top: 8px; }
        .badge-status { background: #1f2937; border-radius: 20px; padding: .25rem .75rem; color: #38b449; font-size: .85rem; }
    </style>
</head>
<body>
    <div class="tracking-card">
        <div class="text-center mb-4">
            <img src="../assets/img/logo.png" alt="TEA" width="70" class="mb-2">
            <h2 class="fw-bold">Seguimiento de Paquetes</h2>
            <p class="text-muted">Ingresa tu código y PIN para ver el estatus</p>
        </div>
        <form id="trackingForm" class="mb-4">
            <div class="mb-3">
                <label class="form-label">Código de seguimiento</label>                
                <input type="text" id="tracking_code" name="tracking_code"
                    class="form-control"
                    value="<?= htmlspecialchars($autoCode) ?>"
                    placeholder="Ej. PKG00000001" required>
            </div>
            <div class="mb-3">
                <label class="form-label">PIN (últimos 4 del teléfono)</label>                
                <input type="text" id="tracking_pin" name="tracking_pin"
                    class="form-control"
                    maxlength="4"
                    value="<?= htmlspecialchars($autoPin) ?>"
                    required>
            </div>
            <button type="submit" class="btn btn-success w-100">Consultar</button>
        </form>
        <div id="result" style="display:none;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-semibold" id="routeLabel"></span>
                <span class="badge-status" id="statusLabel"></span>
            </div>
            <p class="text-muted mb-1" id="branchInfo"></p>
            <p class="mb-3"><strong>Última actualización:</strong> <span id="statusDate"></span></p>
            <h6 class="mb-3"><strong>Historial</strong></h6>
            <ul class="timeline" id="timeline"></ul>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        const endpoint = '../Controllers/packageDeliveriesController.php';


        $(document).ready(function(){

                const autoCode = "<?= $autoCode ?>";
                const autoPin  = "<?= $autoPin ?>";

                if(autoCode && autoPin){
                    $('#trackingForm').submit();
                }

        });


        $('#trackingForm').on('submit', function(e) {
            e.preventDefault();
            const code = $('#tracking_code').val();
            const pin = $('#tracking_pin').val();
            $.getJSON(`${endpoint}?op=tracking&tracking_code=${code}&tracking_pin=${pin}`, function(response) {
                if (!response.success) {
                    Swal.fire('Ups...', response.message || 'No se encontró el paquete', 'error');
                    $('#result').hide();
                    return;
                }
                const pkg = response.package;
                $('#routeLabel').text(`Origen: ${pkg.stop_origin} → Destino: ${pkg.stop_destination}`);
                $('#statusLabel').text(pkg.status);
                $('#statusDate').text(pkg.status_changed_at || pkg.updated_at);
                const timeline = $('#timeline');
                timeline.empty();
                response.events.forEach(event => {
                    timeline.append(`<li><strong>${event.status}</strong> - ${event.created_at}<br><small>${event.branch_name || ''}</small><p class="mb-0">${event.notes || ''}</p></li>`);
                });
                $('#result').show();
            }).fail(function() {
                Swal.fire('Error', 'No se pudo consultar el seguimiento', 'error');
                $('#result').hide();
            });
        });



    </script>
</body>
</html>
