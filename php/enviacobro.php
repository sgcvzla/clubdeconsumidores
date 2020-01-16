<?php
header('Content-Type: application/json');
include_once("../_config/conexion.php");
include_once("funciones.php");
include_once("../lib/phpqrcode/qrlib.php");

// Buscar tipo de instrumento
$instrumento = "";
$query = "select * from cards where card='".trim($_POST["tarjeta"])."'";
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
	$instrumento = $row["tipo"];
}

// Buscar tipo de instrumento
$id_socio = 0;
$saldo = 0.00;
$saldoentransito = 0.00;
if ($instrumento=='prepago') {
	$query = "select * from prepago where card='".trim($_POST["tarjeta"])."'";
	$result = mysqli_query($link, $query);
	if ($row = mysqli_fetch_array($result)) {
		$id_socio = $row["id_socio"];
	    $saldo = $row["saldo"];
    	$saldoentransito = $row["saldoentransito"];
	}
}

// Asignar parámetros a variables
$fecha = date("Y-m-d");
$id_proveedor = $_POST["idproveedor"];
$tipo = '01'; // Cobro
$moneda = $_POST["moneda"];
$monto = $_POST["monto"];
$id_instrumento = $_POST["tarjeta"];
$documento = generatransaccion_pdv($link);
$status = 'Por confirmar'; // Estatus pendiente por confirmación

// Calcular disponibilidad
$disponible = $saldo - $saldoentransito;
if ($disponible - $monto > 0.00) {
	// Insertar transacción para confirmar
	$query  = "INSERT INTO pdv_transacciones (fecha, id_proveedor, id_socio, tipo, moneda, monto, ";
	$query .= "instrumento, id_instrumento, documento, status) ";
	$query .= "VALUES ('".$fecha."',".$id_proveedor.",".$id_socio.",'".$tipo."','".$moneda."',".$monto;
	$query .= ",'".$instrumento."','".$id_instrumento."','".$documento."','".$status."')";
	if ($result = mysqli_query($link, $query)) {
		$saldoentransito += $monto;
		$query = 'UPDATE prepago SET saldoentransito='.$saldoentransito.' WHERE card="'.trim($id_instrumento).'"';
		if ($result = mysqli_query($link, $query)) {
			$mensaje = '["Registro exitoso."]';
			$respuesta = '{"exito":"SI","mensaje":'.$mensaje.',"transaccion":"'.$documento.'"}';
		} else {
			$mensaje = '["Fallo el registro, por favor comuniquese con soporte técnico."]';
			$respuesta = '{"exito":"NO","mensaje":'.$mensaje.',"transaccion":"'.$documento.'"}';
		}
	} else {
		$mensaje = '["Fallo el registro, por favor comuniquese con soporte técnico."]';
		$respuesta = '{"exito":"NO","mensaje":'.$mensaje.',"transaccion":"'.$documento.'"}';
	}
} else {
	$mensaje = '["Ups! Ocurrió un problema."';
	$mensaje .= ',"Aparentemente el comprador no tiene suficiente saldo disponible."';
	if ($saldo - $_POST["monto"] > 0.00) {
		$mensaje .= ',"Tiene transacciones pendientes por confirmar o rechazar."';
	} else {
		$mensaje .= ',"Puede recargar saldo a esta tarjeta para poder usarla."]';
	}
	$respuesta = '{"exito":"NO","mensaje":'.$mensaje.',"transaccion":"'.$documento.'"}';
}
echo $respuesta;
?>
