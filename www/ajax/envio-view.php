
<?php if (isset($resultEnvio) && !empty($resultEnvio)) { ?>

    <?php $i=1; foreach($resultEnvio as $env) { ?>

    <div class="form-check form-check-inline py-2 pe-3 ps-5 my-2 border bg-white">
    <input type="radio" name="envio" id="envio<?php echo $i ?>" data-id="<?php echo $i ?>" class="form-check-input" value="D" required>

    <input type="hidden" name="provincia<?php echo $i ?>" id="provincia<?php echo $i ?>" value="<?php echo $env['provincia'] ?>">
    <input type="hidden" name="id_correo<?php echo $i ?>" id="id_correo<?php echo $i ?>" value="correo-propio">
    <input type="hidden" name="nombre_correo<?php echo $i ?>" id="nombre_correo<?php echo $i ?>" value="<?php echo $env['env_nombre'] ?>">
    <input type="hidden" name="descripcion_correo<?php echo $i ?>" id="descripcion_correo<?php echo $i ?>" value="<?php echo $env['env_nombre'] ?>">
    <input type="hidden" name="despacho<?php echo $i ?>" id="despacho<?php echo $i ?>" value="-">
    <input type="hidden" name="modalidad<?php echo $i ?>" id="modalidad<?php echo $i ?>" value="-">
    <input type="hidden" name="servicio<?php echo $i ?>" id="servicio<?php echo $i ?>" value="-">
    <input type="hidden" name="horas_entrega<?php echo $i ?>" id="horas_entrega<?php echo $i ?>" value="<?php echo $env['env_horas_entrega'] ?>">
    <input type="hidden" name="costo_envio<?php echo $i ?>" id="costo_envio<?php echo $i ?>" value="<?php echo $env['price'] ?>">

    <label class="form-check-label label-shipping-method-item ps-3" for="envio<?php echo $i ?>">
        <div class="shipping-method-item">
            <span>
                <h4 class="shipping-method-item-price"><?php echo $env['valor'] ?></h4>
                <div class="shipping-method-item-name"><?php echo $env['env_nombre'] ?></div>
                <div class="shipping-method-item-desc"><small><?php echo $env['env_descripcion'] ?></small>
                <br><small><?php echo $env['env_horas_entrega'] ?></small></div>
            </span>
        </div>
    </label>
    </div>

<?php $i++; } ?>

<?php } else { ?>
    <div class="alert alert-primary" role="alert">
        <i class="bi bi-exclamation-octagon-fill"></i> No tenemos envíos para ese código postal.
    </div>
<?php } ?>