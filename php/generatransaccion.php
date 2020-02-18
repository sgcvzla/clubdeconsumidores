<?php
include_once("../_config/conexion.php");
include_once("funciones.php");
include_once("../lib/phpqrcode/qrlib.php");

$tipo = $_GET["t"];
$card = $_GET["c"];
$monto = $_GET["m"];
$transaccion = random_int(0, $monto);

// código qr
// $dir = 'https://www.cash-flag.com/php/temp/';
$dir = 'temp/';
	
// $filename = $dir.'test.png';
$filename = $dir.'trx-'.$transaccion.'.png';
$tamanio = 5;
$level = 'H';
$frameSize = 1;
$contenido = 'https://www.cash-flag.com/php/preparatransaccion.php?j={"t":"'.$tipo.'","c":"'.$card.'","m":'.$monto.'}';

QRcode::png($contenido, $filename, $level, $tamanio, $frameSize);

echo $filename;
// Hasta aqui
?>
