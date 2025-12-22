<?php
require_once "../Middlewares/authMiddleware.php";
$userData = verificarToken();
require_once "../Models/Client.php";
$Client = new Client();

switch ($_GET["op"]) {
    case 'index':
        $rspta = $Client->index();
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            
            $boton_editar = '<button type="button" class="btn btn-sm btn-warning" onclick="show('.$reg->id.')"><i class="ti ti-edit"></i></button>';
            $boton_borrar = '<button type="button" class="btn btn-sm btn-danger" onclick="deleteItem('.$reg->id.')"><i class="ti ti-trash"></i></button>';

            $direccion = "";
            if (!empty($reg->address)) {
                $addr = json_decode($reg->address, true);
                if ($addr) {
                    $direccion = ($addr['street'] ?? '') . ' ' . ($addr['number'] ?? '') . ', ' . 
                                ($addr['neighborhood'] ?? '') . ', ' . 
                                ($addr['city'] ?? '');
                }
            }

            $estatus = $reg->status === "active"
                ? '<span class="badge bg-success">Activo</span>'
                : '<span class="badge bg-danger">Inactivo</span>';

            $data[] = array(
                $boton_editar . ' ' . $boton_borrar,
                $reg->id,
                $reg->name,
                $reg->phone,
                $reg->email,
                $direccion,
                $estatus
            );
        }

        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );

        echo json_encode($results);
    break;

    case 'store':
        $id = $_POST['client_id'] ?? null;
        $rspta = $Client->store($_POST);

        echo json_encode([
            "success" => true,
            "id" => $id ?: $rspta
        ]);
    break;


    case 'update':
        $rspta = $Client->update($_POST);
        echo json_encode(["success" => true, "result" => $rspta]);
    break;

    case 'show':
        $id = $_POST['id']; 
        $rspta = $Client->show($id);
        echo json_encode($rspta);
    break;

    case 'delete':
        $id = $_POST['id'];
        $rspta = $Client->delete($id);
        echo json_encode(["success" => true, "result" => $rspta]);
    break;

}
