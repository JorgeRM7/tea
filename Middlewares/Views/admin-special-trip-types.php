<!doctype html>
<?php ;$title = "Tipos de Viajes Especiales"; ?>
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
                        <div class="row">
                            <!-- Tabla -->
                            <div class="col-xl-12 col-lg-12 col-md-12 order-0 order-md-1">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Tipos de Viajes Especiales</h5>
                                        <div class="d-flex justify-content-end">
                                            <button class="crear btn btn-primary me-2" onclick="create()">
                                                <i class="ti ti-cloud-up"></i> Crear
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-datatable table-responsive">
                                        <table class="dt-responsive table table-striped" id="tbllistado">
                                            <thead>
                                                <tr>
                                                    <th>Acciones</th>
                                                    <th>#</th>
                                                    <th>Origen</th>
                                                    <th>Destino</th>
                                                    <th>Días</th>
                                                    <th>Precio</th>
                                                    <th>Vigencia Desde</th>
                                                    <th>Vigencia Hasta</th>
                                                    <th>Estatus</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal animate__animated animate__flipInX" id="modal_create" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Registrar / Editar Tipo de Viaje Especial</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="formulario" method="POST">
                                        <input type="hidden" id="trip_type_id" name="trip_type_id" />

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">Origen</label>
                                                <input type="text" id="origin" name="origin" class="form-control" required/>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Destino</label>
                                                <input type="text" id="destination" name="destination" class="form-control" required/>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Días</label>
                                                <input type="number" id="days" name="days" class="form-control" required/>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Precio</label>
                                                <input type="number" step="0.01" id="price" name="price" class="form-control" required/>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Vigencia Desde</label>
                                                <input type="date" id="valid_from" name="valid_from" class="form-control"/>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Vigencia Hasta</label>
                                                <input type="date" id="valid_to" name="valid_to" class="form-control"/>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Estatus</label>
                                                <select id="status" name="status" class="form-control">
                                                    <option value="active">Activo</option>
                                                    <option value="inactive">Inactivo</option>
                                                </select>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button class="crear btn btn-primary me-2" onclick="store()">
                                        <i class="ti ti-device-floppy"></i> Guardar
                                    </button>
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="clean()">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Fin Modal -->

                    <?php require_once('footer.php'); ?>
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<script>
    var tabla;
    $(document).ready(function() {    
        let module = $("#module").val();    
        const menuItem = document.querySelector('a[href="admin-special-trip-types.php"]').parentElement;
        menuItem.classList.add('active');
        const menuToggle = document.querySelector(`a[href="${module}"]`).parentElement;
        menuToggle.classList.add('open');        
        index();
    });

    const create = () => {
        $('#modal_create').modal('show');
        clean();
    };

    const store = () => {
        const form = document.getElementById("formulario");
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        const formData = new FormData(form);

        $.ajax({
            url: "../Controllers/adminSpecialTripTypesController.php?op=store",
            type: "POST",
            headers: { "Authorization": "Bearer " + token },
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                Swal.fire({ toast: true, icon: 'success', title: 'Registro guardado' });
                $('#modal_create').modal('hide');
                clean();
                index();
            },
            error: function() {
                Swal.fire({ icon: "error", title: "Error", text: "No se pudo guardar el registro." });
            }
        });
    };


const index = () => {
    if ($.fn.DataTable.isDataTable('#tbllistado')) {
        $('#tbllistado').DataTable().ajax.reload();
        return;
    }

    tabla = $('#tbllistado').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "ajax": {
            url: '../Controllers/adminSpecialTripTypesController.php?op=index',
            type: "get",
            headers: { "Authorization": "Bearer " + token },
            dataType: "json",
            error: (e) => { console.log(e.responseText); }
        },
        "bDestroy": true,
        "iDisplayLength": 10,
        "lengthMenu": [5, 10, 25, 50, 100],
        "order": [[1, "asc"]], // orden por ID descendente
        "language": {
            "url": "../assets/json/Spanish.json" // local para evitar CORS
        },
        "responsive": false,
    }).DataTable();
};


    const show = (id) => {
        $('#modal_create').modal('show');
        $.ajax({
            url: "../Controllers/adminSpecialTripTypesController.php?op=show",
            type: "POST",
            dataType: "json",
            headers: {"Authorization": "Bearer " + token},
            data: { id: id },
            success: function (data) {
                $("#trip_type_id").val(data.id);
                $("#origin").val(data.origin);
                $("#destination").val(data.destination);
                $("#days").val(data.days);
                $("#price").val(data.price);
                $("#valid_from").val(data.valid_from);
                $("#valid_to").val(data.valid_to);
                $("#status").val(data.status);
            }
        });
    };

    const deleteItem = (id) => {
        Swal.fire({
            title: "Alerta", text: "¿Seguro que deseas eliminar este tipo de viaje?",
            icon: "warning", showCancelButton: true, confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33", confirmButtonText: "Sí"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "../Controllers/adminSpecialTripTypesController.php?op=delete",
                    type: "POST",
                    headers: {"Authorization": "Bearer " + token},
                    data: { id: id },
                    success: function() {
                        Swal.fire({ toast: true, icon: 'success', title: 'Eliminado' });
                        index();
                    }
                });
            }
        });
    };

    const clean = () => {   
        $("#trip_type_id").val('');
        $("#origin").val('');
        $("#destination").val('');
        $("#days").val('');
        $("#price").val('');
        $("#valid_from").val('');
        $("#valid_to").val('');
        $("#status").val('active');
    };
</script>
