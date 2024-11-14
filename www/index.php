<?php 
require_once 'class/class.php';
$Obj = new mainClass();
$slides = $Obj->getSlides();
$categoriasHome = $Obj->getCategoriasHome();
$destacados = $Obj->getDestacados();
$novedades = $Obj->getNovedades();

?>
<!DOCTYPE html>
<html lang="es-ES">

<head>
    <title>Iris Iluminación</title>
    <meta name="description" content="" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSS -->
    <link rel="stylesheet" media="all" href="<?php echo constant('URL'); ?>css/custom.css" />

    <?php require_once("include/favicon.php") ?>
    <?php require_once("include/scripts-head.php"); ?>
</head>

<body>
    <?php require_once("include/scripts-body.php") ?>
    <?php require_once("include/header.php"); ?>

    <?php if ($slides) { ?>  
    <div id="homeCarousel" class="carousel slide carouselHome caption-animate" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <?php foreach($slides as $sld) { ?>
                <button type="button" data-bs-target=".homeCarousel" data-bs-slide-to="<?php echo $sld['imgid'] ?>" <?php echo ($sld['imgid']==0) ? 'class="active"' : '' ; ?> ></button>
            <?php } ?>
        </div>
        <div class="carousel-inner">

            <?php foreach($slides as $sld) { ?>
                <div class="carousel-item <?php echo $sld['active'] ?>">
                    <picture>
                        <source srcset="<?php echo constant('URL'); ?>img/slide/<?php echo $sld['imagen_desktop'] ?>" media="(min-width: 600px)">
                        <img src="<?php echo constant('URL'); ?>img/slide/<?php echo $sld['imagen_mobile'] ?>" class="bd-placeholder-img" width="100%" height="100%">
                    </picture>
                </div>
            <?php } ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
        </button>
    </div>
    <?php } ?>

    <?php if (isset($novedades) && !empty($novedades)) { ?>
    <section id="productos" class="productos pt-5">
        <div class="container-fluid py-5">
            <div class="row text-center">
                <div class="col">
                    <h2 class="main-title px-5">Novedades</h2>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 align-self-center">
                    <div class="row product-grid">
                        <div id="car-novedades" class="owl-carousel owl-theme">

                        <?php foreach($novedades as $prod) { ?>
                            <div class="wrap-card">
                                <?php require("include/item-producto.php"); ?>
                            </div>
                        <?php } ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php } ?>

    <?php if (isset($destacados) && !empty($destacados)) { ?>
    <section id="productos" class="productos padding-section-bottom">
        <div class="container-fluid py-5">
            <div class="row text-center">
                <div class="col">
                    <h2 class="main-title px-5">Los preferidos <i class="bi bi-heart"></i></h2>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 align-self-center">
                    <div class="row product-grid">

                    <?php foreach($destacados as $prod) { ?>
                        <div class="col-6 col-lg-3 wrap-card">
                            <?php require("include/item-producto.php"); ?>
                        </div>
                    <?php } ?>

                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid px-5">
            <div class="row text-center">
                <div class="col">
                    <a href="<?php echo constant('URL') ?>productos.php" class="btn btn-primary rounded-pill btn-custom btn-lg text-white my-5">Ver todos los productos</a>
                </div>
            </div>
        </div>
    </section>
    <?php } ?>

    <?php if (isset($categoriasHome) && !empty($categoriasHome)) { ?>
    <section id="categorias">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 align-self-center">
                    <div class="row">

                    <?php foreach($categoriasHome as $cat) { ?>
                        <div class="col-6 col-lg-3">
                            <div class="grid">
                                <figure class="effect-honey">
                                    <img src="img/categorias/<?php echo $cat['ct_id'] ?>-<?php echo $cat['ct_alias'] ?>.jpg" alt="<?php echo $cat['ct_titulo'] ?>" />
                                    <figcaption>
                                        <h2><?php echo $cat['ct_titulo'] ?></h2>
                                        <a href="productos/<?php echo $cat['ct_alias'] ?>">Ver categoría <?php echo $cat['ct_titulo'] ?></a>
                                    </figcaption>
                                </figure>
                            </div>
                        </div>
                    <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php } ?>

    <?php require_once("include/info.php"); ?>


    <section id="nosotros" class="padding-section-bottom pt-5">
        <div class="container-fluid py-5 px-lg-5">
            <div class="text-center">
                <h2 class="main-title px-5">Nosotros</h2>
            </div>
        </div>
        <div class="container-xxl">
            <div class="row align-items-center justify-content-center">
                <div class="col-12 col-lg-6 px-5">
                    <img src="<?php echo constant('URL'); ?>/img/iris-iluminacion_1.jpg" class="pt-4" width="100%">
                </div>
                <div class="col-12 col-lg-6 px-5">
                    <p class="lead">Desde 1955, iluminando hogares y acompañando a cada cliente en la creación de espacios únicos y acogedores. Con 70 años de trayectoria, nos enorgullece ofrecerte una atención personalizada, pensada para ayudarte a encontrar la iluminación ideal para cada rincón de tu hogar.</p>
                </div>
            </div>
        </div>
        <div class="container-xxl py-4">
            <div class="row align-items-center justify-content-center">
                <div class="col-12 col-lg-6 px-5">
                    <p class="lead">En Iris Iluminación, combinamos experiencia, calidad y estilo en cada producto, y también te ofrecemos la comodidad de realizar tus compras online de manera fácil y segura. Transformá tus ambientes con luz y diseño, de la mano de quienes saben iluminar tus momentos. </p>
                </div>
                <div class="col-12 col-lg-6 px-5">
                    <img src="<?php echo constant('URL'); ?>/img/iris-iluminacion_2.jpg" class="pb-4" width="100%">
                </div>

            </div>
        </div>
        <div class="container-xxl">
            <div class="row align-items-center justify-content-center">
                <div class="col-12 col-lg-6 px-5">
                    <img src="<?php echo constant('URL'); ?>/img/iris-iluminacion_3.jpg" class="pt-4" width="100%">
                </div>
                <div class="col-12 col-lg-6 px-5">
                    <p class="lead">Nuestra selección incluye desde lámparas colgantes y apliques decorativos, hasta soluciones de iluminación LED para interior y exterior, siempre con la más alta calidad y diseño. Además, contamos con un amplio stock para que encuentres justo lo que buscás cuando lo necesitás. </p>
                </div>
            </div>
        </div>
    </section>

    <?php require_once("include/contacto.php"); ?>
    <?php require_once("include/footer.php"); ?>

    <?php include_once("include/scripts-bottom.php") ?>

</body>

</html>