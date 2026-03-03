<!doctype html>
<?php ;$title = "Reportes"; ?>
<html lang="es" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr"
    data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template">
<!--HEADER-->
<?php require_once('header.php'); ?>
<!--HEADER-->
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
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row">

                            <!--Tabla de asistencias-->
                            <div class="col-xl-6 col-lg-6 col-md-4 order-0 order-md-1 mt-3">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Corte de caja</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                             <div class="col-md-6">
                                                <label class="form-label section-title">Desde</label>
                                                <input id="date_start" name="date_start" type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>"/>
                                            </div>
                                             <div class="col-md-6">
                                                <label class="form-label section-title">Hasta</label>
                                                <input id="date_end" name="date_end" type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>"/>
                                            </div>
                                        </div> 
                                        <div class="row g-3 mt-2">
                                            <div class="col-md-12 text-end">
                                                <button id="btnGenerate" class="btn btn-success" type="button" onclick="exportCSV()"> 
                                                    <i class="bi bi-receipt"></i> Generar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-8 col-lg-8 col-md-8 order-0 order-md-1 mt-3" id="detail" style="display:none">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Detalle</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row" id="items"></div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
    $(document).ready(function() {
        let module = $("#module").val();
        const menuItem = document.querySelector('a[href="admin-reports.php"]').parentElement;
        menuItem.classList.add('active');
        const menuToggle = document.querySelector(`a[href="${module}"]`).parentElement;
        menuToggle.classList.add('open');
    });
   
   
    const show = () => {
        const date_start = document.getElementById("date_start").value;
        const date_end = document.getElementById("date_end").value;
        let report_type = document.getElementById('report_id').value;

        Swal.fire({
            title: 'Exportando...',
            text: 'Por favor, espera mientras se procesa la información.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    
        $.ajax({
            url: "../Controllers/adminReportsController.php?op=show",
            type: "POST",
            dataType: "json",
            headers: {
                Authorization: `Bearer ${token}`,
            },
            data: { date_start: date_start, date_end:date_end, report_type:report_type },
            success: function (response) {
                Swal.close();
                document.getElementById("detail").style.display = "block";
                const firstItem = response.resultados?.[0];

                if (!firstItem) {
                    $("#items").html(`<h5 class="text-center text-muted">No se encontraron datos</h5>`);
                    return;
                }
                const headers = Object.keys(firstItem);

                let table = `
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle shadow-sm rounded">
                        <thead class="bg-primary text-white">
                            <tr>
                                ${headers.map(h => `<th class="text-center">${h.toUpperCase()}</th>`).join("")}
                            </tr>
                        </thead>
                        <tbody>
                `;

                        response.resultados.forEach(row => {
                            table += "<tr>";

                            headers.forEach(col => {
                                let value = row[col];
                                let num = parseFloat(value);
                                if (!isNaN(num)) {
                                    value = num.toLocaleString();
                                }

                                table += `<td class="text-center fw-medium">${value ?? "-"}</td>`;
                            });

                            table += "</tr>";
                        });

                        table += `</tbody></table></div>`;
                $("#items").html(table);

                
            },
            error: function (xhr, status, error) {
                console.error("Error en la solicitud:", error);
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Hubo un problema al procesar los datos.",
                    confirmButtonColor: "#f07d42"
                });
            }
        });
        // exportCSV()
    }

    const exportCSV = () => {

        const date_start = document.getElementById("date_start").value;
        const date_end = document.getElementById("date_end").value;
        const branch_office_id = document.getElementById("branch_office_id_selected").value;
        // const report_type = document.getElementById("report_type").value;

        Swal.fire({
            title: "Exportando...",
            text: "Por favor, espera mientras se genera el archivo Excel.",
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => Swal.showLoading()
        });

        $.ajax({
            url: "../Controllers/adminReportsController.php?op=xls",
            type: "POST",
            dataType: "text",
            headers: { Authorization: `Bearer ${token}` },
            data: { date_start, date_end, branch_office_id },

            success: function (responseText) {

                let data;

                try {
                    data = JSON.parse(responseText);
                } catch (e) {

                    console.error("Respuesta NO es JSON válido:", responseText);

                    Swal.fire({
                        icon: "error",
                        title: "Respuesta inválida",
                        text: "El servidor devolvió algo que no es JSON.",
                        confirmButtonColor: "#f07d42"
                    });

                    return;
                }

                const boletos = data.boletos || [];
                const paquetes = data.paquetes || [];

                if (boletos.length === 0 && paquetes.length === 0) {

                    Swal.fire({
                        icon: "warning",
                        title: "Sin datos",
                        text: "No hay registros para exportar.",
                        confirmButtonColor: "#f07d42"
                    });

                    return;
                }

                const rows = [];
                boletos.forEach(b => {

                    rows.push({
                        "TIPO": "BOLETO",
                        "ID": b.id,
                        "TIPO PAGO": b["TIPO PAGO"],
                        "PRECIO": b.PRECIO,
                        "ESTATUS": b.ESTATUS,
                        "HORA": b.HORA,
                        "FECHA": b.FECHA,
                        "DESCUENTO": b.DESCUENTO,
                        "ORIGEN": b.ORIGEN,
                        "DESTINO": b.DESTINO,
                        "CHOFER": b.CHOFER,
                        "NUMERO UNIDAD": b["NUMERO UNIDAD"],
                        "HORA SALIDA": b["HORA SALIDA"],
                        "VENDEDOR": b.VENDEDOR
                    });

                });

                paquetes.forEach(p => {

                    rows.push({
                        "TIPO": "PAQUETE",
                        "ID": p.id,
                        "PRECIO": p.PRECIO,
                        "CANTIDAD": p.CANTIDAD,
                        "DESCRIPCION": p.DESCRIPCION,
                        "ORIGEN": p.ORIGEN,
                        "DESTINO": p.DESTINO,
                        "CHOFER": p.CHOFER,
                        "NUMERO UNIDAD": p["NUMERO UNIDAD"],
                        "HORA SALIDA": p["HORA SALIDA"],
                        "VENDEDOR": p.VENDEDOR,
                        "FECHA": p.FECHA
                    });

                });

                const worksheet = XLSX.utils.json_to_sheet(rows);

                const workbook = XLSX.utils.book_new();

                XLSX.utils.book_append_sheet(workbook, worksheet, "Reporte");

                XLSX.writeFile(workbook, `reporte_${branch_office_id}.xlsx`);

                Swal.fire({
                    icon: "success",
                    title: "Exportación completada",
                    text: "El archivo Excel ha sido generado con éxito.",
                    confirmButtonColor: "#28c76f"
                });

            },

            error: function (xhr, status, error) {

                console.error("Error AJAX:", status, error, xhr.responseText);

                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Hubo un problema al procesar los datos.",
                    confirmButtonColor: "#f07d42"
                });

            }
        });
    };

</script>