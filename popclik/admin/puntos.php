<?php 
header('Content-Type: application/json');
include_once("../../_config/conexion.php");

$query = 'SELECT * FROM puntosderecaudacion order by nombre';
if ($result = mysqli_query($link, $query)) {
    $respuesta = '{"exito":"SI", "puntos":[';
    $first = true;
    $coma = "";
    while ($row = mysqli_fetch_array($result)) {
        if ($first) {
            $first = false;
            $coma = "";
        } else {
            $coma = ",";
        }
        $respuesta .= $coma.'{';
        $respuesta .= '"id":'.$row['id'].',';
        $respuesta .= '"nombre":"' . utf8_encode($row["nombre"]) . '",';
        $respuesta .= '"lineadecredito":"' . utf8_encode($row["lineadecredito"]) . '",';
        $respuesta .= '"diasdecredito":"' . utf8_encode($row["diasdecredito"]) . '"';
        $respuesta .= '}';
    }
    $respuesta .= ']}';
} else {
    $respuesta = '{"exito":"NO", "puntos":[]}';
}
echo $respuesta;
?>
