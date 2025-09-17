<?php 
require_once "../Models/RouteSchedule.php";
require_once "../Models/Route.php";
$Route = new Route();
$RouteSchedule = new RouteSchedule();


switch ($_GET["op"]) {
    case 'store':
        $rspta = $RouteSchedule->store ( $_POST );
        echo $rspta;
    break;
    
    
    case 'show':
        $rspta = $RouteSchedule->show( $_POST );
        echo json_encode($rspta);
    break;
    
    case 'deleteItem':
        $rspta=$RouteSchedule->deleteItem($_POST);
        echo $rspta;
    break;

    case 'index':
    $rspta = $Route->index();
    $data = [];

    while ($reg = $rspta->fetch_object()) {
        $schedules = $RouteSchedule->index($reg->id);

        $horarios_html = '';
        $days_es = [
            "monday"    => "Lunes",
            "tuesday"   => "Martes",
            "wednesday" => "Miércoles",
            "thursday"  => "Jueves",
            "friday"    => "Viernes",
            "saturday"  => "Sábado",
            "sunday"    => "Domingo"
        ];

        // Colores dinámicos por día
        $day_colors = [
            "monday"    => "primary",   // Azul
            "tuesday"   => "success",   // Verde
            "wednesday" => "warning",   // Amarillo
            "thursday"  => "info",      // Celeste
            "friday"    => "danger",    // Rojo
            "saturday"  => "secondary", // Gris
            "sunday"    => "dark"       // Negro
        ];

        $horarios_por_dia = [];

        // Agrupar horarios por día
        while ($schedule = $schedules->fetch_object()) {
            $day = strtolower($schedule->day ?? '');
            if ($day) {
                $horarios_por_dia[$day][] = date("H:i", strtotime($schedule->leaving_time));
            }
        }

        // Pintar en orden de lunes a domingo
        foreach ($days_es as $day_en => $day_es) {
            $color = $day_colors[$day_en] ?? "secondary";

            $horarios_html .= '<div class="p-2 mb-2 border rounded shadow-sm" style="background:#f8f9fa;">';
            $horarios_html .= '<h6 class="fw-bold text-' . $color . ' mb-2">
                                  <i class="ti ti-calendar"></i> ' . $day_es . '
                               </h6>';

            if (!empty($horarios_por_dia[$day_en])) {
                $contador = 0;
                foreach ($horarios_por_dia[$day_en] as $hour) {
                    $contador++;
                    $horarios_html .= '<span class="badge bg-' . $color . ' text-white me-1 mb-1 p-2" 
                                            style="font-size:0.85rem; border-radius:8px;">
                                            <i class="ti ti-clock"></i> ' . $hour . '
                                       </span>';

                    if ($contador % 5 == 0) {
                        $horarios_html .= '<br>';
                    }
                }
            } else {
                $horarios_html .= '<span class="badge bg-light text-dark p-2" 
                                      style="font-size:0.85rem; border-radius:8px;">
                                      <i class="ti ti-ban"></i> Sin horarios
                                   </span>';
            }

            $horarios_html .= '</div>';
        }

        $boton_editar = '<button type="button" class="editar btn btn-sm btn-warning" 
                          onclick="show(' . $reg->id . ')"><i class="ti ti-edit"></i></button>';
        $boton_borrar = '<button type="button" class="eliminar btn btn-sm btn-danger" 
                          onclick="deleteItem(' . $reg->id . ')"><i class="ti ti-trash"></i></button>';

        $origen_html = '<div class="p-2 border rounded bg-light shadow-sm d-inline-block me-2">
                   <span class="badge bg-success me-1"><i class="ti ti-map-pin"></i> Origen</span><br>
                   <span class="fw-bold text-dark">' . htmlspecialchars($reg->origin) . '</span>
                </div>';

        $destino_html = '<div class="p-2 border rounded bg-light shadow-sm d-inline-block">
                            <span class="badge bg-danger me-1"><i class="ti ti-flag"></i> Destino</span><br>
                            <span class="fw-bold text-dark">' . htmlspecialchars($reg->destination) . '</span>
                        </div>';

        $ruta_html = '<div class="d-flex align-items-center gap-2">'
           . $origen_html
           . '<span class="fw-bold text-primary">→</span>'
           . $destino_html
           . '</div>';

        $data[] = [
            $boton_editar . ' ' . $boton_borrar,
            $reg->id,
            $ruta_html,
            $horarios_html
        ];
    }

    $results = [
        "sEcho" => 1,
        "iTotalRecords" => count($data),
        "iTotalDisplayRecords" => count($data),
        "aaData" => $data
    ]; 
    echo json_encode($results);
break;



}
?>
