<?php 
// session_start();
header('Content-Type: application/json');
include_once("../_config/conexion.php");

// Buscar prepago
$query = "SELECT * from prepago where card=".$_GET["t"];
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
	$idproveedor = $row["id_proveedor"];
	$tipo = 'prepago';
	$nombres = utf8_decode(trim($row["nombres"])." ".trim($row["apellidos"]));
	$vencimiento = '12/2020';
	$qr = '';
}

// Buscar proveedor
$query = "SELECT * from proveedores where id=".$idproveedor;
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
	$logo = $row["logo"];
}

$respuesta = '{"logo":"'.$logo.'","tipo":"'.$tipo.'","nombres":"'.$nombres.'","vencimiento":"'.$vencimiento.'","qr":"'.$qr.'"}';

echo $respuesta;
?>
