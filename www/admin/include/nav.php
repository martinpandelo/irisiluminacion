
          <div class="page-sidebar sidebar">
                <div class="page-sidebar-inner slimscroll">
                    <ul class="menu accordion-menu">
                        
                        <li <?php if (isset($dashboard)) echo 'class="active"'; ?>><a href="productos.php" class="waves-effect waves-button"><span class="menu-icon glyphicon glyphicon-home"></span><p>Productos</p></a></li>
                        <li <?php if (isset($menuCategorias)) echo 'class="active"'; ?>><a href="categorias.php" class="waves-effect waves-button"><span class="menu-icon fa fa-navicon"></span><p>Categorias</p></a></li>
                        
                        <li <?php if (isset($menuCompras)) echo 'class="active"'; ?>><a href="index.php" class="waves-effect waves-button"><span class="menu-icon glyphicon glyphicon-shopping-cart"></span><p>Ventas</p></a></li>
                        <li <?php if (isset($menuPreguntas)) echo 'class="active"'; ?>><a href="preguntas.php" class="waves-effect waves-button"><span class="badge badge-danger pull-right badge-menu"><?php echo $countPreg ?></span><span class="menu-icon fa fa-comments"></span><p>Preguntas y Respuestas</p></a></li>
                        <li <?php if (isset($menuEstadisticas)) echo 'class="active"'; ?>><a href="estadisticas.php" class="waves-effect waves-button"><span class="menu-icon fa fa-line-chart"></span><p>Estadísticas</p></a></li>
                        <li <?php if (isset($menuEnvios)) echo 'class="active"'; ?>><a href="envios.php" class="waves-effect waves-button"><span class="menu-icon fa fa-truck"></span><p>Envíos</p></a></li>
                        <li <?php if (isset($menuSlides)) echo 'class="active"'; ?>><a href="slides.php" class="waves-effect waves-button"><span class="menu-icon glyphicon glyphicon-picture"></span><p>Slides</p></a></li>


                        <li class="droplink <?php if (isset($dashboard)) echo 'active'; ?>>"><a href="#" class="waves-effect waves-button"><span class="menu-icon fa fa-gears"></span><p>Configuraciones</p></a>
                            <ul class="sub-menu">
                                <li <?php if (isset($menuDescuento)) echo 'class="active"'; ?>><a href="descuento.php" class="waves-effect waves-button"><p>% Desc. General</p></a></li>
                                <li <?php if (isset($menuDesctransf)) echo 'class="active"'; ?>><a href="descuento-transferencia.php" class="waves-effect waves-button"><p>% Desc. Transferencia</p></a></li>
                                <li <?php if (isset($menuDatos)) echo 'class="active"'; ?>><a href="datos-transferencia.php" class="waves-effect waves-button"><p>Datos transferencia</p></a></li>
                                <li <?php if (isset($menuCuotas)) echo 'class="active"'; ?>><a href="cuotas.php" class="waves-effect waves-button"><p>Cuotas sin interes</p></a></li>
                                <li <?php if (isset($menuEmails)) echo 'class="active"'; ?>><a href="estados-ordenes.php" class="waves-effect waves-button"><p>Texto emails ventas</p></a></li>
                                <li <?php if (isset($menuSincro)) echo 'class="active"'; ?>><a href="sincro.php" class="waves-effect waves-button"><p>Sincronizar productos ML</p></a></li>
                                <li <?php if (isset($menuFeed)) echo 'class="active"'; ?>><a href="feed.php" class="waves-effect waves-button"><p>Feed</p></a></li>
                                <li <?php if (isset($menuMarcas)) echo 'class="active"'; ?>><a href="marcas.php" class="waves-effect waves-button"><p>Marcas</p></a></li>
                                <li <?php if (isset($menuSeo)) echo 'class="active"'; ?>><a href="seo.php" class="waves-effect waves-button"><p>SEO</p></a></li> 
                                <li <?php if (isset($menuScripts)) echo 'class="active"'; ?>><a href="scripts.php" class="waves-effect waves-button"><p>Scripts</p></a></li>
                            </ul>
                        </li>


                    </ul>
                </div><!-- Page Sidebar Inner -->
            </div><!-- Page Sidebar -->