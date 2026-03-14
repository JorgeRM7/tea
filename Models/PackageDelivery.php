<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once dirname(__DIR__) . "/Database/conexion.php";

class PackageDelivery
{
    private $uploadBase;

    public function __construct()
    {
        $this->uploadBase = dirname(__DIR__) . "/assets/uploads/packages";
    }

    public function store(array $data, array $files = []): array
    {
        date_default_timezone_set('America/Mexico_City');
        $now = date('Y-m-d H:i:s');
        $user_id = $_SESSION['user_id'] ?? null;
        $branch_office_id = $_SESSION['branch_office_id'] ?? null;
        $route_id = $data['route_id'] ?? null;
        $route_stop_id = $data['routes_stop_id'] ?? null;
        $route_schedule_id = $data['route_schedule_id'] ?? null;
        $vehicle_id = $data['vehicle_id'] ?? null;
        $employee_id = $data['employee_id'] ?? null;
        $price = $data['price'] ?? 0;
        $quantity = $data['quantity'] ?? 1;
        $description = $data['description'] ?? '';
        $sender_name = $data['sender_name'] ?? '';
        $sender_phone = $data['sender_phone'] ?? '';
        $receiver_name = $data['receiver_name'] ?? '';
        $receiver_phone = $data['receiver_phone'] ?? '';
        $package_weight = $data['package_weight'] ?? null;
        $declared_value = $data['declared_value'] ?? null;
        $notes = $data['notes'] ?? null;
        $status = 'CREADO';

        try {
            iniciarTransaccion();

            $sql = "
                INSERT INTO `tickets_delivery` (
                    `route_id`,
                    `route_stop_id`,
                    `route_schedule_id`,
                    `vehicle_id`,
                    `employee_id`,
                    `price`,
                    `quantity`,
                    `date`,
                    `description`,
                    `branch_office_id`,
                    `status`,
                    `status_changed_at`,
                    `user_id`,
                    `sender_name`,
                    `sender_phone`,
                    `receiver_name`,
                    `receiver_phone`,
                    `package_weight`,
                    `declared_value`,
                    `notes`,
                    `created_at`,
                    `updated_at`
                ) VALUES (
                    '$route_id',
                    '$route_stop_id',
                    '$route_schedule_id',
                    '$vehicle_id',
                    '$employee_id',
                    '$price',
                    '$quantity',
                    '$now',
                    '$description',
                    '$branch_office_id',
                    '$status',
                    '$now',
                    '$user_id',
                    '$sender_name',
                    '$sender_phone',
                    '$receiver_name',
                    '$receiver_phone',
                    '$package_weight',
                    '$declared_value',
                    '$notes',
                    '$now',
                    '$now'
                )
            ";

            $result = ejecutarConsulta($sql);
            if (!$result) {
                global $conexion;
                throw new Exception('Error al guardar el paquete: ' . $conexion->error);
            }

            global $conexion;
            $package_id = mysqli_insert_id($conexion);
            $tracking_code = 'PKG' . str_pad($package_id, 8, '0', STR_PAD_LEFT);
            $tracking_pin = $this->generatePin($receiver_phone, $data['tracking_pin'] ?? null);

            ejecutarConsulta("UPDATE `tickets_delivery` SET `tracking_code`='$tracking_code', `tracking_pin`='$tracking_pin' WHERE id='$package_id'");

            $this->storeEvent(
            $package_id, 
            $status,
            'Paquete registrado en sucursal de origen',
            $user_id);

            confirmarTransaccion();

            return [
                'success' => true,
                'id' => $package_id,
                'tracking_code' => $tracking_code,
                'tracking_pin' => $tracking_pin
            ];
        } catch (Exception $e) {
            revertirTransaccion();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function details( $data ){
        $route_schedule_id = $data['route_schedule_id'] ?? 0;
        $route_stop_id = $data['route_stop_id'] ?? 0;
        
        $sql = "SELECT 
                    routes.id AS route_id,
                    routes_schedule.id AS route_schedule_id,
                    vehicles.unidad_number AS vehicle_id,
                    vehicles.capacity AS vehicle_capacity,
                    employees.id AS employee_id,
                    routes_schedule.leaving_time,
                    routes_schedule.date,
                    routes_stop.id AS routes_stop_id,
                    routes_stop.origin,
                    routes_stop.destination,
                    routes_stop.price,
                    vehicles.type,
                    vehicles.model,
                    employees.name,
                    (SELECT COUNT(id) FROM tickets WHERE route_schedule_id ='$route_schedule_id' AND status ='VENDIDO') AS tickets_sale
                FROM `routes_schedule`
                LEFT JOIN routes ON routes.id = routes_schedule.route_id
                INNER JOIN routes_stop ON routes_stop.route_id = routes.id
                LEFT JOIN vehicles ON vehicles.id = routes_schedule.vehicle_id
                LEFT JOIN employees ON employees.id = vehicles.employee_id
                WHERE routes_schedule.id='$route_schedule_id' AND routes_stop.id ='$route_stop_id'";
                // echo $sql;
        return ejecutarConsultaSimpleFila($sql);
    }

    public function show_subpaths ( $data ){
        $route_id = $data['route_id'] ?? null;
        $sql = "SELECT * FROM `routes_stop` WHERE route_id='$route_id' AND deleted_at is null";
        return ejecutarConsulta($sql);
    }


    public function routes_by_schedule($data) {
        $hour  = date("H:i:s"); 
        $today = date("Y-m-d");
        $search_date     = $data['search_date'] ?? NULL;
        $search_schedule = $data['search_schedule'] ?? NULL;
        $search_route    = $data['search_route'] ?? NULL;

        $sql = "SELECT 
                    routes.*,
                    routes_schedule.leaving_time,
                    routes_schedule.id AS route_schedule_id,
                    vehicles.capacity AS vehicle_capacity,
                    vehicles.id AS vehicle_id,
                    (SELECT COUNT(id) FROM tickets WHERE route_schedule_id = routes_schedule.id AND status ='VENDIDO') AS tickets_sale
                FROM `routes_schedule` 
                INNER JOIN routes ON routes.id = routes_schedule.route_id
                LEFT JOIN vehicles ON vehicles.id = routes_schedule.vehicle_id
                WHERE 1=1";

        if (!empty($search_date)) {
            if ($search_date == $today) {
                $sql .= " AND routes_schedule.date = '$search_date' 
                        AND routes_schedule.leaving_time >= '$hour'";
            } elseif ($search_date > $today) {
                $sql .= " AND routes_schedule.date = '$search_date'";
            } else {
                $sql .= " AND 1=0";
            }
        }

        if (!empty($search_schedule)) {
            $sql .= " AND routes_schedule.id = '$search_schedule'";
        }

        if (!empty($search_route)) {
            $sql .= " AND routes_schedule.route_id = '$search_route'";
        }

        $sql .= " ORDER BY routes_schedule.leaving_time ASC";

        return ejecutarConsulta($sql);
    }    


    public function index(array $filters)
    {
        $branch_id = $filters['branch_office_id'] ?? null;
        $view = $filters['view'] ?? 'origin';
        $status = $filters['status'] ?? null;
        $date_start = $filters['date'] ?? null;
        $date_end = $filters['date_filter_end'] ?? null;

        $sql = "
            SELECT 
                td.*, 
                routes.origin AS route_origin,
                routes.destination AS route_destination,
                routes_stop.origin AS stop_origin,
                routes_stop.destination AS stop_destination,
                vehicles.unidad_number,
                CONCAT(employees.name,' ', employees.paternal_surname) AS driver_name
            FROM tickets_delivery td
            LEFT JOIN routes ON routes.id = td.route_id
            LEFT JOIN routes_stop ON routes_stop.id = td.route_stop_id
            LEFT JOIN vehicles ON vehicles.id = td.vehicle_id
            LEFT JOIN employees ON employees.id = td.employee_id
            WHERE td.deleted_at IS NULL
            ";
            
        // if(!empty($view)){
        //     if ($view === 'origin') {
        //         $sql .= " AND td.branch_office_id = '$branch_id'";
        //     } elseif ($view === 'destination') {
        //         $sql .= " AND td.branch_office_destination_id = '$branch_id'";
        //     }
        // }

        if (!empty($status)) {
            $sql .= " AND td.status = '$status'";
        }

        if (!empty($date_start) && !empty($date_end)) {
            $sql .= " AND td.date BETWEEN '$date_start' AND '$date_end'";
        }

        $sql .= " ORDER BY td.id DESC";

        return ejecutarConsulta($sql);
    }

    public function show(array $data)
    {
        $package_id = $data['package_id'] ?? null;
        if (!$package_id) {
            return null;
        }

        $sql = "
            SELECT 
                td.*, 
                routes.origin AS route_origin,
                routes.destination AS route_destination,
                routes_stop.origin AS stop_origin,
                routes_stop.destination AS stop_destination,
                vehicles.unidad_number,
                CONCAT(employees.name,' ', employees.paternal_surname, ' ', employees.maternal_surname) AS driver_name
            FROM tickets_delivery td
            LEFT JOIN routes ON routes.id = td.route_id
            LEFT JOIN routes_stop ON routes_stop.id = td.route_stop_id
            LEFT JOIN vehicles ON vehicles.id = td.vehicle_id
            LEFT JOIN employees ON employees.id = td.employee_id
            WHERE td.id = '$package_id'
        ";

        return ejecutarConsultaSimpleFila($sql);
    }

    private function decryptData($data, $key, $method)
    {
        $data = base64_decode($data);

        $ivLength = openssl_cipher_iv_length($method);
        $iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);

        return openssl_decrypt($encrypted, $method, $key, 0, $iv);
    }

    public function scan(array $data)
    {
        $secretKey = 'TEA_SUPER_SECRET_2026';
        $method = 'AES-256-CBC';

        $encrypted = $data['encrypted'] ?? null;
        $tracking_code = $data['tracking_code'] ?? null;

        if ($encrypted) {

            $decrypted = $this->decryptData($encrypted, $secretKey, $method);

            if (!$decrypted) {
                return [
                    "success" => false,
                    "message" => "QR inválido"
                ];
            }

            $payload = json_decode($decrypted, true);
            $tracking_code = $payload['code'] ?? null;
        }

        if (!$tracking_code) {
            return [
                "success" => false,
                "message" => "Código no válido"
            ];
        }

        $tracking_code = addslashes($tracking_code);
        
            $sql = "
                SELECT 
                    td.*, 
                    routes.origin AS route_origin,
                    routes.destination AS route_destination,
                    routes_stop.origin AS stop_origin,
                    routes_stop.destination AS stop_destination,
                    vehicles.unidad_number,
                    CONCAT(employees.name,' ', employees.paternal_surname, ' ', employees.maternal_surname) AS driver_name                
                FROM tickets_delivery td
                LEFT JOIN routes ON routes.id = td.route_id
                LEFT JOIN routes_stop ON routes_stop.id = td.route_stop_id
                LEFT JOIN vehicles ON vehicles.id = td.vehicle_id
                LEFT JOIN employees ON employees.id = td.employee_id
                WHERE td.tracking_code = '$tracking_code'
                LIMIT 1
            ";

            $package = ejecutarConsultaSimpleFila($sql);

            if (!$package) {
                return [
                    "success" => false,
                    "message" => "Paquete no encontrado"
                ];
            }

            return [
                "success" => true,
                "package" => $package
            ];
        }

    public function timeline(array $data)
    {
        $package_id = $data['package_id'] ?? null;
        if (!$package_id) {
            return [];
        }

        $sql = "
            SELECT events.*, branch_offices.name AS branch_name
            FROM tickets_delivery_events events
            LEFT JOIN branch_offices ON branch_offices.id = events.branch_office_id
            WHERE events.tickets_delivery_id = '$package_id'
            ORDER BY events.id ASC
        ";

        $result = ejecutarConsulta($sql);
        $data = [];
        while ($row = $result->fetch_object()) {
            $data[] = $row;
        }
        return $data;
    }

    public function updateStatus(array $data, array $files = []): array
    {
        $package_id = $data['package_id'] ?? null;
        $status = $data['status'] ?? null;
        $notes = $data['notes'] ?? null;
        $branch_office_id = $data['branch_office_id'] ?? null;
        $user_id = $_SESSION['user_id'] ?? null;

        if (!$package_id || !$status) {
            return ['success' => false, 'message' => 'Datos incompletos'];
        }

        $now = date('Y-m-d H:i:s');
        $photo_path = null;

        if (!empty($files['photo']['tmp_name'])) {
            $photo_path = $this->saveMedia($package_id, $files['photo']);
        }

        $this->storeEvent($package_id, $status, $notes, $branch_office_id, $user_id, $photo_path);

        $updateSql = "
            UPDATE `tickets_delivery`
            SET 
                `status` = '$status',               
                `status_changed_at` = '$now',
                `photo_path` = IFNULL('$photo_path', `photo_path`),
                `updated_at` = '$now'
                ";

                if($status === 'EN_DESTINO')
                    {
                        $updateSql.= " , `branch_office_destination_id` = '$branch_office_id'";
                    }

                        $updateSql.= " WHERE id = '$package_id'";

        ejecutarConsulta($updateSql);

        return ['success' => true, 'message' => 'Estatus actualizado'];
    }

    public function tracking(array $params): array
    {
        $tracking_code = $params['tracking_code'] ?? null;
        $tracking_pin = $params['tracking_pin'] ?? null;

        if (empty($tracking_code) || empty($tracking_pin)) {
            return ['success' => false, 'message' => 'Código o PIN inválidos'];
        }

        $sql = "
            SELECT td.*, routes.origin AS route_origin, routes.destination AS route_destination,
                   routes_stop.origin AS stop_origin, routes_stop.destination AS stop_destination
            FROM tickets_delivery td
            LEFT JOIN routes ON routes.id = td.route_id
            LEFT JOIN routes_stop ON routes_stop.id = td.route_stop_id
            WHERE td.tracking_code = '$tracking_code' AND td.tracking_pin = '$tracking_pin'
        ";

        $package = ejecutarConsultaSimpleFila($sql);
        if (!$package) {
            return ['success' => false, 'message' => 'No se encontró información'];
        }

        $events = $this->timeline(['package_id' => $package['id']]);

        return [
            'success' => true,
            'package' => $package,
            'events' => $events
        ];
    }

    public function findByTrackingCode(string $tracking_code)
    {
        $sql = "
            SELECT 
                td.*, 
                routes.origin AS route_origin,
                routes.destination AS route_destination,
                routes_stop.origin AS stop_origin,
                routes_stop.destination AS stop_destination,
                vehicles.unidad_number,
                CONCAT(employees.name,' ', employees.paternal_surname,' ',employees.maternal_surname) AS driver_name,
                origin_branch.name AS origin_branch,
                destination_branch.name AS destination_branch
            FROM tickets_delivery td
            LEFT JOIN routes ON routes.id = td.route_id
            LEFT JOIN routes_stop ON routes_stop.id = td.route_stop_id
            LEFT JOIN vehicles ON vehicles.id = td.vehicle_id
            LEFT JOIN employees ON employees.id = td.employee_id
            LEFT JOIN branch_offices origin_branch ON origin_branch.id = td.branch_origin_id
            LEFT JOIN branch_offices destination_branch ON destination_branch.id = td.branch_destination_id
            WHERE td.tracking_code = '$tracking_code'
        ";

        return ejecutarConsultaSimpleFila($sql);
    }

    public function routes(array $filters)
    {
        $branch_ids = [];
        $user_id = $_SESSION['user_id'] ?? null;

        if ($user_id) {
            $sql = "SELECT branch_office_id FROM branch_offices_user WHERE user_id = '$user_id' AND deleted_at IS NULL";
            $result = ejecutarConsulta($sql);
            while ($row = $result->fetch_assoc()) {
                $branch_ids[] = $row['branch_office_id'];
            }
        }

        if (!empty($branch_ids)) {
            $ids = implode(',', array_unique($branch_ids));
            $sql = "SELECT * FROM routes WHERE deleted_at IS NULL AND branch_office_id IN ($ids)";
        } else {
            $sql = "SELECT * FROM routes WHERE deleted_at IS NULL";
        }

        return ejecutarConsulta($sql);
    }

    public function subpaths(array $filters)
    {
        $route_id = $filters['route_id'] ?? null;
        if (!$route_id) {
            return [];
        }
        $sql = "SELECT id AS routes_stop_id, origin, destination FROM routes_stop WHERE route_id = '$route_id' AND deleted_at IS NULL";
        $result = ejecutarConsulta($sql);
        $data = [];
        while ($row = $result->fetch_object()) {
            $data[] = $row;
        }
        return $data;
    }

    public function schedules(array $filters)
    {
        $route_id = $filters['route_id'] ?? null;
        $date = $filters['date'] ?? date('Y-m-d');
        if (!$route_id) {
            return [];
        }

        $sql = "
            SELECT 
                routes_schedule.id,
                routes_schedule.leaving_time,
                routes_schedule.date,
                routes_schedule.vehicle_id,
                vehicles.unidad_number,
                vehicles.capacity,
                routes_schedule.route_id
            FROM routes_schedule
            LEFT JOIN vehicles ON vehicles.id = routes_schedule.vehicle_id
            WHERE routes_schedule.route_id = '$route_id'
            AND routes_schedule.date = '$date'
            ORDER BY routes_schedule.leaving_time ASC
        ";

        $result = ejecutarConsulta($sql);
        $data = [];
        while ($row = $result->fetch_object()) {
            $data[] = $row;
        }
        return $data;
    }

    public function scheduleDetails(array $filters)
    {
        $route_schedule_id = $filters['route_schedule_id'] ?? null;
        if (!$route_schedule_id) {
            return null;
        }

        $sql = "
            SELECT 
                routes_schedule.*, 
                vehicles.unidad_number,
                vehicles.id AS vehicle_id,
                vehicles.employee_id,
                CONCAT(employees.name,' ', employees.paternal_surname,' ',employees.maternal_surname) AS driver_name
            FROM routes_schedule
            LEFT JOIN vehicles ON vehicles.id = routes_schedule.vehicle_id
            LEFT JOIN employees ON employees.id = vehicles.employee_id
            WHERE routes_schedule.id = '$route_schedule_id'
        ";

        return ejecutarConsultaSimpleFila($sql);
    }

    public function branches()
    {
        $sql = "SELECT id, name FROM branch_offices WHERE deleted_at IS NULL";
        $result = ejecutarConsulta($sql);
        $data = [];
        while ($row = $result->fetch_object()) {
            $data[] = $row;
        }
        return $data;
    }

    private function generatePin(?string $receiver_phone, ?string $pin = null): string
    {
        if (!empty($pin) && strlen($pin) === 4) {
            return $pin;
        }

        if ($receiver_phone) {
            $digits = preg_replace('/\D/', '', $receiver_phone);
            if (strlen($digits) >= 4) {
                return substr($digits, -4);
            }
        }

        return str_pad((string)rand(0, 9999), 4, '0', STR_PAD_LEFT);
    }

    private function storeEvent(
        int $package_id,
        string $status,
        ?string $notes,
        ?int $branch_id = null, 
        ?int $user_id = null,
        ?string $photo = null): void
    {
        $notes = $notes ?? '';
        $branch_id = $branch_id ? "'$branch_id'" : 'NULL';
        $user_id = $user_id ? "'$user_id'" : 'NULL';
        $photo = $photo ? "'$photo'" : 'NULL';

        $sql = "
            INSERT INTO tickets_delivery_events (
                tickets_delivery_id,
                status,
                notes,
                branch_office_id,
                user_id,
                photo_path,
                created_at
            ) VALUES (
                '$package_id',
                '$status',
                '$notes',
                $branch_id,
                $user_id,
                $photo,
                NOW()
            )
        ";
        ejecutarConsulta($sql);
    }

    private function saveMedia(int $package_id, array $file): ?string
    {
        if (empty($file['tmp_name'])) {
            return null;
        }

        if (!is_dir($this->uploadBase)) {
            mkdir($this->uploadBase, 0775, true);
        }

        $packagePath = $this->uploadBase . DIRECTORY_SEPARATOR . $package_id;
        if (!is_dir($packagePath)) {
            mkdir($packagePath, 0775, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $extension = strtolower($extension);
        if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
            $extension = 'jpg';
        }

        $filename = 'evidence_' . time() . '_' . rand(100, 999) . '.' . $extension;
        $fullPath = $packagePath . DIRECTORY_SEPARATOR . $filename;

        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            return null;
        }

        $relative = 'assets/uploads/packages/' . $package_id . '/' . $filename;

        $sql = "
            INSERT INTO tickets_delivery_media (
                tickets_delivery_id,
                file_path,
                created_at
            ) VALUES (
                '$package_id',
                '$relative',
                NOW()
            )
        ";
        ejecutarConsulta($sql);

        return $relative;
    }

    public function updateNames( $data ){
        $sender_name    = $data['sender_name'];
        $sender_phone   = $data['sender_phone'];
        $receiver_name  = $data['receiver_name'];
        $receiver_phone = $data['receiver_phone'];
        $package_id = $data['package_id'];
    
        $sql="
        UPDATE `tickets_delivery` 
        SET 
            `sender_name`   ='$sender_name',
            `sender_phone`  ='$sender_phone',
            `receiver_name` ='$receiver_name',
            `receiver_phone`='$receiver_phone',
            `updated_at`    = NOW() 
        WHERE `id`='$package_id'";
        return ejecutarConsulta($sql);
    }

    public function deleteItem ( $data ){
        $package_id = $data['package_id'];
        $comment    = $data['comment'];

        $sql="
        UPDATE `tickets_delivery` 
        SET 
            `status`                = 'CANCELADO',
            `comment_cancellation`  = '$comment',
            `date_cancellation`     = NOW(),
            `updated_at`            = NOW()
        WHERE `id`='$package_id'";
        return ejecutarConsulta($sql);
    }

    public function xls ( $data ){
        $date_start = $data['date_start'] ?? null;
        $date_end = $data['date_end'] ?? null;
        $branch_office_id =$data['branch_office_id'];

        $sql ="
            SELECT 
                tickets_delivery.tracking_code,
                tickets_delivery.status,
                tickets_delivery.price,
                tickets_delivery.quantity,
                tickets_delivery.description,
                tickets_delivery.sender_name,
                tickets_delivery.receiver_name,
                tickets_delivery.package_weight,
                tickets_delivery.declared_value,
                tickets_delivery.date,
                routes_stop.origin,
                routes_stop.destination,
                vehicles.unidad_number,
                CONCAT(employees.name,' ', employees.paternal_surname, ' ', employees.maternal_surname) AS employee
            FROM `tickets_delivery`
            INNER JOIN routes_stop ON routes_stop.id = tickets_delivery.route_stop_id
            INNER JOIN routes_schedule ON routes_schedule.id = tickets_delivery.route_schedule_id
            INNER JOIN vehicles ON vehicles.id = tickets_delivery.vehicle_id
            INNER JOIN employees ON employees.id = tickets_delivery.employee_id
            WHERE tickets_delivery.branch_office_id = '$branch_office_id' AND tickets_delivery.date >='$date_start' AND tickets_delivery.date <='$date_end'
        ";
    
        $sql .= " ORDER BY tickets_delivery.date DESC";
        $resultado = ejecutarConsulta($sql);
        $data = array();
        while ( $item = $resultado->fetch_object()) {
            $data[] = $item;
        }
        return $data;
    }
}

?>
