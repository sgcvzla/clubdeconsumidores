<?php
include_once("../_config/configShopify.php");

$url = $urlUnaOrden.$_GET["orden"].".json";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);// set optional params
curl_setopt($ch,CURLOPT_HEADER, false);

$result=curl_exec($ch);

curl_close($ch);

// echo $result;

$respuesta = '{"exito":"NO",';
$respuesta .= '"orden":{';
$respuesta .= '"id":0,';
$respuesta .= '"numero":0,';
$respuesta .= '"nombres":"",';
$respuesta .= '"email":"",';
$respuesta .= '"saldo":0}';
$respuesta .= '}';
if (isset($result)) {
    $orden=json_decode($result,true);
    $respuesta = '{"exito":"SI",';
    $respuesta .= '"orden":{';
    $respuesta .= '"id":'.$orden["order"]["id"].',';
    $respuesta .= '"numero":'. $orden["order"]["order_number"] .',';
    $respuesta .= '"nombres":"';
        $respuesta .= trim($orden["order"]["customer"]["first_name"]).' ';
        $respuesta .= trim($orden["order"]["customer"]["last_name"]) . '",';
    $respuesta .= '"email":"'. $orden["order"]["email"] .'",';
    $respuesta .= '"saldo":'. $orden["order"]["total_price"] .'}';
    $respuesta .= '}';
}
echo $respuesta;
?>
