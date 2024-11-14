
<?php require_once("devoluciones.php"); ?>
<?php require_once("formas-de-pago.php"); ?>
<?php require_once("envios.php"); ?>
<?php require_once("faqs.php"); ?>
<?php //require_once("nosotros.php"); ?>

<footer class="bg-primary">
        <div class="container-xxl">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-11 align-self-center">
                    <div class="row justify-content-center align-items-center py-5">
                        <div class="col-12 col-xl-4 py-4 py-lg-0 text-center text-lg-start">
                            <ul class="list-unstyled footer-menu">
                                <li><a type="button" href="<?php echo constant('URL') ?>#nosotros" >Nosotros</a></li>
                                <li><a type="button" href="#" data-bs-toggle="modal" data-bs-target="#formasPago">Formas de pago</a></li>
                                <li><a type="button" href="#" data-bs-toggle="modal" data-bs-target="#devoluciones">Cambios, devoluciones y facturación</a></li>
                                <li><a type="button" href="#" data-bs-toggle="modal" data-bs-target="#devoluciones">Garantía</a></li>
                                <li><a type="button" href="<?php echo constant('URL') ?>#contacto">Contacto</a></li>
                            </ul>
                        </div>
                        <div class="col-12 col-xl-3 redes py-4 py-lg-0 text-center text-lg-start">
                            <div class="d-flex align-items-center"><i class="bi bi-telephone pe-3"></i><a href="tel:+541142904373" class="link-offset-2 link-underline-light link-underline-opacity-50 py-1" target="_blank">(011) 4290-4373</a></div>
                            <div class="d-flex align-items-center"><i class="bi bi-envelope pe-3"></i><a href="mailto:irisguillon@gmail.com" class="link-offset-2 link-underline-light link-underline-opacity-50 py-1" target="_blank">irisguillon@gmail.com</a></div>
                            <ul class="list-unstyled m-0 mt-3">
                                <li>
                                    <a href="https://www.instagram.com/irisiluminacion/" target="_blank" class="mb-2"><i class="bi bi-instagram"></i></a>
                                    <a href="https://www.facebook.com/iluminacioniris" target="_blank" class="mb-2"><i class="bi bi-facebook ps-3"></i></a>
                                </li>
                                <li>
                                    <p>Encontranos en las redes sociales</p>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-xl-3 py-4 py-lg-0">
                            <img src="https://imgmp.mlstatic.com/org-img/banners/ar/medios/468X60.jpg" title="Mercado Pago - Medios de pago" alt="Mercado Pago - Medios de pago" class="banner-mp" />
                        </div>
                        <div class="col-12 col-xl-2 py-4 py-lg-0 text-end">
                            <a href="http://qr.afip.gob.ar/?qr=S3NyoGxSlGuLA-GzL3JWkw,," target="_F960AFIPInfo"><img src="http://www.afip.gob.ar/images/f960/DATAWEB.jpg" border="0" width="50"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <section class="border-top border-primary">
        <div class="container-fluid py-4">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 align-self-center">
                    <div class="row justify-content-center align-items-center">
                        <div class="col-12 text-center">
                            <img src="<?php echo constant('URL') ?>img/iris-logo-sobre-blanco.png" class="logo-footer py-4" alt="Iris Iluminación">
                            <p class="m-0">© <?php echo date("Y") ?> Iris Iluminación - Diseño y Desarrollo <a href="https://www.jaquecomunicacion.com.ar" target="_blank" class="link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">Jaque Comunicación</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <a href="https://wa.me/5491144388173" target="_blank" class="button-whatsapp">WhatsApp <i class="bi bi-whatsapp"></i></a>