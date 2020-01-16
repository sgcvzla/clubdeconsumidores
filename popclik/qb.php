<?php 
$severName = "localhost";
$connectionInfo = array("Database"=>"prueba_usuarios","UID"=>"prueba23","CharacterSet"=>"UTF-8");
$con = sqlsrv_connect($serverName,$connectInfo);

if ($con) {
	echo "Conexión Exitosa";
} else {
	echo "Falló la conexión";
}

?>