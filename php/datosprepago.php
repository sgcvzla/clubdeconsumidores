<?php
include_once("../_config/conexion.php");

$nombres = (isset($_POST['nombres'])) ? $_POST['nombres'] : "" ;
$apellidos = (isset($_POST['apellidos'])) ? $_POST['apellidos'] : "" ;
$telefono = (isset($_POST['telefono'])) ? $_POST['telefono'] : "" ;
$email = (isset($_POST['email'])) ? $_POST['email'] : "" ;
$monto = (isset($_POST['monto'])) ? $_POST['monto'] : 0 ;
$idproveedor = (isset($_POST['idproveedor'])) ? $_POST['idproveedor'] : 0 ;
$moneda = (isset($_POST['moneda'])) ? $_POST['moneda'] : "bs" ;
$hash = hash("sha256",$retmiente.$nombres.$apellidos.$telefono.$email.$monto.$idproveedor.$moneda);

$query = "select * from datos_giftcards where hash='".$hash."'";
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
    $respuesta = '{"exito":"NO","hash":"Esa tarjeta se está procesando."}';
} else {
	$query = "INSERT INTO datos_giftcards (remitente, nombres, apellidos, telefono, email, monto, id_proveedor, moneda, hash) VALUES ('".$remitente."','".$nombres."','".$apellidos."','".$telefono."','".$email."',".$monto.",".$idproveedor.",'".$moneda."','".$hash."')";
	if ($result = mysqli_query($link,$query)) {
	    $respuesta = '{"exito":"SI","hash":"'.$hash.'"}';	
	}
}
echo $respuesta;
?>
