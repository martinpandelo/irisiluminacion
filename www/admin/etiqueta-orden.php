<?php
ob_start();
require_once("../class/checkout.class.php");
include_once("clases/class_admin.php");


if (isset($_GET['id_orden']) && (int)$_GET['id_orden'] > 0) {
    $id_orden=filter_input(INPUT_GET,'id_orden', FILTER_SANITIZE_NUMBER_INT);
} else {
    header('Location: index.php');
}
$ObjCheckout = new Checkout();
$orderContent = $ObjCheckout->GetOrderContent($id_orden);
$orderInfo = $ObjCheckout->GetOrderInfo($id_orden);

?>
<!DOCTYPE html>
<html lang="es-AR">
	
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Iris Iluminación</title>
        
        <!-- CSS -->
        <link rel="stylesheet" media="all" href="assets/css/bootstrap.css" /> 
        <link rel="stylesheet" href="assets/css/ficha-pdf.css">

        <!-- Theme Styles -->

</head>
<body>


            <div class="row">
                <?php for ($x=0; $x < 2; $x++) { ?>

                <div class="col-xs-6 p-0">

                    <div class="wrap-etiqueta">

                        <div class="header-etiqueta">
                            <img src="assets/images/logo-iris.png" width="30px">
                            <div class="header">
                                <h1>Orden #<?php echo $id_orden ?></h1>
                                <p><small><?php echo date("d M Y", strtotime($orderInfo["fecha_alta"])) ?></small></p>
                            </div>
                        </div>

                                
                                                                        <table class="table">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th><span>Productos</span></th>
                                                                                    <th><span>Cantidad</span></th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>

                                                                            <?php
    
                                                                            $numItem=count($orderContent); 
                                                                            $cant_prod=0;
                                                                                for ($i=0; $i<$numItem; $i++) {
                                                                                    extract($orderContent[$i]);
                                                                                    $cant_prod += $cantidad;
                                                                                ?>
                                                                            
                                                                                        <tr>
                                                                                            <td>
                                                                                                <div class="dat_comp">
                                                                                                    <?php echo $pd_titulo ?>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td><?php echo $cantidad ?></td>
                                                                                        </tr>
                                                                            
                                                                            <?php } ?>

                                                                            </tbody>
                                                                        </table>
                                        
                                        
                                        <div class="etiqueta-info">
                                            <div class="server-load">
                                                <div class="server-stat">
                                                    <span>Pago</span>
                                                    <?php 
                                                    
                                                    switch ($orderInfo["or_medio_pago"]) {
                                                        case 'mp':
                                                            echo '<p>MERCADO PAGO</p>';
                                                            break;
                                                        case 'tp':
                                                            echo '<p>TODO PAGO</p>';
                                                            break;
                                                        case 'transferencia':
                                                            echo '<p>TRANSFERENCIA BANCARIA</p>';
                                                            break;
                                                    } ?>
                                                </div>
                                                <div class="server-stat">
                                                    <span>ID DE PAGO</span>
                                                    <p>#<?php echo $orderInfo["pago_id"]; ?></p>
                                                </div>
                                                <div class="server-stat">
                                                    <span>ESTADO</span>
                                                    <p><?php echo $orderInfo["pago_status"]; ?></p>
                                                </div>
                                            </div>
                                        </div>


                                <?php if ($orderInfo["env_tipo"]=='D') { ?>

                                        
                                        <div class="etiqueta-info">
                                            <div class="server-load">
                                                <div class="server-stat">
                                                    <span>Correo</span>
                                                    <p><?php echo $orderInfo["env_nom_correo"]; ?></p>
                                                    <p><?php echo $orderInfo["env_descripcion"]; ?></p>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="etiqueta-info">
                                            <div class="server-load">
                                                <div class="server-stat">
                                                    <span>Costo de envío</span>
                                                    <p class="text-danger">$<?php echo number_format($orderInfo["env_valor"],2,',','.'); ?></p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="etiqueta-info">
                                            <h4 class="panel-title">Enviar a:</h4>
                                            <p><span><?php echo $orderInfo["env_nombre"].' '.$orderInfo["env_apellido"]; ?></span><br>
                                                <?php echo $orderInfo['env_calle'].' '.$orderInfo['env_numero'];
                                                    if (!empty($orderInfo['env_piso'])) {
                                                        echo ' '.$orderInfo['env_piso'];
                                                    }
                                                    if (!empty($orderInfo['env_depto'])) {
                                                        echo ' '.$orderInfo['env_depto'];
                                                    }          
                                                    echo ', CP '.$orderInfo['env_codpostal']; ?><br>
                                                    <?php echo $orderInfo['env_localidad'].', '.$orderInfo['env_provincia']; ?><br>
                                                    Teléfono <span><?php echo $orderInfo["or_telefono"]; ?></span></p>
                                        </div>
                                        
                                <?php } elseif ($orderInfo["env_tipo"]=='S') {?>
                                        
                                        <div class="etiqueta-info">
                                            <div class="server-load">
                                                <div class="server-stat">
                                                    <span>Retiro personal</span>
                                                    <p>Retiro en sucursal</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="etiqueta-info">
                                            <h4 class="panel-title">Retira:</h4>
                                            <p><span><?php echo $orderInfo["env_nombre"].' '.$orderInfo["env_apellido"]; ?></span><br>
                                                <?php echo $orderInfo['env_calle'].' '.$orderInfo['env_numero'];
                                                    if (!empty($orderInfo['env_piso'])) {
                                                        echo ' '.$orderInfo['env_piso'];
                                                    }
                                                    if (!empty($orderInfo['env_depto'])) {
                                                        echo ' '.$orderInfo['env_depto'];
                                                    }          
                                                    echo ', CP '.$orderInfo['env_codpostal']; ?><br>
                                                    <?php echo $orderInfo['env_localidad'].', '.$orderInfo['env_provincia']; ?><br>
                                                    Teléfono <span><?php echo $orderInfo["or_telefono"]; ?></span></p>
                                        </div>

                                <?php } ?>
                                        
                                        <div class="etiqueta-info">
                                            <h4 class="panel-title">Remitente:</h4>
                                            <p><span>Iris Iluminación</span><br>
                                            Blvr. Buenos Aires 1520, Luis Guillón, Buenos Aires<br>
                                            CP 1838</p>
                                        </div>

                    </div>
                </div>
                <?php 
                            if ($x==1) {
                                echo '<div class="row">';
                                echo '</div>';
                            }
                            ?>

                <?php } ?>
            </div>


</body>
</html>          
            
<?php

use Dompdf\Dompdf;
require 'vendor/autoload.php';
$dompdf = new DOMPDF();
$dompdf->loadHtml(ob_get_clean());
$dompdf->setPaper("A4", "portrait");
$dompdf->render();
$filename = "Orden #".$id_orden.".pdf";
$dompdf->stream($filename, array("Attachment" => false));


?>