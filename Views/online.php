<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Venta de Boletos - TEA</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #0d1a1e, #0a4d0e);
      color: #fff;
      font-family: 'Public Sans', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .ticket-box {
      background: rgba(255,255,255,0.1);
      border-radius: 20px;
      padding: 2rem;
      width: 100%;
      max-width: 500px;
      backdrop-filter: blur(15px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.4);
    }
    .form-label {
      font-weight: 600;
      color: #f2a71e;
    }
    .btn-buy {
      background: linear-gradient(90deg, #38b449, #f2a71e);
      border: none;
      padding: 0.9rem;
      font-weight: 700;
      font-size: 1.1rem;
      border-radius: 12px;
      transition: all 0.3s ease;
    }
    .btn-buy:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.5);
    }
    .summary-box {
      background: rgba(255,255,255,0.08);
      border-radius: 12px;
      padding: 1rem;
      margin-top: 1rem;
    }
    .summary-box h6 {
      margin: 0;
      font-size: 0.9rem;
      color: #a9b5bd;
    }
    .summary-box p {
      font-size: 1rem;
      font-weight: bold;
      margin: 0;
      color: #fff;
    }
  </style>
</head>
<body>
    <div class="ticket-box">
        <div class="text-center mb-4">
            <img src="../assets/img/logo.png" alt="TEA" width="80" class="mb-3">
            <h3 class="fw-bold text-uppercase">Venta de Boletos</h3>
            <p class="text-white">Selecciona tu viaje y compra tu boleto en línea</p>

        </div>

        <form id="ticketForm">
        <div class="mb-3">
            <label class="form-label"><i class="bi bi-geo-alt-fill"></i> Origen</label>
            <select class="form-select" id="origin">
                <option selected disabled>Selecciona origen</option>
                <option value="Morelia">Morelia</option>
                <option value="Uruapan">Uruapan</option>
                <option value="Pátzcuaro">Pátzcuaro</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label"><i class="bi bi-flag-fill"></i> Destino</label>
            <select class="form-select" id="destination">
                <option selected disabled>Selecciona destino</option>
                <option value="Morelia">Morelia</option>
                <option value="Uruapan">Uruapan</option>
                <option value="Pátzcuaro">Pátzcuaro</option>
            </select>
        </div>

        <div class="mb-3 row g-2">
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-calendar-event"></i> Fecha</label>
                <input type="date" class="form-control" id="date" value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-clock-fill"></i> Horario</label>
                <select class="form-select" id="schedule">
                    <option selected disabled>Selecciona horario</option>
                    <option>08:00</option>
                    <option>10:30</option>
                    <option>14:00</option>
                    <option>18:00</option>
                </select>
            </div>
        </div>

        <div class="mb-3 row g-2">
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-ticket-detailed-fill"></i> Cantidad</label>
                <input type="number" class="form-control" id="quantity" min="1" value="1">
            </div>
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-cash"></i> Costo</label>
                <input type="text" class="form-control" id="cost" value="120" readonly>
            </div>
        </div>
        <div class="summary-box">
            <h6><i class="bi bi-receipt"></i> Resumen</h6>
            <p id="summary">Total: $120</p>
        </div>

        <button type="button" class="btn btn-buy w-100 mt-3" onclick="buyTicket()">
            <i class="bi bi-credit-card"></i> Comprar Boleto
        </button>
    </form>
</div>

<script>
function buyTicket() {
    let origin = document.getElementById("origin").value;
    let destination = document.getElementById("destination").value;
    let date = document.getElementById("date").value;
    let schedule = document.getElementById("schedule").value;
    let quantity = parseInt(document.getElementById("quantity").value);
    let cost = parseFloat(document.getElementById("cost").value);

    let total = quantity * cost;
    document.getElementById("summary").innerText = `Total: $${total}`;

    alert(`Compra realizada:\nOrigen: ${origin}\nDestino: ${destination}\nFecha: ${date}\nHorario: ${schedule}\nBoletos: ${quantity}\nTotal: $${total}`);
}
</script>
</body>
</html>
