<a href="#" id="js_up" class="boton-subir text-center"><i class="bi bi-arrow-up fa-2x"></i></a>

    <!-- Modal Cart -->
    <div class="modal right fade" id="pop_cart" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Carrito de compras</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="outer_div"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Search -->
    <div class="modal fade" id="modalSearch" tabindex="-1" aria-labelledby="modalSearchLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body py-4">
                    <form action="<?php echo constant('URL') ?>productos/" method="GET" name="buscForm" id="buscForm">
                        <div class="input-group">
                            <input class="form-control" type="search" name="buscar" id="buscarInput" placeholder="Buscar por código o palabra clave" aria-label="Search" aria-describedby="basic-addon2">
                            <button class="btn btn-buscar" type="submit"><i class="bi bi-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

        <section class="top-bar bg-primary d-none d-sm-block">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-10 align-self-center">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="col text-center p-0">
                                <div class="nav-top-bar">
                                    <a href="tel:+541142904373" target="_blank" class="pe-3"><i class="bi bi-telephone px-1"></i> Atención al cliente: (011) 4290-4373</a>
                                    <a href="https://www.instagram.com/irisiluminacion/" target="_blank" class="px-1"><i class="bi bi-instagram px-1"></i></a>
                                    <a href="https://www.facebook.com/iluminacioniris" target="_blank" class="px-1"><i class="bi bi-facebook px-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    <section class="navigation">
        <header>
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-10 align-self-center">
                        <div class="d-flex align-items-center align-items-lg-end">
                            <div class="logo ">
                                <a href="<?php echo constant('URL') ?>"><img src="<?php echo constant('URL') ?>img/iris-factory-logo.svg" alt="Iris iluminación"></a>
                            </div>
                            <div class="header-nav mx-auto">
                                <nav>
                                    <ul class="primary-nav">
                                        <li><a href="<?php echo constant('URL') ?>">Inicio</a></li>
                                        <li><a href="<?php echo constant('URL') ?>productos.php">Productos</a></li>
                                        <li><a href="<?php echo constant('URL') ?>#nosotros">Nosotros</a></li>
                                        <li><a href="#" data-bs-toggle="modal" data-bs-target="#formasPago">Formas de pago</a></li>
                                        <li><a href="#contacto">Contacto</a></li>
                                        <li class="nav-item d-block d-sm-none">
                                            <div class="social-media mt-4">
                                                <a href="https://www.instagram.com/irisiluminacion/" target="_blank" class="mb-2 mr-4"><i class="bi bi-instagram"></i></a>
                                                <a href="https://www.facebook.com/iluminacioniris" target="_blank" class="mb-2"><i class="bi bi-facebook ps-3"></i></a>
                                            </div>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                            <div class="ms-5">
                                <div class="icons">
                                    <ul class="list-inline m-0">
                                        <li class="list-inline-item"><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modalSearch" class="show-search"><i class="bi bi-search"></i></a></li>
                                        <li class="list-inline-item"><a href="#" data-bs-toggle="modal" data-bs-target="#pop_cart" id="icon_cart"><i class="bi bi-bag"></i><span></span></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="navicon">
                                <a class="nav-toggle" href="#"><span></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    </section>