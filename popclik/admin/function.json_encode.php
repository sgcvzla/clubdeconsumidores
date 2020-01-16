<?php
if(!function_exists('json_encode')) {
    include_once('./class.json.php4.php');

    $GLOBALS['JSON_OBJECT'] = new Services_JSON();
    
    function json_encode($value) { return $GLOBALS['JSON_OBJECT']->encode($value); }
        
    function json_decode($value) { return $GLOBALS['JSON_OBJECT']->decode($value); }
}
?>