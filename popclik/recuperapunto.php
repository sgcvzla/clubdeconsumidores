<?php 
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$quer0 = 'SELECT * FROM puntosderecaudacion where email="'.$_GET["email"].'"';
$resul0 = mysqli_query($link, $quer0);
if ($ro0 = mysqli_fetch_array($resul0)) {
    $query = 'UPDATE puntosderecaudacion SET hashp="'.$_GET["hashp"].'" WHERE email="'.$_GET["email"].'"';
    if($result = mysqli_query($link, $query)) {
        $respuesta = '{"exito":"SI",';
        $respuesta .= '"mensaje":"Password restablecido exitosamente"}';
    }
} else {
    $respuesta = '{"exito":"NO",';
    $respuesta .= '"mensaje":"Correo no registrado"}';
}
echo $respuesta;
?>
