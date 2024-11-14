<?php
// SDK de Mercado Pago
require __DIR__ .  '/vendor/autoload.php';

// TEST
//MercadoPago\SDK::setAccessToken('APP_USR-7708904245408069-101413-070a1a0b8f791c5a29a2d14fa8fec444-658774491');
// PRODUCCION
MercadoPago\SDK::setAccessToken('APP_USR-4732735103752906-061309-f721f3c6ccb914f0d4e634c91273ae23-63400367');

// Crea un objeto de preferencia
$preference = new MercadoPago\Preference();
			
$numItem=count($orderContent);
$itemsProd = array();

for ($i=0; $i<$numItem; $i++) {
                
    extract($orderContent[$i]);

    // Crea un ítem en la preferencia
    $item = new MercadoPago\Item();
    $item->id=$pd_id;
    $item->title=$pd_titulo;
    $item->currency_id="ARS";
    $item->picture_url=$pd_thumbnail;
    $item->description="Iris Iluminación";
    $item->category_id="home";
    $item->quantity=intval($cantidad);
    $item->unit_price=(float) $precio;

    $itemsProd[]=$item;
}

if ($env_valor!=0.00) {

    $item = new MercadoPago\Item();
    $item->id='envio';
    $item->title='Costo de envío';
    $item->currency_id="ARS";
    $item->description="Costo de envío";
    $item->category_id="others";
    $item->quantity=intval(1);
    $item->unit_price=(float) $env_valor;

    $itemsProd[]=$item;
}


$preference->items = $itemsProd;


$payer = new MercadoPago\Payer();
$payer->name = strval($or_nombre);
$payer->surname = strval($or_apellido);
$payer->email = strval($or_email);
$payer->phone = array(
    "area_code"=> "",
    "number"=> $or_telefono
);
$payer->identification = array(
    "type"=> "DNI",
    "number"=> $or_dni
);
$payer->address = array(
    "street_name"=> $or_calle,
    "street_number"=> $or_calle_num,
    "zip_code"=> $or_codpostal
);
$preference->payer = $payer;


$preference->notification_url = constant('URL')."checkout/mp/receive-ipn.php";
$preference->back_urls = array(
    "success" => constant('URL')."checkout/success.php",
    "failure" => constant('URL')."checkout/failure.php",
    "pending" => constant('URL')."checkout/pending.php"
);
$preference->auto_return = "approved";
$preference->external_reference = strval($orderId);

$preference->save();

?>

<hr>
<div class="action_cart my-4">
    <a onclick="actualizar('<?php echo $preference->init_point; ?>');" class="btn btn-primary text-white btn-lg">IR A PAGAR</a>
</div>