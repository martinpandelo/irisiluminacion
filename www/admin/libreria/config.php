<?php 
session_start();

define('HTTP_SERVER', 'https://irisiluminacion.com.ar/'); //DIRECTORIO RAIZ DEL SITIO WEB 

$thisFile = str_replace('\\', '/', __FILE__);
$docRoot = $_SERVER['DOCUMENT_ROOT'];

$webRoot  = str_replace(array($docRoot, 'libreria/config.php'), '', $thisFile);
$srvRoot  = str_replace('libreria/config.php', '', $thisFile);

define('WEB_ROOT', $webRoot);
define('SRV_ROOT', $srvRoot);
?>