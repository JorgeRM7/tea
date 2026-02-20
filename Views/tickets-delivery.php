<!doctype html>
<?php ;$title = "Paqueteria"; ?>
<html lang="es" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr"
    data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template">
<!--HEADER-->
<?php require_once('header.php'); ?>
<!--HEADER-->

<style>
    :root{--naranja:#f07d42;--verde:#28c76f;--rojo:#ef4444;--amarillo:#f59e0b;--azul:#1f2a44;}
    /* body{background:linear-gradient(180deg,#f8fafc 0%,#eef2f6 100%)} */
    .card-main{border:0;border-radius:18px;box-shadow:0 10px 30px rgba(2,8,20,.05)}
    .time-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:.75rem}
    .time-card{border:1px solid #e5e7eb;border-radius:12px;padding:.85rem;cursor:pointer;transition:.2s}
    .time-card:hover{border-color:var(--naranja);box-shadow:0 0 0 2px rgba(240,125,66,.15)}
    .time-card.disabled{opacity:.55;pointer-events:none}
    .time-card.active{border-color:var(--verde);background:#ecfdf3}
    .badge-seats{border:1px solid #e5e7eb}
    .badge-green{color:#065f46;border-color:#a7f3d0}
    .badge-yellow{background:#fff7ed;color:#92400e;border-color:#fed7aa}
    .badge-red{background:#fee2e2;color:#991b1b;border-color:#fecaca}
    .stub{border:1px dashed #cbd5e1;border-radius:12px;padding:.75rem;color:#64748b}
    .section-title{font-weight:700}
    .kpi{border:1px solid #e5e7eb;border-radius:16px;padding:.75rem 1rem}
    .rule-note{font-size:.85rem;color:#6b7280}

    .time-card {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 18px 12px;
        text-align: center;
        cursor: pointer;
        transition: all 0.25s ease;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .time-card:hover {
        border-color: #28c76f;
        transform: translateY(-4px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }
    .time-hour {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 8px;
    }
    .time-availability {
        font-size: 0.85rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-block;
    }
    .time-availability.bg-success {
        background: #d1fae5;
        /* color: #065f46; */
    }
    .time-availability.bg-warning {
        background: #fef9c3;
        /* color: #92400e; */
    }
    .time-availability.bg-danger {
        background: #fee2e2;
        /* color: #991b1b; */
    }

    .time-card.selected {
        border-color: #28c76f;
        box-shadow: 0 0 8px rgba(40,199,111,0.4);
    }



  </style>
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
                        <form name="formulario" id="formulario" method="POST">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="card mb-4">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">Envio de paquetes</h5>
                                            <small class="text-muted float-end">TEA</small>
                                        </div>
                                        <div class="card-body">

                                            <div class="row g-3 mb-3">
                                                <div class="col-md-4">
                                                    <label class="form-label section-title">Ruta</label>
                                                    <select id="search_route" name="search_route" class="form-select form-select-lg">
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
                                                    <label class="form-label section-title">Destino</label>
                                                    <select id="routes_stop_id" name="routes_stop_id" class="form-select form-select-lg" onchange="clean()"></select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label section-title">Fecha</label>
                                                    <input id="search_date" name="search_date" type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>"/>
                                                </div>
                                            </div>
                                            
                                            
                                            <hr class="my-4">
                                            <div class="row g-3 align-items-end">
                                                
                                                <div class="col-md-3">
                                                    <label class="form-label section-title">Cantidad</label>
                                                    <input id="quantity" name="quantity" type="number" class="form-control" min="1" max="5" value="1" />
                                                </div>
                                                <div class="col-md-9">
                                                    <label class="form-label section-title">Descripcion</label>
                                                    <input id="description" name="description" type="text" class="form-control">
                                                </div>
                                            
                                            </div>
                                            <hr class="my-4">
                                            <div class="row g-3 mb-2">
                                                <div class="col-md-4">
                                                    <label class="form-label section-title">Precio</label>
                                                    <input id="price" name="price" type="number" class="form-control">
                                                </div>
                                                
                                            </div>
                                            <div class="row g-3 align-items-end">
                                                <div class="col-md-4">
                                                    <label class="form-label section-title">Total</label>
                                                    <input id="total" name="total" type="number" class="form-control" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label section-title">Recibido</label>
                                                    <input id="amount_received" name="amount_received" type="number" class="form-control">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label section-title">Cambio</label>
                                                    <input id="change_amount" name="change_amount" type="number" class="form-control" readonly>
                                                    <input id="employee_id" name="employee_id" type="hidden" class="form-control" readonly>
                                                    <input id="route_schedule_id" name="route_schedule_id" type="hidden" class="form-control" readonly>
                                                    <input id="route_id" name="route_id" type="hidden" class="form-control" readonly>
                                                    <input id="vehicle_id" name="vehicle_id" type="hidden" class="form-control" readonly>
                                                    <input id="branch_office_id" name="branch_office_id" type="hidden" class="form-control" readonly>
                                                    <input id="discount" name="discount" type="hidden" class="form-control" readonly>
                                                    <input id="route_discount_id" name="route_discount_id" type="hidden" class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="row g-3 mt-2">
                                                <div class="col-md-12 text-end">
                                                    <button id="btnClear" class="btn btn-outline-secondary" type="button" onclick="clean()">
                                                        <i class="bi bi-x-circle"></i> Limpiar
                                                    </button>
                                                    <button id="btnGenerate" class="btn btn-success btn-lg" type="button"> 
                                                        <i class="bi bi-receipt"></i> Generar boleto
                                                    </button>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
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
    var tabla;
    $(document).ready(function() {
        let module = $("#module").val();
        const menuItem = document.querySelector('a[href="tickets-delivery.php"]').parentElement;
        menuItem.classList.add('active');
        const menuToggle = document.querySelector(`a[href="${module}"]`).parentElement;
        menuToggle.classList.add('open');
        $("#search_route").select2({ width:"100%"});
        $("#search_schedule").select2({ width:"100%"});
        $("#routes_stop_id").select2({ width:"100%"});

        $("#search_route").on("change", function() {
            show_subpaths();
        });

        $("#quantity, #amount_received, #price").on("change keyup", function() {
            totales();
        });
        let branch_office_id = document.getElementById('branch_office_id_selected').value;
        $("#branch_office_id").val(branch_office_id);
        show_subpaths();

    });
    
    document.getElementById("btnGenerate").addEventListener("click", () => {
        const btn = document.getElementById("btnGenerate");
    
        // 🔒 Bloquear botón
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Generando...';
    
        // Ejecutar tu función normal
        store();
    
        // 🔓 Reactivar después de 4 segundos
        setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-receipt"></i> Generar boleto';
        }, 4000);
    });

   
    const store = () => {
        
        let quantity = $("#quantity").val();
        let price = $("#price").val();
        let description = $("#description").val();

        if( quantity == '' ){
            Swal.fire({
                title: "Ups...",
                text: "La cantidad de paquetes no puede ser vacio o 0.",
                icon: "warning"
            });
            return;
        }

        if( price == '' ){
            Swal.fire({
                title: "Ups...",
                text: "El precio del paquete no puede ser vacio o 0.",
                icon: "warning"
            });
            return;
        }

        if( description == '' ){
            Swal.fire({
                title: "Ups...",
                text: "El campo de descripción no debe estar vacia",
                icon: "warning"
            });
            return;
        }
        const formData = new FormData(document.getElementById("formulario"));
        $.ajax({
            url: "../Controllers/ticketsDeliveriesController.php?op=store",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + token
            },
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(response) {
                console.log(response)
                if (response.success) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Registro creado exitosamente.',
                    });
                    let tickets_id = response.ids;
                    let url = `../Pdf/ticket_delivery.php?tickets_id=${tickets_id.join(",")}`;
                    var iframe = document.createElement('iframe');
                    iframe.className = 'pdfIframe';
                    document.body.appendChild(iframe);
                    iframe.style.display = 'none';

                    iframe.onload = function () {
                        Swal.close();
                        setTimeout(function () {
                            iframe.focus();
                            iframe.contentWindow.print();
                            URL.revokeObjectURL(url);
                        }, 1);
                    };
                    iframe.src = url;
                    
                    clean();
                }
            },
            error: function(error) {
                Swal.fire({
                    title: "Error",
                    text: "No se pudo guardar el registro.",
                    icon: "error"
                });
            }
        });
    };

    const show_subpaths = () => { 
        let route_id = $("#search_route").val(); 
        $.ajax({
            url: "../Controllers/ticketsController.php?op=show-subpaths",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + token
            },
            data: { route_id: route_id },
            dataType: "json",
            success: function (response) {
                let data = response;
                let $select = $("#routes_stop_id");
                $select.empty();
                // $select.append('<option value="">Selecciona destino...</option>');
                data.forEach(item => {
                    $select.append(
                        `<option value="${item.routes_stop_id}">
                            ${item.destination}
                        </option>`
                    );
                });

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
    };


    const clean = () => {  
        $("#price").val('');
        $("#quantity").val(1);  
        $("#total").val(0);
        $("#change_amount").val(0); 
        $("#description").val(''); 
        $("#amount_received").val(0); 
    }

     const totales = () => {
        let price = parseFloat($("#price").val()) || 0;
        let quantity = parseFloat($("#quantity").val()) || 0;
        let amount_received = parseFloat($("#amount_received").val()) || 0;

        let total = price * quantity;
        let change = amount_received - total ;

        if( change <= 0 ){
            change = 0;
        }

        $("#total").val(total.toFixed(2));
        $("#change_amount").val(change.toFixed(2));
    
    };


    
    document.addEventListener("keydown", function(e) {
        if (e.key === "Enter") {
            store();
        }
    });


</script>