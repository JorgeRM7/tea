<!doctype html>
<?php ;$title = "Taquilla"; ?>
<html lang="es" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr"
    data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template">

<?php require_once('header.php'); ?>

<style>
    body {
        background: #f5f7fa;
    }

    .scanner-container {
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        padding: 20px;
        background: #fff;
        text-align: center;
    }

    .scanner-header {
        font-size: 1.4rem;
        font-weight: 600;
        margin-bottom: 15px;
        color: #38b449;
    }

    #qr-reader {
        width: 100%;
        border: 3px solid #38b449;
        border-radius: 12px;
        background: #000;
        min-height: 280px;
    }

    .scanner-instructions {
        margin-top: 10px;
        font-size: 0.95rem;
        color: #555;
    }

    .scanner-instructions i {
        color: #38b449;
        margin-right: 6px;
    }

    .result-container {
        margin-top: 18px;
        background: #f8fff8;
        border: 2px solid #38b449;
        border-radius: 12px;
        padding: 15px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        text-align: left;
        animation: fadeIn .4s ease-in-out;
    }

    .result-title {
        font-weight: bold;
        color: #2e7d32;
        margin-bottom: 6px;
    }

    .result-text {
        word-break: break-word;
        color: #333;
        font-size: 0.95rem;
    }

    .btn-scan {
        margin-top: 15px;
        border-radius: 30px;
        padding: 10px 18px;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .scanner-container {
            padding: 15px;
        }
        .scanner-header {
            font-size: 1.2rem;
        }
        #qr-reader {
            min-height: 230px;
        }
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px);}
        to { opacity: 1; transform: translateY(0);}
    }
</style>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php require_once('menu.php'); ?>
            <div class="layout-page">
                <?php require_once('barra_navegacion.php'); ?>
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="scanner-container">
                                    <div class="scanner-header">📷 Escanear Boleto</div>
                                    <div id="qr-reader"></div>
                                    <div class="scanner-instructions">
                                        <i class="fas fa-info-circle"></i> Usa la cámara trasera para apuntar al código QR.
                                    </div>
                                    <!-- Resultados -->
                                    <div id="qr-reader-results" class="result-container d-none">
                                        <div class="result-title">QR Detectado:</div>
                                        <div class="result-text" id="result-text"></div>
                                    </div>
                                    <!-- Botón reinicio -->
                                    <button type="button" id="btnRestart" class="btn btn-success btn-scan d-none">
                                        <i class="fas fa-redo"></i> Escanear otro
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php require_once('footer.php'); ?>
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
    let html5QrCode;
    const config = { fps: 10, qrbox: { width: 250, height: 250 } };

    $(document).ready(function () {
        startQrScanner();

        $("#btnRestart").on("click", function () {
            $("#qr-reader-results").addClass("d-none");
            $(this).addClass("d-none");
            startQrScanner();
        });

        const menuItem = document.querySelector('a[href="tickets-qr-scanner.php"]').parentElement;
        menuItem.classList.add('active');
        const menuToggle = document.querySelector('a[href="BOLETOS"]').parentElement;
        menuToggle.classList.add('open');
    });

    function onScanSuccess(ticket_id) {
        $.ajax({
            url: "../Controllers/ticketsController.php?op=check-ticket",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + token
            },
            data: { ticket_id: ticket_id },
            success: function(data, status) {
                $("#result-text").text(ticket_id);
                $("#qr-reader-results").removeClass("d-none");
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                Swal.fire({
                    title: "Error",
                    text: "No se pudo obtener la información del registro.",
                    icon: "error"
                });
            }
        });

        // Si quieres detener la cámara después de leer
        // html5QrCode.stop().then(() => console.log("Escaneo detenido"));
    }

    function onScanFailure(error) {
        console.warn(`Error escaneo: ${error}`);
    }

    function startQrScanner() {
        html5QrCode = new Html5Qrcode("qr-reader");
        html5QrCode.start(
            { facingMode: { exact: "environment" } },
            config,
            onScanSuccess,
            onScanFailure
        ).catch(err => {
            console.error("Error al iniciar cámara:", err);
            Html5Qrcode.getCameras().then(devices => {
                if (devices && devices.length) {
                    let backCamera = devices.find(d =>
                        d.label.toLowerCase().includes("back") ||
                        d.label.toLowerCase().includes("environment")
                    );
                    let cameraId = backCamera ? backCamera.id : devices[0].id;
                    html5QrCode.start(cameraId, config, onScanSuccess, onScanFailure);
                }
            });
        });
    }
</script>
