<?php
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$respuesta = '{';
$respuesta .= '"exito":"NO",';
$respuesta .= '"mensaje":"' . utf8_encode('Pago no enviado') . '"';
$respuesta .= '}';

$exito = 'SI';
$tipodepositante = 'cliente';
$fechareporte = date('Y-m-d');
$idorden = $_POST["idorden"];
$orden = $_POST["orden"];
$origen = $_POST["origen"];
$fechatransaccion = $_POST["fecha"];
$nombredepositante = $_POST["cliente"];
$monto = $_POST["monto"];
$referencia = $_POST["referencia"];
$status = 'Pendiente por verificar';
$correo = $_POST["email"];

$query  = 'INSERT INTO reportepago(tipodepositante, fechareporte, idorden, orden, origen, fechatransaccion, nombredepositante, monto, referencia, status) VALUES ("'.$tipodepositante.'","'.$fechareporte.'",'.$idorden.','.$orden.',"'.$origen.'","'.$fechatransaccion.'","'.$nombredepositante.'",'.$monto.',"'.$referencia.'","'.$status.'")';
if($result = mysqli_query($link, $query)) {
  $si = 'SI';
  $mensaje = 'Pago registrado exitosamente';
  enviaremail($correo,$fechareporte,$fechatransaccion,$nombredepositante,$orden,$monto,$referencia,$origen);
} else {
  $si = 'NO';
  $mensaje = 'Ocurrió un error en el registro de la transacción';
}
$respuesta = '{';
$respuesta .= '"exito":"'.$si.'",';
$respuesta .= '"mensaje":"' . $mensaje . '"';
$respuesta .= '}';

echo $respuesta;


function enviaremail($correo,$fechareporte,$fechapago,$nombres,$orden,$monto,$referencia,$origen) {
$fecha1 = substr($fechareporte,8,2).'/'.substr($fechareporte,5,2).'/'.substr($fechareporte,0,4);
$fecha2 = substr($fechapago,8,2).'/'.substr($fechapago,5,2).'/'.substr($fechapago,0,4);
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
                  <b>¡Reporte de Pago recibido!</b>
                </span>
              </p>
              <p>Estimado, <b>'.trim($nombres).'</b></p>

              <p>El día '.$fecha1.' se ha registrado un reporte de pago en la orden <b style="font-size: 150%;">No. '.trim($orden).'.</b></p>

              <p>Por la cantidad de <b>USD '.number_format($monto,2,',','.').'</b></p>

              <p>Realizado por medio de <b>'.$origen.'</b> con el número de referencia <b>'.$referencia.'</b> en la fecha <b>'.$fecha2.'</b></p>

              <p><b>Una vez verificada esta información, se le informará por esta vía y se procederá al despacho de la mercancía.</b></p>

              <p><i>¡Gracias por comprar en POPClik!</i></p>

              <p style="font-size: 80%;"><b>Si tienes alguna pregunta o comentario, contáctanos a través de <a href="mailto:info@popclik.com">info@popclik.com</a></b></p>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </body>
  </html>';

$asunto = utf8_decode('Reporte de pago recibido.');

$cabeceras = 'Content-type: text/html; charset=utf-8'."\r\n";
$cabeceras .= 'From: '.utf8_decode('POP CLIK - Sistema de Recaudación <info@popclik.com>');

  mail($correo,$asunto,$mensaje,$cabeceras);

$a = fopen('log.html','w+');
fwrite($a,$asunto);
fwrite($a,'-');
fwrite($a,$mensaje);
}
?>
