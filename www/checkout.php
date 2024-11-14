<?php
require_once("class/class.php");
require_once("class/cart.class.php");
require_once("class/checkout.class.php");

$Obj = new mainClass;
$ObjCart = new Cart;
$ObjCheckout = new Checkout;

$descTransf = $Obj->descuentoTransferencia();
$combProvincias = $ObjCheckout->comb_provincias();
$cartContent = $ObjCart->getCartContent();


$cantProductos=0;
$subtotal=0;
$categoria_envio='normal';
$bultos=0;
$productosGA4='';
$prodApiConv='';
$idsApiConv='';

$cartItem=count($cartContent);
for ($i=0; $i<$cartItem; $i++) {
    extract($cartContent[$i]);

    $subtotal += $totalItemSinFormat;
    $cantProductos += $cantidad;
    if ($pd_categoria_envio == 'especial') {
        $categoria_envio = 'especial';
    } else if ($pd_categoria_envio == 'convenir') {
        $categoria_envio = 'convenir';
    }
    $bultos += $pd_bulto_envio * $cantidad;

    $productosGA4 .= "{'item_name': '".$pd_titulo."','item_id': '".$pr_sku."','price': ".number_format($precioFinalSinFormat,2,'.','').",'quantity': ".$cantidad."},";
    $prodApiConv .= "{'id': '".$pr_sku."','quantity': ".$cantidad.",'item_price': ".number_format($precioFinalSinFormat,2,'.','')."},";
    $idsApiConv .= "'".$pr_sku."',";
} 
$productosGA4 = substr($productosGA4, 0, -1);
$prodApiConv = substr($prodApiConv, 0, -1);
$idsApiConv = substr($idsApiConv, 0, -1);

if ($bultos==0) {
    $bultos=1;
}


$faceTrack = "";

if ($ObjCart->isCartEmpty()) {
    header('Location: '.constant('URL'));
} else if (isset($_GET['step']) && (int)$_GET['step'] > 0 && (int)$_GET['step'] <= 2) {
    $step = (int)$_GET['step'];
    $includeFile = '';

    switch ($step) {
        case 1:
            $includeFile = 'shippingAndPaymentInfo.php';
            break;
        case 2:
            if (!empty($_POST)) {

                $orderId = $ObjCheckout->saveOrder();

                    if ($orderId) {
                        $orderAmount = $ObjCheckout->getOrderAmount($orderId);
                        $orderContent = $ObjCheckout->GetOrderContent($orderId);
                        $orderInfo = $ObjCheckout->GetOrderInfo($orderId);
                        extract($orderInfo);
                        
                        $_SESSION['orderId'] = $orderId;
                        
                        switch ($_POST['opcion_pago']) {
                            case 'mp':
                                $includeFilePayment = 'mp/payment.php';
                                break;
                            case 'transferencia':
                                $includeFilePayment = 'transferencia.php';
                                break;
                        }
                        $includeFile = 'checkoutConfirmation.php';
                        require "conversions/add_payment_info.php";
                    } else {
                        extract($_POST);
                        $includeFile = 'shippingAndPaymentInfo.php';
                    }

            } else {
                header('Location: '.constant('URL').'checkout.php?step=1');
            }
            break;
    }

} else { 
    header('Location: '.constant('URL'));
}
?>
    <!DOCTYPE html>
    <html lang="es-ES">

    <head>
        <title>Iris Iluminación</title>
        <meta name="description" content="" />
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" media="all" href="<?php echo constant('URL'); ?>css/custom.css" />   
        <?php require_once("include/favicon.php") ?>

        <?php if ($step==1) { ?>
            <script>
                dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
                dataLayer.push({
                    event: "begin_checkout",
                    ecommerce: {
                        currency: "ARS",
                        value: <?php echo number_format($total,2,',','.');  ?>,
                        items: [<?php echo $productosGA4 ?>]
                    }
                });
            </script>
        <?php } else if ($step==2) { ?>
            <script>
                dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
                dataLayer.push({
                event: "add_payment_info",
                ecommerce: {
                    currency: "ARS",
                    value: <?php echo number_format($orderAmount,2,',','.');  ?>,
                    items: [<?php echo $productosGA4 ?>]
                }
                });
            </script>
        <?php } ?>

        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-T3JZ5NCM');</script>
        <!-- End Google Tag Manager -->

        <!-- Meta Pixel Code -->
        <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '3603298286624898');
        fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id=3603298286624898&ev=PageView&noscript=1"
        /></noscript>
        <!-- End Meta Pixel Code -->
    </head>

    <body class="page">
        <?php require_once("include/scripts-body.php") ?>
        <?php require_once("include/header.php"); ?>

        <?php require_once "checkout/$includeFile"; ?>

        <?php require_once("include/footer.php"); ?>
        <?php require_once("include/scripts-bottom.php") ?>

        <script src="<?php echo constant('URL'); ?>js/jquery.validate.js"></script>
        <script src="<?php echo constant('URL'); ?>js/localization/messages_es_AR.js"></script>
        <script src="<?php echo constant('URL'); ?>js/shippingAndPaymentInfo.js"></script>
        <script src="<?php echo constant('URL'); ?>js/checkout.js"></script>
    </body>
    </html>