<?php
include_once("../_config/conexion.php");
include_once("./funciones.php");

// Asignación de variables
$remitente = (isset($_POST['remitente'])) ? $_POST['remitente'] : "" ;
$nombres = (isset($_POST['nombres'])) ? $_POST['nombres'] : "" ;
$apellidos = (isset($_POST['apellidos'])) ? $_POST['apellidos'] : "" ;
$telefono = (isset($_POST['telefono'])) ? $_POST['telefono'] : "" ;
$email = (isset($_POST['email'])) ? $_POST['email'] : "" ;
$moneda = (isset($_POST['moneda'])) ? $_POST['moneda'] : "bs" ;
$monto = (isset($_POST['monto'])) ? $_POST['monto'] : 0 ;
switch ($moneda) {
	case 'bs':
		$montobs = $monto; $montodolares = 0.00; $montocripto = 0.00; 
		break;
	case 'dolares':
		$montobs = 0.00; $montodolares = $monto; $montocripto = 0.00; 
		break;
	case 'cripto':
		$montobs = 0.00; $montodolares = 0.00; $montocripto = $monto; 
		break;
	default:
		$montobs = $monto; $montodolares = 0.00; $montocripto = 0.00; 
		break;
}
$tipotransaccion = '01';
$tasadolarbs = 1.00;
$tasadolarcripto = 1.00;
$idproveedor = (isset($_POST['idproveedor'])) ? $_POST['idproveedor'] : 0 ;
$tipopago = (isset($_POST['tipopago'])) ? $_POST['tipopago'] : 'efectivo' ;
$origen = (isset($_POST['origen'])) ? $_POST['origen'] : '' ;
$referencia = (isset($_POST['referencia'])) ? $_POST['referencia'] : '' ;
// Buscar el nombre del proveedor para generar la giftcard
$query = "select * from proveedores where id=".$idproveedor;
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
    $nombreproveedor = $row["nombre"];
} else {
    $nombreproveedor = '';
}

// Buscar el id del socio (si existe)
$query = "select * from socios where email='".trim($email)."'";
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
    $idsocio = $row["id"];
} else {
    $idsocio = 0;
}

// Buscaar datos de la tarjeta
$tarjetaexiste = false;
$query = "select * from giftcards where email='".trim($email)."' and moneda='".trim($moneda)."'";
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
	// Busca la tarjeta existente
	$tarjetaexiste = true;
    $card = $row["card"];
    $saldoant = $row["saldo"];
    $saldo = ($tipopago == 'efectivo') ? $row["saldo"]+$monto : $row["saldo"] ;
} else {
	// Generar la tarjeta
	$tarjetaexiste = false;
	$card = generagiftcard($nombres,$apellidos,$telefono,$email,$nombreproveedor,$moneda,$link);
    $saldoant = 0.00;
    $saldo = ($tipopago == 'efectivo') ? $monto : 0.00 ;
}

// Fecha de compra
$fecha = date('Y-m-d');

// Fecha de vecnimiento (1 año)
$fechavencimiento = strtotime('+1 year', strtotime ($fecha));
$fechavencimiento = date('Y-m-d', $fechavencimiento);

$datetime1 = date_create($fecha);
$datetime2 = date_create($fechavencimiento);
$diferencia = date_diff($datetime1, $datetime2);

// Status, si es en efectivo queda lista para usar de inmediato, si no queda por conciliar
$status = ( $tipopago == 'efectivo') ? 'Lista para usar' : 'Por confirmar pago' ;

// Encripta la giftcard
$hash = hash("sha256",$card.$nombres.$apellidos.$telefono.$email.$monto.$idproveedor.$moneda);

$query = "INSERT INTO giftcards_transacciones (idsocio, fecha, tipotransaccion, tipomoneda, montobs, montodolares, montocripto, tasadolarbs, tasadolarcripto, documento, origen, status, card) VALUES (".$idsocio.",'".$fecha."','".$tipotransaccion."','".$moneda."',".$montobs.",".$montodolares.",".$montocripto.",".$tasadolarbs.",".$tasadolarcripto.",'".$referencia."','".$origen."','".$status."','".$card."')";
if ($result = mysqli_query($link,$query)) {
	if ($tarjetaexiste) {
		$query = "UPDATE giftcards SET saldo=".$saldo." WHERE card='".trim($card)."'";
		if ($result = mysqli_query($link,$query)) {
			$txtcard = substr($card,0,4).'-'.substr($card,4,4).'-'.substr($card,8,4).'-'.substr($card,12,4);
			if ($tipopago == 'efectivo') {
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
			} else {
				$mensaje = '["Transacción registrada exitosamente.","",';
				$mensaje .= '"A nombre de: '.trim($nombres).' '.trim($apellidos).'",';
				$mensaje .= '"Número de tarjeta: '.$txtcard.'","",';
				$mensaje .= '"Su saldo actual es de: ';
				switch ($moneda) {
					case 'bs':      $mensaje .= "Bs. ";     break;
					case 'dolares': $mensaje .= "US$ ";     break;
					case 'cripto':  $mensaje .= "Criptos "; break;
				}
				$mensaje .= number_format($saldoant,2,',','.').'",';
				$mensaje .= '"Una vez confirmada la transacción, su nuevo saldo será de: ';
				switch ($moneda) {
					case 'bs':      $mensaje .= "Bs. ";     break;
					case 'dolares': $mensaje .= "US$ ";     break;
					case 'cripto':  $mensaje .= "Criptos "; break;
				}
				$mensaje .= number_format($saldoant+$monto,2,',','.').'","",';
				$mensaje .= '"Fecha de vencimiento: '.substr($fechavencimiento,8,2)."/".substr($fechavencimiento,5,2)."/".substr($fechavencimiento,0,4).'",';
				$mensaje .= '"Te quedan ';
				$mensaje .= $diferencia->format('%a').' días';
				$mensaje .= ' para usarla."]';
			}
		    $respuesta = '{"exito":"SI","mensaje":'.$mensaje.',"card":"'.$card.'","hash":"'.$hash.'"}';	
		} else {
		    $respuesta = '{"exito":"NO","mensaje":"La tarjeta no pudo generarse por favor comuniquese con soporte técnico","card":"'.$card.'","hash":"'.$hash.'"}';	
		}
	} else {
		$query = "INSERT INTO giftcards (card, remitente, nombres, apellidos, telefono, email, saldo, moneda, fechacompra, fechavencimiento, status, id_socio, id_proveedor, hash, tipopago, origen, referencia) VALUES ('".$card."','".$remitente."','".$nombres."','".$apellidos."','".$telefono."','".$email."',".$monto.",'".$moneda."','".$fecha."','".$fechavencimiento."','".$status."',".$idsocio.",".$idproveedor.",'".$hash."','".$tipopago."','".$origen."','".$referencia."')";
		if ($result = mysqli_query($link,$query)) {
			$txtcard = substr($card,0,4).'-'.substr($card,4,4).'-'.substr($card,8,4).'-'.substr($card,12,4);
			if ($tipopago == 'efectivo') {
				$mensaje = '["Tarjeta de regalo generada exitosamente:","",';
				$mensaje .= '"A nombre de: '.trim($nombres).' '.trim($apellidos).'",';
				$mensaje .= '"Número de tarjeta: '.$txtcard.'",';
				$mensaje .= '"Con un saldo de: ';
				switch ($moneda) {
					case 'bs':      $mensaje .= "Bs. ";     break;
					case 'dolares': $mensaje .= "US$ ";     break;
					case 'cripto':  $mensaje .= "Criptos "; break;
				}
				$mensaje .= number_format($monto,2,',','.').'","",';
				$mensaje .= '"Fecha de vencimiento: '.substr($fechavencimiento,8,2)."/".substr($fechavencimiento,5,2)."/".substr($fechavencimiento,0,4).'",';
				$mensaje .= '"Te quedan ';
				$mensaje .= $diferencia->format('%a').' días';
				$mensaje .= ' para usarla."]';
			} else {
				$mensaje = '["Transacción registrada exitosamente.","",';
				$mensaje .= '"A nombre de: '.trim($nombres).' '.trim($apellidos).'",';
				$mensaje .= '"Número de tarjeta: '.$txtcard.'","",';
				$mensaje .= '"Una vez confirmada la transacción, su nuevo saldo será de: ';
				switch ($moneda) {
					case 'bs':      $mensaje .= "Bs. ";     break;
					case 'dolares': $mensaje .= "US$ ";     break;
					case 'cripto':  $mensaje .= "Criptos "; break;
				}
				$mensaje .= number_format($saldoant+$monto,2,',','.').'"]';
			}
		    $respuesta = '{"exito":"SI","mensaje":'.$mensaje.',"card":"'.$card.'","hash":"'.$hash.'"}';	
		} else {
		    $respuesta = '{"exito":"NO","mensaje":"La tarjeta no pudo generarse por favor comuniquese con soporte técnico","card":"'.$card.'","hash":"'.$hash.'"}';	
		}
	}
} else {
    $respuesta = '{"exito":"NO","mensaje":"La transacción no pudo completarse por favor comuniquese con soporte técnico","card":"'.$card.'","hash":"'.$hash.'"}';	
}
echo $respuesta;





/*
// Buscar el nombre del proveedor para generar la giftcard
$query = "select * from proveedores where id=".$idproveedor;
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
    $nombreproveedor = $row["nombre"];
} else {
    $nombreproveedor = '';
}

// Generar la giftcard
$card = generagiftcard($nombres,$apellidos,$telefono,$email,$nombreproveedor,$moneda,$link);

// Fecha de compra
$fecha = date('Y-m-d');

// Fecha de vecnimiento (1 año)
$fechavencimiento = strtotime('+1 year', strtotime ($fecha));
$fechavencimiento = date('Y-m-d', $fechavencimiento);

$datetime1 = date_create($fecha);
$datetime2 = date_create($fechavencimiento);
$diferencia = date_diff($datetime1, $datetime2);

// Status, si es en efectivo queda lista para usar de inmediato, si no queda por conciliar
$status = ( $tipopago == 'efectivo') ? 'Lista para usar' : 'Por confirmar pago' ;

// Encripta la giftcard
$hash = hash("sha256",$card.$remitente.$nombres.$apellidos.$telefono.$email.$monto.$idproveedor.$moneda);

$query = "INSERT INTO giftcards (card,remitente, nombres, apellidos, telefono, email, saldo, moneda, fechacompra, fechavencimiento, status, id_proveedor, hash, tipopago, origen, referencia) VALUES ('".$card."','".$remitente."','".$nombres."','".$apellidos."','".$telefono."','".$email."',".$monto.",'".$moneda."','".$fecha."','".$fechavencimiento."','".$status."',".$idproveedor.",'".$hash."','".$tipopago."','".$origen."','".$referencia."')";
if ($result = mysqli_query($link,$query)) {
	$txtcard = substr($card,0,4).'-'.substr($card,4,4).'-'.substr($card,8,4).'-'.substr($card,12,4);
	$mensaje = '["Tarjeta de regalo generada exitosamente:","",';
	$mensaje .= '"A nombre de: '.trim($nombres).' '.trim($apellidos).'",';
	$mensaje .= '"Número de tarjeta: '.$txtcard.'",';
	$mensaje .= '"Con un sado de: ';
	switch ($moneda) {
		case 'bs':      $mensaje .= "Bs. ";     break;
		case 'dolares': $mensaje .= "US$ ";     break;
		case 'cripto':  $mensaje .= "Criptos "; break;
	}
	$mensaje .= number_format($monto,2,',','.').'",';
	$mensaje .= '"Fecha de vencimiento: '.substr($fechavencimiento,8,2)."/".substr($fechavencimiento,5,2)."/".substr($fechavencimiento,0,4).'",';
	$mensaje .= '"Te quedan ';
	$mensaje .= $diferencia->format('%a').' días';
	$mensaje .= ' para usarla."]';

    $respuesta = '{"exito":"SI","mensaje":'.$mensaje.',"card":"'.$card.'","hash":"'.$hash.'"}';	
} else {
    $respuesta = '{"exito":"NO","mensaje":"La tarjeta no pudo generarse por favor comuniquese con soporte técnico","card":"'.$card.'","hash":"'.$hash.'"}';	
}
echo $respuesta;
?>
*/
?>
