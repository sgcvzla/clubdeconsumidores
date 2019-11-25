<?php 
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$quer0 = 'SELECT * FROM puntosderecaudacion where email="'.$_GET["email"].'"';
$resul0 = mysqli_query($link, $quer0);
if ($ro0 = mysqli_fetch_array($resul0)) {
    $respuesta = '{"exito":"NO",';
    $respuesta .= '"mensaje":"Correo ya registrado"}';
} else {
    $query = "INSERT INTO puntosderecaudacion (nombre, email, direccion, rif, usuario, telefono, pregunta, hashp, hashr) VALUES ('SGC Consultores C.A.','".$_GET["email"]."','San Blas','J-40242441-8','Luis Rodríguez','0424-407.1820','".$_GET["question"]."','".$_GET["hashp"]."','".$_GET["hashr"]."')";
    if($result = mysqli_query($link, $query)) {
        $respuesta = '{"exito":"SI",';
        $respuesta .= '"mensaje":"Registro exitoso"}';
    } else {
        $respuesta = '{"exito":"NO",';
        $respuesta .= '"mensaje":"Falló el registro."}';
    }
}
echo $respuesta;
?>
