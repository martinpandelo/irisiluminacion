		$(function() {
		    load(1);
		});

		function load(page) {
		    var query = $("#busqueda").val();
		    var cat = $("#categoria").val();
		    var subcat = $("#subcategoria").val();
		    var orden = $("[name=ord]:checked").val();
		    var pd_etiqueta = "";



		    //fix pd_etiqueta filtro por URL ?cybersale=1
		    const urlParams = new URLSearchParams(window.location.search);

		    ///pd_etiqueta cybersale
		    if (urlParams.has("cybersale") && urlParams.get("cybersale") == 1) {
		        pd_etiqueta = 'cybersale';
		    }

		    ///pd_etiqueta cybersale
		    if (urlParams.has("factorysale") && urlParams.get("factorysale") == 1) {
		        pd_etiqueta = 'factorysale';
		    }


		    ///pd_etiqueta fabrica  filtro por URL ?fabrica=1
		    if (urlParams.has("fabrica") && urlParams.get("fabrica") == 1) {
		        pd_etiqueta = 'fabrica';
		    }

		    //.... el resto
		    var per_page = 48;
		    var parametros = { "action": "ajax", "page": page, 'query': query, 'cat': cat, 'subcat': subcat, 'orden': orden, 'per_page': per_page, 'pd_etiqueta': pd_etiqueta };
		    $.ajax({
		        url: WEB_ROOT + '/ajax/grilla-productos.php',
		        data: parametros,
		        beforeSend: function(objeto) {
		            document.body.classList.add('loading');
		        },
		        success: function(data) {
		            $("#grilla-productos").html(data).fadeIn('slow');
		            document.body.classList.remove('loading');
		        }
		    })
		}