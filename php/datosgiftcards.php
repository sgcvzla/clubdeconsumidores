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



	/*
		El número de la tarjeta está compuesto por 10 caracteres en el orden que sigue:

		AAGBBGCCDDGEEGFF -> AAGB BGCC DDGE EGFF

		AA = Código de dos dígitos correspondiente a la primera letra del nombre
		G  = Primer dígito del número correlativo de 4 dígitos
		BB = Código de dos dígitos correspondiente a la primera letra del apellido
		G  = Segundo dígito del número correlativo de 4 dígitos
		CC = Código de dos dígitos correspondiente al último dígito del teléfono
		DD = Código de dos dígitos correspondiente a la primera letra del email
		G  = Tercer dígito del número correlativo de 4 dígitos
		EE = Código de dos dígitos correspondiente a la primera letra del nombre del proveedor
		G  = Cuarto dígito del número correlativo de 4 dígitos
		FF = Código de dos dígitos correspondiente a la primera letra de la moneda

	*/
		/*
		$card = "";
	    $card .= generacodigo(substr($nombres,0,1),$link);
    	$card .= substr($txtgiftcard,0,1);
	    $card .= generacodigo(substr($apellidos,0,1),$link);
    	$card .= substr($txtgiftcard,1,1);
	    $card .= generacodigo(substr($telefono,strlen($telefono)-1,1),$link);
    	$card .= generacodigo(substr($email,0,1),$link);
	    $card .= substr($txtgiftcard,2,1);
    	$card .= generacodigo(substr($nombreproveedor,0,1),$link);
	    $card .= substr($txtgiftcard,3,1);
    	$card .= generacodigo(substr($moneda,0,1),$link);
		*/
?>
