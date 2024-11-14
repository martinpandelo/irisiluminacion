		$(function() {
		    load(1);
		});

		function load(page) {
		    var query = $("#busqueda").val();
		    var cat = $("#categoria").val();
		    var subcat = $("#subcategoria").val();
		    var orden = $("[name=ord]:checked").val();

		    var per_page = 48;
		    var parametros = { "action": "ajax", "page": page, 'query': query, 'cat': cat, 'subcat': subcat, 'orden': orden, 'per_page': per_page };
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