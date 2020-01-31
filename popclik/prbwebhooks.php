<?php

define('SHOPIFY_APP_SECRET', 'c1d069cbc028ee555014195a4e072a96dff5be25df89686ce8e0bd5e249fc45a');

function verify_webhook($data, $hmac_header)
{
  $calculated_hmac = base64_encode(hash_hmac('sha256', $data, SHOPIFY_APP_SECRET, true));
  return hash_equals($hmac_header, $calculated_hmac);
}

$hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
$data = file_get_contents('php://input');
	    $mensaje = $data;
$verified = verify_webhook($data, $hmac_header);
if ($verified) {
    $orden=json_decode($data,true);
	$mensaje = '';
	foreach ($orden as $key => $value) {
	    $mensaje .= $key .' -> '.$value.'  ***  <br/>';
	}
}
// error_log('Webhook verified: '.var_export($verified, true)); //check error.log to see the result

/////////////////////////////////////////////////////////////////////////////////////////////////

// echo '<pre>';
// 	$arr = headers_list();
// 	if (isset($arr)) {
// 		var_dump($arr);
// 	    $mensaje .= 'headers_list<br/>';
// 		foreach ($arr as $value) {
// 		    $mensaje .= ' -> '.$value.'<br/>';
// 		}
// 	}
// 	if (isset($_SERVER)) {
// 		var_dump($_SERVER);
// 	    $mensaje .= '$_SERVER<br/>';
// 		foreach ($_SERVER as $key => $value) {
// 		    $mensaje .= $key .' -> '.$value.'<br/>';
// 		}
// 	}
// 	if (isset($_POST)) {
// 		var_dump($_POST);
// 	    $mensaje .= '$_POST<br/>';
// 		foreach ($_POST as $key => $value) {
// 		    $mensaje .= $key .' -> '.$value.'<br/>';
// 		}
// 	}
// 	if (isset($_GET)) {
// 		var_dump($_GET);
// 	    $mensaje .= '$_GET<br/>';
// 		foreach ($_GET as $key => $value) {
// 		    $mensaje .= $key .' -> '.$value.'<br/>';
// 		}
// 	}
// echo '</pre>';
    $asunto = utf8_decode('prueba webhook');
    // $cabeceras = 'Content-type: text/json';
    $cabeceras = '';

    $correo = 'soluciones2000@gmail.com';

	mail($correo,$asunto,$mensaje,$cabeceras);
?>
