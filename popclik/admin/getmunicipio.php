<?php
header('Access-Control-Allow-Origin: *');
$url = "http://sandbox.grupozoom.com/baaszoom/public/canguroazul/getMunicipios?codciudad=".$_GET["codciudad"];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url );
curl_setopt($ch, CURLOPT_POST, false);
// curl_setopt($ch, CURLOPT_POSTFIELDS, "login=".$user."&clave=".$pass);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);// set optional params
curl_setopt($ch,CURLOPT_HEADER, false);

$result=curl_exec($ch);

curl_close($ch);

echo $result;

// echo '{"codciudad": "4", "nombre_ciudad": "VALENCIA", "nombre_estado": "CARABOBO"}';
?>