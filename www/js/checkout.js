var formatNumber = {
    separador: ".", // separador para los miles
    sepDecimal: ',', // separador para los decimales
    formatear: function(num) {
        num += '';
        var splitStr = num.split('.');
        var splitLeft = splitStr[0];
        var splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1] : '';
        var regx = /(\d+)(\d{3})/;
        while (regx.test(splitLeft)) {
            splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
        }
        return this.simbol + splitLeft + splitRight;
    },
    new: function(num, simbol) {
        this.simbol = simbol || '';
        return this.formatear(num);
    }
}


//Cuando seleccionamos la forma de envío
$(document).on('change', 'input[name=envio]', function(e) {
    e.preventDefault();

    //Capturo variables de las opciones de envío
    var idEnv = $(this).data('id');
    var id_correo = $("input#id_correo" + idEnv).val();
    var nombre_correo = $("input#nombre_correo" + idEnv).val();
    var descripcion_correo = $("input#descripcion_correo" + idEnv).val();
    var despacho = $("input#despacho" + idEnv).val();
    var modalidad = $("input#modalidad" + idEnv).val();
    var servicio = $("input#servicio" + idEnv).val();
    var horas_entrega = $("input#horas_entrega" + idEnv).val();
    var costoEnvio = $("input#costo_envio" + idEnv).val();
    var provincia = $("input#provincia" + idEnv).val();

    //completa las opciones de envío
    $('input[name=id_correo]').val(id_correo);
    $('input[name=nombre_correo]').val(nombre_correo);
    $('input[name=descripcion_correo]').val(descripcion_correo);
    $('input[name=despacho]').val(despacho);
    $('input[name=id_correo]').val(id_correo);
    $('input[name=modalidad]').val(modalidad);
    $('input[name=servicio]').val(servicio);
    $('input[name=horas_entrega]').val(horas_entrega);
    $('input[name=costo_envio]').val(costoEnvio);


    //completa la provincia según el codigo postal
    $('select[name=envio_provincia]').val(provincia);
    $('select[name=per_provincia]').val($('select[name=envio_provincia]').val());

    //actualiza resumen checkout
    var totalSinEnvio = $("input#totalSinEnvio").val();
    var montoTotal = parseFloat(totalSinEnvio) + parseFloat(costoEnvio);

    if ($(this).val() === "C") {
        costoEnvio = 'A convenir';
    } else {
        if (parseFloat(costoEnvio) === 0) {
            costoEnvio = 'Gratis';
        } else {
            costoEnvio = formatNumber.new(parseFloat(costoEnvio).toFixed(0), "$");
        }
    }

    montoTotal = formatNumber.new(parseFloat(montoTotal).toFixed(0), "$");
    $(".cart-widget-ship-value").html(costoEnvio).fadeIn('slow');
    $(".cart-widget-total-value").html(montoTotal).fadeIn('slow');


    //Si el envio es a domicilio o retira personal
    if ($(this).val() === "D") {

        $('#datosEnvio').collapse('show')
        $('#datosFacturacion').collapse('show')
        $(".switch-mismos-datos").show();

        if ($('input[name=chkDatos]').prop('checked')) {
            $('#formDatosFacturacion').collapse('hide')
        }

        $('input[name=chkDatos]').on('change', function() {
            if ($(this).is(':checked')) {
                $('#formDatosFacturacion').collapse('hide')
            } else {
                $('#formDatosFacturacion').collapse('show')
            }
        });

        $('input[name=env_codpostal]').val($('input[name=envio_codpostal]').val());
        $('input[name=per_codpostal]').val($('input[name=envio_codpostal]').val());
        $('input[name=env_codpostal]').prop("readonly", true);

    } else if ($(this).val() === "S") {

        $('#datosEnvio').collapse('hide')
        $('#datosFacturacion').collapse('show')
        $('#formDatosFacturacion').collapse('show')
        $(".switch-mismos-datos").hide();

    } else if ($(this).val() === "C") {

        $('#datosEnvio').collapse('hide')
        $('#datosFacturacion').collapse('show')
        $('#formDatosFacturacion').collapse('show')
        $(".switch-mismos-datos").hide();

    }

});


//Cuando seleccionamos la forma de pago
$(document).on('change', 'input[name=opcion_pago]', function(e) {
    e.preventDefault();

    //actualiza resumen checkout
    var costoEnvio = $("input[name=costo_envio]").val();
    var totalSinEnvio = $("input#totalSinEnvio").val();
    var descTransf = $("input#descTransf").val();
    var montoTotal = 0;

    if ($('input[name=opcion_pago]:checked').val() === "transferencia") {
        if (parseFloat(descTransf) === 0) {
            descuento = 0;
        } else {
            descuento = (parseFloat(descTransf) * parseFloat(totalSinEnvio)) / 100;
        }
        montoTotal = parseFloat(totalSinEnvio) + parseFloat(costoEnvio) - parseFloat(descuento);

        widget = '<div class="cart-widget-block cart-widget-row cart-widget-title cart-widget-maintitle">';
        widget += '<div class="cart-widget-label">';
        widget += '<small class="text-success">Descuento pago por transferencia</small>';
        widget += '</div>';
        widget += '<div class="cart-widget-value">';
        widget += '<small class="text-success">-' + formatNumber.new(parseFloat(descuento).toFixed(0), "$") + '</small>';
        widget += '</div></div>';
    } else {
        widget = '';
        montoTotal = parseFloat(totalSinEnvio) + parseFloat(costoEnvio);
    }

    montoTotal = formatNumber.new(parseFloat(montoTotal).toFixed(0), "$");
    $(".cart-widget-discount").html(widget).fadeIn('slow');
    $(".cart-widget-total-value").html(montoTotal).fadeIn('slow');

});


function actualizar(redireccion) {
    $.ajax({
        url: WEB_ROOT + '/checkout/confirma-orden.php',
        type: "POST",
        beforeSend: function() {
            document.body.classList.add('loading');
        },
    }).success(function() {
        window.location.href = redireccion;
    });
}