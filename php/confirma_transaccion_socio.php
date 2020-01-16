<?php
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$monto = 0.00;
$query = 'SELECT * FROM pdv_transacciones where id='.$_POST["transaccion"];
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
  $monto = $row['monto'];
  $card = $row['id_instrumento'];
  $referencia = $row['documento'];
  $fecha = $row['fecha'];

  $query = 'SELECT * FROM prepago where card="'.trim($card).'"';
  $result = mysqli_query($link, $query);
  if ($row = mysqli_fetch_array($result)) {
    $saldo = $row["saldo"];
    $saldoentransito = $row["saldoentransito"];
  }
}

$respuesta = '{';
$respuesta .= '"exito":"NO",';
$respuesta .= '"mensaje":"'.utf8_encode('Ocurrió un error, comuniquese con soporte técnico al +584244071820.').'"';
$respuesta .= '}';

switch ($_POST["accion"]) {
  case 'confirmar':
    $query = 'UPDATE pdv_transacciones SET status="Confirmada" WHERE id='.$_POST["transaccion"];
    $quer2 = 'UPDATE prepago_transacciones SET status="Confirmada" WHERE ';
    $quer2 .= 'card="'.$card.'" and documento="'.$referencia.'" and fecha="'.$fecha.'"';
    $saldo -= $monto;
    $saldoentransito -= $monto;
    break;
  case 'rechazar':
    $query = 'UPDATE pdv_transacciones SET status="Rechazada" WHERE id='.$_POST["transaccion"];
    $quer2 = 'UPDATE prepago_transacciones SET status="Rechazada" WHERE ';
    $quer2 .= 'card="'.$card.'" and documento="'.$referencia.'" and fecha="'.$fecha.'"';
    $saldoentransito -= $monto;
    break;
}
if ($result = mysqli_query($link, $query)) {
  if ($result = mysqli_query($link, $quer2)) {
    $query = 'UPDATE prepago SET saldo='.$saldo.', saldoentransito='.$saldoentransito.' WHERE card="'.trim($card).'"';
    if ($result = mysqli_query($link, $query)) {
      $respuesta = '{';
      $respuesta .= '"exito":"SI",';
      $respuesta .= '"mensaje":"' . utf8_encode('Proceso exitoso.') . '"';
      $respuesta .= '}';
    }
  }
}
echo $respuesta;
?>
