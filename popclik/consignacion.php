<?php
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$tipo = '01'; // Recarga
$signo = '+';
$status = 'Por confirmar';

$query  = 'INSERT INTO recaudo_transacciones (punto, fecha, banco, referencia, tipo, signo, monto, status, usuario) ';
$query .= 'VALUES ('.$_POST["punto"].',"'.$_POST["fechapago"].'","'.$_POST["banco"].'","'.$_POST["referencia"].'",';
$query .= '"'.$tipo.'","'.$signo.'",'.$_POST["monto"].',"'.$status.'",'.$_POST["usuario"].')';
if($result = mysqli_query($link, $query)) {
  $respuesta = '{';
  $respuesta .= '"exito":"SI",';
  $respuesta .= '"mensaje":"' . utf8_encode('Registro exitoso, esperando confirmación.') . '"';
  $respuesta .= '}';
} else {
  $respuesta = '{';
  $respuesta .= '"exito":"NO",';
  $respuesta .= '"mensaje":"' . utf8_encode('OCurrió un error, intentelo de nuevo más tarde, si persiste el error comuniquese con soporte técnico.') . '"';
  $respuesta .= '}';
}
echo $respuesta;
?>
