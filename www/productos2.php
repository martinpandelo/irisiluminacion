<?php 
require_once 'class/class.php';
$Obj = new mainClass();

$catActiva=filter_input(INPUT_GET,'cat', FILTER_SANITIZE_SPECIAL_CHARS);
if(isset($catActiva)) $datCat=$Obj->getCategoriaActiva($catActiva);

$subcatActiva=filter_input(INPUT_GET,'subcat', FILTER_SANITIZE_SPECIAL_CHARS);
if(isset($subcatActiva)) $datCat=$Obj->getSubCategoriaActiva($subcatActiva);

if(isset($_REQUEST['cybersale']))
    $cybersale=filter_input(INPUT_GET,'cybersale', FILTER_SANITIZE_SPECIAL_CHARS);


$urlProductos = explode('?buscar=', $_SERVER["REQUEST_URI"]);

// if (isset($urlProductos[1])) {
//     $resultBusqueda = $Obj->buscarProductos($urlProductos[1]);
// }

$categorias = $Obj->getCategorias();
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

<body class="page">
    <?php require_once("include/scripts-body.php") ?>
    <?php require_once("include/header.php"); ?>

    <section>
        <div class="container-fluid wrap-breadcrumb">
            <div class="row align-items-center justify-content-center">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                                <?php if (isset($_GET['buscar'])) { ?>
                                        <li class="breadcrumb-item">RESULTADOS PARA SU BUSQUEDA</li>
                                        <li class="breadcrumb-item active"><?php echo $_GET['buscar'] ?></li>
                                <?php } else { ?>
                                    <?php if (isset($datCat)) { ?>
                                        <li class="breadcrumb-item"><a href="<?php echo constant('URL') ?>productos/">PRODUCTOS</a></li>
                                        <li class="breadcrumb-item active" aria-current="page"><?php echo $datCat['ct_titulo'] ?></li>
                                    <?php } else { ?>
                                        <li class="breadcrumb-item"><a href="<?php echo constant('URL') ?>productos/">TODOS LOS PRODUCTOS</a></li>
                                    <?php } ?>
                                <?php } ?> 
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <section id="productos" class="padding-section-bottom">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-lg-3 pe-lg-5">

                <?php if (isset($categorias) && !empty($categorias)) { ?>
                    <div class="bg-light p-2 p-lg-4 filters sticky-top">
                        <h4 class="d-none d-lg-block fw-bold text-primary">Categorías</h4>
                        <div class="nav navbar-expand-lg justify-content-center">
                            <button class="navbar-toggler py-3 bg-primary fw-bold text-white w-100" type="button" data-bs-toggle="collapse" data-bs-target="#navbarFilt" aria-expanded="false" aria-controls="navbarFilt">
                                <i class="bi bi-filter-left bi-lg"></i> Categorías
                            </button>

                            <div class="collapse navbar-collapse pt-4" id="navbarFilt">
                                <ul class="list-unstyled list-filter">
                                    <?php 
                                    if (is_array($categorias)) { 
                                        $i=1;
                                        foreach($categorias as $cat) { 
                                            $active = '';
                                            if (isset($catActiva) && $cat['ct_alias'] == $catActiva ) {
                                                $active = 'active';
                                            }
                                    ?>
                                    <li><a href="<?php echo constant('URL') ?>productos/<?php echo $cat['ct_alias'] ?>" class="<?php echo $active ?>"><span class="Form-label-text"><?php echo $cat['ct_titulo'] ?></span></a></li>
                                    <?php $i++; } } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php } ?>


                </div>
                <div class="col-12 col-lg-9 pt-4 pt-lg-0">
                    <div class="d-flex justify-content-center justify-content-lg-end">
                        <div class="form-orden btn-group mb-3">
                            <button class="btn btn-orden dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Ordenar por: <span class="chevron"></span></button>
                            <div class="dropdown-menu">
                                            <li class="dropdown-item"><label>
                                                <input onclick="load(1);" type="radio" name="ord" value="nuevo" class="d-none" >
                                                <span class="Form-label-text">New arrivals</span>
                                            </label></li>
                                            <li class="dropdown-item"><label>
                                                <input onclick="load(1);" type="radio" name="ord" value="destacado" class="d-none">
                                                <span class="Form-label-text">Destacados</span>
                                            </label></li>
                                            <li class="dropdown-item"><label>
                                                <input onclick="load(1);" type="radio" name="ord" value="novedad" class="d-none" >
                                                <span class="Form-label-text">Novedades</span>
                                            </label></li>
                                            <li class="dropdown-item"><label>
                                                <input onclick="load(1);" type="radio" name="ord" value="oferta" class="d-none" >
                                                <span class="Form-label-text">Ofertas</span>
                                            </label></li>
                                            <li class="dropdown-item"><label>
                                                <input onclick="load(1);" type="radio" name="ord" value="alpha-ascending" class="d-none" >
                                                <span class="Form-label-text">A - Z</span>
                                            </label></li>
                                            <li class="dropdown-item"><label>
                                                <input onclick="load(1);" type="radio" name="ord" value="alpha-descending" class="d-none" >
                                                <span class="Form-label-text">Z - A</span>
                                            </label></li>
                                            <li class="dropdown-item"><label>
                                                <input onclick="load(1);" type="radio" name="ord" value="price-ascending" class="d-none" >
                                                <span class="Form-label-text">Menor precio</span>
                                            </label></li>
                                            <li class="dropdown-item"><label>
                                                <input onclick="load(1);" type="radio" name="ord" value="price-descending" class="d-none" >
                                                <span class="Form-label-text">Mayor precio</span>
                                            </label></li>
                                            <li class="dropdown-item"><label>
                                                <input onclick="load(1);" type="radio" name="ord" value="predeterminado" class="d-none" >
                                                <span class="Form-label-text">Predeterminado</span>
                                            </label></li>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="busqueda" value="<?php if (isset($_GET['buscar'])) {echo $_GET['buscar'];} ?>" />
                    <input type="hidden" id="categoria" value="<?php if (isset($_GET['cat'])) {echo $_GET['cat'];} ?>" />
                    <input type="hidden" id="subcategoria" value="<?php if (isset($_GET['subcat'])) {echo $_GET['subcat'];} ?>" />
                    <div class="row productos" id="grilla-productos"></div>
                </div>
            </div>

        </div>
    </section>


    <?php require_once("include/info.php"); ?>
    <?php require_once("include/contacto.php"); ?>
    <?php require_once("include/footer.php"); ?>

    <?php include_once("include/scripts-bottom.php") ?>
    <script src="<?php echo constant('URL') ?>js/productos.js"></script>

</body>

</html>