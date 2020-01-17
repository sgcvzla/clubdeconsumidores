<?php 
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$quer0 = 'SELECT * FROM socios where id="'.$_GET["idsocio"].'"';
$resul0 = mysqli_query($link, $quer0);
if ($ro0 = mysqli_fetch_array($resul0)) {
    $respuesta = '{"exito":"SI",';
    $respuesta .= '"nombres":"'. trim(utf8_encode($ro0["nombres"])) .'",';
    $respuesta .= '"apellidos":"' . trim(utf8_encode($ro0["apellidos"])) . '"';
    $respuesta .= '}';
} else {
    $respuesta = '{"exito":"NO",';
    $respuesta .= '"nombres":"",';
    $respuesta .= '"apellidos":""';
    $respuesta .= '}';
}
echo $respuesta;
?>
