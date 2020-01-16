<?php
// require("../php-shopify/lib/AuthHelper.php");
// require("../php-shopify/lib/Exception/SdkException.php");
// require("../php-shopify/lib/Exception/CurlException.php");
// require("../php-shopify/lib/CurlResponse.php");
// require("../php-shopify/lib/CurlRequest.php");
// require("../php-shopify/lib/HttpRequestJson.php");
// require("../php-shopify/lib/ShopifyResource.php");
// require("../php-shopify/lib/ShopifySDK.php");
// require("../php-shopify/lib/order.php");

// $config = array(
//     'ShopUrl' => 'anatie.myshopify.com',
//     'ApiKey' => '060d3f560e548b08d411633144ae09fb',
//     'Password' => '44e7bd60350b8ad36bc0f899930ad51e',
// );

// PHPShopify\ShopifySDK::config($config);

// $config = array(
//     'ShopUrl' => 'anatie.myshopify.com',
//     'ApiKey' => '060d3f560e548b08d411633144ae09fb',
//     'SharedSecret' => '9eab1dc9805a64405c3ce49c56ff5e62',
// );

// PHPShopify\ShopifySDK::config($config);

// //your_authorize_url.php
// $scopes = 'read_orders,write_orders';
// //This is also valid
// //$scopes = array('read_products','write_products','read_script_tags', 'write_script_tags');
// $redirectUrl = 'http://localhost/clubdeconsumidores/popclik/exito.php';

// \PHPShopify\AuthHelper::createAuthRequest($scopes, $redirectUrl);

// // \PHPShopify\AuthHelper::createAuthRequest($scopes, $redirectUrl, null, null, true);

// PHPShopify\ShopifySDK::config($config);

// $accessToken = \PHPShopify\AuthHelper::getAccessToken();

// $shopify = new PHPShopify\ShopifySDK;
// // $shopify = new PHPShopify\ShopifySDK($config);

// $params = array(
//     'status' => 'paid'
// );

// $orders = $shopify->Order->get($params);

// echo $orders;

$url = 'https://060d3f560e548b08d411633144ae09fb:44e7bd60350b8ad36bc0f899930ad51e@anatie.myshopify.com/admin/api/2019-10/products/'.$_GET["id"].'.json';

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
