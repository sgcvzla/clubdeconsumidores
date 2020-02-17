<?php
header('Content-Type: application/json');
include_once("../../_config/conexion.php");

// $fecha = date("Y-m-d");

// // Fecha de vecnimiento (1 año)
// $fechavencimiento = strtotime('+1 year', strtotime ($fecha));
// $fechavencimiento = date('Y-m-d', $fechavencimiento);

$query = 'UPDATE puntosderecaudacion SET lineadecredito='.$_POST["lineadecredito"].', diasdecredito='.$_POST["diasdecredito"].', proximopago="'.$fecha.'" WHERE id='.$_POST["punto"];
if ($result = mysqli_query($link, $query)){
	$respuesta = '{';
	$respuesta .= '"exito":"SI",';
	$respuesta .= '"mensaje":"' . utf8_encode('Registro exitoso.') . '"';
	$respuesta .= '}';
} else {
	$respuesta = '{';
	$respuesta .= '"exito":"NO",';
	$respuesta .= '"mensaje":"' . utf8_encode('No se realizó correctamente la operación, comuniquese con soporte técnico.') . '"';
	$respuesta .= '}';
}
echo $respuesta;
?>
