<?php
header('Content-Type: application/json');
include_once("../../_config/conexion.php");

$tipo = '03'; // Recarga por sistema
$signo = '+';
$status = 'Confirmada';
$fechapago = date("Y-m-d");

$query  = 'INSERT INTO recaudo_transacciones (punto, fecha, banco, referencia, tipo, signo, monto, status, usuario,detalle) ';
$query .= 'VALUES ('.$_POST["punto"].',"'.$fechapago.'","Sistema","'.$_POST["referencia"].'",';
$query .= '"'.$tipo.'","'.$signo.'",'.$_POST["monto"].',"'.$status.'",0,"Transacción hecha por administración")';
if($result = mysqli_query($link, $query)) {
    $query = 'SELECT * FROM puntosderecaudacion where id='.$_POST["punto"];
    $result = mysqli_query($link, $query);
    if ($row = mysqli_fetch_array($result)) {
    	$lineadecredito = $row['lineadecredito'];
		$lineadecredito += $_POST["monto"];
		$query = 'UPDATE puntosderecaudacion SET lineadecredito='.$lineadecredito.' WHERE id='.$_POST["punto"];
		if ($result = mysqli_query($link, $query)){
			$respuesta = '{';
			$respuesta .= '"exito":"SI",';
			$respuesta .= '"mensaje":"' . utf8_encode('Registro exitoso.') . '"';
			$respuesta .= '}';
		} else {
			$respuesta = '{';
			$respuesta .= '"exito":"NO",';
			$respuesta .= '"mensaje":"' . utf8_encode('No se realizó correctamente la recarga, comuniquese con soporte técnico.') . '"';
			$respuesta .= '}';
		}
	} else {
		$respuesta = '{';
		$respuesta .= '"exito":"NO",';
		$respuesta .= '"mensaje":"' . utf8_encode('No se realizó correctamente la recarga, comuniquese con soporte técnico.') . '"';
		$respuesta .= '}';
	}
} else {
	$respuesta = '{';
	$respuesta .= '"exito":"NO",';
	$respuesta .= '"mensaje":"' . utf8_encode('Ocurrió un error, intentelo de nuevo más tarde, si persiste el error comuniquese con soporte técnico.') . '"';
	$respuesta .= '}';
}
echo $respuesta;
?>
