<?php
header('Content-Type: application/json');
include_once("../../_config/conexion.php");

$valores = json_decode($_POST["seleccion"], true);

$mensaje = 'Hubo error al actualizar las transacciones: ';
$errores = 0;
$coma = '';
foreach ($valores["confirmar"] as $confirmar => $transaccion) {
  $query = 'SELECT * FROM reportepago WHERE id='.$transaccion;
  $result = mysqli_query($link, $query);
  $punto = '';
  $monto = 0.00;
  $flagerror = false;
  if ($row = mysqli_fetch_array($result)) {
    $punto = $row['punto'];
    $monto = $row['monto'];
    $query = 'UPDATE reportepago SET status="Confirmado" WHERE id='.$transaccion;
    $result = mysqli_query($link, $query);
    $flagerror = false;
  } else {
    $flagerror = true;
  }
  if ($flagerror) {
    $coma = ($errores==0) ? '' : ',';
    $errores++;
    $mensaje .= $coma.$transaccion;
  }
}
foreach ($valores["anular"] as $anular => $transaccion) {
  $query = 'UPDATE reportepago SET status="Anulado" WHERE id='.$transaccion;
  $flagerror = false;
  if($result = mysqli_query($link, $query)) {
    $flagerror = false;
  } else {
    $flagerror = true;
  }
  if ($flagerror) {
    $coma = ($errores==0) ? '' : ',';
    $errores++;
    $mensaje .= $coma.$transaccion;
  }
}
if ($errores>0) {
  $respuesta = '{';
  $respuesta .= '"exito":"NO",';
  $respuesta .= '"mensaje":"' . utf8_encode($mensaje.' comuniquese con soporte técnico al +584244071820.') . '"';
  $respuesta .= '}';
} else {
  $respuesta = '{';
  $respuesta .= '"exito":"SI",';
  $respuesta .= '"mensaje":"' . utf8_encode('Proceso exitoso.') . '"';
  $respuesta .= '}';
}
echo $respuesta;
?>
