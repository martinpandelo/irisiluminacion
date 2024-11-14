<?php
include_once("../class/class.php");

$Obj = new mainClass();
$prod=filter_input(INPUT_GET,'idprec', FILTER_SANITIZE_NUMBER_INT);
		
$datosVar = $Obj->getDatosVariacion($prod);

			$datos[] = array(
				'precio'          	=> $datosVar['pr_precio'],
				'id'     	    => $datosVar['pr_id'],
				'codigo'     	    => $datosVar['pr_codigo'],
				'stock'     	    => $datosVar['pr_stock'],
				'preciofinal'     	    => $datosVar['precioFinal'],
				'precioorig'     	    => $datosVar['precioOriginal'],
				'descuento'     	    => $datosVar['descuento'],
				'cuota'     	    => $datosVar['valorCuota']
			);
			echo json_encode($datos);


?>
