<?php
require_once '../class/class.php';
require_once '../class/cart.class.php';
require_once '../class/checkout.class.php';
$Obj = new mainClass;
$ObjCheckout = new Checkout;


if (isset($_SESSION['orderId'])) {

    $order_reference = $_SESSION['orderId'];
    $sIDprev=session_id();
    $status=20;
    
    if ($ObjCheckout->CheckOrder($order_reference)) {


        $orderAmount = $ObjCheckout->getOrderAmount($order_reference);
        $orderContent = $ObjCheckout->GetOrderContent($order_reference);
        $numItem = count($orderContent);
        $orderInfo = $ObjCheckout->GetOrderInfo($order_reference);

        $cantProductos=0;
        $prodApiConv='';
        $idsApiConv='';

        for ($i=0; $i<$numItem; $i++) {
            extract($orderContent[$i]);
            $cantProductos += $cantidad;
            $prodApiConv .= "{'id': '".$sku."','quantity': ".$cantidad.",'item_price': ".number_format($precio,2,'.','')."},";
            $idsApiConv .= "'".$sku."',";
        }
        $prodApiConv = substr($prodApiConv, 0, -1);
        $idsApiConv = substr($idsApiConv, 0, -1);
        require "../conversions/initiate-checkout.php";

        $ObjCheckout->removeItemsCart();
        $ObjCheckout->statusOrder($order_reference,$status);
        
        session_unset();
        session_destroy();
        unset($_SESSION['orderId']);
        session_write_close();
        setcookie(session_name(),'',0,'/');
        
    }
    
}

?>
