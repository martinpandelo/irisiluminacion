<?php
require_once("../class/class.php");
require_once("../class/cart.class.php");
require_once("../class/checkout.class.php");

$Obj = new mainClass;
$ObjCheckout = new Checkout;

$collection_id = filter_input(INPUT_GET, 'collection_id', FILTER_SANITIZE_SPECIAL_CHARS);
$collection_status = 'cancelled';
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

switch ($collection_status) {
    case 'pending':
        $statusOrden=20;
        break;
    case 'approved':
        $statusOrden=30;
        break;
    case 'in_process':
        $statusOrden=20;
        break;
    case 'in_mediation':
        $statusOrden=20;
        break;
    case 'rejected':
        $statusOrden=90;
        break;
    case 'cancelled':
        $statusOrden=80;
        break;
    case 'refunded':
        $statusOrden=20;
        break;
    case 'charged_back':
        $statusOrden=20;
        break;
}
$total_pagado=0;

$ObjCheckout->ActualizarOrder($order_reference,$collection_id,$collection_status,$payment_type,$total_pagado,$statusOrden);
	
?>
    <!DOCTYPE html>
    <html lang="es-ES">

    <head>
        <meta charset="utf-8" />
        <title>Iris Iluminación - Pedido cancelado</title>
        <meta name="description" content="Su compra fue cancelada" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <link rel="stylesheet" media="all" href="<?php echo constant('URL'); ?>css/custom.css" />    
        <?php require_once("../include/favicon.php") ?>
        <?php require_once("../include/scripts-head.php"); ?>
    </head>

    <body class="page">
        <?php require_once("../include/scripts-body.php") ?>
        <?php require_once("../include/header.php"); ?>

        <section id="checkout">
            <div class="container py-5">
                <div class="row">
                    <div class="col-12 pb-5">
                        <h2 class="main_title">Pedido cancelado</h2>
                    </div>
                    <div class="col-12">
                        <h4>Tu pedido está cancelado</h4>
                        <hr>
                        <p>Te invitamos a realizar un nuevo pedido en cualquier momento.<br /><br />
                        <strong>Muchas Gracias!!!</strong></p> 
                    </div>	
                </div>
            </div>
        </section>

        <?php require_once("../include/footer.php"); ?>
        <?php require_once("../include/scripts-bottom.php") ?>
    </body>
    </html>