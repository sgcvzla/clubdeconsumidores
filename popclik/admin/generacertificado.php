<?php
header('Access-Control-Allow-Origin: *');
$url = "http://sandbox.grupozoom.com/baaszoom/public/guiaelectronica/zoomCert";

$user = $_POST["login"];
$pass = $_POST["clave"];
$token = $_POST["token"];
$priv_key = $_POST["frase_privada"];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url );
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, "login=".$user."&password=".$pass."&token=".$token."&frase_privada=".$priv_key);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);// set optional params
curl_setopt($ch,CURLOPT_HEADER, false);

$result=curl_exec($ch);

curl_close($ch);

echo $result;
?>