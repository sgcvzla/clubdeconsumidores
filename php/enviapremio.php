<?php
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$query = "SELECT * from premios where id_proveedor=".$_POST["idproveedor"]." and clasepremio='".$_POST["clasepremio"]."'";
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
  $query = 'UPDATE premios SET tipopremio="'.$_POST["tipopremio"].'", montopremio='.$_POST["cantidad"].', descpremio="'.$_POST["descripcion"].'", diasvalidez='.$_POST["validez"].', activo='.$_POST["activo"].' WHERE id_proveedor='.$_POST["idproveedor"].' and clasepremio="'.$_POST["clasepremio"].'"';
} else {
  $query = 'INSERT INTO premios (id_proveedor, clasepremio, tipopremio, montopremio, descpremio, diasvalidez, activo) VALUES ('.$_POST["idproveedor"].', "'.$_POST["clasepremio"].'", "'.$_POST["tipopremio"].'", '.$_POST["cantidad"].', "'.$_POST["descripcion"].'", '.$_POST["validez"].','.$_POST["activo"].')';
}
if ($result = mysqli_query($link, $query)) {
  $respuesta = '{"exito":"SI",';
  $respuesta .= '"mensaje":"Registro exitoso."';
  $respuesta .= '}';
} else {
  $respuesta = '{"exito":"NO",';
  $respuesta .= '"mensaje":"Falló el registro."';
  $respuesta .= '}';
}
echo $respuesta;
?>
