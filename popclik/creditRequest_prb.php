<?php

if(isset($_FILES['doccedula']) && $_FILES['doccedula']['error'] === UPLOAD_ERR_OK) {
    // obtener detalles del archivo
    $fileTmpPath = $_FILES['doccedula']['tmp_name'];
    $fileName = $_FILES['doccedula']['name'];
    $fileSize = $_FILES['doccedula']['size'];
    $fileType = $_FILES['doccedula']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
    // // Limpiar espacios en blanco del nombre del archivo
    // $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
    // $newFileName = basename($fileName);
    $newFileName = '12345678_cedula.'.$fileExtension;

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
            echo substr(sprintf('%o', fileperms($dest_path)), -4);
            echo '<pre>';
            var_dump(stat($dest_path));
            echo '</pre>';
            echo $dest_path;
            // chmod($dest_path, 0755);
            echo " *** ".is_readable($dest_path)." *** ";
            echo " *** ".is_writeable($dest_path)." *** ";
            echo substr(sprintf('%o', fileperms($dest_path)), -4);
            $respuesta ='File is successfully uploaded.';
        } else {
            $respuesta = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
        }
        // print_r($_FILES);
    } else {
        $respuesta = 'error2';
    }
} else {
    $respuesta = 'error';
}

// if (form_mail("soluciones2000@gmail.com", "Asunto de prueba", "Cuerpo de prueba", "info@popclik.com")) {
//     echo "Su formulario ha sido enviado con exito";
// }

$bHayFicheros = 0;
$sCabeceraTexto = "";
$sAdjuntos = "";

$sCabeceras = "From:info@popclik.com\n";
$sCabeceras .= "MIME-version: 1.0\n";

foreach ($_FILES as $vAdjunto) {
    if($bHayFicheros == 0) {
        $bHayFicheros = 1;
        $sCabeceras .= "Content-type: multipart/mixed;";
        $sCabeceras .= "boundary=\"--_Separador-de-mensajes_--\"\n";

        $sCabeceraTexto = "----_Separador-de-mensajes_--\n";
        $sCabeceraTexto .= "Content-type: text/plain;charset=iso-8859-1\n";
        $sCabeceraTexto .= "Content-transfer-encoding: 7BIT\n";

        $sTexto = $sCabeceraTexto.$sTexto;
    }

    // $fileTmpPath = $_FILES['doccedula']['tmp_name'];
    // $fileName = $_FILES['doccedula']['name'];
    // $fileSize = $_FILES['doccedula']['size'];
    // $fileType = $_FILES['doccedula']['type'];
    // $fileNameCmps = explode(".", $fileName);
    // $fileExtension = strtolower(end($fileNameCmps));


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

if ($bHayFicheros) $sTexto .= $sAdjuntos."\n\n----_Separador-de-mensajes_----\n";

mail("soluciones2000@gmail.com", "Asunto de prueba", $sTexto, $sCabeceras);




/*
$bHayFicheros = 0;
$sCabeceraTexto = "";
$sAdjuntos = "";

if($sDe) $sCabeceras = "From:".$sDe."\n";
else $sCabeceras = "";

$sCabeceras .= "MIME-version: 1.0\n";

foreach ($_FILES as $vAdjunto) {
    if($bHayFicheros == 0) {
        $bHayFicheros = 1;
        $sCabeceras .= "Content-type: multipart/mixed;";
        $sCabeceras .= "boundary=\"--_Separador-de-mensajes_--\"\n";

        $sCabeceraTexto = "----_Separador-de-mensajes_--\n";
        $sCabeceraTexto .= "Content-type: text/plain;charset=iso-8859-1\n";
        $sCabeceraTexto .= "Content-transfer-encoding: 7BIT\n";

        $sTexto = $sCabeceraTexto.$sTexto;
    }

    if($vAdjunto["size"] > 0) {
        $sAdjuntos .= "\n\n----_Separador-de-mensajes_--\n";
        $sAdjuntos .= "Content-type: ".$vAdjunto["type"].";name=\"".$vAdjunto["name"]."\"\n";
        $sAdjuntos .= "Content-Transfer-Encoding: BASE64\n";
        $sAdjuntos .= "Content-disposition: attachment;filename=\"".$vAdjunto["name"]."\"\n\n";

        $oFichero = fopen($vAdjunto["tmp_name"], 'r');
        $sContenido = fread($oFichero, filesize($vAdjunto["tmp_name"]));
        $sAdjuntos .= chunk_split(base64_encode($sContenido));
        fclose($oFichero);
    }
}

if ($bHayFicheros) $sTexto .= $sAdjuntos."\n\n----_Separador-de-mensajes_----\n";

mail("soluciones2000@gmail.com", "Asunto de prueba", $sTexto, $sCabeceras);
*/
echo $respuesta
?>
