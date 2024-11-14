<?php 
require_once 'class/class.php';
require_once("class/cart.class.php");
$Obj = new mainClass;
$ObjCart = new Cart;

$id_prod=filter_input(INPUT_GET,'id', FILTER_SANITIZE_SPECIAL_CHARS);
if(!isset($id_prod)) header('Location: '.constant('URL'));

$prod = $Obj->getProducto($id_prod);
if (!$prod) header('Location: '.constant('URL').'productos.php');

$imagenes=$Obj->getImagenes($id_prod);
$relacionados=$Obj->getRelacionados($id_prod,$prod['pd_categoria'],$prod['pd_subcategoria']);
$variaciones = $Obj->getVariaciones($id_prod);
$descTransf = $Obj->descuentoTransferencia();

$cartContent = $ObjCart->getCartContent();


$cantProductos = 0;
$subtotal = 0;
$categoria_envio = $prod['pd_categoria_envio'];
$bultos = 0;

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
} 

$subtotal += $prod['precioFinalSinFormat'];
$bultos += 1;

require_once("conversions/view-content.php");
?>
<!DOCTYPE html>
<html lang="es-ES">

<head>
    <title>Iris Iluminación - <?php echo $prod['pd_titulo'] ?></title>
    <meta name="description" content="<?php echo $prod['ct_titulo'] ?> - <?php echo $prod['pd_titulo'] ?> de Iris iluminación" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSS -->
    <link rel="stylesheet" media="all" href="<?php echo constant('URL'); ?>css/custom.css" />
    <link rel="stylesheet" media="all" href="<?php echo constant('URL'); ?>css/flexslider.css" />
    <link rel="stylesheet" media="all" href="<?php echo constant('URL'); ?>css/flexslider_ficha.css" />

    <?php require_once("include/favicon.php") ?>
    <?php require_once("include/scripts-head.php"); ?>
</head>

<body class="page">
    <?php require_once("include/scripts-body.php") ?>
    <?php require_once("include/header.php"); ?>

    <section>
        <div class="container-fluid wrap-breadcrumb">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 align-self-center">

                    <div class="row align-items-center justify-content-center">
                        <div class="col-12">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo constant('URL'); ?>productos.php">PRODUCTOS</a></li>
                                    <li class="breadcrumb-item"><a href="<?php echo constant('URL'); ?>productos.php?cat=<?php echo $prod['ct_alias'] ?>"><?php echo $prod['ct_titulo'] ?></a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?php echo $prod['pd_titulo'] ?></li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="ficha">
        <div class="container-fluid">

            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 align-self-center">

                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-7 pe-lg-5">
                            <div id="slider" class="flexslider">
                                <ul class="slides">
                                    <?php if (is_array($imagenes)) { 
                                    foreach($imagenes as $im) { ?>
                                    <li>
                                        <div class="item-gallery">
                                            <div class="figcaption">
                                                <figure class="gallery-image">
                                                    <div class="gallery-img">
                                                        <a href="<?php echo $im['im_800x800'] ?>" data-fancybox="gallery">
                                                            <i class="bi bi-zoom-in"></i>
                                                            <img src="<?php echo $im['im_800x800'] ?>" alt="..." />
                                                        </a>
                                                    </div>
                                                </figure>
                                            </div>
                                        </div>
                                    </li>
                                    <?php } } ?>
                                </ul>
                            </div>
                            <div id="carousel" class="flexslider">
                                <ul class="slides">
                                    <?php if (is_array($imagenes)) { 
                                    foreach($imagenes as $im) { ?>
                                    <li><img src="<?php echo $im['im_400x400'] ?>" alt="..." /></li>
                                    <?php } } ?>
                                </ul>
                            </div>

                            <div class="d-none d-lg-block info-ficha">
                                <?php if (!empty($prod['pd_caracteristicas'])) { ?>
                                <div class="text-ficha pt-5">
                                    <hr>
                                    <h5><i class="bi bi-chat-square-text bi-lg text-primary me-2"></i> Características</h5>
                                    <hr>
                                    <p><?php echo $prod['pd_caracteristicas'] ?></p>
                                </div>
                                <?php } ?>
                                        
                                <div class="text-ficha py-4">
                                    <hr>
                                    <h5><i class="bi bi-chat-square-text bi-lg text-primary me-2"></i> Descripción del producto</h5>
                                    <hr>
                                    <?php
                                    if (!empty($prod['pd_descripcion'])) {
                                            $hash='* *';
                                            $first_step = explode($hash , $prod['pd_descripcion']);
                                            $textoDesc=trim($first_step[0]);
                                            echo '<p>'.nl2br(trim($textoDesc)).'</p>';
                                        }
                                    ?>
                                </div>

                                <hr>
                                <!--<h5><i class="bi bi-wechat bi-lg text-primary me-2"></i> Preguntas y respuestas</h5>-->
                                <!-- comments list -->
                                <!--<div class="commentsWrap">
                                </div>
                                <hr>-->
                                <!-- Respond -->
                                <!--<div class="wrap-form-comments" id="respond">

                                    <p><small>La respuesta tambíen te llegará a tu email, no te preocupes que no será publicado.</small></p>

                                    <div class="row">
                                        <div class="col">
                                            <label class="sr-only" for="pregauthor">Nombre</label>
                                            <input type="text" id="pregauthor" name="pregauthor" class="form-control" placeholder="Tu nombre">
                                        </div>
                                        <div class="col">
                                            <label class="sr-only" for="pregemail">Email</label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">@</div>
                                                </div>
                                                <input type="email" id="pregemail" name="pregemail" class="form-control" placeholder="Tu email">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="sr-only" for="pregcomment">Escribe una pregunta</label>
                                        <textarea id="pregcomment" name="pregcomment" class="form-control" placeholder="Escribe una pregunta" required></textarea>
                                    </div>
                                    <p class="mb-3">
                                        <span id="errorPreg" class="text-danger"></span>
                                        <button type="submit" name="submitpreg" id="submitpreg" class="btn btn-primary text-white float-right">Preguntar</button>
                                        <input type="hidden" id="comment_post_ID" name="comment_post_ID" value="<?php echo $id_prod ?>">
                                    </p>

                                </div>-->
                            </div>
                        </div>

                        <div class="col-12 col-lg-5 px-3 px-lg-5 pt-5 pb-3 pb-lg-5 bg-light">
                            <div class="info-ficha sticky-top">

                                <form name="datcart" id="datcart">
                                    <h1><?php echo $prod['pd_titulo'] ?></h1>
                                    <h2><?php echo $prod['ct_titulo'] ?></h2>

                                    <input type="hidden" name="prod" value="<?php echo $prod['pd_id'] ?>">
                                    <div class="py-3">
                                        <div class="act_prec"><input type="hidden" name="precio" id="precio" value="<?php echo $prod['pr_id'] ?>"></div>
                                        <div class="price-display">
                                            <?php if ($prod['precioFinal'] != $prod['precioOriginal']) { ?>
                                            <p class="mb-2"><del class="text-muted">$<?php echo $prod['precioOriginal'] ?></del> <span class="badge text-bg-primary text-white"><?php echo $prod['descuento'] ?>% OFF</span></p>
                                            <?php } ?>
                                            <h3 class="precio-ficha">$<?php echo $prod['precioFinal'] ?></h3>
                                        </div>
                                    </div>
                                    
                                    <?php if ($prod['cantCuotas'] > 0) { ?>
                                    <h6 class="d-inline-block text-success"><span class="fw-bold"><?php echo $prod['cantCuotas'] ?></span> cuotas sin interés de <span class="fw-bold valorcuota">$<?php echo $prod['valorCuota'] ?></span></h6>
                                    <?php } ?>

                                    <?php $descTransf = $Obj->descuentoTransferencia(); ?>
                                    <?php if ($descTransf) { ?>
                                        <h6 class="d-inline-block text-success"><span class="fw-bold"><?php echo $descTransf['porcentaje_descuento'] ?>% Descuento</span> pagando por Transferencia Bancaria</h6>
                                    <?php } ?>

                                    <?php if (isset($variaciones) && !empty($variaciones)) { ?>
                                        <ul class="list-unstyled variaciones my-4">
                                            <?php 
                                                $a=1;
                                                $checked = '';
                                                $cantVar = count($variaciones);
                                                foreach($variaciones as $var) { 
                                                
                                                        $arrVar = explode(",", $var["pr_variacion"]);
                                                        $arrLenghtVar = count($arrVar);
                                                        $totalVar = '';
                                                        for($i=0;$i<$arrLenghtVar;$i++)
                                                            {
                                                                $pos = strpos($arrVar[$i], ':');
                                                                $part1 = substr($arrVar[$i], 0, $pos);
                                                                $variacion = '<strong>'.$arrVar[$i].'</strong>';
                                                                $part2 = str_replace($part1, "", $variacion);
                                                                $totalVar .= $part1.$part2.'<br>';
                                                            }
                                                        if ($cantVar == 1) $checked = 'checked';                          
                                            ?>
                                                <li class="cardCheckOptions">
                                                    <div class="form-check form-check-inline py-2 pe-3 ps-5 my-2 border bg-white">
                                                        <input onclick="actualizarPrec(<?php echo $var['pr_id'] ?>);" type="radio" id="variacion<?php echo $a ?>" name="variacion" class="form-check-input" value="<?php echo $var['pr_variacion'] ?>" <?php echo $checked ?> >
                                                        <label class="form-check-label mb-2 d-flex align-items-center" for="variacion<?php echo $a ?>">
                                                            <div><img src="<?php echo $var['pr_foto'] ?>" class="ms-3 me-2" alt="<?php echo $var['pr_variacion'] ?>" width="46"></div>
                                                            <div><small><?php echo $totalVar ?></small></div>
                                                        </label>
                                                    </div>
                                                </li>
                                            <?php 
                                                $a++; 
                                                } ?>
                                        </ul>
                                    <?php } ?>

                                    <div class="form-inline">
                                        <label for="cant">Cantidad: </label>
                                        <div class="input-group spinner">
                                            <input type="text" name="cant" id="cant" class="form-control box_cant" value="1" min="1">
                                            <div class="input-group-btn-vertical">
                                                <button class="btn btn-default" type="button"><i class="bi bi-caret-up-fill"></i></button>
                                                <button class="btn btn-default" type="button"><i class="bi bi-caret-down-fill"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <small class="stock"><?php echo $prod['pr_stock'] ?> disponibles</small>

                                    <?php if (!empty($prod['pd_disponibilidad']) && $prod['pd_disponibilidad'] !== 'inmediata') { ?>
										<br><small class="fw-bold text-dark"> Disponible <?php echo $prod['pd_disponibilidad'] ?> hábiles después de tu compra</small>
                                    <?php } else { ?>
										<br><small class="fw-bold text-dark"> Disponibilidad inmediata</small>
                                    <?php } ?>

                                    <hr>
                                    
                                    <div class="sect-comp">
                                        <div aria-live="polite" aria-atomic="true" class="position-relative">
                                            <div class="toast-container p-3 top-50 start-50 translate-middle">
                                                <div id="errorToast" class="toast align-items-center text-bg-warning text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
                                                    <div class="d-flex">
                                                        <div class="toast-body"></div>
                                                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                                                    </div>
                                                </div> 
                                            </div> 
                                        </div> 
                                        <a onclick="addToCartCheckout(); ga4_add_to_cart();" class="btn btn-primary rounded-pill btn-lg text-white fw-bold w-100 my-2" id="addToCartButton">¡COMPRAR AHORA!</a>
                                        <a onclick="addToCart(); ga4_add_to_cart();" class="btn btn-primary rounded-pill text-white w-100 my-2" id="addToCartButton"><i class="bi bi-bag-plus"></i> Agregar al carrito</a>
                                    </div>
                                    <hr>

                                    <div id="envios">
                                        <?php if($categoria_envio == 'convenir') { ?>

                                        <h5><i data-feather="home" class="mr-2 text-secondary"></i> Envío a convenir</h5>
                                        <div class="form-check form-check-inline py-2 pe-3 ps-5 my-2 border bg-white">
                                            <label class="form-check-label label-shipping-method-item ps-3" for="envio40">
                                                <div class="shipping-method-item">
                                                    <span>
                                                        <h4 class="shipping-method-item-price"></h4>
                                                        <div class="shipping-method-item-name">Envío a convenir</div>
                                                        <div class="shipping-method-item-desc"><small>Luego de realizada la compra podes acordar el costo y la forma de envío</small></div>
                                                    </span>
                                                </div>
                                            </label>
                                        </div>

                                        <?php } else { ?>

                                        <h5><i class="bi bi-truck bi-lg text-primary me-2"></i> Envíos a domicilio</h5>
                                        <div class="input-group mb-3">
                                            <input onblur="loadEnvios()" type="number" class="form-control" name="envio_codpostal" id="envio_codpostal" placeholder="Tu código postal" aria-label="Tu código postal" aria-describedby="submitship" value="<?php if(isset($_SESSION['codPostal'])) echo $_SESSION['codPostal'] ?>">
                                            <input type="hidden" id="categoria_envio" name="categoria_envio" value="<?php echo $categoria_envio ?>" >
                                            <input type="hidden" id="cantproductos_envio" name="cantproductos_envio" value="<?php echo $bultos ?>" >
                                            <input type="hidden" id="total_envio" name="total_envio" value="<?php echo $subtotal ?>" >

                                            <div class="input-group-append">
                                                <button class="btn btn-primary text-white" type="button" name="submitship" id="submitship">Calcular</button>
                                            </div>
                                        </div>
                                        <span id="errorShip" class="text-danger"></span>
                                        <div id="result-envios" class="text-left"></div>
                                        <hr>

                                        <?php } ?>

                                        <h5><i class="bi bi-shop bi-lg text-primary me-2"></i> Retiro personal</h5>
                                        <div class="form-check form-check-inline py-2 pe-3 ps-5 my-2 border bg-white">
                                            <input type="radio" name="envio" id="envio30" data-id="30" class="form-check-input" value="S" required>
                                            <label class="form-check-label label-shipping-method-item ps-3" for="envio30">
                                                    <div class="shipping-method-item">
                                                        <span>
                                                            <h4 class="shipping-method-item-price">Gratis</h4>
                                                            <div class="shipping-method-item-name">Retiro en Showroom</div>
                                                            <div class="shipping-method-item-desc"><small>Blvr. Buenos Aires 1520, Luis Guillón</small></div>
                                                        </span>
                                                    </div>
                                                </label>
                                        </div>
                                    </div>

                                    <hr>
                                    <h5><i class="bi bi-credit-card bi-lg text-primary me-2"></i> Formas de pago</h5>
                                    <h6 class="my-3 fw-bold">- Tarjetas de crédito o débito y otros medios de pago con:</h6>
                                    <?php $cuotas = $Obj->getCuotas(); ?>
                                    <?php if ($cuotas && $cuotas['cuotas'] > 1) { ?>
                                        <p class="d-inline-block text-success mb-3 mr-3 "><span class="bg-success text-white px-2 fw-bold"><?php echo $cuotas['cuotas'] ?> cuotas sin interés</span> con tarjéta de crédito</p>
                                    <?php } ?>
                                    <img src="https://imgmp.mlstatic.com/org-img/banners/ar/medios/468X60.jpg" title="MercadoPago - Medios de pago" alt="MercadoPago - Medios de pago" width="100%" style="max-width: 468px" />

                                    <h6 class="my-3 fw-bold">- Mediante una transferencia bancaria</h6>
                                    <?php if ($descTransf) { ?>
                                        <p class="d-inline-block text-success mb-3 mr-3 "><span class="bg-success text-white px-2 fw-bold"><?php echo $descTransf['porcentaje_descuento'] ?>% Descuento</span> pagando por Transferencia Bancaria</p>
                                    <?php } ?>
                                
                                </form>
                                <div class="d-block d-lg-none info-ficha">
                                    <?php if (!empty($prod['pd_caracteristicas'])) { ?>
                                    <div class="text-ficha">
                                        <hr>
                                        <h5><i class="bi bi-chat-square-text bi-lg text-primary me-2"></i> Características</h5>
                                        <hr>
                                        <p><?php echo $prod['pd_caracteristicas'] ?></p>
                                    </div>
                                    <?php } ?>
                                            
                                    <div class="text-ficha py-4">
                                        <hr>
                                        <h5><i class="bi bi-chat-square-text bi-lg text-primary me-2"></i> Descripción del producto</h5>
                                        <hr>
                                        <?php
                                        if (!empty($prod['pd_descripcion'])) {
                                                $hash='* *';
                                                $first_step = explode($hash , $prod['pd_descripcion']);
                                                $textoDesc=trim($first_step[0]);
                                                echo '<p>'.nl2br(trim($textoDesc)).'</p>';
                                            }
                                        ?>
                                    </div>

                                </div>
                            </div>

                            
                        </div>

                            
                        
                    </div>
                </div>
            </div>
        </div>
    </section>


    <?php if (isset($relacionados) && !empty($relacionados)) { ?>
    <section id="productos" class="productos padding-section">
        <div class="container-xxl pb-5">
            <div class="row align-items-center justify-content-start">
                <div class="col text-center">
                    <h2 class="main-title px-5">Relacionados</h2>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 align-self-center">
                    <div class="row product-grid">

                    <?php foreach($relacionados as $prod) { ?>
                        <div class="col-6 col-lg-3 wrap-card">
                            <?php require("include/item-producto.php"); ?>
                        </div>
                    <?php } ?>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php } ?>


    <?php require_once("include/info.php"); ?>
    <?php require_once("include/contacto.php"); ?>
    <?php require_once("include/footer.php"); ?>

    <?php include_once("include/scripts-bottom.php") ?>
    <!-- FlexSlider -->
    <script defer src="<?php echo constant('URL'); ?>js/jquery.flexslider-min.js"></script>
    <script type="text/javascript">
        $(window).load(function() {
            // The slider being synced must be initialized first
            $('#carousel').flexslider({
                animation: "slide",
                controlNav: true,
                animationLoop: false,
                slideshow: false,
                directionNav: false,
                itemWidth: 100,
                itemMargin: 0,
                minItems: 2,
                maxItems: 6,
                asNavFor: '#slider'
            });

            $('#slider').flexslider({
                animation: "slide",
                controlNav: false,
                animationLoop: false,
                slideshow: false,
                sync: '#carousel'
            });
        });
        (function($) {
            $('.spinner .btn:first-of-type').on('click', function() {
                $('.spinner input').val(parseInt($('.spinner input').val(), 10) + 1);
            });
            $('.spinner .btn:last-of-type').on('click', function() {
                if ($('.spinner input').val() > 1) {
                    $('.spinner input').val(parseInt($('.spinner input').val(), 10) - 1);
                }
            });
        })(jQuery);
    </script>
    <script>
            function ga4_add_to_cart() {
                dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
                dataLayer.push({
                event: "add_to_cart",
                ecommerce: {
                    items: [{
                    item_name: "<?php echo $prod['pd_titulo'] ?>", // Name or ID is required.
                    item_id: "<?php echo $prod['pd_id'] ?>",
                    price: "<?php echo number_format($prod['pr_precio'],2,'.','') ?>",
                    quantity: $("input#cant").val()
                    }]
                }
                });
            }
        </script>

</body>

</html>