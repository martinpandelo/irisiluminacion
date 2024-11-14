$(function() {

    //Cargar carro
    loadCart();

    //Actualizar carro de compras
    $(document).on('change', '.box_cant_cart', function(e) {
        e.preventDefault();
        document.body.classList.add('loading');

        var pid = $(this).data('id'); // get id of clicked row
        var cantidad = $("input#txtQty" + pid).val();
        var idprec = $("input#prec" + pid).val();

        if (cantidad == 0) {
            $(".toast-body").html("<strong>Esperá!</strong><br>Ingresa la cantidad").fadeIn('slow');
            const toastLiveExample = document.getElementById('liveToast');
            const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample);
            toastBootstrap.show();
            document.body.classList.remove('loading');
            return false;
        }

        if (window.XMLHttpRequest) {
            objetoAjax = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            objetoAjax = new ActiveXObject("Microsoft.XMLHTTP");
        }
        objetoAjax.onreadystatechange = mostrar2;
        objetoAjax.open('POST', WEB_ROOT + '/cart/verificar_stock_cart.php', true);
        objetoAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        var parametros = "cantidad=" + cantidad +
            '&idpr=' + idprec;

        objetoAjax.send(parametros);

        function mostrar2() {
            if (objetoAjax.readyState == 4) {
                if (objetoAjax.status == 200) {
                    if (objetoAjax.responseText != 0) {
                        $(".toast-body").html("<strong>Que lástima!</strong><br>No tenemos stock suficiente").fadeIn('slow');
                        const toastLiveExample = document.getElementById('errorToast');
                        const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample);
                        toastBootstrap.show();

                        loadCart();
                        document.body.classList.remove('loading');
                        return false;
                    } else {
                        var parametros = { "idCart": pid, "cant": cantidad };
                        $.ajax({
                            url: WEB_ROOT + '/cart/action-cart.php?action=update',
                            data: parametros,
                            beforeSend: function(objeto) {
                                document.body.classList.add('loading');
                            },

                            success: function(data) {
                                $("#outer_div").html(data).fadeIn('slow');
                                countCart();
                                document.body.classList.remove('loading');

                                $(".toast-body").html("Cantidad Actualizada").fadeIn('slow');
                                const toastLiveExample = document.getElementById('liveToast');
                                const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample);
                                toastBootstrap.show();
                            }
                        });

                    }
                }
            }
        }

    });

});


function loadCart() {
    var parametros = {};
    $.ajax({
        url: WEB_ROOT + '/cart/action-cart.php?action=load',
        data: parametros,
        beforeSend: function(objeto) {
            document.body.classList.add('loading');
        },

        success: function(data) {
            $("#outer_div").html(data).fadeIn('slow');
            countCart();
            document.body.classList.remove('loading');
        }
    });
}

function countCart() {
    var parametros = {};
    $.ajax({
        url: WEB_ROOT + '/cart/action-cart.php?action=count',
        data: parametros,
        success: function(data) {
            $("#icon_cart span").html(data).fadeIn('slow');
        }
    });
}


function deleteCart(idcart) {
    var parametros = { "idCart": idcart };
    $.ajax({
        url: WEB_ROOT + '/cart/action-cart.php?action=delete',
        data: parametros,
        beforeSend: function(objeto) {
            document.body.classList.add('loading');
        },

        success: function(data) {
            $("#outer_div").html(data).fadeIn('slow');
            countCart();
            document.body.classList.remove('loading');
        }
    });
}


function addToCartCheckout() {
    $(".error_addcart").hide();

    //variaciones
    if (document.getElementsByName("variacion").length != 0) {
        variacion = document.getElementsByName("variacion");
        var itemselected = false;
        for (var i = 0; i < variacion.length; i++) {
            if (variacion[i].checked) {
                itemselected = true;
                break;
            }
        }

        if (!itemselected) {
            $(".toast-body").html("<h4 class='fw-bold'>Esperá!</h4>primero seleccioná una de las variantes del producto").fadeIn('slow');
            const toastLiveExample = document.getElementById('errorToast');
            const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample);
            toastBootstrap.show();
            return false;
        }
    }

    //cantidad
    var cantidad = $("input#cant").val();
    if (cantidad == 0) {
        $(".toast-body").html("<h4 class='fw-bold'>Esperá!</h4>Ingresá la cantidad").fadeIn('slow');
        const toastLiveExample = document.getElementById('errorToast');
        const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample);
        toastBootstrap.show();
        return false;
    }


    if (window.XMLHttpRequest) {
        objetoAjax = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        objetoAjax = new ActiveXObject("Microsoft.XMLHTTP");
    }
    objetoAjax.onreadystatechange = mostrar;
    objetoAjax.open('POST', WEB_ROOT + '/cart/verificar_stock_cart.php', true);
    objetoAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    var idprec = $("input#precio").val();

    var parametros = "cantidad=" + cantidad +
        '&idpr=' + idprec;

    objetoAjax.send(parametros);

    function mostrar() {
        if (objetoAjax.readyState == 4) {
            if (objetoAjax.status == 200) {
                if (objetoAjax.responseText != 0) {
                    $(".toast-body").html("<h4 class='fw-bold'>¡Que lástima!</h4>No tenemos stock suficiente").fadeIn('slow');
                    const toastLiveExample = document.getElementById('errorToast');
                    const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample);
                    toastBootstrap.show();
                    return false;
                } else {

                    $.ajax({
                        url: WEB_ROOT + '/cart/action-cart.php?action=add',
                        data: $('#datcart').serialize(),
                        beforeSend: function(objeto) {
                            document.body.classList.add('loading');
                        },

                        success: function(data) {
                            window.location.href = WEB_ROOT + '/checkout.php?step=1';
                        }
                    });

                }
            }
        }
    }
}

function addToCart() {

    $(".error_addcart").hide();

    //variaciones
    if (document.getElementsByName("variacion").length != 0) {
        variacion = document.getElementsByName("variacion");
        var itemselected = false;
        for (var i = 0; i < variacion.length; i++) {
            if (variacion[i].checked) {
                itemselected = true;
                break;
            }
        }

        if (!itemselected) {
            $(".toast-body").html("<h4 class='fw-bold'>Esperá!</h4>primero seleccioná una de las variantes del producto").fadeIn('slow');
            const toastLiveExample = document.getElementById('errorToast');
            const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample);
            toastBootstrap.show();

            return false;
        }
    }

    //cantidad
    var cantidad = $("input#cant").val();

    if (cantidad == 0) {
        $(".toast-body").html("<h4 class='fw-bold'>Esperá!</h4>Ingresá la cantidad").fadeIn('slow');
        const toastLiveExample = document.getElementById('errorToast');
        const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample);
        toastBootstrap.show();
        return false;
    }


    if (window.XMLHttpRequest) {
        objetoAjax = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        objetoAjax = new ActiveXObject("Microsoft.XMLHTTP");
    }
    objetoAjax.onreadystatechange = mostrar;
    objetoAjax.open('POST', WEB_ROOT + '/cart/verificar_stock_cart.php', true);
    objetoAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    var idprec = $("input#precio").val();

    var parametros = "cantidad=" + cantidad +
        '&idpr=' + idprec;

    objetoAjax.send(parametros);

    function mostrar() {
        if (objetoAjax.readyState == 4) {
            if (objetoAjax.status == 200) {
                if (objetoAjax.responseText != 0) {
                    $(".toast-body").html("<h4 class='fw-bold'>¡Que lástima!</h4>No tenemos stock suficiente").fadeIn('slow');
                    const toastLiveExample = document.getElementById('errorToast');
                    const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample);
                    toastBootstrap.show();

                    return false;
                } else {

                    $.ajax({
                        url: WEB_ROOT + '/cart/action-cart.php?action=add',
                        data: $('#datcart').serialize(),
                        beforeSend: function(objeto) {
                            document.body.classList.add('loading');
                        },

                        success: function(data) {
                            $("#outer_div").html(data).fadeIn('slow');
                            countCart();
                            document.body.classList.remove('loading');
                            $('#pop_cart').modal('show');
                        }
                    });

                }
            }
        }
    }
}

function actualizarPrec(id) {
    var param = 'idprec=' + id;

    $.ajax({
        data: param,
        type: "GET",
        dataType: "json",
        url: WEB_ROOT + '/cart/actualizar-precio-ficha.php',
        success: function(data) {

            if (data.length > 0) {
                $.each(data, function(i, item) {
                    input = '<input type="hidden" name="precio" id="precio" value="' + item.id + '">';
                    if (item.stock == 1) {
                        stock = 'Último disponible';
                    } else {
                        stock = item.stock + ' disponibles';
                    }
                    if (item.precioorig == item.preciofinal) {
                        precio = '<h3 class="precio-ficha">$' + item.preciofinal + '</h3>';
                    } else {
                        precio = '<p class="mb-2"><del class="text-muted">$' + item.precioorig + '</del> <span class="badge text-bg-primary text-white">' + item.descuento + '% OFF</span></p>';
                        precio += '<h3 class="precio-ficha">$' + item.preciofinal + '</h3>';
                    }
                    cuota = '$' + item.cuota;
                });
            }

            $(".act_prec").html(input);
            $(".stock").html(stock);
            $(".price-display").html(precio);
            $(".valorcuota").html(cuota);
        }
    });
}