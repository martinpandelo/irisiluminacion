<?php
require 'Meli/meli.php';
require 'configApp.php';
require '/home/fulmkodp/public_html/class/sincroml.class.php';

$Obj = new sincroML;

$datosmeli = $Obj->DatosMeli();
$token = $datosmeli['ml_token'];
$refreshToken = $datosmeli['ml_refresh_token'];

if (!empty($token) and !empty($refreshToken)) {

    $meli = new Meli($appId,$secretKey,$token,$refreshToken);
    $refresh_tokn = $meli->refreshAccessToken();

    if ($refresh_tokn['body']->status==400) {
        echo '<pre>Error 1</pre>';
    } else {
        $new_token = $refresh_tokn['body']->access_token;
        $new_refresh_token = $refresh_tokn['body']->refresh_token;

        if (!empty($new_token) and !empty($new_refresh_token)) {
            if ($Obj->ActualizaToken($new_token,$new_refresh_token)) {
                echo '<pre>Ok</pre>';
            } else {
                echo '<pre>Error 3</pre>';
            }
        } else {
            echo '<pre>Error 2</pre>';
        }
    }
}
?>
