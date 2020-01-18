<?php
include_once("../_config/configShopify.php");

$url = $urlTransaccionesUnaOrden;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url );
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);// set optional params
curl_setopt($ch,CURLOPT_HEADER, false); 
 
$result=curl_exec($ch);
curl_close($ch);

$transaccion=json_decode($result,true);

echo '<pre>';
var_dump($transaccion);
echo '</pre>';
$parent_id = $transaccion["transactions"][0]["id"];

echo $parent_id;

// print_r($result);

?>