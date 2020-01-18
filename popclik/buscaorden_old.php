<?php 
header('Content-Type: application/json');
include_once("../_config/conexion.php");
require("../php-shopify/lib/Exception/CurlException.php");
require("../php-shopify/lib/CurlResponse.php");
require("../php-shopify/lib/CurlRequest.php");
require("../php-shopify/lib/HttpRequestJson.php");
require("../php-shopify/lib/ShopifyResource.php");
require("../php-shopify/lib/ShopifySDK.php");
require("../php-shopify/lib/order.php");
include_once("../_config/configShopify.php");


$config = array(
    'ShopUrl' => $urlTienda,
    'ApiKey' => $KeyTienda,
    'SharedSecret' => $SecretTienda,
);

//your_authorize_url.php
$scopes = 'read_orders,write_orders';
//This is also valid
//$scopes = array('read_products','write_products','read_script_tags', 'write_script_tags'); 
$redirectUrl = 'https://www.clubdeconsumidores.com.ve/popclik/exito.php';

\PHPShopify\AuthHelper::createAuthRequest($scopes, $redirectUrl);
If you want the function to return the authentication url instead of auto-redirecting, you can set the argument $return (5th argument) to true.

\PHPShopify\AuthHelper::createAuthRequest($scopes, $redirectUrl, null, null, true);



PHPShopify\ShopifySDK::config($config);

$shopify = new PHPShopify\ShopifySDK($config);
$params = array(
    'status' => 'paid'
);

// $orders = $shopify->Order->get($params);

// echo $orders;


// // $ch = curl_init();
// // curl_setopt($ch, CURLOPT_URL,$url );

// // $server_output = curl_exec($ch);
// // curl_close ($ch);

// // echo $server_output;
// // if (isset($server_output)) {
// //     $tran=json_decode($server_output,true);
// //     $valores = array('exito' => true, 'tran' => $tran);
// // } else{
// //     $valores = array('exito' => false, 'tran' => '');
// // } 
// // return $valores;



/*
$query = 'SELECT * FROM puntosderecaudacion where email="'.$_GET["email"].'"';
$result = mysqli_query($link, $query);
$id = 0;
if ($row = mysqli_fetch_array($result)) {
    $id = $row['id'];
    $respuesta = '{"exito":"SI",';
    $respuesta .= '"id":'.$id.',';
    $respuesta .= '"hashp":"'. $row["hashp"] .'",';
    $respuesta .= '"pregunta":"' . utf8_encode($row["pregunta"]) . '",';
    $respuesta .= '"hashr":"' . $row["hashr"] . '",';
    $respuesta .= '"mensaje":"exito"}';
} else {
    $respuesta = '{"exito":"NO",';
    $respuesta .= '"id":'.$id.',';
    $respuesta .= '"hashp":"",';
    $respuesta .= '"pregunta":"",';
    $respuesta .= '"hashr":"",';
    $respuesta .= '"mensaje":"correo no registrado"}';
}
*/

$respuesta = '{"exito":"SI",';
$respuesta .= '"orden":{';
$respuesta .= '"numero":"'. $_GET["orden"] .'",';
$respuesta .= '"nombres":"' . utf8_encode('Luis Rodriguez') . '",';
$respuesta .= '"identificacion":"' . '7.132.358' . '",';
$respuesta .= '"montoorden":' . '100000' . '}';
$respuesta .= '}';

echo $respuesta;
?>
