<?php
require '/home/fulmkodp/public_html/class/sincroml.class.php';
$Obj = new sincroML;

$datos = json_decode(file_get_contents('php://input'), true);
$mla = str_replace("/items/", "", $datos['resource']);
$fecha = date("Y-m-d H:i:s");

if ($Obj->CargarNotificaciones($datos['resource'],$mla,$fecha)) {
    http_response_code(200);
}

?>
