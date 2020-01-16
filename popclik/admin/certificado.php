<?php
	include("./zoomcert.php");

	$codcliente = $_POST["codcliente"];
	$password = $_POST["password"];
	$token = $_POST["token"];
	$frase_privada = $_POST["fraseprivada"];

	$mensaje = zoomCert($codcliente,$password,$token,$frase_privada);

	echo $mensaje;
?>