<?php
include_once("../_config/configShopify.php");

$url = $urlTransaccionesUnaOrden;

$variant = array('transaction' => 
    array(
        'currency' =>  'USD',
        'amount' => '100.00',
        'kind' => 'capture', 
        'parent_id' => 2368669876298
    )
);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($variant)); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
print_r($response);
?>