<?php 
require_once "global.php";

$conexion=new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);

mysqli_query($conexion, 'SET NAMES "'.DB_ENCODE.'"');

date_default_timezone_set('America/Mexico_City');
$conexion->set_charset('utf8mb4');

if (mysqli_connect_errno()) {
	printf("Ups parece que falló en la conexion con la base de datos: %s\n",mysqli_connect_error());
	exit();
}

if (!function_exists('ejecutarConsulta')) {
	Function ejecutarConsulta($sql){ 
		global $conexion;
		$query=$conexion->query($sql);
		return $query;

	} 

	function ejecutarConsultaSimpleFila($sql){
		global $conexion;

		$query=$conexion->query($sql);
		$row=$query->fetch_assoc();
		return $row;
	}
	
	function ejecutarConsulta_retornarID($sql){
		global $conexion;
		$query=$conexion->query($sql);
		return $conexion->insert_id;
	}

	function limpiarCadena($str){
		global $conexion;
		$str=mysqli_real_escape_string($conexion,trim($str));
		return htmlspecialchars($str);
	}
	

    function iniciarTransaccion() {
        global $conexion;
        $conexion->begin_transaction();
    }

    function confirmarTransaccion() {
        global $conexion;
        $conexion->commit();
    }

    function revertirTransaccion() {
        global $conexion;
        $conexion->rollback();
    }
	

}

?>