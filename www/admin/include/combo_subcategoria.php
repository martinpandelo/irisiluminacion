<?php
require_once('../clases/class_admin.php');


$Obj=new Configuracion;

$categoria = $Obj->ComboSubCategoriasTable();

			$numItem=count($categoria);
            for ($i=0; $i<$numItem; $i++) {
                $datos[] = array(
                    'value'            => $categoria[$i]['sct_mla'],
                    'text'          => $categoria[$i]['sct_titulo']
                );
            }
            echo json_encode($datos);
?>