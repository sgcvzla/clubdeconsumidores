<?php
header('Content-Type: application/json');
include_once("../../_config/conexion.php");

$valores = json_decode($_POST["seleccion"], true);

$mensaje = 'Hubo error al actualizar las transacciones: ';
$errores = 0;
$coma = '';
foreach ($valores["confirmar"] as $confirmar => $transaccion) {
  $query = 'SELECT * FROM reportepago WHERE id='.$transaccion;
  $result = mysqli_query($link, $query);
  $punto = '';
  $monto = 0.00;
  $flagerror = false;
  if ($row = mysqli_fetch_array($result)) {
    $punto = $row['punto'];
    $monto = $row['monto'];
    $fechareporte = $row['fechareporte'];
    $orden = $row['orden'];
    $email = $row['email'];
    $nombre = $row['nombredepositante'];
    $query = 'UPDATE reportepago SET status="Confirmado" WHERE id='.$transaccion;
    $result = mysqli_query($link, $query);
    confirmacionemail($email,$nombre,$fechareporte,$orden);
    $flagerror = false;
  } else {
    $flagerror = true;
  }
  if ($flagerror) {
    $coma = ($errores==0) ? '' : ',';
    $errores++;
    $mensaje .= $coma.$transaccion;
  }
}
foreach ($valores["anular"] as $anular => $transaccion) {
  $query = 'UPDATE reportepago SET status="Anulado" WHERE id='.$transaccion;
  $flagerror = false;
  if($result = mysqli_query($link, $query)) {
    $flagerror = false;
  } else {
    $flagerror = true;
  }
  if ($flagerror) {
    $coma = ($errores==0) ? '' : ',';
    $errores++;
    $mensaje .= $coma.$transaccion;
  }
}
if ($errores>0) {
  $respuesta = '{';
  $respuesta .= '"exito":"NO",';
  $respuesta .= '"mensaje":"' . utf8_encode($mensaje.' comuniquese con soporte técnico al +584244071820.') . '"';
  $respuesta .= '}';
} else {
  $respuesta = '{';
  $respuesta .= '"exito":"SI",';
  $respuesta .= '"mensaje":"' . utf8_encode('Proceso exitoso.') . '"';
  $respuesta .= '}';
}
echo $respuesta;



function confirmacionemail($correo,$nombre,$fechareporte,$orden) {
$fecha1 = substr($fechareporte,8,2).'/'.substr($fechareporte,5,2).'/'.substr($fechareporte,0,4);
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
        src: url("https://www.cash-flag.com/popclik/MyriadWebPro.ttf") format("truetype");
      }
    </style>
  </head>
  <body>
    <table width="400" height="auto">
      <tbody>
        <tr>
          <td>
            <div style="padding: 10px; border: solid 12.5px gray; text-align: center;font-family: Arial; color: gray;">
              <p><img src="https://www.cash-flag.com/img/popclikhoriz.png" width="120" height="27.39" /></p>
              <p>
                <img src="https://www.cash-flag.com/img/icono-03.png" width="60" height="60.41" /><br/>
                <span style="font-size: 150%; color: #00770b;">
                  <b>¡Su pago ha sido verificado!</b>
                </span>
              </p>
              <p>Estimado, <b>'.trim($nombre).'</b></p>

              <p>Su pago reportado el día '.$fecha1.' para la orden <b style="font-size: 150%;">No. '.trim($orden).'.</b> ha sido verirficado exitosamente.</p>

              <p>Su compra está siendo procesada en nuestro almacenes y será despachada a la brevedad posible.</p>

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
