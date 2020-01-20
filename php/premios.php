<?php 
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$query = "SELECT * from premios where id_proveedor=".$_GET["prov"]." and clasepremio='".$_GET["clase"]."'";
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
	$id = $row['id'];
	$clasepremio = $_GET["clase"];
	$tipopremio = $row['tipopremio'];
	$cantidad = $row['montopremio'];
	$descripcion = $row['descpremio'];
	$validez = $row['diasvalidez'];
	$activo = $row['activo'];

	$respuesta = '{"exito":"SI",';
	$respuesta .= '"id":' . $id  . ',';
	$respuesta .= '"clasepremio":"' . $clasepremio  . '",';
	$respuesta .= '"tipopremio":"' . $tipopremio  . '",';
	$respuesta .= '"cantidad":' . $cantidad  . ',';
	$respuesta .= '"descripcion":"' . $descripcion  . '",';
	$respuesta .= '"validez":' . $validez  . ',';
	$respuesta .= '"activo":' . $activo;
	$respuesta .= '}';
} else {
	$respuesta = '{"exito":"NO",';
	$respuesta .= '"id":0,';
	$respuesta .= '"clasepremio":"'.$_GET["clase"].'",';
	$respuesta .= '"tipopremio":"ninguno",';
	$respuesta .= '"cantidad":"",';
	$respuesta .= '"descripcion":"",';
	$respuesta .= '"validez":0,';
	$respuesta .= '"activo":0';
	$respuesta .= '}';
}
echo $respuesta;
?>
