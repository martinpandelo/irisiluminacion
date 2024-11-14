<?php
require_once '../class/class.php';

$Obj = new Envio();
$resultEnvio = $Obj->calcularEnvio();
require("envio-view.php");

?>          