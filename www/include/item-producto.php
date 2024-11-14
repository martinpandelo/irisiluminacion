                            <div class="card">
                                <figure class="effect-steve <?php echo $prod['pd_etiqueta'] ?>">
                                    <img src="<?php echo $prod['imagen'] ?>" class="card-img-top" alt="<?php echo $prod['pd_titulo'] ?>" style="<?php echo $prod['padding'] ?>">
                                    <i class="bi bi-plus-lg"></i>
                                    <a href="<?php echo $prod['linkProd'] ?>" class="main-link">ver</a>
                                </figure>
                                <div class="card-body info-producto">
                                    <a href="<?php echo $prod['linkProd'] ?>" class="btn btn-primary rounded-pill btn-comprar mb-3"><i class="bi bi-bag"></i></a>

                                    <h4 class="card-title"><a href="<?php echo $prod['linkProd'] ?>"><?php echo $prod['pd_titulo'] ?></a></h4>
                                    <span class="badge text-bg-primary text-white d-inline-block mb-1"><?php echo $prod['descuento'] ?>% OFF</span>
                                    <div class="price d-lg-flex justify-content-center align-items-center">
                                        <div>
                                            <p>$<?php echo $prod['precioFinal'] ?></p>
                                        </div>
                                        <?php if ($prod['precioFinal'] != $prod['precioOriginal']) { ?>
                                        <div>
                                            <p><del><small>$<?php echo $prod['precioOriginal'] ?></small></del></p>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <?php if ($prod['cantCuotas'] > 0) { ?>
                                    <div class="py-1">
                                        <p class="m-0"><a href="<?php echo $prod['linkProd'] ?>"><small class="d-inline-block text-primary m-0"><span class="fw-bold"><?php echo $prod['cantCuotas'] ?></span> cuotas sin interés de <span class="fw-bold">$<?php echo $prod['valorCuota'] ?></span></small></a></p>
                                    </div>
                                    <?php } ?>
                                    
                                </div>
                            </div>