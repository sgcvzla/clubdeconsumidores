<?php 
header('Content-Type: application/json');
include_once("./conexion.php");

$query = "SELECT * from proveedores where id = " . $_GET["prov"];
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
	$nombre = $row['nombre'];
	$email = $row['email'];
	$logo = $row['logo'];
	$direccion = $row['direccion'];
	$rif = $row['rif'];
	$contacto = $row['contacto'];
	$telefono = $row['telefono'];
	$categoria = $row['categoria'];
	$claveadmin = $row['claveadmin'];
	$clavecanje = $row['clavecanje'];
	$color = $row['color'];

	$respuesta = '{"exito":"SI","proveedor":{';
	$respuesta .= '"nombre":"' . $nombre . '",';
	$respuesta .= '"email":"' . $email  . '",';
	$respuesta .= '"logo":"' . $logo  . '",';
	$respuesta .= '"direccion":"' . $direccion  . '",';
	$respuesta .= '"rif":"' . $rif  . '",';
	$respuesta .= '"contacto":"' . $contacto  . '",';
	$respuesta .= '"telefono":"' . $telefono  . '",';
	$respuesta .= '"categoria":"' . $categoria  . '",';
	$respuesta .= '"claveadmin":"' . $claveadmin  . '",';
	$respuesta .= '"clavecanje":"' . $clavecanje  . '",';
	$respuesta .= '"color":"' . $color  . '"';
	$respuesta .= '}}';

} else {
	$respuesta = '{"exito":"NO","proveedor":{}}';
}
echo $respuesta;
?>
