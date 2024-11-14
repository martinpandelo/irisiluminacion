<?php
require_once("../class/class.php");
require_once("../class/cart.class.php");
require_once("../class/checkout.class.php");

$Obj = new mainClass;
$ObjCheckout = new Checkout;

$collection_id = filter_input(INPUT_GET, 'collection_id', FILTER_SANITIZE_SPECIAL_CHARS);
$collection_status = 'pending';
$order_reference = filter_input(INPUT_GET, 'external_reference', FILTER_SANITIZE_SPECIAL_CHARS);
$payment_type = filter_input(INPUT_GET, 'payment_type', FILTER_SANITIZE_SPECIAL_CHARS);

if (!isset($collection_id) || !isset($collection_status) || !isset($order_reference) || !isset($payment_type)) {
    header('Location: '.constant('URL') );
    exit();
}

if (!$ObjCheckout->CheckOrderConfirmada($order_reference)) { 
    header('Location: '.constant('URL') );
    exit();
}
	
?>
    <!DOCTYPE html>
    <html lang="es-ES">

    <head>
        <meta charset="utf-8" />
        <title>Iris Iluminación - Pago pendiente</title>
        <meta name="description" content="Su compra esta pendiente" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <link rel="stylesheet" media="all" href="<?php echo constant('URL'); ?>css/custom.css" />   
        <?php require_once("../include/favicon.php") ?>
        <?php require_once("../include/scripts-head.php"); ?>
    </head>

    <body class="page">
        <?php require_once("../include/scripts-body.php") ?>
        <?php require_once("../include/header.php"); ?>

            <section id="checkout">
                <div class="text-center">
                    <h1 class="main-title">Pago pendiente</h1>
                </div>
            	<div class="container py-5">
                	<div class="row">
                    	<div class="col-12">
                            <h4>Tu pago está pendiente</h4>
                            <hr>
                            <p>Cuando tu pago este aprobado se te enviará un email a tu casilla con los detalles de la compra.<br />
                            Si de todos modos tienes alguna consulta, puedes comunicarte a los teléfonos o mails informados en este sítio web.<br /><br />
                            <strong>Muchas Gracias!!!</strong></p> 
                        </div>	
                    </div>
            	</div>
            </section>
			
            <?php require_once("../include/footer.php"); ?>
            <?php require_once("../include/scripts-bottom.php") ?>
    </body>
    </html>