<?php 
/*
* ESTE PROCESO SE ENCARGA DE LIMPIAR LAS NOTIFICACIONES DE PUBLICACIONES MERCADOLIBRE 
* QUE SE MANTIENEN EN ESTADO "pendiente" DURANTE MAS DE 2 HORAS. Si el estado es "finalizado" lo
* elimina luego de 5 dias. 
* ESTE PROCESO SE EJECUTA CADA 5 MINUTOS.
* @package     Limpieza Notificaciones  
*/

error_reporting(E_ALL);
ini_set('display_errors', 1);


$time_limit_process = 60*120; // 2 horas
$time_limit_old = 60*60*24*5; // 5 dias


require '/home/fulmkodp/public_html/class/sincroml.class.php';
$ObjSinc = new SincroML;

print '<pre>';

$params = array(
    'time_limit' => $time_limit_process,
    'status_estado' => 'pendiente',

    
);
var_dump($params);
echo 'elimina notificaciones "pendientes" que superen el tiempo de espera '. ceil($time_limit_process/60/60)  .' horas <br/><br/>';




///procesa limpieza de notificaciones "colgadas"
$ObjSinc->eliminarNotificaciones($params);



$params = array(
    'time_limit' => $time_limit_old,
    'status_estado' => 'procesada',

    
);



var_dump($params);
echo 'elimina notificaciones "procesada" que superen el tiempo de espera '. ceil($time_limit_old/60/60/24)  .' dias ';

////elimina las notificaciones YA procesadas viejas
$ObjSinc->eliminarNotificaciones($params);

print '</pre>';








