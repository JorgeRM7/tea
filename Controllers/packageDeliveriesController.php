<?php
session_start();
header('Content-Type: application/json');
require_once "../Models/PackageDelivery.php";

$Package = new PackageDelivery();
$operation = $_GET['op'] ?? null;

switch ($operation) {
    case 'store':
        $response = $Package->store($_POST, $_FILES);
        echo json_encode($response);
    break;

    case 'update':
        $response = $Package->updateNames($_POST );
        echo json_encode($response);
    break;

    case 'details':
        $data = $Package->details($_POST);
        echo json_encode($data);
        break;

    case 'show-subpaths':
        $rspta = $Package->show_subpaths($_POST);
        $data = [];
        while ($reg = $rspta->fetch_assoc()) {
            $data[] = [
                "routes_stop_id" => $reg['id'],
                "destination" => $reg['destination'],
                "price" => $reg['price'],
            ];
        }
        echo json_encode($data);
        break;

    case 'routes_by_schedule':
        $rspta = $Package->routes_by_schedule($_POST);
        $data = [];
        while ($reg = $rspta->fetch_assoc()) {
            $data[] = [
                "route_schedule_id" => $reg['route_schedule_id'],
                "origin" => $reg['origin'],
                "destination" => $reg['destination'],
                "cost" => $reg['cost'],
                "leaving_time" => $reg['leaving_time'],
                "tickets_sale" => $reg['tickets_sale'],
                "vehicle_capacity" => $reg['vehicle_capacity'],
                "vehicle_id" => $reg['vehicle_id'],
                
            ];
        }

        echo json_encode($data);
        break;           
    case 'index':
        $result = $Package->index($_GET);
        $data = [];
        while ($row = $result->fetch_object()) {
            $badge = '<span class="badge bg-label-primary">' . htmlspecialchars($row->status) . '</span>';
            $route = '<div class="fw-semibold">' . htmlspecialchars($row->stop_origin ?? $row->route_origin) . ' → ' . htmlspecialchars($row->stop_destination ?? $row->route_destination) . '</div>';
            $driver = !empty($row->driver_name) ? $row->driver_name : 'Sin asignar';
            $unit = !empty($row->unidad_number) ? $row->unidad_number : 'N/A';

            $boton_show='<button class="btn btn-sm btn-primary" onclick="showPackage(' . $row->id . ')"><i class="ti ti-eye"></i></button>';
            $boton_editar = '<button type="button" class="editar btn btn-sm btn-warning" onclick="show('.$row->id.')"><i class="ti ti-edit"></i></button>';
            $bonton_borrar = '<button type="button" class="eliminar btn btn-sm btn-danger" onclick="deleteItem(' . $row->id . ')" title="Eliminar boleto">
                                <i class="ti ti-trash"></i>
                            </button>';
            $data[] = [
                $boton_show.' '.$boton_editar.' '.$bonton_borrar,
                '<strong>' . $row->id . '</strong>',
                $route,
                '$ ' . number_format($row->price, 2),
                htmlspecialchars($row->status),
                $driver,
                $unit,
                $row->tracking_code
            ];
        }

        echo json_encode([
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        ]);
        break;

    case 'show':
        $response = $Package->show($_POST);
        echo json_encode($response);
        break;

    case 'scan':
        $encrypted = $_POST['encrypted'] ?? null;
        $tracking_code = $_POST['tracking_code'] ?? null;

        $response = $Package->scan([
            'encrypted' => $encrypted,
            'tracking_code' => $tracking_code
        ]);

        echo json_encode($response);
        break;        

    case 'timeline':
        $response = $Package->timeline($_GET);
        echo json_encode($response);
        break;

    case 'update-status':
        $response = $Package->updateStatus($_POST, $_FILES);
        echo json_encode($response);
        break;

    case 'tracking':
        $response = $Package->tracking($_GET);
        echo json_encode($response);
        break;

    case 'routes':
        $result = $Package->routes($_GET);
        $data = [];
        while ($row = $result->fetch_object()) {
            $data[] = $row;
        }
        echo json_encode($data);
        break;

    case 'subpaths':
        $response = $Package->subpaths($_GET);
        echo json_encode($response);
        break;

    case 'schedules':
        $response = $Package->schedules($_GET);
        echo json_encode($response);
        break;

    case 'schedule-details':
        $response = $Package->scheduleDetails($_GET);
        echo json_encode($response);
        break;

    case 'branches':
        $response = $Package->branches();
        echo json_encode($response);
        break;

    case 'deleteItem':
        $rspta=$Package->deleteItem($_POST);
        echo $rspta;
    break;

    case 'xls':
        $data = $Package->xls($_POST);
        echo json_encode($data);
    break;

    default:
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Operación no encontrada']);
        break;
}
