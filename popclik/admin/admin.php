<?php 
header('Content-Type: application/json');
include_once("../../_config/conexion.php");

$query = 'SELECT * FROM admin where email="'.$_GET["email"].'"';
$result = mysqli_query($link, $query);
$id = 0;
if ($row = mysqli_fetch_array($result)) {
    $id = $row['id'];
    $respuesta = '{"exito":"SI",';
    $respuesta .= '"id":'.$id.',';
    $respuesta .= '"hashp":"'. $row["hashp"] .'",';
    $respuesta .= '"pregunta":"' . utf8_encode($row["pregunta"]) . '",';
    $respuesta .= '"hashr":"' . $row["hashr"] . '",';
    $respuesta .= '"nombrepunto":"' . utf8_encode($row["nombre"]) . '",';
    $respuesta .= '"operador":"' . utf8_encode($row["usuario"]) . '",';
    $respuesta .= '"mensaje":"exito"}';
} else {
    $respuesta = '{"exito":"NO",';
    $respuesta .= '"id":'.$id.',';
    $respuesta .= '"hashp":"",';
    $respuesta .= '"pregunta":"",';
    $respuesta .= '"hashr":"",';
    $respuesta .= '"nombrepunto":"",';
    $respuesta .= '"operador":"",';
    $respuesta .= '"mensaje":"correo no registrado"}';
}
echo $respuesta;
?>
