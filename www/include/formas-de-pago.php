<!-- Modal Garantía, Cambios y devoluciones -->
<div class="modal fade" id="formasPago" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="titlePago" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg modal-fullscreen-md-down" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="titlePago">Formas de pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                        <div class="card mb-5">
                            <div class="card-header">
                            <strong>Tarjetas de crédito o débito y otros medios de pago con:</strong>
                            </div>
                            <div class="card-body p-4">
                                <h5 class="card-title">Mercado Pago</h5>
                                <?php $cuotas = $Obj->getCuotas(); ?>
                                <?php if ($cuotas && $cuotas['cuotas'] > 1) { ?>
                                    <h5 class="text-primary my-3"><strong class="bg-primary text-white px-2"><?php echo $cuotas['cuotas'] ?> cuotas sin interés</strong> con tarjéta de crédito</h5>
                                <?php } ?>
                                <img src="https://imgmp.mlstatic.com/org-img/banners/ar/medios/468X60.jpg" title="MercadoPago - Medios de pago" alt="MercadoPago - Medios de pago" width="100%" style="max-width: 468px" />
                            </div>
                        </div>
                        <div class="card mb-5">
                            <div class="card-header">
                            <strong>Deposito o transferencia bancaria</strong>
                            </div>
                            <div class="card-body p-4">
                                <?php $descTransf = $Obj->descuentoTransferencia(); ?>
                                <?php if ($descTransf) { ?>
                                    <h5 class="text-primary my-3"><strong class="bg-primary text-white px-2"><?php echo $descTransf['porcentaje_descuento'] ?>% Descuento</strong> pagando por Transferencia Bancaria</h5>
                                <?php } ?>
                                <p>Seleccione esta opción de pago y luego recibiras los datos para realizar la transferencia o depósito bancario a tu correo electrónico.</p>
                            </div>
                        </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary text-white btn-lg" data-bs-dismiss="modal">Cerrar ventana</button>
            </div>
        </div>
    </div>
</div>