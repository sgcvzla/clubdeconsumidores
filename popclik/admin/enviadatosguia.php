<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include_once("../../_config/conexion.php");
include_once("../../_config/configShopify.php");


// Buscar orden para generar guía
$url = $urlUnaOrden.trim($_POST["orden_id"]).".json";
// $url = $urlUnaOrden."2153289089098.json";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url );
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);// set optional params
curl_setopt($ch,CURLOPT_HEADER, false);

$result=curl_exec($ch);

$aux=json_decode($result,true);
$orden = $aux["order"];

curl_close($ch);


// Extracción de los códigos de ciudad, municipio y parroquia
$cadena0 = substr($orden["shipping_address"]["address2"],15);
$codciud = substr($cadena0,0,strpos($cadena0,"."));

$cadena1 = substr($cadena0,strpos($cadena0,".")+1);
$codmunc = substr($cadena1,0,strpos($cadena1,"."));

$codparr = substr($cadena1,strpos($cadena1,".")+1);


// Cálculo de la cantidad, peso y monto de la órden
$cantidad = 0;
$largo = count($orden["line_items"]);
for ($i=0; $i < $largo; $i++) { 
  $cantidad += $orden["line_items"][$i]["quantity"];
}

$pesoenKg = $orden["total_weight"]/1000;
$valorenDolares = $orden["total_price"]*78000;

$idcliente = $orden["customer"]["id"];
$zip = $orden["shipping_address"]["zip"];
$nombrecliente = $orden["shipping_address"]["first_name"]." ".$orden["shipping_address"]["last_name"];
$phone = $orden["shipping_address"]["phone"];
$address = $orden["shipping_address"]["address1"];
$numorden = $orden["order_number"];


// Generar token
$url = "http://sandbox.grupozoom.com/baaszoom/public/guiaelectronica/generarToken";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url );
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, "login=".$user_zoom."&clave=".$pass_zoom);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);// set optional params
curl_setopt($ch,CURLOPT_HEADER, false);

$result=curl_exec($ch);
$aux=json_decode($result,true);
$token = $aux["entidadRespuesta"][0]["token"];

curl_close($ch);


// Genera certificado
$url = "http://sandbox.grupozoom.com/baaszoom/public/guiaelectronica/zoomCert";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url );
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, "login=".$user_zoom."&password=".$pass_zoom."&token=".$token."&frase_privada=".$priv_zoom);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);// set optional params
curl_setopt($ch,CURLOPT_HEADER, false);

$result=curl_exec($ch);
$aux=json_decode($result,true);
$certificado = $aux["entidadRespuesta"][0]["certificado"];

curl_close($ch);


// Genera codigo oficina destino
$url = "http://sandbox.grupozoom.com/baaszoom/public/canguroazul/getOficinasGE?codigo_ciudad_destino=".$codciud;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url );
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);// set optional params
curl_setopt($ch,CURLOPT_HEADER, false);

$result=curl_exec($ch);
$aux=json_decode($result,true);
$codoficinadestino = $aux["entidadRespuesta"][0]["codoficina"];

curl_close($ch);


// Buscar cédula o rif del cliente
$url = $urlUnCustomer.trim($idcliente)."/metafields.json";
// $url = $urlUnaOrden."2153289089098.json";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url );
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);// set optional params
curl_setopt($ch,CURLOPT_HEADER, false);

$result=curl_exec($ch);
if (isset($result)) {
    $metafields=json_decode($result,true);
    $cedularif = "0";
    foreach ($metafields as $lista => $orden) {
        if ($lista["key"]=="cedula") {
            $cedularif = (isset($lista["value"]) ? $lista["value"] : "0");
        }
    }
}

curl_close($ch);


// Genera guía
$campos =  "login=".$user_zoom;
$campos .= "&clave=".$pass_zoom;
$campos .= "&certificado=".$certificado;
$campos .= "&codservicio="."71"; // Codigo de servicio de envío nacional 10KG
$campos .= "&consignacion="."0"; // Default, solo aplica para envíos prepagados, el "1" es consignación
$campos .= "&contacto_remitente="."POP CLIK";
$campos .= "&codciudadrem="."4"; // "4" es Valencia
$campos .= "&codmunicipiorem="."814"; // "814" es Valencia
$campos .= "&codparroquiarem="."81407"; // "81407" es San José
$campos .= "&zona_postal_remitente="."2001"; // Valencia
$campos .= "&telefono_remitente="."02418427724";
$campos .= "&direccion_remitente="."Paseo Cabriales, Torre Movilnet";
$campos .= "&inmueble_remitente="."mezzanina local 2-3";
$campos .= "&retira_oficina="."0"; // Por default no se aceptará retiro en oficina
$campos .= "&codciudaddes=".$codciud;
$campos .= "&codmunicipiodes=".$codmunc;
$campos .= "&codparroquiades=".$codparr;
$campos .= "&zona_postal_destino=".$zip;
$campos .= "&codoficinades=".$codoficinadestino;
$campos .= "&destinatario=".$nombrecliente;
$campos .= "&contacto_destino=".$nombrecliente;
$campos .= "&cirif_destinatario=".$cedularif;

$campos .= "&celular="."584244178584";

$campos .= "&telefono_destino=".$phone;
$campos .= "&direccion_destino=".$address;
$campos .= "&inmueble_destino=".$address;
// $campos .= "&siglas_casillero=".BRM
$campos .= "&descripcion_contenido="."MERCANCIA";
$campos .= "&referencia="."Orden No. ".$numorden;
$campos .= "&numero_piezas=".$cantidad;
$campos .= "&peso_bruto=".$pesoenKg;
$campos .= "&tipo_envio="."M";   // "M" para mercancía
$campos .= "&valor_declarado=".$valorenDolares;
$campos .= "&seguro="."1";    // "1" para seguro
$campos .= "&valor_mercancia=".$valorenDolares;
$campos .= "&modalidad_cod="."1";   // Default
$campos .= "&codoficinaori="."136"; // Principal Valencia

// echo $campos;
$url = "http://sandbox.grupozoom.com/baaszoom/public/guiaelectronica/createShipment";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, $campos);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);// set optional params
curl_setopt($ch,CURLOPT_HEADER, false);

$result=curl_exec($ch);
$aux=json_decode($result,true);
$numguia = 0;
$numguia = $aux["entidadRespuesta"][0]["numguia"];

curl_close($ch);

// Respuesta
$respuesta = '{"exito":"SI","mensaje":"Registro exitoso, número de guía: '.$numguia.'","numguia": '.$numguia.', "response":'.$result.'}';

// Buscar orden para generar guía
$url = $urlUnaOrden.trim($_POST["orden_id"])."/fulfillments.json";
// $url = $urlUnaOrden."2153289089098.json";

$ch = curl_init();
$variant = array('fulfillment' =>
    array(
        'location_id' =>  33406189642,  // Este número corresponde al warehouse
        'tracking_number' => $numguia,
        'tracking_urls' => array('www.zoomenvios.com'),
        'notify_customer' => true,
        'tracking_company' => 'Zoom Envíos'
    )
);

curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($variant));
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result=curl_exec($ch);

$status = curl_error($ch);

curl_close($ch);


echo $respuesta;
?>
