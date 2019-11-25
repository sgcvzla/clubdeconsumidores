<?php
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$card = '1111';
$remitente = $_POST['remitente'];
$nombres = $_POST['nombres'];
$apellidos = $_POST['apellidos'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];
$socio = 0;
$idsocio = ($socio) ? $_POST['idsocio'] : '0' ;
$idproveedor = $_POST['idproveedor'];
$moneda = $_POST['moneda'];
$tipotransaccion = $_POST['tipotransaccion'];
$documento = $_POST['documento'];
$origen = $_POST['origen'];
$monto = $_POST['monto'];
$fecha = date('Y-m-d');
$montodolares="0";
$montocripto="0";
$tasadolar="0";
$tasadolarcripto="0";
$status= "En espera";
$hash = '2222';

$query = "INSERT INTO giftcards (card, remitente, nombres, apellidos, telefono, email, saldo, moneda, fechacompra, status, id_proveedor, hash) VALUES ('".$card."','".$remitente."','".$nombres."','".$apellidos."','".$telefono."','".$email."',".$monto.",'".$moneda."','".$fecha."','".$status."',".$idproveedor.",'".$hash."')";

$SQL = "INSERT INTO giftcards_transacciones (socio, idsocio, fecha, tipotransaccion, tipomoneda, montobs, montodolares, montocripto, tasadolarbs, tasadolarcripto, documento, origen) VALUES (".$socio.", '".$idsocio."','".$fecha."','".$tipotransaccion."','".$moneda."','".$monto."','".$montodolares."','".$montocripto."','".$tasadolar."','".$tasadolarcripto."','".$documento."','".$origen."')";

if (!mysqli_query($link, $query)) $response = '{"exito":"NO","mensaje":"Error al agregar una GiftCard"}';
if (!mysqli_query($link, $SQL)) return '{"exito":"NO","mensaje":"Error al agregar transacciones a la tarjeta"}';
$response = '{"exito":"SI","mensaje":"Exitoso"}';
echo $response;
?>