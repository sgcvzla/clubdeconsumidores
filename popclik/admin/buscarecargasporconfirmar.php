<?php
header('Content-Type: application/json');
include_once("../../_config/conexion.php");

$query  = 'SELECT recaudo_transacciones.*,puntosderecaudacion.nombre FROM recaudo_transacciones ';
$query .= 'INNER JOIN puntosderecaudacion on recaudo_transacciones.punto=puntosderecaudacion.id ';
$query .= 'WHERE recaudo_transacciones.tipo="01" and recaudo_transacciones.status="Por confirmar" ';
$query .= 'ORDER BY nombre,fecha,banco,referencia';
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
    $respuesta .= '"nombre":"'.$row["nombre"].'"'.',';
    $respuesta .= '"fecha":"'.$row["fecha"].'"'.',';
    $respuesta .= '"banco":"'.$row["banco"].'"'.',';
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
