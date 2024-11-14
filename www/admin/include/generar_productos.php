<?php
require_once('../clases/class_admin.php');

    $action = isset($_GET["action"]) != '' ? $_GET['action'] : '';

    if (isset($_GET['cat'])) {
        $cat=filter_input(INPUT_GET,'cat', FILTER_SANITIZE_SPECIAL_CHARS);
    } else {
        $cat='';
    }


    $Obj=new Productos;

    switch($action) {
     case 'edit':
        $Obj->editar($_POST["name"],$_POST["value"],$_POST["pk"]);
     break;
     default:
     $Obj->lista($cat);
     return;
    }
?>