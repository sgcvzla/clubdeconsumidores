<?php 

$servidor = "localhost";
$cuenta = "root";
$password = "rootmyapm";
$database = "clubdeconsumidores";
$link = mysqli_connect($servidor, $cuenta, $password) or die ("Error al conectar al servidor.");
mysqli_select_db($link, $database) or die ("Error al conectar a la base de datos.");
echo "conectó";
date_default_timezone_set('America/Caracas');

?>
