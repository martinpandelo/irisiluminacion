<?php
require_once '../class/class.php';
$Obj = new mainClass();

$arrGrilla = $Obj->GrillaProductos();
?>          

<?php if (isset($arrGrilla) && !empty($arrGrilla)) { ?>

    <?php foreach($arrGrilla['productos'] as $prod) { ?>

        <div class="col-6 col-lg-3 wrap-card">
            <?php require("../include/item-producto.php"); ?>
        </div>

    <?php } ?>

    <?php if($arrGrilla['page'] < $arrGrilla['total_pages']) { ?>
        <div class="col-12 text-center py-5">
            <a onclick="load(<?php echo $arrGrilla['page'] + 1; ?>)" class="btn btn-outline-primary btn-lg px-5">...mostrame más</a>
        </div>
    <?php } ?>

<?php } else { ?>

    <div class="col-12 text-primary text-center">
        <i class="bi bi-emoji-frown pb-4 bi-3x"></i>
        <h4><strong>¡QUE LÁSTIMA!</strong><br>no encontramos productos.</h4>
    </div>

<?php } ?>