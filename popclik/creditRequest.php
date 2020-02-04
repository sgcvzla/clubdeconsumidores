<?php
if(isset($_FILES)) {
    $bHayFicheros = 0;
    $sCabeceraTexto = "";
    $sAdjuntos = "";

    $sCabeceras = "From:credito@popclik.com\n";
    $sCabeceras .= "MIME-version: 1.0\n";

    foreach ($_FILES as $Campo => $vAdjunto) {
        if($vAdjunto['error'] === UPLOAD_ERR_OK) {
            // obtener detalles del archivo
            $fileTmpPath = $vAdjunto['tmp_name'];
            $fileName = $vAdjunto['name'];
            $fileSize = $vAdjunto['size'];
            $fileType = $vAdjunto['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $newFileName = $_POST["cedula"].'_'.$Campo.'.'.$fileExtension;

            // // Sólo acepta los archivos con las siguientes extensiones
            // $allowedfileExtensions = array('jpg', 'gif', 'png', 'zip', 'txt', 'xls', 'doc');
            // if(in_array($fileExtension, $allowedfileExtensions)) {
            //     // ...
            // }
            // // Directorio al cual será copiado el archivo
            $uploadFileDir = 'creditSupport/';
            $dest_path = $uploadFileDir . trim($newFileName);

            if (is_uploaded_file($fileTmpPath)) {
                if(move_uploaded_file($fileTmpPath, $dest_path)) {
                    $respuesta ='File is successfully uploaded.';
                } else {
                    $respuesta = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
                }
            } else {
                $respuesta = 'error2';
            }
        }
        if($bHayFicheros == 0) {
            $bHayFicheros = 1;
            $sCabeceras .= "Content-type: multipart/mixed;";
            $sCabeceras .= "boundary=\"--_Separador-de-mensajes_--\"\n";

            $sCabeceraTexto = "----_Separador-de-mensajes_--\n";
            $sCabeceraTexto .= "Content-type: text/html;charset=iso-8859-1\n";
            $sCabeceraTexto .= "Content-transfer-encoding: 7BIT\n";

            $sTexto = $sCabeceraTexto.$sTexto;
        }
        if($fileSize > 0) {
            $sAdjuntos .= "\n\n----_Separador-de-mensajes_--\n";
            $sAdjuntos .= "Content-type: ".$fileType.";name=\"".$newFileName."\"\n";
            $sAdjuntos .= "Content-Transfer-Encoding: BASE64\n";
            $sAdjuntos .= "Content-disposition: attachment;filename=\"".$dest_path."\"\n\n";

            $oFichero = fopen($dest_path, 'r');
            $sContenido = fread($oFichero, filesize($dest_path));
            $sAdjuntos .= chunk_split(base64_encode($sContenido));
            fclose($oFichero);
        }
    }

    $asunto = "Solicitud de crédito de ";
    $asunto .= trim($_POST["nombres"]).' '.trim($_POST["apellidos"]);

    $sTexto .= "Buen día<br/>";
    $sTexto .= "Hemos recibido una solicitud de crédito por <b>".$_POST["credito"]."</b> USD ";
    $sTexto .= "para la compra del siguiente producto:<br/>";
    $sTexto .= "<b>".trim($_POST["producto"])."</b><br/><br/>";
    $sTexto .= "<u>Los datos de la solicitud son:</u><br/>";

    $sTexto .= "Nombres: <b>".trim($_POST["nombres"])."</b><br/>";
    $sTexto .= "Apellidos: <b>".trim($_POST["apellidos"])."</b><br/>";
    $sTexto .= "Número de cédula: <b>".trim($_POST["cedula"])."</b><br/>";
    $sTexto .= "Dirección: <b>".trim($_POST["direccion"])."</b><br/>";
    $sTexto .= "Teléfono: <b>".trim($_POST["telefono"])."</b><br/>";
    $sTexto .= "Correo Electrónico: <b>".trim($_POST["email"])."</b><br/>";
    $sTexto .= "Ingresos mensuales: <b>".trim($_POST["ingresos"])."</b><br/>";
    $sTexto .= "Referencia comercial 1: <b>".trim($_POST["refcom1"]). "</b> - teléfono: <b>".trim($_POST["telrefcom1"])."</b><br/>";
    $sTexto .= "Referencia comercial 2: <b>".trim($_POST["refcom2"]). "</b> - teléfono: <b>".trim($_POST["telrefcom2"])."</b><br/>";
    $sTexto .= "Referencia personal 1: <b>".trim($_POST["refper1"]). "</b> - teléfono: <b>".trim($_POST["telrefper1"])."</b><br/>";
    $sTexto .= "Referencia personal 2: <b>".trim($_POST["refper2"]). "</b> - teléfono: <b>".trim($_POST["telrefper2"])."</b><br/><br/>";

    $sTexto .= "Se debe responder a esta solicitud a la brevedad posible<br/><br/>";

    $sTexto .= "Muchas gracias por tu gestión!<br/>";

    $asunto = utf8_decode($asunto);
    $sTexto = utf8_decode($sTexto);

    if ($bHayFicheros) $sTexto .= $sAdjuntos."\n\n----_Separador-de-mensajes_----\n";

    mail("soluciones2000@gmail.com", $asunto, $sTexto, $sCabeceras);
} else {
    $respuesta = 'error files';
}

echo '
    <script>
        window.open("./CreditRequestSent.html", "_self");
    </script>
    ';

?>
