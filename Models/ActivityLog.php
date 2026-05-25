<?php 
session_start();
require_once dirname(__DIR__) . "/Database/conexion.php";

class ActivityLog {

    public function __construct() {}
    
    
    public static  function store( $data ){
        date_default_timezone_set('America/Mexico_City');
        $today              = date("Y-m-d H:i:s");
        $user_id            = $_SESSION['user_id'];
        $table_name         = $data['table_name'];
        $relationship_id    = $data['relationship_id'];
        $old_data           = $data['old_data'];
        $action             = $data['action'];
        

        $sql = "
            INSERT INTO `activity_log`
            (
                `user_id`,
                `relationship_id`,
                `table_name`,
                `date`,
                `old_data`,
                `action`,
                `created_at`
            ) VALUES (
                '$user_id',
                '$relationship_id',
                '$table_name',
                '$today',
                '$old_data',
                '$action', 
                '$today'
            )
        ";
        return ejecutarConsulta($sql);
    }

    
    public function index() {
        $sql = "SELECT 
            branch_offices.*,
            social_reasons.name AS social_reason
        FROM branch_offices 
        LEFT JOIN social_reasons ON social_reasons.id = social_reason_id
        WHERE branch_offices.deleted_at IS NULL";
        return ejecutarConsulta($sql);
    }
    
   
    public function show( $data ) {
        $branch_office_id = $data['branch_office_id'];
        $sql = "SELECT * FROM branch_offices WHERE id = '$branch_office_id' ";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function deleteItem ( $data ){
        $branch_office_id = $data['branch_office_id'];
        $sql="
        UPDATE 
        `branch_offices` SET 
            `deleted_at`= NOW()
        WHERE `id`='$branch_office_id'";
        return ejecutarConsulta($sql);
    }
}   
?>
