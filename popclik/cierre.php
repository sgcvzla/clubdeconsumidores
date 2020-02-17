<?php
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$query  = 'SELECT * FROM recaudo_transacciones ';
$query  .= 'WHERE punto='.$_GET["punto"].' AND fecha="'.$_GET["fecha"]; 
$query  .= '" AND tipo="51" AND status="Confirmada"';
$cantidad = 0;
$recaudado = 0.00;
if ($result = mysqli_query($link, $query)) {
    while($row = mysqli_fetch_array($result)) {
        $cantidad++;
        $recaudado += $row["monto"];
    }
}

$query  = 'SELECT * FROM puntosderecaudacion ';
$query  .= 'WHERE id='.$_GET["punto"];
$lineadecredito = 0;
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
    $lineadecredito = $row["lineadecredito"];
}

$respuesta = '{';
$respuesta .= '"exito":"SI",';
$respuesta .= '"cantidad":'.$cantidad.',';
$respuesta .= '"recaudado":'.$recaudado.',';
$respuesta .= '"saldolineadecredito":'.$saldolineadecredito;
$respuesta .= '}';
echo $respuesta;
?>
