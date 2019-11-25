<?php 
// session_start();
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$query = "SELECT * from proveedores where id = " . $_GET["prov"];
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
	$respuesta = '{"exito":"SI","proveedor":{"nombre":"'.utf8_encode($row["nombre"]).'","logo":"'.$row["logo"].'"}';

	$query   = "SELECT * from pdv_transacciones where id_proveedor=".$_GET["prov"];
	$query  .= " and (status='Por confirmar' or status='Confirmada')";
	$query  .= " order by status desc,id_instrumento";
	$result = mysqli_query($link, $query);
	$respuesta .= ',"transacciones":';
	$respuesta .= '[';
	$first = true;
	while ($row = mysqli_fetch_array($result)) {
		if ($row["fecha"]==date("Y-m-d")) {
			if ($first) {
				$coma = "";
				$first = false;
			} else {
				$coma = ",";
			}
			$respuesta .= $coma.'{'; 
			$respuesta .= '"id":'.$row["id"];
			$respuesta .= ','.'"tarjeta":"'.trim($row["id_instrumento"]).'"';
			$respuesta .= ','.'"referencia":"'.trim($row["documento"]).'"';
			$respuesta .= ','.'"monto":'.$row["monto"];
			$respuesta .= ','.'"status":"'.$row["status"].'"';
			$respuesta .= '}';
		}
	}
	$respuesta .= ']';
	$respuesta .= '}';
} else {
	$respuesta = '{"exito":"NO","proveedor":{},"transaciones":[]}';
}
echo $respuesta;
?>
