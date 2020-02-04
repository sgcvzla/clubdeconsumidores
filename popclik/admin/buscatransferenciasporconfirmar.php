<?php
header('Content-Type: application/json');
include_once("../../_config/conexion.php");

$query  = 'SELECT * FROM reportepago ';
$query .= 'WHERE tipodepositante="cliente" and status="Pendiente por verificar" ';
$query .= 'ORDER BY fechatransaccion, referencia';
if ($result = mysqli_query($link, $query)) {
  $respuesta = '{';
  $respuesta .= '"exito":"SI",';
  $respuesta .= '"transacciones":[';
  $first = true;
  $coma = "";
  while($row = mysqli_fetch_array($result)) {
    if ($first) {
      $first = false;
      $coma = "";
    } else {
      $coma = ",";
    }
    $respuesta .= $coma.'{';
    $respuesta .= '"id":'.$row["id"].',';
    $respuesta .= '"nombre":"'.$row["nombredepositante"].'"'.',';
    $respuesta .= '"fecha":"'.$row["fechatransaccion"].'"'.',';
    $respuesta .= '"origen":"'.$row["origen"].'"'.',';
    $respuesta .= '"referencia":"'.$row["referencia"].'"'.',';
    $respuesta .= '"monto":'.$row["monto"];
    $respuesta .= '}';
  }
  $respuesta .= ']';
  $respuesta .= '}';
} else {
    $respuesta = '{';
    $respuesta .= '"exito":"NO",';
    $respuesta .= '"transacciones":[';
    $respuesta .= ']';
    $respuesta .= '}';
}
echo $respuesta;
?>
