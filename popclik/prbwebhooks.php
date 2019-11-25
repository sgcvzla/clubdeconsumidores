<?php
	if (isset($_POST)) {
	    $mensaje = '$_POST<br/>';
		foreach ($_POST as $key => $value) {
		    $mensaje .= $key .' -> '.$value.'<br/>';
		}
	}
	if (isset($_GET)) {
	    $mensaje = '$_GET<br/>';
		foreach ($_GET as $key => $value) {
		    $mensaje .= $key .' -> '.$value.'<br/>';
		}
	}
    $asunto = utf8_decode('prueba webhook');
    // $cabeceras = 'Content-type: text/json';
    $cabeceras = '';

    $correo = 'soluciones2000@gmail.com';

	mail($correo,$asunto,$mensaje,$cabeceras);
?>
