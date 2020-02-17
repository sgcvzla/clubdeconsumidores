<?php
// include ("./class_zoom_json_services.php"); // Implementacion del objeto proxy (PHP4)
// include ("./function_json_encode.php"); // para php4 la funcion no existe

// url para conectarse al servicio
// $url = "http://sandbox.grupozoom.com/proveedores/frontend/webservicesge/";
$url = "http://sandbox.grupozoom.com/baaszoom/public/guiaelectronica/createShipment";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url );
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, "codcliente=1&clave=456789&certificado=$1$RH0sVTL9$Vwrq9vN7c0ltZ1Xt5yeW61&codservicio=2&consignacion=0&contacto_remitente=DENIS RAMIREZ&codciudadrem=19&codmunicipiorem=507&codparroquiarem=10107&zona_postal_remitente=1010&telefono_remitente=04125887034&direccion_remitente=CALLE 3A URBANIZACIÓN LA URBINA&inmueble_remitente=EDIFICIO DON PASQUALES, PISO PB, APTO 1080&retira_oficina=0&codciudaddes=19&codmunicipiodes=507&codparroquiades=10107&zona_postal_destino=2001&codoficinades=136&destinatario=JOHAN FRANCO&contacto_destino=JOHAN FRANCO&cirif_destinatario=V-20175579&celular=04125887034&telefono_destino=02412410789&direccion_destino=AV BOLIVAR DE LA CANDELARIA&inmueble_destino=CASA N°10-10 URBANIZACIÓN LA CANDELERIA&siglas_casillero=BRM&descripcion_contenido=ROPA Y CALZADO&referencia=CREANDO GUIA N° 1&numero_piezas=1&peso_bruto=1.75&tipo_envio=D&valor_declarado=150000&seguro=1&valor_mercancia=100&modalidad_cod=1&codoficinaori=44");
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);// set optional params
curl_setopt($ch,CURLOPT_HEADER, false);

$result=curl_exec($ch);

curl_close($ch);


print_r( $result);
    
// $cliente = new ZoomJsonService($url);

// $cliente->call("generarToken", array("codigo_cliente"=>1,"clave"=>456789), "llamadaExitosa", "llamadaError");

function llamadaExitosa($result) {
    print_r( json_encode($result));
    print_r( json_encode('Exito'));
}    

function llamadaError($error) {
    print_r( json_encode('Error'));
}
?>