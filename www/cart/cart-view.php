    
<div aria-live="polite" aria-atomic="true" class="position-relative">
    <div class="toast-container p-3 top-50 start-50 translate-middle">
        <div id="liveToast" class="toast align-items-center text-bg-success text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div> 
    </div> 
</div> 

<?php if (isset($carrito) && !empty($carrito)) { ?>
<div class="modal-body carrito">
    <div class="table-responsive">
        <table class="table table-striped">
            <tr>
                <th>PRODUCTOS</th>
                <th>SUBTOTAL</th>
                <th></th>
            </tr>

            <?php $subtotal = 0; 
                foreach($carrito as $car) { ?>

                <tr>
					<td style="min-width:30%"><img class="mb-1" src="<?php echo $car['imagen'] ?>" alt="<?php echo $car['pr_codigo'] ?>" style="width: 80px;">
						<p><?php echo $car['pr_codigo'] ?><br>
                            <?php echo $car['pd_titulo'] ?>
							<?php if ($car['variacion']) { ?>
								<br><?php echo $car['variacion'] ?>
                            <?php } ?>
							<br><strong class="unit-price">$<?php echo $car['precioFinal'] ?></strong></p>
										
						<div class="row g-2 align-items-center">
                            <div class="col-auto">
                                <label for="txtQty" class="col-form-label">Cantidad</label>
                            </div>
							<div class="col-auto">
								<input type="hidden" name="prec<?php echo $car['id'] ?>" id="prec<?php echo $car['id'] ?>" value="<?php echo $car['pr_id'] ?>">
								<input name="txtQty" type="number" data-id="<?php echo $car['id'] ?>" id="txtQty<?php echo $car['id'] ?>" class="form-control form-control-sm box_cant_cart" value="<?php echo $car['cantidad'] ?>" min="0" max="999" maxlength="4">
							</div>
						</div>

					</td>
					<td class="price">$<?php echo $car['totalItem'] ?></td>
					<td><a href="#" onclick="deleteCart(<?php echo $car['id'] ?>);" class="btn_quitar text-primary"><i class="bi bi-trash-fill bi-lg"></i></a></td>
				</tr>

            <?php $subtotal += $car['totalItemSinFormat']; 
            } ?>

        </table>
    </div>
    <div class="total-cart pt-4">
        <p>TOTAL <span>$<?php echo number_format(round($subtotal),0,',','.') ?></span></p>
    </div>
    <div class="action-cart py-4">
        <a href="<?php echo constant('URL') ?>checkout.php?step=1" class="btn btn-primary btn-lg text-white"><i class="fas fa-shopping-cart mr-2"></i> COMPRAR AHORA!</a>
        <button type="button" class="btn" data-bs-dismiss="modal">¡Seguir mirando más!</button>
    </div>
</div>

<?php } else { ?>

    <div class="modal-body pop_content">
		<p><strong class="text-primary">El carrito está vacío!</strong></p>
	</div>

<?php } ?>