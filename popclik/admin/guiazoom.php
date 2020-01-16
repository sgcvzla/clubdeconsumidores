<?php
include ("./class_zoom_json_services.php"); // Implementacion del objeto proxy (PHP4)
include ("./function_json_encode.php"); // para php4 la funcion no existe

// url para conectarse al servicio
// $url = "http://sandbox.grupozoom.com/proveedores/frontend/webservicesge/";
$url = "http://www.grupozoom.com/servicios/webservices/";
    
$cliente = new ZoomJsonService($url);

$cliente->call("generarToken", array("codigo_cliente"=>1,"clave"=>456789), "llamadaExitosa", "llamadaError");

function llamadaExitosa($result) {
    print_r( json_encode($result));
    print_r( json_encode('Exito'));
}    

function llamadaError($error) {
    print_r( json_encode('Error'));
}
?>