<?php
require_once('../clases/class_admin.php');


$Obj=new Configuracion;

$categoria = $Obj->ComboCategoriasTable();

			$numItem=count($categoria);
            for ($i=0; $i<$numItem; $i++) {
                $datos[] = array(
                    'value'            => $categoria[$i]['ct_mla'],
                    'text'          => $categoria[$i]['ct_titulo']
                );
            }
            echo json_encode($datos);
?>