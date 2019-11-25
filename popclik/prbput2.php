<?php

$url = "https://060d3f560e548b08d411633144ae09fb:44e7bd60350b8ad36bc0f899930ad51e@anatie.myshopify.com/admin/api/2019-10/orders/1839940239434/transactions.json";

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