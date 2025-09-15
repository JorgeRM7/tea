<?php
// session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>TEA - <?php echo $title; ?></title>
    <meta name="description" content="" />
   
    <link rel="icon" type="image/x-icon" href="https://tea.systemsorion.com/Librerias/img/logo" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap" rel="stylesheet" />

    <link rel="stylesheet" href="../assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="../assets/vendor/fonts/tabler-icons.css" />
    <link rel="stylesheet" href="../assets/vendor/fonts/flag-icons.css" />


    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />
    
    <!-- Datatables -->
    <link rel="stylesheet" href="../assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css">
    <link rel="stylesheet" href="../assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css">
    <link rel="stylesheet" href="../assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css">
    <link rel="stylesheet" href="../assets/vendor/libs/datatables-fixedcolumns-bs5/fixedcolumns.bootstrap5.css">
    <link rel="stylesheet" href="../assets/vendor/libs/datatables-fixedheader-bs5/fixedheader.bootstrap5.css">
    <link rel="stylesheet" href="../assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css">
    <link rel="stylesheet" href="../assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css">
    <link rel="stylesheet" href="../assets/vendor/libs/datatables-select-bs5/select.bootstrap5.css">
    <!-- Datatables -->

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/animate-css/animate.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/select2/select2.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/@form-validation/form-validation.css" />
    <script src="../assets/vendor/js/helpers.js"></script>
    <script src="../assets/vendor/js/template-customizer.js"></script>
    <script src="../assets/js/config.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../assets/vendor/css/pages/page-auth.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js" integrity="sha512-r6rDA7W6ZeQhvl8S7yRVQUKVHdexq+GAlNkNNqVC7YyIV+NwqCTJe2hDWCiffTyRNOeGEzRRJ9ifvRm/HCzGYg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <link rel="stylesheet" href="../assets/vendor/libs/dropzone/dropzone.css" />
    <!--<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">-->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <!--<link rel="stylesheet" href="css/stiles.css">-->

   




</head>
