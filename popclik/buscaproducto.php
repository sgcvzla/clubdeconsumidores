<?php
include_once("../_config/configShopify.php");

$url = $urlBuscarProducto.$_GET["id"].'.json';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url );
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);// set optional params
curl_setopt($ch,CURLOPT_HEADER, false);

$result=curl_exec($ch);

curl_close($ch);

// echo $result;

$notfound = true;

$respuesta = '{"exito":"NO",';
$respuesta .= '"producto":{';
$respuesta .= '"id":"",';
$respuesta .= '"nombre":""}';
$respuesta .= '}';
if (isset($result)) {
    $producto=json_decode($result,true);
    $respuesta = '{"exito":"SI",';
    $respuesta .= '"producto":{';
    $respuesta .= '"id":"'.$producto["product"]["id"].'",';
    $respuesta .= '"nombre":"' . trim($producto["product"]["title"]) . '"}';
    $respuesta .= '}';
}
echo $respuesta;
?>
