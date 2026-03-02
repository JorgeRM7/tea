<!doctype html>
<?php ;$title = "Viajes Especiales"; ?>
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

          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h5 class="mb-0">Viajes Especiales</h5>
              <button class="btn btn-primary" onclick="create()"><i class="ti ti-cloud-up"></i> Crear</button>
            </div>
            <div class="card-datatable table-responsive">
              <table class="dt-responsive table table-striped" id="tbllistado">
                <thead>
                <tr>
                  <th>Acciones</th>
                  <th>#</th>
                  <th>Cliente</th>
                  <th>Vehículo</th>
                  <th>Origen</th>
                  <th>Destino</th>
                  <th>Días</th>
                  <th>Precio</th>
                  <th>Inicio</th>
                  <th>Fin</th>
                  <th>Estatus</th>
                </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>

        </div>

        <!-- Modal Crear/Editar -->
        <div class="modal animate__animated animate__flipInX" id="modal_create" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Registrar / Editar Viaje Especial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <form id="formulario" method="POST">
                  <input type="hidden" id="trip_id" name="id"/>

                  <div class="row">
                    <!-- Cliente -->
                    <div class="col-md-6">
                      <label class="form-label">Cliente</label>
                      <select id="client_id" name="client_id" class="form-select">
                        <option value="">--- Seleccionar ---</option>
                        <option value="_new">+ Nuevo cliente...</option>
                      </select>
                    </div>

                    <!-- Vehículo -->
                    <div class="col-md-6">
                      <label class="form-label">Vehículo</label>
                      <select id="vehicle_id" name="vehicle_id" class="form-select" required>
                        <option value="">--- Seleccionar ---</option>
                      </select>
                    </div>

                    <!-- Tipo de viaje (precios y días predefinidos) -->
                    <div class="col-md-12 mt-3">
                      <label class="form-label">Tipo de viaje (opcional / plantilla)</label>
                      <select id="trip_type_id" name="trip_type_id" class="form-select">
                        <option value="">--- Seleccionar plantilla ---</option>
                      </select>
                      <small class="text-muted">Al seleccionar una plantilla se autocompleta origen, destino, días y precio.</small>
                    </div>

                    <div class="col-md-6 mt-3">
                      <label class="form-label">Origen</label>
                      <input type="text" id="origin" name="origin" class="form-control" required/>
                    </div>
                    <div class="col-md-6 mt-3">
                      <label class="form-label">Destino</label>
                      <input type="text" id="destination" name="destination" class="form-control" required/>
                    </div>
                    <div class="col-md-3 mt-3">
                      <label class="form-label">Días</label>
                      <input type="number" id="days" name="days" class="form-control" required/>
                    </div>
                    <div class="col-md-3 mt-3">
                      <label class="form-label">Precio</label>
                      <input type="number" step="0.01" id="price" name="price" class="form-control" required/>
                    </div>
                    <div class="col-md-3 mt-3">
                      <label class="form-label">Fecha Inicio</label>
                      <input type="date" id="start_date" name="start_date" class="form-control" required/>
                    </div>
                    <div class="col-md-3 mt-3">
                      <label class="form-label">Fecha Fin</label>
                      <input type="date" id="end_date" name="end_date" class="form-control" required/>
                    </div>
                    <div class="col-md-3 mt-3">
                      <label class="form-label">Estatus</label>
                      <select id="status" name="status" class="form-select">
                        <option value="pending">Pendiente</option>
                        <option value="in_progress">En curso</option>
                        <option value="completed">Completado</option>
                        <option value="cancelled">Cancelado</option>
                      </select>
                    </div>
                  </div>

                  <!-- Alta rápida de cliente -->
                  <div id="new_client_box" class="border rounded p-3 mt-4" style="display:none;">
                    <h6 class="mb-3"><i class="ti ti-user-plus"></i> Nuevo cliente</h6>
                    <div class="row">
                      <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" id="nc_name" class="form-control" placeholder="Nombre del cliente">
                      </div>
                      <div class="col-md-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" id="nc_phone" class="form-control">
                      </div>
                      <div class="col-md-3">
                        <label class="form-label">Email</label>
                        <input type="email" id="nc_email" class="form-control">
                      </div>

                      <div class="col-12 mt-3">
                        <small class="text-muted">Dirección (opcional)</small>
                      </div>
                      <div class="col-md-6">
                        <input type="text" id="nc_street" class="form-control" placeholder="Calle">
                      </div>
                      <div class="col-md-2">
                        <input type="text" id="nc_number" class="form-control" placeholder="Núm.">
                      </div>
                      <div class="col-md-4">
                        <input type="text" id="nc_neighborhood" class="form-control" placeholder="Colonia">
                      </div>
                      <div class="col-md-4 mt-2">
                        <input type="text" id="nc_city" class="form-control" placeholder="Ciudad">
                      </div>
                      <div class="col-md-4 mt-2">
                        <input type="text" id="nc_state" class="form-control" placeholder="Estado">
                      </div>
                      <div class="col-md-4 mt-2">
                        <input type="text" id="nc_zip" class="form-control" placeholder="C.P.">
                      </div>
                    </div>
                  </div>

                </form>
              </div>
              <div class="modal-footer">
                <button class="btn btn-primary" onclick="store()"><i class="ti ti-device-floppy"></i> Guardar</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="clean()">Cerrar</button>
              </div>
            </div>
          </div>
        </div>
        <!-- Fin Modal -->

        <?php require_once('footer.php'); ?>
      </div>

    </div>
  </div>
</div>

</body>
</html>

<script>
  var tabla, TRIP_TYPES_CACHE = [];

  $(document).ready(function () {
    let module = $("#module").val();
    // evita fallo por i18n CDN si estás offline
    $.fn.dataTable.ext.errMode = 'none';
    const menuItem = document.querySelector('a[href="admin-special-trip-types.php"]').parentElement;
    menuItem.classList.add('active');
    const menuToggle = document.querySelector(`a[href="${module}"]`).parentElement;
    menuToggle.classList.add('open');        
    index();
  });

  const create = () => {
    $('#modal_create').modal('show');
    clean();
    loadOptions();
  };

  const loadOptions = () => {
  return new Promise((resolve) => {
    $.ajax({
      url: "../Controllers/adminSpecialTripsController.php?op=options",
      type: "GET",
      headers: {"Authorization": "Bearer " + token},
      dataType: "json",
      success: function (res) {
        // Clientes
        const $cli = $("#client_id").empty();
        $cli.append('<option value="">--- Seleccionar ---</option>');
        $cli.append('<option value="_new">+ Nuevo cliente...</option>');
        (res.clients || []).forEach(c => {
          $cli.append(`<option value="${c.id}">${c.name}</option>`);
        });

        // Vehículos
        const $veh = $("#vehicle_id").empty();
        $veh.append('<option value="">--- Seleccionar ---</option>');
        (res.vehicles || []).forEach(v => {
          $veh.append(`<option value="${v.id}">${v.unidad_number}</option>`);
        });

        // Tipos de viaje
        const $types = $("#trip_type_id").empty();
        $types.append('<option value="">--- Seleccionar plantilla ---</option>');
        TRIP_TYPES_CACHE = res.types || [];
        TRIP_TYPES_CACHE.forEach(t => {
          const label = `${t.origin} → ${t.destination} | ${t.days} días | $${parseFloat(t.price).toFixed(2)}`;
          $types.append(`<option value="${t.id}">${label}</option>`);
        });

        resolve();
      }
    });
  });
};


  $("#client_id").on("change", function(){
    if ($(this).val() === "_new") {
      $("#new_client_box").slideDown(150);
    } else {
      $("#new_client_box").slideUp(150);
    }
  });

  $("#trip_type_id").on("change", function(){
    const id = $(this).val();
    const item = TRIP_TYPES_CACHE.find(x => String(x.id) === String(id));
    if (!item) return;
    $("#origin").val(item.origin || "");
    $("#destination").val(item.destination || "");
    $("#days").val(item.days || "");
    $("#price").val(item.price || "");

    autoEndDate();
  });

  $("#days, #start_date").on("input change", autoEndDate);
  function autoEndDate(){   
    const days = parseInt($("#days").val() || "0", 10);
    const start = $("#start_date").val();
    if (!start || !days || days <= 0) return;
    const d = new Date(start);
    d.setDate(d.getDate() + (days - 1));
    const yyyy = d.getFullYear();
    const mm = String(d.getMonth()+1).padStart(2,"0");
    const dd = String(d.getDate()).padStart(2,"0");
    $("#end_date").val(`${yyyy}-${mm}-${dd}`);
  }

  const store = async () => {
    if ($("#client_id").val() === "_new") {
      const newId = await createClientInline();
      if (!newId) return; 
      $("#client_id").val(newId);
    }

    const form = document.getElementById("formulario");
    if (!form.checkValidity()) { form.reportValidity(); return; }

    const formData = new FormData(form);
    $.ajax({
      url: "../Controllers/adminSpecialTripsController.php?op=store",
      type: "POST",
      headers: { "Authorization": "Bearer " + token },
      data: formData,
      contentType: false,
      processData: false,
      success: function(resp) {
        let r = typeof resp === "string" ? JSON.parse(resp) : resp;
        if (r.error) {
            Swal.fire({ icon: "error", title: "Error", text: r.message });
            return;
        }
        Swal.fire({toast: true, icon: 'success', title: 'Guardado correctamente'});
        $('#modal_create').modal('hide');
        clean();
        index();
        },
      error: function(){
        Swal.fire({icon:'error', title:'Error', text:'No se pudo guardar el viaje.'});
      }
    });
  };


function createClientInline(){
  return new Promise((resolve) => {
    const name = $("#nc_name").val().trim();
    if (!name) {
      Swal.fire({icon:'warning', text:'Ingresa al menos el nombre del cliente.'});
      resolve(null); return;
    }
    const address = JSON.stringify({
      street: $("#nc_street").val(), number: $("#nc_number").val(),
      neighborhood: $("#nc_neighborhood").val(), city: $("#nc_city").val(),
      state: $("#nc_state").val(), zipcode: $("#nc_zip").val()
    });
    const fd = new FormData();
    fd.append("name", name);
    fd.append("phone", $("#nc_phone").val());
    fd.append("email", $("#nc_email").val());
    fd.append("address", address);
    fd.append("status", "active");

    $.ajax({
      url: "../Controllers/adminClientsController.php?op=store",
      type: "POST",
      headers: {"Authorization": "Bearer " + token},
      data: fd, contentType: false, processData: false,
      success: function(resp){
        try{
          const r = typeof resp === 'string' ? JSON.parse(resp) : resp;
          if (r && r.id) {
            Swal.fire({toast:true, icon:'success', title:'Cliente creado'});
            
            const $cli = $("#client_id");
            $cli.append(`<option value="${r.id}" selected>${name}</option>`);
            $cli.val(r.id).trigger("change");

            resolve(r.id);
          } else { resolve(null); }
        }catch(e){ resolve(null); }
      },
      error: function(){ resolve(null); }
    });
  });
}


  const index = () => {
    if ($.fn.DataTable.isDataTable('#tbllistado')) {
      $('#tbllistado').DataTable().ajax.reload();
      return;
    }
    tabla = $('#tbllistado').dataTable({
      "aProcessing": true,
      "aServerSide": true,
      "ajax": {
        url: '../Controllers/adminSpecialTripsController.php?op=index',
        type: "get",
        headers: { "Authorization": "Bearer " + token },
        dataType: "json",
        error: (e) => console.log(e.responseText)
      },
      "bDestroy": true,
      "iDisplayLength": 10,
      "lengthMenu": [5, 10, 25, 50, 100],
      "language": { "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json" },
      "responsive": false,
      "order": [[1, "desc"]]
    }).DataTable();
  };

const show = (id) => {
  $('#modal_create').modal('show');
  clean();

  loadOptions().then(() => {
    $.ajax({
      url: "../Controllers/adminSpecialTripsController.php?op=show",
      type: "POST",
      dataType: "json",
      headers: {"Authorization": "Bearer " + token},
      data: { id },
      success: function (d) {
        $("#trip_id").val(d.id);
        $("#origin").val(d.origin);
        $("#destination").val(d.destination);
        $("#days").val(d.days);
        $("#price").val(d.price);
        $("#start_date").val(d.start_date);
        $("#end_date").val(d.end_date);
        $("#status").val(d.status);

        $("#client_id").val(d.client_id).trigger("change");
        $("#vehicle_id").val(d.vehicle_id).trigger("change");
        $("#trip_type_id").val(d.trip_type_id).trigger("change");
      }
    });
  });
};

  const deleteItem = (id) => {
    Swal.fire({
      title: "¿Seguro?", text: "Esta acción eliminará el viaje.",
      icon: "warning", showCancelButton: true,
      confirmButtonColor: "#3085d6", cancelButtonColor: "#d33", confirmButtonText: "Sí"
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "../Controllers/adminSpecialTripsController.php?op=delete",
          type: "POST",
          headers: {"Authorization": "Bearer " + token},
          data: { id },
          success: function () {
            Swal.fire({toast: true, icon:'success', title: 'Eliminado correctamente'});
            index();
          }
        });
      }
    });
  };

  const clean = () => {
    $("#trip_id").val('');
    $("#client_id").val('');
    $("#vehicle_id").val('');
    $("#trip_type_id").val('');
    $("#origin").val('');
    $("#destination").val('');
    $("#days").val('');
    $("#price").val('');
    $("#start_date").val('');
    $("#end_date").val('');
    $("#status").val('pending');

    $("#new_client_box input").val('');
    $("#new_client_box").hide();
  };

  const contract = ( id ) => {
    window.open('../Pdf/contract.php?id='+id)
  }
</script>
