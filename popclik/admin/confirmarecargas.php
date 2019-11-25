<?php
header('Content-Type: application/json');
include_once("../../_config/conexion.php");

$valores = json_decode($_POST["seleccion"], true);

$mensaje = 'Hubo error al actualizar las transacciones: ';
$errores = 0;
$coma = '';
foreach ($valores["confirmar"] as $confirmar => $transaccion) {
  $query = 'SELECT * FROM recaudo_transacciones WHERE id='.$transaccion;
  $result = mysqli_query($link, $query);
  $punto = '';
  $monto = 0.00;
  $flagerror = false;
  if ($row = mysqli_fetch_array($result)) {
    $punto = $row['punto'];
    $monto = $row['monto'];
    $query = 'UPDATE recaudo_transacciones SET status="Confirmada" WHERE id='.$transaccion;
    if ($result = mysqli_query($link, $query)) {
      $query = 'SELECT * FROM puntosderecaudacion where id='.$punto;
      $result = mysqli_query($link, $query);
      if ($row = mysqli_fetch_array($result)) {
        $lineadecredito = $row['lineadecredito'];
        $lineadecredito += $monto;
        $query = 'UPDATE puntosderecaudacion SET lineadecredito='.$lineadecredito.' WHERE id='.$punto;
        if ($result = mysqli_query($link, $query)){
          $flagerror = false;
        } else {
          $flagerror = true;
        }
      } else {
        $flagerror = true;
      }
    } else {
      $flagerror = true;
    }
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
  $query = 'UPDATE recaudo_transacciones SET status="Anulada" WHERE id='.$transaccion;
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
