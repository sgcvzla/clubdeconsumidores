<?php
function mensajes($archivojson,$texto){
    $parametros = json_decode(file_get_contents($archivojson),true);
    $mensaje = '[';
    for ($i = 0; $i < count($parametros["mensajes"][$texto]); $i++) {
        $mensaje .= '"' . $parametros["mensajes"][$texto][$i] . '"';
        if (count($parametros["mensajes"][$texto]) > 1 && $i + 1 < count($parametros["mensajes"][$texto])) {
            $mensaje .= ',';
        }
    }
    $mensaje .= ']';
    return $mensaje;
}

function asignacodigo($ultcupon){
    $valores = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $a = strlen($valores)-1;
    $base = 36;
    $codigo = '';
    $arriba = 1;
    $newcodigo = '';
    $numero = $ultcupon;
    // echo $numero.'<br>';
    for ($i=strlen($ultcupon)-1 ; $i>=0 ; $i--) { 
        $pos = strpos($valores, substr($numero,$i,1));
        if ($arriba==1) {
            if ($pos==$a) {
                $codigo = substr($valores,0,1);
            } else {
                $codigo = substr($valores,$pos+1,1);
                $arriba = 0;
            }
        } else {
            $codigo = substr($numero,$i,1);
        }
        $newcodigo = $codigo.$newcodigo;
    }
    // switch (strlen($newcodigo)) {
    //  case '1':
    //      $newcodigo = '0000'.$newcodigo;
    //      break;
    //  case '2':
    //      $newcodigo = '000'.$newcodigo;
    //      break;
    //  case '3':
    //      $newcodigo = '00'.$newcodigo;
    //      break;
    //  case '4':
    //      $newcodigo = '0'.$newcodigo;
    //      break;
    // }
    for ($i=0 ; $i< strlen($newcodigo); $i++) { 
        // echo substr($newcodigo,$i,1).'<br>';
    }

    return $newcodigo;
}

function asignacodigolargo($ultcupon){
    $newcodigo = $ultcupon;

    $cuponlargo = substr($newcodigo,0,2);
    $cuponlargo .= codigocaracter(strtoupper(substr($_POST["email"],-1)));
    $cuponlargo .= substr($newcodigo,2,2);
    $cuponlargo .= codigocaracter(strtoupper(substr($_POST["nombres"],-1)));
    $cuponlargo .= substr($newcodigo,4,2);
    $cuponlargo .= codigocaracter(strtoupper(substr($_POST["apellidos"],-1)));
    $cuponlargo .= substr($newcodigo,6,2);
    $cuponlargo .= codigocaracter(strtoupper(substr($_POST["telefono"],-1)));
    $cuponlargo .= substr($newcodigo,8,2);

    return $cuponlargo;
}

function asignacodigolargo2($ultcupon,$email,$nombres,$apellidos,$telefono){
    $newcodigo = $ultcupon;

    $cuponlargo = substr($newcodigo,0,2);
    $cuponlargo .= codigocaracter(strtoupper(substr($email,-1)));
    $cuponlargo .= substr($newcodigo,2,2);
    $cuponlargo .= codigocaracter(strtoupper(substr($nombres,-1)));
    $cuponlargo .= substr($newcodigo,4,2);
    $cuponlargo .= codigocaracter(strtoupper(substr($apellidos,-1)));
    $cuponlargo .= substr($newcodigo,6,2);
    $cuponlargo .= codigocaracter(strtoupper(substr($telefono,-1)));
    $cuponlargo .= substr($newcodigo,8,2);

    return $cuponlargo;
}

function codigocaracter($valor) {
    $llaves = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    $codigos =  '111213141A1B1C1D212223242A2B2C2D3132';
    $codigos .= '33343A3B3C3D414243444A4B4C4DA1A2A3A4';

    $posicion = strpos($llaves, $valor);
    $pos2 = $posicion*2;
    $newvalor = substr($codigos,$pos2,2);

    return $newvalor;
}

function generagiftcard($nombres,$apellidos,$telefono,$email,$nombreproveedor,$moneda,$link) {
    // Busca el próximo número correlativo (único)
    $query = "select auto_increment from information_schema.tables where table_schema='clubdeconsumidores' and table_name='giftcards'";
    $result = mysqli_query($link,$query);
    if($row = mysqli_fetch_array($result)) {
        $numgiftcard = $row["auto_increment"];
    } else {
        $numgiftcard = 0;
    }

    // Si el número del correlativo supera los cuatro dígitos se trunca a cuatro
    if ($numgiftcard > 9999) { $numgiftcard -= intval($numgiftcard/10000)*10000; }

    // Rellena con ceros los caracteres faltantes hasta 4
    if ($numgiftcard < 10) {
        $txtgiftcard = "000".trim($numgiftcard);
    } elseif ($numgiftcard < 100) {
        $txtgiftcard = "00".trim($numgiftcard);
    } elseif ($numgiftcard < 1000) {
        $txtgiftcard = "0".trim($numgiftcard);
    } else {
        $txtgiftcard = trim($numgiftcard);
    }
    /*
        El número de la tarjeta está compuesto por 10 caracteres en el orden que sigue:

        AABBCCDDEEFFGGGG -> AABB CCDD EEFF GGGG

        AA   = Código de dos dígitos correspondiente a la primera letra del nombre
        BB   = Código de dos dígitos correspondiente a la primera letra del apellido
        CC   = Código de dos dígitos correspondiente al último dígito del teléfono
        DD   = Código de dos dígitos correspondiente a la primera letra del email
        EE   = Código de dos dígitos correspondiente a la primera letra del nombre del proveedor
        FF   = Código de dos dígitos correspondiente a la primera letra de la moneda
        GGGG = Número correlativo de 4 dígitos
    */
    $card = "";
    $card .= generacodigo(substr($nombres,0,1),$link);
    $card .= generacodigo(substr($apellidos,0,1),$link);
    $card .= generacodigo(substr($telefono,strlen($telefono)-1,1),$link);
    $card .= generacodigo(substr($email,0,1),$link);
    $card .= generacodigo(substr($nombreproveedor,0,1),$link);
    $card .= generacodigo(substr($moneda,0,1),$link);
    $card .= substr($txtgiftcard,0,1);
    $card .= substr($txtgiftcard,1,1);
    $card .= substr($txtgiftcard,2,1);
    $card .= substr($txtgiftcard,3,1);

    return $card;
}

function generaprepago($nombres,$apellidos,$telefono,$email,$nombreproveedor,$moneda,$link) {
    // Busca el próximo número correlativo (único)
    $query = "select auto_increment from information_schema.tables where table_schema='clubdeconsumidores' and table_name='prepago'";
    $result = mysqli_query($link,$query);
    if($row = mysqli_fetch_array($result)) {
        $numgiftcard = $row["auto_increment"];
    } else {
        $numgiftcard = 0;
    }

    // Si el número del correlativo supera los cuatro dígitos se trunca a cuatro
    if ($numgiftcard > 9999) { $numgiftcard -= intval($numgiftcard/10000)*10000; }

    // Rellena con ceros los caracteres faltantes hasta 4
    if ($numgiftcard < 10) {
        $txtgiftcard = "000".trim($numgiftcard);
    } elseif ($numgiftcard < 100) {
        $txtgiftcard = "00".trim($numgiftcard);
    } elseif ($numgiftcard < 1000) {
        $txtgiftcard = "0".trim($numgiftcard);
    } else {
        $txtgiftcard = trim($numgiftcard);
    }
    /*
        El número de la tarjeta está compuesto por 10 caracteres en el orden que sigue:

        AAGBBGCCDDGEEGFF -> AAGB BGCC DDGE EGFF

        AA = Código de dos dígitos correspondiente a la primera letra del nombre
        G  = Primer dígito del número correlativo de 4 dígitos
        BB = Código de dos dígitos correspondiente a la primera letra del apellido
        G  = Segundo dígito del número correlativo de 4 dígitos
        CC = Código de dos dígitos correspondiente al último dígito del teléfono
        DD = Código de dos dígitos correspondiente a la primera letra del email
        G  = Tercer dígito del número correlativo de 4 dígitos
        EE = Código de dos dígitos correspondiente a la primera letra del nombre del proveedor
        G  = Cuarto dígito del número correlativo de 4 dígitos
        FF = Código de dos dígitos correspondiente a la primera letra de la moneda
    */
    $card = "";
    $card .= generacodigo(substr($nombres,0,1),$link);
    $card .= substr($txtgiftcard,0,1);
    $card .= generacodigo(substr($apellidos,0,1),$link);
    $card .= substr($txtgiftcard,1,1);
    $card .= generacodigo(substr($telefono,strlen($telefono)-1,1),$link);
    $card .= generacodigo(substr($email,0,1),$link);
    $card .= substr($txtgiftcard,2,1);
    $card .= generacodigo(substr($nombreproveedor,0,1),$link);
    $card .= substr($txtgiftcard,3,1);
    $card .= generacodigo(substr($moneda,0,1),$link);

    return $card;
}

function generacodigo($letra,$link) {
    $query = "select codigo from _codigo where valor='".$letra."'";
    $result = mysqli_query($link, $query);
    if ($row = mysqli_fetch_array($result)) {
        $codigo = $row["codigo"];
    } else {
        $query = "select codigo from _codigo where valor='?'";
        $result = mysqli_query($link, $query);
        $row = mysqli_fetch_array($result);
        $codigo = $row["codigo"];
    }
    return $codigo;
}

// Generar el próximo número de transacción en el pdv
function generatransaccion_pdv($link) {
    // Busca el próximo número correlativo (único)
    $query = "select auto_increment from information_schema.tables ";
    $query .= "where table_schema='clubdeconsumidores' and table_name='pdv_transacciones'";
    $result = mysqli_query($link,$query);
    if($row = mysqli_fetch_array($result)) {
            $numero = $row["auto_increment"];
    } else {
            $numero = 0;
    }
    return $numero;
}

?>