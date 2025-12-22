<!doctype html>
<?php ;$title = "Clientes"; ?>
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
                            <!-- Tabla de clientes -->
                            <div class="col-xl-12 col-lg-12 col-md-12 order-0 order-md-1">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Clientes</h5>
                                        <div class="d-flex justify-content-end">
                                            <button class="crear btn btn-primary me-2" onclick="create()">
                                                <i class="ti ti-cloud-up"></i> Crear
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-datatable table-responsive">
                                        <div class="row">
                                            <div class="col-xl-12 col-lg-12 col-md-12 order-0 order-md-1">
                                                <table class="dt-responsive table table-striped" id="tbllistado">
                                                    <thead>
                                                        <tr>
                                                            <th>Acciones</th>
                                                            <th>#</th>
                                                            <th>Nombre</th>
                                                            <th>Teléfono</th>
                                                            <th>Email</th>
                                                            <th>Dirección</th>
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
                        </div>
                    </div>

                    <!-- Modal Crear -->
                    <div class="modal animate__animated animate__flipInX" id="modal_create" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Crear Cliente</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="formulario" method="POST">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">Nombre</label>
                                                <input type="text" id="name" name="name" class="form-control" required/>
                                                <input type="hidden" id="client_id" name="client_id" />
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Teléfono</label>
                                                <input type="text" id="phone" name="phone" class="form-control" />
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Correo</label>
                                                <input type="email" id="email" name="email" class="form-control" />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Estatus</label>
                                                <select id="status" name="status" class="form-control">
                                                    <option value="active">Activo</option>
                                                    <option value="inactive">Inactivo</option>
                                                </select>
                                            </div>

                                            <hr>
                                            <h6>Dirección</h6>
                                            <div class="col-md-6">
                                                <label class="form-label">Calle</label>
                                                <input type="text" id="street" class="form-control" />
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Número</label>
                                                <input type="text" id="number" class="form-control" />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Colonia</label>
                                                <input type="text" id="neighborhood" class="form-control" />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Ciudad</label>
                                                <input type="text" id="city" class="form-control" />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Estado</label>
                                                <input type="text" id="state" class="form-control" />
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">C.P.</label>
                                                <input type="text" id="zipcode" class="form-control" />
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
        const menuItem = document.querySelector('a[href="admin-clients.php"]').parentElement;
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

    // Construir JSON de dirección
    let address = {
        street: $("#street").val(),
        number: $("#number").val(),
        neighborhood: $("#neighborhood").val(),
        city: $("#city").val(),
        state: $("#state").val(),
        zipcode: $("#zipcode").val()
    };

    const formData = new FormData(form);
    formData.append("address", JSON.stringify(address));

    $.ajax({
        url: "../Controllers/adminClientsController.php?op=store",
        type: "POST",
        headers: { "Authorization": "Bearer " + token },
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            Swal.fire({ toast: true, icon: 'success', title: 'Cliente guardado' });
            $('#modal_create').modal('hide');
            clean();
            index();
        },
        error: function() {
            Swal.fire({ icon: "error", title: "Error", text: "No se pudo guardar el cliente." });
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
            url: '../Controllers/adminClientsController.php?op=index',
            type: "GET",
            dataType: "json",
            headers: { "Authorization": "Bearer " + token },
            error: (e) => { console.log(e.responseText); }
        },
        "bDestroy": true,
        "iDisplayLength": 10,
        "lengthMenu": [5, 10, 25, 50, 100],
        "order": [[1, "asc"]], // ID descendente
        "language": {
            "url": "../assets/json/Spanish.json" // mejor local que CDN
        },
        "responsive": false,
    }).DataTable();
};



const show = (id) => {
    $('#modal_create').modal('show');
    $.ajax({
        url: "../Controllers/adminClientsController.php?op=show",
        type: "POST",
        dataType: "json",
        headers: {"Authorization": "Bearer " + token},
        data: { id: id },
        success: function (data) {
            $("#client_id").val(data.id);
            $("#name").val(data.name);
            $("#phone").val(data.phone);
            $("#email").val(data.email);

            let addr = {};
            try { addr = JSON.parse(data.address); } catch(e) {}

            $("#street").val(addr.street || "");
            $("#number").val(addr.number || "");
            $("#neighborhood").val(addr.neighborhood || "");
            $("#city").val(addr.city || "");
            $("#state").val(addr.state || "");
            $("#zipcode").val(addr.zipcode || "");
        }
    });
}

const deleteItem = (id) => {
    Swal.fire({
        title: "Alerta", text: "¿Seguro que deseas eliminar este cliente?",
        icon: "warning", showCancelButton: true, confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33", confirmButtonText: "Sí"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "../Controllers/adminClientsController.php?op=delete",
                type: "POST",
                headers: {"Authorization": "Bearer " + token},
                data: { id: id },
                success: function() {
                    Swal.fire({ toast: true, icon: 'success', title: 'Cliente eliminado' });
                    index();
                }
            });
        }
    });
};


const clean = () => {   
    $("#client_id").val('');
    $("#name").val('');
    $("#phone").val('');
    $("#email").val('');
    $("#company").val('');
    $("#street").val('');
    $("#number").val('');
    $("#neighborhood").val('');
    $("#city").val('');
    $("#state").val('');
    $("#zipcode").val('');

}
</script>
