<?php
include_once("../_config/configShopify.php");

$url = 'https://060d3f560e548b08d411633144ae09fb:44e7bd60350b8ad36bc0f899930ad51e@anatie.myshopify.com/admin/api/2020-01/orders/1922064580682/metafields.json';

$variant = array('metafield' => 
    array(
        'namespace' =>  'cobro_punto',
        'key' => 'punto-02',
        'value' => '789012', 
        'value_type' => 'string'
    )
);
echo '<pre>';
var_dump($variant);
echo json_encode($variant);
echo '</pre>';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url );
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($variant)); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch,CURLOPT_HEADER, false); 
$response = curl_exec($ch);
echo curl_error($ch);
echo '<pre>';
var_dump(curl_getinfo($ch));
echo '</pre>';
curl_close($ch);
print_r($response);
?>
