<?php
include_once("../_config/conexion.php");

$hash1 = (isset($_GET['hash'])) ? $_GET['hash'] : '' ;
$urlback = (isset($_GET['urlback'])) ? $_GET['urlback'] : 'https://www.clubdeconsumidores.com.ve' ;

$query = "select * from datos_giftcards where hash='".$hash1."'";
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
	$remitente = $row["remitente"];
	$nombres = $row["nombres"];
	$apellidos = $row["apellidos"];
	$telefono = $row["telefono"];
	$email = $row["email"];
	$monto = $row["monto"];
	$idproveedor = $row["id_proveedor"];
	$moneda = $row["moneda"];
}

$query = "select * from proveedores where id=".$idproveedor;
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
    $nombreproveedor = $row["nombre"];
}

// Busca el próximo número de giftcard
$query = "select auto_increment from information_schema.tables where table_schema='clubdeconsumidores' and table_name='giftcards'";
$result = mysqli_query($link,$query);
if($row = mysqli_fetch_array($result)) {
    $numgiftcard = $row["auto_increment"];
} else {
    $numgiftcard = 0;
}

if ($numgiftcard > 9999) { $numgiftcard -= 9999; }

if ($numgiftcard < 10) {
    $txtgiftcard = "000".trim($numgiftcard);
} elseif ($numgiftcard < 100) {
    $txtgiftcard = "00".trim($numgiftcard);
} elseif ($numgiftcard < 1000) {
    $txtgiftcard = "0".trim($numgiftcard);
} else {
    $txtgiftcard = trim($numgiftcard);
}

$card = "";
$card .= generacodigo(substr($nombres,0,1),$link);
$card .= generacodigo(substr($apellidos,0,1),$link);
$card .= generacodigo(substr($telefono,strlen($telefono)-1,1),$link);
$card .= generacodigo(substr($email,0,1),$link);
$card .= generacodigo(substr($nombreproveedor,0,1),$link);
$card .= generacodigo(substr($moneda,0,1),$link);
$card .= substr($txtgiftcard,0,1);
$card .= substr($txtgiftcard,1,1);
$card .= substr($txtgiftcard,2,1);
$card .= substr($txtgiftcard,3,1);

$fecha = date('Y-m-d');
$status = 'Lista para usar';
$hash = hash("sha256",$card.$idproveedor.$nombres.$apellidos.$telefono.$email.$monto.$moneda.$status);

$query = "INSERT INTO giftcards (card, remitente, nombres, apellidos, telefono, email, saldo, moneda, fechacompra, status, id_proveedor, hash) VALUES ('".$card."','".$remitente."','".$nombres."','".$apellidos."','".$telefono."','".$email."',".$monto.",'".$moneda."','".$fecha."','".$status."',".$idproveedor.",'".$hash."')";
if ($result = mysqli_query($link,$query)) {
	$quer2 = "DELETE FROM datos_giftcards WHERE hash='".$hash1."'";
	if ($result = mysqli_query($link,$quer2)) {
	    $respuesta = '{"exito":"SI","card":"'.$card.'"}';
	} else {
	    $respuesta = '{"exito":"NO","card":""}';
	}
} else {
    $respuesta = '{"exito":"NO","card":""}';
}

echo '
<script>
	parent.opener.location.assign("'.$urlback.'");
	window.close();
</script>
';

function generacodigo($letra,$link) {
    $query = "select codigo from _codigo where valor='".$letra."'";
	echo $query;
    $result = mysqli_query($link, $query);
    if ($row = mysqli_fetch_array($result)) {
        $codigo = $row["codigo"];
    } else {
        $query = "select codigo from _codigo where valor='?'";
		echo $query;
        $result = mysqli_query($link, $query);
        $row = mysqli_fetch_array($result);
        $codigo = $row["codigo"];
    }
    return $codigo;
}
?>
