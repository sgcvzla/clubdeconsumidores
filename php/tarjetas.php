<?php 
// session_start();
header('Content-Type: application/json');
include_once("../_config/conexion.php");

// Buscar giftcards
$query = "SELECT * from socios where id=".$_GET["idsocio"];
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
	$email = $row["email"];

	$respuesta = '{"exito": "SI", "tarjetas":[';
	$coma = '';
	$inicio = true;

	// Buscar prepagos
	$query = "SELECT 'prepago' as tipo, moneda, card, saldo, saldoentransito, validez, status from prepago union SELECT 'giftcard' as tipo, moneda, card, saldo, 0 as saldoentransito, validez, status from giftcards where email='".$email."' order by status, validez, tipo desc, card";
	$result = mysqli_query($link, $query);
	while ($row = mysqli_fetch_array($result)) {
		$monto = $row["saldo"]-$row["saldoentransito"];
		if($monto>0) {
			if ($inicio) {
				$coma = '';
				$inicio = false;
			} else {
				$coma = ',';
			}
			$respuesta .= $coma.'{';
			$respuesta .= '"tipo":"'.$row["tipo"].'",';
			$respuesta .= '"moneda":"'.$row["moneda"].'",';
			$respuesta .= '"tarjeta":"'.$row["card"].'",';
			$respuesta .= '"saldo":'.$monto.',';
			$respuesta .= '"validez":"'.$row["validez"].'",';
			$respuesta .= '"status":"'.$row["status"].'"';
			$respuesta .= '}';
		}
	}
	$respuesta .= ']}';
} else {
	$respuesta = '{"exito": "NO"}';
}

// // Buscar giftcards
// $query = "SELECT moneda, card, saldo, validez, status from giftcards where id_socio=".$_GET["idsocio"];
// $result = mysqli_query($link, $query);
// while ($row = mysqli_fetch_array($result)) {
// 	if ($inicio) {
// 		$coma = '';
// 		$inicio = false;
// 	} else {
// 		$coma = ',';
// 	}
// 	$respuesta .= $coma.'{';
// 	$respuesta .= '"tipo":"prepago",';
// 	$respuesta .= '"moneda":"'.$row["moneda"].'",';
// 	$respuesta .= '"saldo":'.$row["saldo"].',';
// 	$respuesta .= '"validez":"'.$row["validez"].'",';
// 	$respuesta .= '"status":"'.$row["status"].'"';
// 	$respuesta .= '}';
// }

echo $respuesta;
?>
