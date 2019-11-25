<?php 
// session_start();
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$query  = "SELECT pdv_transacciones.*,proveedores.nombre from pdv_transacciones ";
$query .= "inner join proveedores on pdv_transacciones.id_proveedor=proveedores.id ";
$query .= "where id_socio=".$_GET["idsocio"]." and status='Por confirmar'";
$result = mysqli_query($link, $query);
$respuesta = '{"transacciones":';
$respuesta .= '[';
$first = true;
while ($row = mysqli_fetch_array($result)) {
	if ($first) {
		$coma = "";
		$first = false;
	} else {
		$coma = ",";
	}
	$respuesta .= $coma.'{'; 
	$respuesta .= '"id":'.$row["id"];
	$respuesta .= ','.'"fecha":"'.$row["fecha"].'"';
	$respuesta .= ','.'"nombreproveedor":"'.trim($row["nombre"]).'"';
	$respuesta .= ','.'"documento":"'.trim($row["documento"]).'"';
	$respuesta .= ','.'"monto":'.$row["monto"];
	$respuesta .= '}';
}
$respuesta .= ']';
$respuesta .= '}';

echo $respuesta;
?>
