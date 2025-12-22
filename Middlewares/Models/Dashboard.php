    <?php 
session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";

class Dashboard {

    public function __construct() {}
    
    // public function index( $data ) {
    //     $start_date =$data['start_date'];
    //     $end_date =$data['end_date'];

    //     $sales_by_route  = [];
    //     $sql_total_sales = "
    //     SELECT 
    //         SUM(
    //             tickets.price - COALESCE(
    //             CAST(REPLACE(REPLACE(TRIM(tickets.discount), ',', ''), ' ', '') AS DECIMAL(10,2))
    //             , 0)
    //         ) AS total_sales,
    //         CONCAT(routes.origin,' - ',routes.destination) as route
    //     FROM `tickets` 
    //     INNER JOIN routes ON routes.id = tickets.route_id
    //     WHERE status='VENDIDO' GROUP BY route_id;";
    //     $response_sales = ejecutarConsulta($sql_total_sales);

    //     while ($item = $response_sales->fetch_assoc()) {
    //         $sales_by_route[] = [
    //             'route'    => $item['route'],
    //             'total_sales' => $item['total_sales']
    //         ];
    //     }

    //     $kpis  = [];
    //     $sql_kpis ="
    //         SELECT 
    //             SUM(
    //                 tickets.price - COALESCE(
    //                 CAST(REPLACE(REPLACE(TRIM(tickets.discount), ',', ''), ' ', '') AS DECIMAL(10,2))
    //                 , 0)
    //             ) AS total_sales,
    //             COUNT(id) AS total_tickets
    //         FROM `tickets`
    //         WHERE status = 'VENDIDO'
    //     ";

    //     $response_kpis = ejecutarConsulta($sql_kpis);

    //     while ($item = $response_kpis->fetch_assoc()) {
    //         $kpis[] = [
    //             'total_tickets'    => $item['total_tickets'],
    //             'total_sales' => $item['total_sales']
    //         ];
    //     }


    //     $sales_by_date  = [];
    //     $sql_total_sales_bi_date = "
    //     SELECT 
    //         SUM(
    //             tickets.price - COALESCE(
    //             CAST(REPLACE(REPLACE(TRIM(tickets.discount), ',', ''), ' ', '') AS DECIMAL(10,2))
    //             , 0)
    //         ) AS total_sales,
    //         date
    //     FROM `tickets` 
    //     INNER JOIN routes ON routes.id = tickets.route_id
    //     WHERE status='VENDIDO' GROUP BY date;";
    //     $response_sales_by_date = ejecutarConsulta($sql_total_sales_bi_date);

    //     while ($item = $response_sales_by_date->fetch_assoc()) {
    //         $sales_by_date[] = [
    //             'date'    => $item['date'],
    //             'total_sales' => $item['total_sales']
    //         ];
    //     }

    //     $sales_by_branch_offices  = [];
    //     $sql_total_sales_bi_branch_office = "
    //     SELECT 
    //         SUM(
    //             tickets.price - COALESCE(
    //             CAST(REPLACE(REPLACE(TRIM(tickets.discount), ',', ''), ' ', '') AS DECIMAL(10,2))
    //             , 0)
    //         ) AS total_sales,
    //         branch_offices.name
    //     FROM `tickets` 
    //     INNER JOIN branch_offices ON branch_offices.id = tickets.branch_office_id
    //     WHERE tickets.status='VENDIDO' GROUP BY branch_office_id";
    //     $response_sales_by_branch_offices = ejecutarConsulta($sql_total_sales_bi_branch_office);

    //     while ($item = $response_sales_by_branch_offices->fetch_assoc()) {
    //         $sales_by_branch_offices[] = [
    //             'branch_office'    => $item['name'],
    //             'total_sales' => $item['total_sales']
    //         ];
    //     }

    //     $data=[
    //         "kpis" => $kpis,
    //         "sales_by_route" =>$sales_by_route,
    //         "sales_by_date" =>$sales_by_date,
    //         "sales_by_branch_office" =>$sales_by_branch_offices
    //     ];

    //     return $data;
    // }

    public function index( $data ) {
        $start_date = $data['start_date'] ?? null;
        $end_date   = $data['end_date'] ?? null;

        // Validar que las fechas existan
        if (empty($start_date) || empty($end_date)) {
            return [
                "kpis" => [],
                "sales_by_route" => [],
                "sales_by_date" => [],
                "sales_by_branch_office" => []
            ];
        }

        // Asegurar que start no sea mayor que end
        if ($start_date > $end_date) {
            $tmp = $start_date;
            $start_date = $end_date;
            $end_date = $tmp;
        }

        // --- 1. Ventas por ruta ---
        $sales_by_route  = [];
        $sql_total_sales = "
            SELECT 
                SUM(
                    tickets.price - COALESCE(
                        CAST(
                            REPLACE(
                                REPLACE(
                                    TRIM(tickets.discount), ',', ''
                                ), ' ', ''
                            ) AS DECIMAL(10,2)
                        ), 0
                    )
                ) AS total_sales,
                CONCAT(routes.origin,' - ',routes.destination) AS route
            FROM tickets 
            INNER JOIN routes ON routes.id = tickets.route_id
            WHERE tickets.status='VENDIDO'
            AND tickets.date BETWEEN '$start_date' AND '$end_date'
            GROUP BY tickets.route_id;
        ";

        $response_sales = ejecutarConsulta($sql_total_sales);

        while ($item = $response_sales->fetch_assoc()) {
            $sales_by_route[] = [
                'route'       => $item['route'],
                'total_sales' => $item['total_sales']
            ];
        }

        // --- 2. KPIs generales ---
        $kpis  = [];
        $sql_kpis = "
            SELECT 
                SUM(
                    tickets.price - COALESCE(
                        CAST(
                            REPLACE(
                                REPLACE(
                                    TRIM(tickets.discount), ',', ''
                                ), ' ', ''
                            ) AS DECIMAL(10,2)
                        ), 0
                    )
                ) AS total_sales,
                COUNT(id) AS total_tickets
            FROM tickets
            WHERE status = 'VENDIDO'
            AND date BETWEEN '$start_date' AND '$end_date';
        ";

        $response_kpis = ejecutarConsulta($sql_kpis);

        while ($item = $response_kpis->fetch_assoc()) {
            $kpis[] = [
                'total_tickets' => $item['total_tickets'],
                'total_sales'   => $item['total_sales']
            ];
        }

        // --- 3. Ventas agrupadas por fecha ---
        $sales_by_date = [];
        $sql_total_sales_by_date = "
            SELECT 
                SUM(
                    tickets.price - COALESCE(
                        CAST(
                            REPLACE(
                                REPLACE(
                                    TRIM(tickets.discount), ',', ''
                                ), ' ', ''
                            ) AS DECIMAL(10,2)
                        ), 0
                    )
                ) AS total_sales,
                tickets.date AS date
            FROM tickets
            WHERE tickets.status='VENDIDO'
            AND tickets.date BETWEEN '$start_date' AND '$end_date'
            GROUP BY tickets.date
            ORDER BY tickets.date ASC;
        ";

        $response_sales_by_date = ejecutarConsulta($sql_total_sales_by_date);

        while ($item = $response_sales_by_date->fetch_assoc()) {
            $sales_by_date[] = [
                'date'        => $item['date'],
                'total_sales' => $item['total_sales']
            ];
        }

        // --- 4. Ventas agrupadas por sucursal (taquilla) ---
        $sales_by_branch_offices = [];
        $sql_total_sales_by_branch_office = "
            SELECT 
                SUM(
                    tickets.price - COALESCE(
                        CAST(
                            REPLACE(
                                REPLACE(
                                    TRIM(tickets.discount), ',', ''
                                ), ' ', ''
                            ) AS DECIMAL(10,2)
                        ), 0
                    )
                ) AS total_sales,
                branch_offices.name AS name
            FROM tickets 
            INNER JOIN branch_offices ON branch_offices.id = tickets.branch_office_id
            WHERE tickets.status='VENDIDO'
            AND tickets.date BETWEEN '$start_date' AND '$end_date'
            GROUP BY tickets.branch_office_id;
        ";

        $response_sales_by_branch_offices = ejecutarConsulta($sql_total_sales_by_branch_office);

        while ($item = $response_sales_by_branch_offices->fetch_assoc()) {
            $sales_by_branch_offices[] = [
                'branch_office' => $item['name'],
                'total_sales'   => $item['total_sales']
            ];
        }

        // ---Ventas por usuario ---
        $sales_by_user  = [];
        $sql_total_sales_by_user = "
            SELECT 
                SUM(
                    tickets.price - COALESCE(
                        CAST(
                            REPLACE(
                                REPLACE(
                                    TRIM(tickets.discount), ',', ''
                                ), ' ', ''
                            ) AS DECIMAL(10,2)
                        ), 0
                    )
                ) AS total_sales,
                users.name
            FROM tickets 
            INNER JOIN users ON users.id = tickets.user_id
            WHERE tickets.status='VENDIDO'
            AND tickets.date BETWEEN '$start_date' AND '$end_date'
            GROUP BY tickets.user_id;
        ";

        $response_sales_by_user = ejecutarConsulta($sql_total_sales_by_user);

        while ($item = $response_sales_by_user->fetch_assoc()) {
            $sales_by_user[] = [
                'user'       => $item['name'],
                'total_sales' => $item['total_sales']
            ];
        }




        // ---Ventas por unicad ---
        $sales_by_vehicle = [];
        $sql_total_sales_by_vehicle  = "
            SELECT 
                SUM(
                    tickets.price - COALESCE(
                        CAST(
                            REPLACE(
                                REPLACE(
                                    TRIM(tickets.discount), ',', ''
                                ), ' ', ''
                            ) AS DECIMAL(10,2)
                        ), 0
                    )
                ) AS total_sales,
                vehicles.unidad_number,
                COUNT(tickets.id) AS total_tickets
            FROM tickets 
            INNER JOIN vehicles ON vehicles.id = tickets.vehicle_id
            WHERE tickets.status='VENDIDO'
            AND tickets.date BETWEEN '$start_date' AND '$end_date'
            GROUP BY tickets.vehicle_id;
        ";

        $response_sales_by_vehicle  = ejecutarConsulta($sql_total_sales_by_vehicle );

        while ($item = $response_sales_by_vehicle ->fetch_assoc()) {
            $sales_by_vehicle [] = [
                'unidad_number' => $item['unidad_number'],
                'total_sales' => $item['total_sales'],
                'total_tickets' => $item['total_tickets'],
            ];
        }
        
        return [
            "kpis"                     => $kpis,
            "sales_by_route"           => $sales_by_route,
            "sales_by_date"            => $sales_by_date,
            "sales_by_branch_office"   => $sales_by_branch_offices,
            "sales_by_users"           => $sales_by_user,
            "sales_by_vehicle"         => $sales_by_vehicle,
        ];
    }

    

}   
?>
