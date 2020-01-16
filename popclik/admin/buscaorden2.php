<?php
$url = 'https://060d3f560e548b08d411633144ae09fb:44e7bd60350b8ad36bc0f899930ad51e@anatie.myshopify.com/admin/api/2019-10/orders.json?financial_status=paid';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url );
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);// set optional params
curl_setopt($ch,CURLOPT_HEADER, false);

$result=curl_exec($ch);

curl_close($ch);

// echo $result;

$respuesta = '{"exito":"NO",';
$respuesta .= '"orden":{';
$respuesta .= '"id":"",';
$respuesta .= '"nombres":"",';
$respuesta .= '"telefono":"",';
$respuesta .= '"direccion":"",';
$respuesta .= '"zonapostal":"",';
$respuesta .= '"ciudad":"",';
$respuesta .= '"estado":""}';
$respuesta .= '}';
if (isset($result)) {
    $ordenes=json_decode($result,true);
    foreach ($ordenes["orders"] as $lista => $orden) {
        if ($_GET["orden"]==$orden["order_number"]) {
            $respuesta = '{"exito":"SI",';
            $respuesta .= '"orden":{';
            $respuesta .= '"id":"'.$orden["id"].'",';
            $respuesta .= '"nombres":"' . trim($orden["customer"]["first_name"]).' '.trim($orden["customer"]["last_name"]) . '",';
            $respuesta .= '"telefono":"' . trim($orden["customer"]["phone"]) . '",';
            $respuesta .= '"direccion":"' . trim($orden["shipping_address"]["address1"]).' '.trim($orden["shipping_address"]["address2"]). '",';
            $respuesta .= '"zonapostal":"' . trim($orden["shipping_address"]["zip"]) . '",';
            $respuesta .= '"ciudad":"' . trim($orden["shipping_address"]["city"]) . '",';
            $respuesta .= '"estado":"' . trim($orden["shipping_address"]["province"]) . '"}';
            $respuesta .= '}';
        }
    }
}

echo $respuesta;
?>
