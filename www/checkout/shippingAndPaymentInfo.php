<?php
if (!defined('WEB_ROOT')
    || !isset($_GET['step'])) {
	exit;
}
?>

            <section id="checkout">
                <div class="js-decorate co-checkoutprogressindicator">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <ul>
                                    <li class="active step-1 layer"><span>1</span>Datos</li>
                                    <li class="inactive step-2 layer"><span>2</span>Confirmar</li>
                                    <li class="inactive step-3 layer"><span>3</span>Pagar</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="container py-5">
                    <div class="row">
                        <div class="col-12 col-md-8">

                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>?step=2" method="post" name="frmCheckout" id="frmCheckout" class="form-horizontal">

                                <h4>Contacto</h4>
                                <hr>
                                <div class="row" id="checkoutEmail">
                                    <div class="col-12">
                                        <label for="per_email">Tu email</label>
                                        <input type="email" class="form-control" name="per_email" id="per_email" value="<?php if(isset($per_email)) echo $per_email ?>" required>
                                    </div>
                                </div>

                                <h4 class="mt-4">Entrega</h4>
                                <hr>

                                <div id="envios">

                                        <?php if($categoria_envio == 'convenir') { ?>

                                                <h5><i data-feather="home" class="mr-2 text-secondary"></i> Envío a convenir</h5>
                                                <div class="form-check form-check-inline py-2 pe-3 ps-5 my-2 border">
                                                    <input type="radio" name="envio" id="envio40" data-id="40" class="form-check-input" value="C" required>

                                                    <input type="hidden" name="provincia40" id="provincia40" value="A convenir">
                                                    <input type="hidden" name="id_correo40" id="id_correo40" value="A convenir">
                                                    <input type="hidden" name="nombre_correo40" id="nombre_correo40" value="A convenir">
                                                    <input type="hidden" name="descripcion_correo40" id="descripcion_correo40" value="Envío a convenir">
                                                    <input type="hidden" name="despacho40" id="despacho40" value="-">
                                                    <input type="hidden" name="modalidad40" id="modalidad40" value="-">
                                                    <input type="hidden" name="servicio40" id="servicio40" value="-">
                                                    <input type="hidden" name="horas_entrega40" id="horas_entrega40" value="-">
                                                    <input type="hidden" name="costo_envio40" id="costo_envio40" value="0">

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
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Tu código postal</span>
                                                </div>
                                                <input onblur="loadEnvios()" type="number" class="form-control" name="envio_codpostal" id="envio_codpostal" placeholder="aquí" aria-label="Tu código postal" aria-describedby="submitship" value="<?php if(isset($_SESSION['codPostal'])) echo $_SESSION['codPostal'] ?>">

                                                <input type="hidden" id="categoria_envio" name="categoria_envio" value="<?php echo $categoria_envio ?>" >
                                                <input type="hidden" id="cantproductos_envio" name="cantproductos_envio" value="<?php echo $bultos ?>" >
                                                <input type="hidden" id="total_envio" name="total_envio" value="<?php echo $subtotal ?>" >
                                            </div>
                                            <span id="errorShip" class="text-danger"></span>
                                            <div id="result-envios" class="text-left"></div>

                                        <?php } ?>
                                    <hr>
                                        <h5><i class="bi bi-shop bi-lg text-primary me-2"></i> Retiro personal</h5>
                                        <div class="form-check form-check-inline py-2 pe-3 ps-5 my-2 border">
                                            <input type="radio" name="envio" id="envio30" data-id="30" class="form-check-input" value="S" required>

                                            <input type="hidden" name="provincia30" id="provincia30" value="Buenos Aires (GBA)">
                                            <input type="hidden" name="id_correo30" id="id_correo30" value="Iris">
                                            <input type="hidden" name="nombre_correo30" id="nombre_correo30" value="Iris">
                                            <input type="hidden" name="descripcion_correo30" id="descripcion_correo30" value="Retiro personal en local">
                                            <input type="hidden" name="despacho30" id="despacho30" value="-">
                                            <input type="hidden" name="modalidad30" id="modalidad30" value="-">
                                            <input type="hidden" name="servicio30" id="servicio30" value="-">
                                            <input type="hidden" name="horas_entrega30" id="horas_entrega30" value="-">
                                            <input type="hidden" name="costo_envio30" id="costo_envio30" value="0">
                                
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

                                
                                
                                <!-- DATOS DE ENTREGA -->
                                <div id="datosEnvio" class="panel-collapse collapse">
                                    <h4 class="mt-5">Datos del Destinatario</h4>
                                    <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="envio_nombre">Nombre</label>
                                                <input class="form-control mb-2" name="envio_nombre" type="text" id="envio_nombre" value="<?php if(isset($envio_nombre)) echo $envio_nombre ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="envio_apellido">Apellido</label>
                                                <input class="form-control mb-2" name="envio_apellido" type="text" id="envio_apellido" value="<?php if(isset($envio_apellido)) echo $envio_apellido ?>" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="envio_telefono">Teléfono</label>
                                                <input type="number" class="form-control mb-2" name="envio_telefono" id="envio_telefono" value="<?php if(isset($envio_telefono)) echo $envio_telefono ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="envio_dni">DNI/CUIT</label>
                                                <input class="form-control mb-2" name="envio_dni" type="number" id="envio_dni" value="<?php if(isset($envio_dni)) echo $envio_dni ?>" max="99999999999" required>
                                            </div>
                                        </div>

                                    <h4 class="mt-5">Domicilio del Destinatario</h4>
                                    <hr>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <label for="envio_direccion">Calle</label>
                                                <input type="text" class="form-control mb-2" name="envio_direccion" id="envio_direccion" value="<?php if(isset($envio_direccion)) echo $envio_direccion ?>" required>
                                            </div>
                                            <div class="col">
                                                <label for="envio_calle_num">Número</label>
                                                <input type="number" class="form-control mb-2" name="envio_calle_num" id="envio_calle_num" value="<?php if(isset($envio_calle_num)) echo $envio_calle_num ?>" min="0" max="999999" required>
                                            </div>
                                            <div class="col">
                                                <label for="envio_piso">Piso</label>
                                                <input type="number" class="form-control mb-2" name="envio_piso" id="envio_piso" value="<?php if(isset($envio_piso)) echo $envio_piso ?>" min="0" max="999">
                                            </div>
                                            <div class="col">
                                                <label for="envio_dpto">Dpto/Of</label>
                                                <input type="text" class="form-control mb-2" name="envio_dpto" id="envio_dpto" value="<?php if(isset($envio_dpto)) echo $envio_dpto ?>" maxlength="3" size="10">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-5">
                                                <label for="envio_ciudad">Ciudad</label>
                                                <input type="text" class="form-control mb-2" name="envio_ciudad" id="envio_ciudad" value="<?php if(isset($envio_ciudad)) echo $envio_ciudad ?>" required>
                                            </div>
                                            <div class="col-md-5">
                                                <label for="envio_provincia">Provincia</label>
                                                <select id="envio_provincia" name="envio_provincia" class="form-control mb-2" required>
                                                    <?php if (isset($combProvincias) && !empty($combProvincias)) { ?>
                                                        <?php foreach($combProvincias as $prov) { ?>
                                                            <option value="<?php echo $prov['provincia'] ?>" <?php if (isset($envio_provincia) and $envio_provincia==$prov["provincia"]) { echo 'selected'; } ?> ><?php echo $prov['provincia'] ?></option>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="env_codpostal">Cód. Postal</label>
                                                <input type="text" class="form-control mb-2" name="env_codpostal" id="env_codpostal" value="<?php if(isset($env_codpostal)) echo $env_codpostal ?>" required>
                                            </div>
                                        </div>
                                </div>
                                <!-- FIN DATOS DE ENTREGA -->



                                <!-- DATOS DE FACTURACION -->
                                <div id="datosFacturacion" class="panel-collapse collapse">

                                    <h4 class="mt-5">Datos de Facturación</h4>
                                    <hr>

                                    <div class="custom-control custom-switch custom-control-inline switch-mismos-datos">
                                        <input type="checkbox" name="chkDatos" id="chkDatos" value="true" class="custom-control-input" checked>
                                        <label class="custom-control-label" for="chkDatos">Mis datos de facturación y entrega son los mismos</label>
                                    </div>

                                    <div id="formDatosFacturacion" class="panel-collapse collapse">
                                        <h6 class="my-3 font-weight-bold">Persona que pagará el pedido:</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="per_nombre">Nombre</label>
                                                <input class="form-control mb-2" name="per_nombre" type="text" id="per_nombre" value="<?php if(isset($per_nombre)) echo $per_nombre ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="per_apellido">Apellido</label>
                                                <input class="form-control mb-2" name="per_apellido" type="text" id="per_apellido" value="<?php if(isset($per_apellido)) echo $per_apellido ?>" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="per_telefono">Teléfono</label>
                                                <input type="number" class="form-control mb-2" name="per_telefono" id="per_telefono" value="<?php if(isset($per_telefono)) echo $per_telefono ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="per_dni">DNI/CUIT</label>
                                                <input class="form-control mb-2" name="per_dni" type="number" id="per_dni" value="<?php if(isset($per_dni)) echo $per_dni ?>" max="99999999999" required>
                                            </div>
                                        </div>

                                        <h6 class="my-3 font-weight-bold"">Domicilio de la persona que pagará el pedido:</h6>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <label for="per_direccion">Calle</label>
                                                <input type="text" class="form-control mb-2" name="per_direccion"  id="per_direccion" value="<?php if(isset($per_direccion)) echo $per_direccion ?>" required>
                                            </div>
                                            <div class="col">
                                                <label for="per_calle_num">Número</label>
                                                <input type="number" class="form-control mb-2" name="per_calle_num" id="per_calle_num" value="<?php if(isset($per_calle_num)) echo $per_calle_num ?>" min="0" max="999999" required>
                                            </div>
                                            <div class="col">
                                                <label for="per_piso">Piso</label>
                                                <input type="number" class="form-control mb-2" name="per_piso" id="per_piso" value="<?php if(isset($per_piso)) echo $per_piso ?>" min="0" max="999">
                                            </div>
                                            <div class="col">
                                                <label for="per_dpto">Dpto/Of</label>
                                                <input type="text" class="form-control mb-2" name="per_dpto" id="per_dpto" value="<?php if(isset($per_dpto)) echo $per_dpto ?>" maxlength="3" size="10">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-5">
                                                <label for="per_ciudad">Ciudad</label>
                                                <input type="text" class="form-control mb-2" name="per_ciudad" id="per_ciudad" value="<?php if(isset($per_ciudad)) echo $per_ciudad ?>" required>
                                            </div>
                                            <div class="col-md-5">
                                                <label for="per_provincia">Provincia</label>
                                                <select id="per_provincia" name="per_provincia" class="form-control mb-2" required>
                                                    <?php if (isset($combProvincias) && !empty($combProvincias)) { ?>
                                                        <?php foreach($combProvincias as $prov) { ?>
                                                            <option value="<?php echo $prov['provincia'] ?>" <?php if (isset($per_provincia) and $per_provincia==$prov["provincia"]) { echo 'selected'; } ?> ><?php echo $prov['provincia'] ?></option>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="per_codpostal">Cód. Postal</label>
                                                <input type="text" class="form-control mb-2" name="per_codpostal" id="per_codpostal" value="<?php if(isset($per_codpostal)) echo $per_codpostal ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- FIN DATOS DE FACTURACION -->
                                
                                
                                <h4 class="mt-5">Medio de Pago:</h4>
                                <hr>
                                <h6 class="my-3 fw-bold">Tarjetas de crédito o débito y otros medios de pago:</h6>
                                <div class="form-check form-check-inline py-3 pe-3 ps-5 my-2 border">
                                    <input type="radio" name="opcion_pago" id="opcion_pago1" value="mp" class="form-check-input" <?php if(isset($opcion_pago) and $opcion_pago=='mp') echo 'checked' ?> required>
                                    <label class="form-check-label" for="opcion_pago1">Mercado Pago 
                                        <?php $cuotas = $Obj->getCuotas(); ?>
                                        <?php if ($cuotas && $cuotas['cuotas'] > 1) { ?>
                                            <h5 class="text-primary my-3"><strong class="bg-primary text-white px-2"><?php echo $cuotas['cuotas'] ?> cuotas sin interés</strong> con tarjéta de crédito</h5>
                                        <?php } ?>
                                    
                                        <img src="https://imgmp.mlstatic.com/org-img/banners/ar/medios/468X60.jpg" title="MercadoPago - Medios de pago" alt="MercadoPago - Medios de pago" width="100%" style="max-width: 468px" />
                                    </label>
                                </div>
                                <h6 class="my-3 fw-bold">Mediante una transferencia bancaria:</h6>
                                <div class="form-check form-check-inline py-3 pe-3 ps-5 my-2 border">
                                    <input type="radio" name="opcion_pago" id="opcion_pago2" value="transferencia" class="form-check-input" <?php if(isset($opcion_pago) and $opcion_pago=='transferencia') echo 'checked' ?> required>
                                    <label class="form-check-label" for="opcion_pago2">Transferencia Bancaria<br>
                                        <small class="form-text text-muted mt-0 mb-2">Cuando realices la compra te llegarán los datos para hacer la transferencia.</small>
                                        <?php if ($descTransf) { ?>
                                            <h5 class="text-primary my-3"><strong class="bg-primary text-white px-2"><?php echo $descTransf['porcentaje_descuento'] ?>% Descuento</strong> pagando por Transferencia Bancaria</h5>
                                            <input type="hidden" name="descTransf" id="descTransf" value="<?php echo $descTransf['porcentaje_descuento'] ?>">
                                        <?php } else { ?>
                                            <input type="hidden" name="descTransf" id="descTransf" value="0">
                                        <?php } ?>
                                    </label>
                                </div>

                            

                                <h4 class="mt-5">Mensaje/Aclaraciones:</h4>
                                <hr>
                                <textarea name="mensaje" id="mensaje" class="form-control"><?php if(isset($mensaje)) echo $mensaje ?></textarea>
                                
                                <input type="hidden" name="totalSinEnvio" id="totalSinEnvio" value="<?php echo $subtotal ?>">
                                
                                <input type="hidden" name="id_correo">
                                <input type="hidden" name="nombre_correo">
                                <input type="hidden" name="descripcion_correo">
                                <input type="hidden" name="despacho">
                                <input type="hidden" name="modalidad">
                                <input type="hidden" name="servicio">
                                <input type="hidden" name="horas_entrega">
                                <input type="hidden" name="costo_envio" value="0">

                                <div class="action_cart mt-3 mb-5 text-right">
                                     <button class="btn btn-primary btn-lg text-white px-5" name="btnStep1" type="submit" id="btnStep1">Continuar</button>
                                </div>
                                                
                            </form>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="sticky-top">
                                <h4>Resumen</h4>
                                <hr>

                                <section class="cart-widget">
                                    <?php
                                        for ($i=0; $i<$cartItem; $i++) {
                                            extract($cartContent[$i]);
                                    ?>
                                                <div class="line-item">
                                                    <div class="media mt-2">
                                                        <img class="mr-2 align-self-center" src="<?php echo $imagen ?>" alt="<?php echo $pd_titulo ?>" style="width: 70px;">
                                                        <div class="media-body">
                                                            <p><?php echo $pd_titulo;
                                                            if ($variacion) {
                                                                echo ' - '.$variacion;
                                                            }
                                                            ?></p>      
                                                            <p>$<?php echo $precioFinal; ?> x <?php echo $cantidad ?></p>                               
                                                        </div>
                                                    </div>
                                                </div>
                                    <?php } ?>
                                    <div class="cart-widget-block cart-widget-row cart-widget-title cart-widget-maintitle">
                                        <div class="cart-widget-label">
                                            Cantidad de productos 
                                        </div>
                                        <div class="cart-widget-value">
                                            <?php echo $cantProductos ?>
                                        </div>
                                    </div>
                                    <div class="cart-widget-block cart-widget-row cart-widget-title cart-widget-maintitle">
                                        <div class="cart-widget-label">
                                            Total Productos
                                        </div>
                                        <div class="cart-widget-value">
                                            $ <?php echo number_format(round($subtotal),0,',','.'); ?>
                                        </div>
                                    </div>
                                    <div class="cart-widget-block cart-widget-row cart-widget-title cart-widget-maintitle">
                                        <div class="cart-widget-label">
                                            Costo de envío
                                        </div>
                                        <div class="cart-widget-value cart-widget-ship-value">
                                            A convenir
                                        </div>
                                    </div>
                                    <div class="cart-widget-discount"></div>
                                    <div class="cart-widget-mainblock cart-products-payment_total">
                                        <div class="cart-widget-row cart-widget-title cart-widget-maintitle cart-products-ordertotal">
                                            <div class="cart-widget-label">
                                                Total
                                            </div>
                                            <div class="cart-widget-value cart-widget-total-value">
                                                $<?php echo number_format(round($subtotal),0,',','.');  ?>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

