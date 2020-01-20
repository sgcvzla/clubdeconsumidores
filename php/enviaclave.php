<?php
header('Content-Type: application/json');
include_once("../_config/conexion.php");
include_once("../php/funciones.php");

$hash1 = hash("sha256",$_POST['idproveedor'].$_POST["anterior"]);
$hash2 = hash("sha256",$_POST['idproveedor'].$_POST["clave"]);

// Validar clave anterior
$query = "select clavecanje from proveedores where id=".$_POST['idproveedor'];
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
	$anterior = $row["clavecanje"];
	if ($anterior==$hash1) {
		$query = "update proveedores set clavecanje='".$hash2."' where id=".$_POST['idproveedor'];
		if ($result = mysqli_query($link, $query)) {
			$respuesta = '{"exito":"SI","mensaje":"Cambio exitoso."}';
		} else {
			$respuesta = '{"exito":"NO","mensaje":"Falló el cambio."}';
		}
	} else {
		$respuesta = '{"exito":"NO","mensaje":"Clave anterior errada."}';
	}
} else {
	$respuesta = '{"exito":"NO","mensaje":"Error en el proceso."}';
}

echo $respuesta;
?>
