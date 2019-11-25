<?php
$ch = curl_init("https://060d3f560e548b08d411633144ae09fb:44e7bd60350b8ad36bc0f899930ad51e@anatie.myshopify.com/admin/api/2019-10/orders/1839940239434/transactions.json");

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