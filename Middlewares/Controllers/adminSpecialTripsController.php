<?php
require_once "../Middlewares/authMiddleware.php";
$userData = verificarToken();
require_once "../Models/SpecialTrip.php";
$Trip = new SpecialTrip();

switch ($_GET["op"]) {

    case 'index':
        $rspta = $Trip->index();
        $data = [];
        while ($reg = $rspta->fetch_assoc()) {
            $btnEdit = '<button type="button" class="editar btn btn-sm btn-warning" onclick="show('.$reg['id'].')"><i class="ti ti-edit"></i></button>';
            $btnDel  = '<button type="button" class="eliminar btn btn-sm btn-danger" onclick="deleteItem('.$reg['id'].')"><i class="ti ti-trash"></i></button>';

            $badgeClass = ($reg['status']=='pending'?'warning':($reg['status']=='in_progress'?'info':($reg['status']=='completed'?'success':'danger')));

            $data[] = [
                $btnEdit.' '.$btnDel,
                $reg['id'],
                $reg['client_name'] ?? '—',
                $reg['vehicle_plate'],
                $reg['origin'],
                $reg['destination'],
                $reg['days'],
                number_format((float)$reg['price'], 2),
                $reg['start_date'],
                $reg['end_date'],
                '<span class="badge bg-'.$badgeClass.'">'.ucfirst(str_replace('_', ' ', $reg['status'])).'</span>'
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

    case 'options':
        $opt = $Trip->options();
        echo json_encode($opt);
    break;

    case 'show':
        $id = $_POST['id'];
        $rspta = $Trip->show($id);
        echo json_encode($rspta);
    break;

    case 'store':
        try {
            $rspta = $Trip->store($_POST);
            if (isset($rspta['error'])) {
                echo json_encode(["error" => true, "message" => $rspta['message']]);
            } else {
                echo json_encode(["success" => true, "id" => $rspta]);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => true, "message" => $e->getMessage()]);
        }
    break;

    case 'delete':
        $id = $_POST['id'];
        $rspta = $Trip->delete($id);
        echo json_encode($rspta);
    break;
}
