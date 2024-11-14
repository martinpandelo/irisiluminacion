<?php
require_once("../class/cart.class.php");

$ObjCart = new Cart();

$action = (isset($_GET['action']) && $_GET['action'] != '');
$action = strip_tags($_GET['action'], ENT_QUOTES);

switch ($action) {
	case 'load' :
		$ObjCart->deleteAbandonedCart();
		$carrito = $ObjCart->getCartContent();
		require("cart-view.php");
		break;
	case 'add' :
		$prod=filter_input(INPUT_GET,'prod', FILTER_SANITIZE_NUMBER_INT);
		$cant=filter_input(INPUT_GET,'cant', FILTER_SANITIZE_NUMBER_INT);
		if (isset($_GET['variacion'])) {
			$variacion=filter_input(INPUT_GET,'variacion', FILTER_SANITIZE_SPECIAL_CHARS);
		} else {
			$variacion=0;
		}
		$prec=filter_input(INPUT_GET,'precio', FILTER_SANITIZE_NUMBER_INT);

		$ObjCart->addToCart($prod,$variacion,$cant,$prec);
		$carrito = $ObjCart->getCartContent();
		require("cart-view.php");
		
		$prodAddCart = $ObjCart->getProdAddCart($prec);
		require("../conversions/add-to-cart.php");
		break;

	case 'update' :
		$ObjCart->updateCart();
		$carrito = $ObjCart->getCartContent();
		require("cart-view.php");
		break;

	case 'count' :
		$cantCart = $ObjCart->getCountCart();
		echo $cantCart;
		break;

	case 'delete' :
		$ObjCart->deleteCart();
		$carrito = $ObjCart->getCartContent();
		require("cart-view.php");
		break;
}

?>
