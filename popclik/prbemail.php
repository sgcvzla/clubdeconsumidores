<?php
// echo '1 '.ini_get('sendmail_from');
// echo '<br/>';
// echo '2 '.ini_set('sendmail_from','cobranza@popclik.com');
// echo '<br/>';
// echo '3 '.ini_get('sendmail_from');
// $from = ini_get('sendmail_from');
    $mensaje .= 
    '<div style="border: solid thin lightgray; width: auto;">
      <p><img src="https://www.cash-flag.com/img/popclikhoriz.png" width="237" height="54.1096" /></p>
      <p>Comprobante de pago #9999</p>
      <h2><b>¡Pago recibido!</b></h2>
      <p>Estimado, <b>Luis Rodriguez</b></p>
      <p>El aliado comercial ZOOM (Aliado C.A.) ha recibido la cantidad de:</p>
      <h2><b>USD 30.00</b></h2>
      <p>Por concepto de pago total de la orden <b>No. 1234.</b></p>
      <p>¡Gracias por comprar en POPClik!</p>
      <p>Para revisar el estado de tu pedido, ingresa a tu cuenta de usuario en <a href="http://www.popclik.com">www.popclik.com</a></p>
      <p>Si tienes alguna pregunta o comentario, contáctanos a través de <a href="mailto:info@popclik.com">info@popclik.com</a></p>
    </div>';

    $asunto = utf8_decode('Recibo de cobro de la orden No. 1234.');
    // $cabeceras = 'Content-type: text/html'.'\r\n';

    $cabeceras = 'Content-type: text/html; charset=utf-8'."\r\n";
    $cabeceras .= 'From: '.utf8_decode('POP CLIK - Sistema de Recaudación <'.trim('cobranza@popclik.com').'>');
    $correo = 'soluciones2000@gmail.com';

   // if (strpos($_SERVER["SERVER_NAME"],'localhost')===FALSE) {              
	mail($correo,$asunto,$mensaje,$cabeceras);
	// mail($correo,$asunto,$mensaje,$cabeceras,'-f '.'cobranza@popclik.com');
?>
