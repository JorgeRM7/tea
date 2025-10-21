<?php
require_once "../Middlewares/authMiddleware.php";
$userData = verificarToken();
require_once "../Models/SpecialTripType.php";
$Type = new SpecialTripType();

switch ($_GET["op"]) {
   case 'index':
        $rspta = $Type->index();
        
        $data = Array();
        while ($reg = $rspta->fetch_object()) {

            $boton_editar = '<button type="button" class="editar btn btn-sm btn-warning" onclick="show('.$reg->id.')"><i class="ti ti-edit"></i></button>';
            $boton_borrar = '<button type="button" class="eliminar btn btn-sm btn-danger" onclick="deleteItem('.$reg->id.')"><i class="ti ti-trash"></i></button>';

            $estatus = $reg->status === "active"
                ? '<span class="badge bg-success">Activo</span>'
                : '<span class="badge bg-danger">Inactivo</span>';

            $data[] = array(
                $boton_editar . ' ' . $boton_borrar,
                $reg->id,
                $reg->origin,
                $reg->destination,
                $reg->days,
                number_format($reg->price, 2),
                $reg->valid_from,
                $reg->valid_to,
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

    case 'show':
        $id = $_POST['id'];
        $rspta = $Type->show($id);
        echo json_encode($rspta);
    break;

    case 'store':
        $rspta = $Type->store ( $_POST );
        echo $rspta;
    break;

    case 'update':
        $rspta = $Type->update($_POST);
        echo json_encode(["success" => true, "result" => $rspta]);
    break;

    case 'delete':
        $id = $_POST['id'];
        $rspta = $Type->delete($id);
        echo json_encode(["success" => true, "result" => $rspta]);
    break;
}
