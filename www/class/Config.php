<?php
session_start();

// define('URL', 'http://localhost:8888/iris/www/');

// define('DB_USERNAME', 'root');
// define('DB_PASSWORD', 'root');
// define('DB_HOST', 'localhost');
// define('DB_NAME', 'bd_irisilum');
// define('CHARSET', 'utf8mb4');

define('URL', 'https://irisiluminacion.com.ar/');

define('DB_USERNAME', 'fulmkodp_webmaster');
define('DB_PASSWORD', 'h9[P}e_Cd2cq');
define('DB_HOST', 'localhost');
define('DB_NAME', 'fulmkodp_iristienda');
define('CHARSET', 'utf8mb4');

$thisFile = str_replace('\\', '/', __FILE__);
$docRoot = $_SERVER['DOCUMENT_ROOT'];

$webRoot  = str_replace(array($docRoot, 'class/Config.php'), '', $thisFile);
$srvRoot  = str_replace('class/Config.php', '', $thisFile);

define('WEB_ROOT', $webRoot);
define('SRV_ROOT', $srvRoot);

?>