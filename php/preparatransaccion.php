<?php 
include_once("../_config/conexion.php");
include_once("./funciones.php");

$transaccion = json_decode($_GET['j'],true);

switch ($transaccion["t"]) {
	// prepago
	case 'p':
		$instrumento = 'prepago';
		$query = "select * from prepago where card='".trim($transaccion["c"])."'";
		break;
	// giftcard
	case 'g':
		$instrumento = 'giftcard';
		$query = "select * from giftcards where card='".trim($transaccion["c"])."'";
		break;
	default:
		$instrumento = 'prepago';
		$query = "select * from prepago where card='".trim($transaccion["c"])."'";
		break;
}
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
	$saldoentransito = $row["saldoentransito"];
	$idsocio = $row["id_socio"];
	$idproveedor = $row["id_proveedor"];
	$tipomoneda = $row["moneda"];
}
$fecha = date('Y-m-d');
$tipotransaccion = '51';
$tipotransaccion_pdv = '01';
switch ($tipomoneda) {
	case 'bs':
		$montobs = $transaccion["m"]; $montodolares = 0.00; $montocripto = 0.00; 
		break;
	case 'dolares':
		$montobs = 0.00; $montodolares = $transaccion["m"]; $montocripto = 0.00; 
		break;
	case 'cripto':
		$montobs = 0.00; $montodolares = 0.00; $montocripto = $transaccion["m"]; 
		break;
	default:
		$montobs = $transaccion["m"]; $montodolares = 0.00; $montocripto = 0.00; 
		break;
}
$tasadolarbs = 1.00;
$tasadolarcripto = 1.00;
$documento = '';
$origen = 'comercio';
$status = 'Por confirmar pago';
$status_pdv = 'Por confirmar';
$card = $transaccion["c"];

// Busca el próximo número correlativo (único)
$query = "select auto_increment from information_schema.tables where table_schema='sgcconsu_clubdeconsumidores' and table_name='prepago_transacciones'";
$result = mysqli_query($link,$query);
if($row = mysqli_fetch_array($result)) {
    $correlativo = $row["auto_increment"];
} else {
    $correlativo = 0;
}

// Si el número del correlativo supera los cuatro dígitos se trunca a cuatro
if ($correlativo > 9999) { $correlativo -= intval($correlativo/10000)*10000; }

// Rellena con ceros los caracteres faltantes hasta 4
if ($correlativo < 10) {
    $txtcorrelativo = "000".trim($correlativo);
} elseif ($correlativo < 100) {
    $txtcorrelativo = "00".trim($correlativo);
} elseif ($correlativo < 1000) {
    $txtcorrelativo = "0".trim($correlativo);
} else {
    $txtcorrelativo = trim($correlativo);
}

$referencia = date("Ym").$txtcorrelativo;

switch ($transaccion["t"]) {
	// prepago
	case 'p':
		$query = "INSERT INTO prepago_transacciones (idsocio, fecha, tipotransaccion, tipomoneda, montobs, montodolares, montocripto, tasadolarbs, tasadolarcripto, documento, origen, status, card) VALUES (".$idsocio.",'".$fecha."','".$tipotransaccion."','".$tipomoneda."',".$montobs.",".$montodolares.",".$montocripto.",".$tasadolarbs.",".$tasadolarcripto.",'".$referencia."','".$origen."','".$status."','".$card."')";
		break;
	// giftcard
	case 'g':
		break;
	default:
		$query = "INSERT INTO prepago_transacciones (idsocio, fecha, tipotransaccion, tipomoneda, montobs, montodolares, montocripto, tasadolarbs, tasadolarcripto, documento, origen, status, card) VALUES (".$idsocio.",'".$fecha."','".$tipotransaccion."','".$moneda."',".$montobs.",".$montodolares.",".$montocripto.",".$tasadolarbs.",".$tasadolarcripto.",'".$referencia."','".$origen."','".$status."','".$card."')";
		break;
}
if ($result = mysqli_query($link,$query)) {
	$saldoentransito += $transaccion["m"];
	$query = "UPDATE prepago SET saldoentransito=".$saldoentransito." WHERE card='".trim($card)."'";
	$result = mysqli_query($link,$query);
}

// Insertar transacción para confirmar
$query  = "INSERT INTO pdv_transacciones (fecha, id_proveedor, id_socio, tipo, moneda, monto, ";
$query .= "instrumento, id_instrumento, documento, status) ";
$query .= "VALUES ('".$fecha."',".$idproveedor.",".$idsocio.",'".$tipotransaccion_pdv."','".$tipomoneda."',";
$query .= $transaccion["m"].",'".$instrumento."','".$card."','".$referencia."','".$status_pdv."')";
$result = mysqli_query($link,$query);

///////////////////////////////////////////////////////////////////////////////////////////////////////
$cadena = 'https://www.cash-flag.com/card/exito.html?p='.$idproveedor; 

echo "
	<script>
		window.location.assign('".$cadena."');
	</script>
	";
?>
