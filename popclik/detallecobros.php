<?php 
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$respuesta = '{"exito":"SI",';
$respuesta .= '"transacciones":[';

$query  = 'SELECT * FROM recaudo_transacciones ';
$query  .= 'WHERE punto='.$_GET["punto"].' AND fecha="'.$_GET["fecha"];
$query  .= '" AND tipo="51" AND status="Confirmada"';
$cantidad = 0;
$recaudado = 0.00;
$first = true;
$coma = "";
if ($result = mysqli_query($link, $query)) {
    while($row = mysqli_fetch_array($result)) {
		if ($first) {
			$first = false;
			$coma = "";
		} else {
			$coma = ",";
		}
		$respuesta .= $coma.'{';
		$respuesta .= '"idtransaccion":"' . $row["referencia"] . '",';
		$respuesta .= '"orden":' . $row["orden"] . ',';
		$respuesta .= '"nombre":"' . $row["detalle"] . '",';
		$respuesta .= '"monto":' . $row["monto"];
		$respuesta .= '}';
        $cantidad++;
        $recaudado += $row["monto"];
    }
}

$respuesta .= ']}';

echo $respuesta;
?>
