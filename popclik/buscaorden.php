<?php
include_once("../_config/configShopify.php");

// require("../php-shopify/lib/AuthHelper.php");
// require("../php-shopify/lib/Exception/SdkException.php");
// require("../php-shopify/lib/Exception/CurlException.php");
// require("../php-shopify/lib/CurlResponse.php");
// require("../php-shopify/lib/CurlRequest.php");
// require("../php-shopify/lib/HttpRequestJson.php");
// require("../php-shopify/lib/ShopifyResource.php");
// require("../php-shopify/lib/ShopifySDK.php");
// require("../php-shopify/lib/order.php");

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

$url = $urlOrdenesPendientes;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);// set optional params
curl_setopt($ch,CURLOPT_HEADER, false);

$result=curl_exec($ch);

curl_close($ch);

// echo $result;

$notfound = true;

$respuesta = '{"exito":"NO",';
$respuesta .= '"orden":{';
$respuesta .= '"id":"",';
$respuesta .= '"numero":"",';
$respuesta .= '"nombres":"",';
$respuesta .= '"identificacion":"",';
$respuesta .= '"montoorden":0}';
$respuesta .= '}';
if (isset($result)) {
    $ordenes=json_decode($result,true);
    var_dump($ordenes);
    foreach ($ordenes["orders"] as $lista => $orden) {
        if ($_GET["orden"]==$orden["order_number"]) {
            $notfound = false;
            $respuesta = '{"exito":"SI",';
            $respuesta .= '"orden":{';
            $respuesta .= '"id":"'.$orden["id"].'",';
            $respuesta .= '"numero":"'. $_GET["orden"] .'",';
            $respuesta .= '"nombres":"' . trim($orden["customer"]["first_name"]).' '.trim($orden["customer"]["last_name"]) . '",';
            $respuesta .= '"identificacion":"' . $orden["email"] . '",';
            $respuesta .= '"montoorden":' . $orden["total_price"] . '}';
            $respuesta .= '}';
        }
    }
}

if ($notfound) {
    $url = $urlOrdenesParcialmentePagadas;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url );
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);// set optional params
    curl_setopt($ch,CURLOPT_HEADER, false);

    $result=curl_exec($ch);

    curl_close($ch);
    if (isset($result)) {
        $ordenes=json_decode($result,true);
        foreach ($ordenes["orders"] as $lista => $orden) {
            if ($_GET["orden"]==$orden["order_number"]) {
                $url = $urlBuscarTransaccion.trim($orden["id"]).'/transactions.json';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,$url );
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);// set optional params
                curl_setopt($ch,CURLOPT_HEADER, false);

                $result=curl_exec($ch);
                curl_close($ch);

                $transaccion=json_decode($result,true);

                $parent_id = $transaccion["transactions"][0]["id"];
                $saldo = $transaccion["transactions"][0]["amount"];
                foreach ($transaccion["transactions"] as $key => $value) {
                  if ($value["id"]!=$parent_id) {
                    $saldo -= $value["amount"];
                  }
                }
                $notfound = false;
                $respuesta = '{"exito":"SI",';
                $respuesta .= '"orden":{';
                $respuesta .= '"id":"'.$orden["id"].'",';
                $respuesta .= '"numero":"'. $_GET["orden"] .'",';
                $respuesta .= '"nombres":"' . trim($orden["customer"]["first_name"]).' '.trim($orden["customer"]["last_name"]) . '",';
                $respuesta .= '"identificacion":"' . $orden["email"] . '",';
                $respuesta .= '"montoorden":' . $saldo . '}';
                $respuesta .= '}';
            }
        }
    }
}

echo $respuesta;
?>
