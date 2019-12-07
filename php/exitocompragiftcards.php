<?php
include_once("../_config/conexion.php");
///////////////////////////////////////////////////////////////////////////////////////////////////////
include_once("./funciones.php");

$registro = json_decode($_GET['registro'],true);
$url = (isset($_GET['url'])) ? $_GET['url'] : "https://www.clubdeconsumidores.com.ve" ;

// Asignación de variables
$nombres = $registro['nombres'];
$apellidos = $registro['apellidos'];
$email = $registro['email'];
$moneda = $registro['moneda'];

// Buscaar datos de la tarjeta
$query = "select * from giftcards where email='".trim($email)."' and moneda='".trim($moneda)."'";
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
    // Busca la tarjeta existente
    $card = $row["card"];
    $saldo = $row["saldo"];

    $fecha = $row["fechacompra"];
    $fechavencimiento = $row["fechacompra"];

    $datetime1 = date_create($fecha);
    $datetime2 = date_create($fechavencimiento);
    $diferencia = date_diff($datetime1, $datetime2);
} else {
    // Generar la tarjeta
    $card = '';
    $saldo = 0.00;

    $fecha = '';
    $fechavencimiento = '';

    $diferencia = 0;
}

$txtcard = substr($card,0,4).'-'.substr($card,4,4).'-'.substr($card,8,4).'-'.substr($card,12,4);

$mensaje = '["Tarjeta de regalo generada exitosamente:","",';
$mensaje .= '"A nombre de: '.trim($nombres).' '.trim($apellidos).'",';
$mensaje .= '"Número de tarjeta: '.$txtcard.'",';
$mensaje .= '"Su nuevo saldo es de: ';
switch ($moneda) {
    case 'bs':      $mensaje .= "Bs. ";     break;
    case 'dolares': $mensaje .= "US$ ";     break;
    case 'cripto':  $mensaje .= "Criptos "; break;
}
$mensaje .= number_format($saldo,2,',','.').'","",';
$mensaje .= '"Fecha de vencimiento: '.substr($fechavencimiento,8,2)."/".substr($fechavencimiento,5,2)."/".substr($fechavencimiento,0,4).'",';
$mensaje .= '"Te quedan ';
$mensaje .= $diferencia->format('%a').' días';
$mensaje .= ' para usarla."]';

///////////////////////////////////////////////////////////////////////////////////////////////////////
$cadena = $url.'&exito=si&mensaje='.$mensaje; 
// $cadena = 'https://www.clubdeconsumidores.com.ve/prepago/exito.html'; 

echo "
    <script>
        parent.opener.location.assign('".$cadena."');
        window.close();
    </script>
    ";
?>
