<?php 
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$respuesta = '{"exito":"SI",';
$respuesta .= '"ordenes":'.$order;
$respuesta .= '"orden":{';
$respuesta .= '"numero":"'. $_GET["orden"] .'",';
$respuesta .= '"nombres":"' . utf8_encode('Luis Rodriguez') . '",';
$respuesta .= '"identificacion":"' . '7.132.358' . '",';
$respuesta .= '"montoorden":' . '100000' . '}';
$respuesta .= '}';

echo $respuesta;
?>
