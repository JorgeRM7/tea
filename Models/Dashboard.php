    <?php 
session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";

class Dashboard {

    public function __construct() {}
    
    public function index() {
        $sales_by_route  = [];
        $sql_total_sales = "
        SELECT 
            SUM(
                tickets.price - COALESCE(
                CAST(REPLACE(REPLACE(TRIM(tickets.discount), ',', ''), ' ', '') AS DECIMAL(10,2))
                , 0)
            ) AS total_sales,
            CONCAT(routes.origin,' - ',routes.destination) as route
        FROM `tickets` 
        INNER JOIN routes ON routes.id = tickets.route_id
        WHERE status='VENDIDO' GROUP BY route_id;";
        $response_sales = ejecutarConsulta($sql_total_sales);

        while ($item = $response_sales->fetch_assoc()) {
            $sales_by_route[] = [
                'route'    => $item['route'],
                'total_sales' => $item['total_sales']
            ];
        }

        $kpis  = [];
        $sql_kpis ="
            SELECT 
                SUM(
                    tickets.price - COALESCE(
                    CAST(REPLACE(REPLACE(TRIM(tickets.discount), ',', ''), ' ', '') AS DECIMAL(10,2))
                    , 0)
                ) AS total_sales,
                COUNT(id) AS total_tickets
            FROM `tickets`
            WHERE status = 'VENDIDO'
        ";

        $response_kpis = ejecutarConsulta($sql_kpis);

        while ($item = $response_kpis->fetch_assoc()) {
            $kpis[] = [
                'total_tickets'    => $item['total_tickets'],
                'total_sales' => $item['total_sales']
            ];
        }


        $sales_by_date  = [];
        $sql_total_sales_bi_date = "
        SELECT 
            SUM(
                tickets.price - COALESCE(
                CAST(REPLACE(REPLACE(TRIM(tickets.discount), ',', ''), ' ', '') AS DECIMAL(10,2))
                , 0)
            ) AS total_sales,
            date
        FROM `tickets` 
        INNER JOIN routes ON routes.id = tickets.route_id
        WHERE status='VENDIDO' GROUP BY date;";
        $response_sales_by_date = ejecutarConsulta($sql_total_sales_bi_date);

        while ($item = $response_sales_by_date->fetch_assoc()) {
            $sales_by_date[] = [
                'date'    => $item['date'],
                'total_sales' => $item['total_sales']
            ];
        }

        $sales_by_branch_offices  = [];
        $sql_total_sales_bi_branch_office = "
        SELECT 
            SUM(
                tickets.price - COALESCE(
                CAST(REPLACE(REPLACE(TRIM(tickets.discount), ',', ''), ' ', '') AS DECIMAL(10,2))
                , 0)
            ) AS total_sales,
            branch_offices.name
        FROM `tickets` 
        INNER JOIN branch_offices ON branch_offices.id = tickets.branch_office_id
        WHERE tickets.status='VENDIDO' GROUP BY branch_office_id";
        $response_sales_by_branch_offices = ejecutarConsulta($sql_total_sales_bi_branch_office);

        while ($item = $response_sales_by_branch_offices->fetch_assoc()) {
            $sales_by_branch_offices[] = [
                'branch_office'    => $item['name'],
                'total_sales' => $item['total_sales']
            ];
        }

        $data=[
            "kpis" => $kpis,
            "sales_by_route" =>$sales_by_route,
            "sales_by_date" =>$sales_by_date,
            "sales_by_branch_office" =>$sales_by_branch_offices
        ];

        return $data;
    }
    

}   
?>
