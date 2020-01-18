<?php
header('Content-Type: application/json');
include_once("../_config/conexion.php");
include_once("../_config/configShopify.php");

// Buscar transacción

$url = $urlBuscarTransaccion.trim($_POST["id"]).'/transactions.json';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url );
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);// set optional params
curl_setopt($ch,CURLOPT_HEADER, false);

$result=curl_exec($ch);
curl_close($ch);

$transaccion=json_decode($result,true);

$parent_id = $transaccion["transactions"][0]["id"];
// Fin buscar transacción

// Enviar transacción
$ch = curl_init($url);

$variant = array('transaction' =>
    array(
        'currency' =>  'USD',
        'amount' => trim($_POST["monto"]),
        'kind' => 'capture',
        'parent_id' => trim($parent_id)
    )
);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($variant));
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$respuesta = '{';
$respuesta .= '"exito":"NO",';
$respuesta .= '"mensaje":"' . utf8_encode('Pago no enviado') . '",';
$respuesta .= '"response":' . $response;
$respuesta .= '}';
if (isset($response)) {
    $exito = (strpos($response,'transaction')==false) ? false : true;
    $si = 'NO';
    $mensaje = 'Error interno en la tienda en línea';
    if ($exito) {
      $pago=json_decode($response,true);

      $si = 'SI';
      $fechapago = date('Y-m-d');
      $banco = 'Cobro en efectivo';
      $referencia = $pago["transaction"]["id"];
      $orden = $_POST["orden"];
      $tipo = '51'; // Recarga
      $signo = '-';
      $status = 'Confirmada';

      $query  = 'INSERT INTO recaudo_transacciones (punto, fecha, banco, referencia, orden, tipo, signo, monto, status, usuario, detalle) ';
      $query .= 'VALUES ('.$_POST["punto"].',"'.$fechapago.'","'.$banco.'","'.$referencia.'","'.$orden.'",';
      $query .= '"'.$tipo.'","'.$signo.'",'.$_POST["monto"].',"'.$status.'",'.$_POST["usuario"].',"'.$_POST["nombres"].'")';
      if($result = mysqli_query($link, $query)) {
        $quer2 = 'SELECT * FROM puntosderecaudacion where id='.$_POST["punto"];
        $resul2 = mysqli_query($link, $quer2);
        if ($ro2 = mysqli_fetch_array($resul2)) {
          $nombrepunto = $ro2['nombre'];
          $emailpunto = $ro2['email'];
          $lineadecredito = $ro2['lineadecredito'];
          $lineadecredito -= $_POST["monto"];

          $quer3 = 'UPDATE puntosderecaudacion SET lineadecredito='.$lineadecredito.' WHERE id='.$_POST["punto"];
          if ($resul3 = mysqli_query($link, $quer3)){
            $si = 'SI';
            $mensaje = 'Pago enviado exitosamente';
            enviaremail($_POST["email"],$nombrepunto,$_POST["nombres"],$_POST["monto"],$_POST["parcial"],$orden,$emailpunto,$referencia);
          } else {
            $si = 'NO';
            $mensaje = 'No se actualizó el saldo de la línea de crédito';
          }
        } else {
          $si = 'NO';
          $mensaje = 'Error en la identificación del punto de recaudación';
        }
      } else {
        $si = 'NO';
        $mensaje = 'Ocurrió un error en el registro de la transacción';
      }
    }
    $respuesta = '{';
    $respuesta .= '"exito":"'.$si.'",';
    $respuesta .= '"mensaje":"' . $mensaje . '",';
    $respuesta .= '"response":' . $response;
    $respuesta .= '}';
}
echo $respuesta;

function enviaremail($correo,$nombrepunto,$nombres,$monto,$parcial,$orden,$emailpunto,$referencia) {
$mensaje = 
  '<!DOCTYPE html>
  <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Recibo</title>
    <link rel="stylesheet" href="">
    <style>
      @font-face {
        font-family: recibo;
        src: url("https://www.clubdeconsumidores.com.ve/popclik/MyriadWebPro.ttf") format("truetype");
      }
    </style>
  </head>
  <body>
    <table width="400" height="auto">
      <tbody>
        <tr>
          <td>
            <div style="padding: 10px; border: solid 12.5px gray; text-align: center;font-family: Arial; color: gray;">
              <p><img src="https://www.clubdeconsumidores.com.ve/img/popclikhoriz.png" width="120" height="27.39" /></p>
              <p>
                <img src="https://www.clubdeconsumidores.com.ve/img/icono-03.png" width="60" height="60.41" /><br/>
                <span style="font-size: 150%; color: #00770b;">
                  <b>¡Pago recibido!</b>
                </span>
              </p>
              <p>Comprobante de pago #'.trim($referencia).'</p>
              <p>Estimado, <b>'.trim($nombres).'</b></p>
              <p>El aliado comercial ZOOM ('.trim($nombrepunto).') ha recibido la cantidad de:</p>
              <h1><b>USD '.number_format($monto,2,',','.').'</b></h1>
              <p>Por concepto de pago ';
                if ($parcial=='si') {
                  $mensaje .= utf8_decode('parcial ');
                } else {
                  $mensaje .= utf8_decode('total ');
                }
                $mensaje .= 'de la orden <br/>
                <b style="font-size: 150%;">No. '.trim($orden).'.</b>
              </p>
                <p><i>¡Gracias por comprar en POPClik!</i></p>
                <p style="font-size: 80%;"><b>Para revisar el estado de tu pedido, ingresa a tu cuenta de usuario en <a href="http://www.popclik.com">www.popclik.com</a></b></p>
                <p style="font-size: 80%;"><b>Si tienes alguna pregunta o comentario, contáctanos a través de <a href="mailto:info@popclik.com">info@popclik.com</a></b></p>
              </div>
          </td>
        </tr>
      </tbody>
    </table>
  </body>
  </html>';

$asunto = utf8_decode('Recibo de cobro de la orden No. '.$orden.'.');
// $cabeceras = 'Content-type: text/html'.'\r\n';

$cabeceras = 'Content-type: text/html; charset=utf-8'."\r\n";
$cabeceras .= 'From: '.utf8_decode('POP CLIK - Sistema de Recaudación <'.trim($emailpunto).'>');

// if (strpos($_SERVER["SERVER_NAME"],'localhost')===FALSE) {              
  // mail($correo,$asunto,$mensaje,$cabeceras,'-f '.trim($emailpunto));
  mail($correo,$asunto,$mensaje,$cabeceras);
// }

$a = fopen('log.html','w+');
fwrite($a,$asunto);
fwrite($a,'-');
fwrite($a,$mensaje);
}

/*
function enviaremail($correo,$nombrepunto,$nombres,$monto,$parcial,$orden,$emailpunto,$referencia) {
    $mensaje = 
    '<div style="width: auto;">
      <p><img src="https://www.clubdeconsumidores.com.ve/img/popclikhoriz.png" width="237" height="54.1096" /></p>
      <p>Comprobante de pago #'.trim($referencia).'</p>
      <h2><b>¡Pago recibido!</b></h2>
      <p>Estimado, <b>'.trim($nombres).'</b></p>
      <p>El aliado comercial ZOOM ('.trim($nombrepunto).') ha recibido la cantidad de:</p>
      <h2><b>USD '.number_format($monto,2,',','.').'</b></h2>
      <p>Por concepto de pago ';
    if ($parcial=='si') {
      $mensaje .= utf8_decode('parcial ');
    } else {
      $mensaje .= utf8_decode('total ');
    }
    $mensaje .= 'de la orden <b>No. '.trim($orden).'.</b></p>
      <p>¡Gracias por comprar en POPClik!</p>
      <p>Para revisar el estado de tu pedido, ingresa a tu cuenta de usuario en <a href="http://www.popclik.com">www.popclik.com</a></p>
      <p>Si tienes alguna pregunta o comentario, contáctanos a través de <a href="mailto:info@popclik.com">info@popclik.com</a></p>
    </div>';

    $asunto = utf8_decode('Recibo de cobro de la orden No. '.$orden.'.');
    // $cabeceras = 'Content-type: text/html'.'\r\n';

    $cabeceras = 'Content-type: text/html; charset=utf-8'."\r\n";
    $cabeceras .= 'From: '.utf8_decode('POP CLIK - Sistema de Recaudación <'.trim($emailpunto).'>');

   // if (strpos($_SERVER["SERVER_NAME"],'localhost')===FALSE) {              
      // mail($correo,$asunto,$mensaje,$cabeceras,'-f '.trim($emailpunto));
      mail($correo,$asunto,$mensaje,$cabeceras);
   // }

    $a = fopen('log.html','w+');
    fwrite($a,$asunto);
    fwrite($a,'-');
    fwrite($a,$mensaje);
}
*/
?>
