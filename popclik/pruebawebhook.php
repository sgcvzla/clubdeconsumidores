<?php
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$mensaje = var_dump($_GET);
$mensaje .= '**************************************';
$mensaje .= var_dump($_POST);

$a = fopen('pruebawebhook.json','w+');
fwrite($a,$asunto);
fwrite($a,'-');
fwrite($a,$mensaje);

?>
