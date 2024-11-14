<?php
$dashboard=true;

require_once('clases/class_admin.php');
require_once('libreria/config.php');
$objAdmin=new LoginAdmin;

// vemos si el usuario quiere desloguar
if (!empty($_GET['salir'])) {
    // borramos y destruimos todo tipo de sesion del usuario
    session_unset();
    session_destroy();
}

// verificamos que no este conectado el usuario
if ( !empty( $_SESSION['ad_usuario'] ) && !empty($_SESSION['ad_password']) ) {
    $arrAdministradores =$objAdmin->esAdmin($_SESSION['ad_usuario'],$_SESSION['ad_password']);
}

// verificamos si esta logeado
if (empty($arrAdministradores)) {
    header( 'Location: login.php' );
    die;
}

if (isset($_GET['cat'])) {
    $cat=filter_input(INPUT_GET,'cat', FILTER_SANITIZE_SPECIAL_CHARS);
} else {
    $cat='';
}

$Obj=new Productos; 
$ObjConfig=new Configuracion;

?>

<!DOCTYPE html>
<html>
    <head>
        
        <!-- Title -->
        <title>Modern | Datatables</title>
        
        <meta content="width=device-width, initial-scale=1" name="viewport"/>
        <meta charset="UTF-8">
        <meta name="description" content="Admin Dashboard Template" />
        <meta name="keywords" content="admin,dashboard" />
        <meta name="author" content="Steelcoders" />
        
        <!-- Styles -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600' rel='stylesheet' type='text/css'>
        <link href="assets/plugins/pace-master/themes/blue/pace-theme-flash.css" rel="stylesheet"/>
        <link href="assets/plugins/uniform/css/uniform.default.min.css" rel="stylesheet"/>
        <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/fontawesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/line-icons/simple-line-icons.css" rel="stylesheet" type="text/css"/>	
        <link href="assets/plugins/offcanvasmenueffects/css/menu_cornerbox.css" rel="stylesheet" type="text/css"/>	
        <link href="assets/plugins/waves/waves.min.css" rel="stylesheet" type="text/css"/>	
        <link href="assets/plugins/switchery/switchery.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/3d-bold-navigation/css/style.css" rel="stylesheet" type="text/css"/>	
        <link href="assets/plugins/slidepushmenus/css/component.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/datatables/css/jquery.datatables.min.css" rel="stylesheet" type="text/css"/>	
        <link href="assets/plugins/datatables/css/jquery.datatables_themeroller.css" rel="stylesheet" type="text/css"/>	
        <link href="assets/plugins/x-editable/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet" type="text/css">
        <link href="assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/toastr/toastr.min.css" rel="stylesheet"/>
        <link href="assets/plugins/bootstrap-colorpicker/css/colorpicker.css" rel="stylesheet" type="text/css"/>
        
        <!-- Theme Styles -->
        <link href="assets/css/modern.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/themes/white.css" class="theme-color" rel="stylesheet" type="text/css"/>
        <link href="assets/css/custom.css" rel="stylesheet" type="text/css"/>
        
        <script src="assets/plugins/3d-bold-navigation/js/modernizr.js"></script>
        <script src="assets/plugins/offcanvasmenueffects/js/snap.svg-min.js"></script>
        
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
    </head>
    <body class="page-header-fixed small-sidebar">
        

        <div class="overlay"></div>
        
        <?php require_once('include/search.php'); ?>
        

        <main class="page-content content-wrap">

            <?php require_once('include/nav-top.php'); ?>
            <?php require_once('include/nav.php'); ?>


            <div class="page-inner">
                <div class="page-title">
                    <h1>Productos</h1>
                    <div class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li><a href="index.php">Home</a></li>
                            <li class="active">Productos</li>
                        </ol>
                    </div>
                </div>
                <div id="main-wrapper">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            
                            <a href="agregar-producto.php" class="btn btn-success btn-addon m-b-sm btn-rounded btn-lg"><i class="fa fa-plus"></i> Agregar publicación</a>
                            <a href="ordenar-productos.php" class="btn btn-warning btn-addon m-b-sm btn-rounded btn-lg"><i class="fa fa-arrows-v"></i> Ordenar todos los productos</a>
                            <a href="ordenar-destaques.php" class="btn btn-warning btn-addon m-b-sm btn-rounded btn-lg"><i class="fa fa-arrows-v"></i> Ordenar destacados</a>
                            <a href="ordenar-nuevos.php" class="btn btn-warning btn-addon m-b-sm btn-rounded btn-lg"><i class="fa fa-arrows-v"></i> Ordenar nuevos</a>
                            <a href="ordenar-ofertas.php" class="btn btn-warning btn-addon m-b-sm btn-rounded btn-lg"><i class="fa fa-arrows-v"></i> Ordenar ofertas</a>

                            <div class="panel panel-white">

                                <div class="panel-body">
                                    <div class="table-responsive project-stats">  
                                       <table id="example" class="display table" style="width: 100%; cellspacing: 0;">
                                           <thead>
                                               <tr>
                                                   <th></th>
                                                   <th>#ML</th>
                                                   <th>Título</th>
                                                   <th>Categoria</th>
                                                   <th>Marca</th>
                                                   <th>Status</th>
                                                   <th>Destacado</th>
                                                   <th>Nuevo</th>
                                                   <th>Aplica descuento gral.</th>
                                                   <th>% Descuento</th>
                                                   <th>Categoría envío</th>
                                                   <th>Bulto</th>
                                                   <th>Etiqueta</th>
                                                   <th></th>
                                               </tr>
                                           </thead>
                                           <tfoot>
                                               <tr>
                                                   <th></th>
                                                   <th>#ML</th>
                                                   <th>Título</th>
                                                   <th>Categoria</th>
                                                   <th>Marca</th>
                                                   <th>Status</th>
                                                   <th>Destacado</th>
                                                   <th>Nuevo</th>
                                                   <th>Aplica descuento gral.</th>
                                                   <th>% Descuento</th>
                                                   <th>Categoría envío</th>
                                                   <th>Bulto</th>
                                                   <th>Etiqueta</th>
                                                   <th></th>
                                               </tr>
                                           </tfoot>
                                           <tbody>
                                           </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- Row -->
                </div><!-- Main Wrapper -->

                <?php require_once('include/footer.php'); ?>

            </div><!-- Page Inner -->
        </main><!-- Page Content -->


        
	

        <!-- Javascripts -->
        <script src="assets/plugins/jquery/jquery-2.1.3.min.js"></script>
        <script src="assets/plugins/jquery-ui/jquery-ui.min.js"></script>
        <script src="assets/plugins/pace-master/pace.min.js"></script>
        <script src="assets/plugins/jquery-blockui/jquery.blockui.js"></script>
        <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
        <script src="assets/plugins/switchery/switchery.min.js"></script>
        <script src="assets/plugins/uniform/jquery.uniform.min.js"></script>
        <script src="assets/plugins/offcanvasmenueffects/js/classie.js"></script>
        <script src="assets/plugins/waves/waves.min.js"></script>
        <script src="assets/plugins/jquery-mockjax-master/jquery.mockjax.js"></script>
        <script src="assets/plugins/moment/moment.js"></script>
        <script src="assets/plugins/x-editable/bootstrap3-editable/js/bootstrap-editable.js"></script>
        <script src="assets/plugins/x-editable/inputs-ext/typeaheadjs/lib/typeahead.js"></script>
        <script src="assets/plugins/x-editable/inputs-ext/typeaheadjs/typeaheadjs.js"></script>
        <script src="assets/plugins/x-editable/inputs-ext/address/address.js"></script>
        <script src="assets/plugins/select2/js/select2.full.min.js"></script>
        <script src="assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
        <script src="assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
        <script src="assets/plugins/toastr/toastr.min.js"></script>
        <script src="assets/plugins/datatables/js/jquery.datatables.min.js"></script>
        <script src="assets/js/modern.js"></script>
            
        
        <script type="text/javascript">
            $(document).ready(function() {

                function getEmployee() {
                    $.ajax({
                      type: "GET",  
                      url: "include/generar_productos.php?cat="+<?php echo '"'.$cat.'"' ?>,
                      dataType: "json",       
                      success: function(response)  
                      {
                        var html_data = '';
                        for (var i = 0; i < response.length; i++) {
                            html_data += '<tr><td><img src="'+response[i].pd_thumbnail+'" width="90">';
                            if (response[i].pd_codigo_mla=='MLA000') {
                                html_data += '<br><a href="fotos-productos.php?id='+response[i].pd_id+'" class="btn btn-info" style="width:90px;"><i class="fa fa-file-image-o"></i> Fotos</a>';
                            } else {
                                html_data += '<br><a href="fotos-productos.php?id='+response[i].pd_id+'" class="btn btn-primary" style="width:90px;"><i class="fa fa-file-image-o"></i> Fotos</a>';
                            }
                            html_data += '</td>';

                            if (response[i].pd_codigo_mla=='MLA000') {
                                html_data += '<td><a href="editar-producto.php?id='+response[i].pd_id+'" class="btn btn-danger">Editar</a></td>';
                            } else {
                                html_data += '<td>'+response[i].pd_codigo_mla+'</td>';
                            }
                             
                             html_data += '<td><a href="#" id="pd_titulo" data-type="textarea" data-pk="'+response[i].pd_id+'" data-value="'+response[i].pd_titulo+'" data-title="Título" class="editable editable-click">'+response[i].pd_titulo+'</a></td>';

                             html_data += '<td><a href="#" id="pd_categoria" data-type="select" data-pk="'+response[i].pd_id+'" data-value="'+response[i].ct_mla+'" data-title="Categoría" class="editable editable-click">'+response[i].ct_titulo+'</a></td>';
                             html_data += '<td>'+response[i].pd_marca+'</td>';
                             if (response[i].status=='pausado') {
                                html_data += '<td><a href="#" id="status" data-type="select" data-pk="'+response[i].pd_id+'" data-value="'+response[i].status+'" data-title="Estado" class="editable editable-click btn btn-danger">'+response[i].status+'</a></td>';
                            } else {
                                html_data += '<td><a href="#" id="status" data-type="select" data-pk="'+response[i].pd_id+'" data-value="'+response[i].status+'" data-title="Estado" class="editable editable-click btn btn-success">'+response[i].status+'</a></td>';
                            }
                             html_data += '<td><a href="#" id="pd_destacado" data-type="select" data-pk="'+response[i].pd_id+'" data-value="'+response[i].pd_destacado+'" data-title="Destacado" class="editable editable-click">'+response[i].pd_destacado+'</a></td>';
                             html_data += '<td><a href="#" id="pd_new" data-type="select" data-pk="'+response[i].pd_id+'" data-value="'+response[i].pd_new+'" data-title="¿Es nuevo?" class="editable editable-click">'+response[i].pd_new+'</a></td>';
                             html_data += '<td><a href="#" id="pd_descuento" data-type="select" data-pk="'+response[i].pd_id+'" data-value="'+response[i].pd_descuento+'" data-title="Aplica descuento general" class="editable editable-click">'+response[i].pd_descuento+'</a></td>';
                             html_data += '<td><a href="#" id="pd_descuento_especial" data-type="number" data-pk="'+response[i].pd_id+'" data-value="'+response[i].pd_descuento_especial+'" data-title="% Descuento" class="editable editable-click">'+response[i].pd_descuento_especial+'</a></td>';
                             html_data += '<td><a href="#" id="pd_categoria_envio" data-type="select" data-pk="'+response[i].pd_id+'" data-value="'+response[i].pd_categoria_envio+'" data-title="Categoría para envío" class="editable editable-click">'+response[i].pd_categoria_envio+'</a></td>';
                             html_data += '<td><a href="#" id="pd_bulto_envio" data-type="number" data-pk="'+response[i].pd_id+'" data-value="'+response[i].pd_bulto_envio+'" data-title="Bulto para envío" class="editable editable-click">'+response[i].pd_bulto_envio+'</a></td>';
                             html_data += '<td><a href="#" id="pd_etiqueta" data-type="select" data-pk="'+response[i].pd_id+'" data-value="'+response[i].pd_etiqueta+'" data-title="Etiqueta" class="editable editable-click">'+response[i].pd_etiqueta+'</a></td>';
                             html_data += '<td><a href="editar-descripcion.php?id='+response[i].pd_id+'" class="btn btn-success btn-sm">Descripción</a></td></tr>';
                         };
                         $('#example tbody').html(html_data);
                         $('#example').DataTable({
                                "displayLength": 25,
                                "language": {
                                  "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                                }
                         });

                      },
                     error: function(jqXHR, textStatus, errorThrown) {
                         toastr["error"]("No hay datos cargados")
                     }
                    });
                }

                function make_editable_selectDest(table_selector,column_selector,ajax_url,title) {
                    $(table_selector).editable({   
                        selector: column_selector,
                        url: ajax_url,
                        title: title,
                        type: "POST",
                        source: [
                            {value: 'si', text: 'si'},
                            {value: 'no', text: 'no'}
                        ],
                        dataType: 'json',
                        success: function(response)  
                          {
                            toastr["success"]("Datos actualizados");
                          }
                      });
                      $.fn.editable.defaults.mode = 'popup';
                    }
                
                    function make_editable_selectEtiq(table_selector,column_selector,ajax_url,title) {
                    $(table_selector).editable({   
                        selector: column_selector,
                        url: ajax_url,
                        title: title,
                        type: "POST",
                        source: [
                            {value: 'normal', text: 'normal'},
                            {value: 'oferta', text: 'oferta'},
                            {value: 'novedad', text: 'novedad'},
                            {value: 'fabrica', text: 'fabrica'}
                        ],
                        dataType: 'json',
                        success: function(response)  
                          {
                            toastr["success"]("Datos actualizados");
                          }
                      });
                      $.fn.editable.defaults.mode = 'popup';
                    }

                    function make_editable_selectStatus(table_selector,column_selector,ajax_url,title) {
                    $(table_selector).editable({   
                        selector: column_selector,
                        url: ajax_url,
                        title: title,
                        type: "POST",
                        source: [
                            {value: 'publicado', text: 'publicado'},
                            {value: 'pausado', text: 'pausado'}
                        ],
                        dataType: 'json',
                        success: function(response)  
                          {
                            toastr["success"]("Datos actualizados");
                          }
                      });
                      $.fn.editable.defaults.mode = 'popup';
                    }

                    function make_editable_selectEnvio(table_selector,column_selector,ajax_url,title) {
                    $(table_selector).editable({   
                        selector: column_selector,
                        url: ajax_url,
                        title: title,
                        type: "POST",
                        source: [
                            {value: 'normal', text: 'normal'},
                            {value: 'especial', text: 'especial'},
                            {value: 'convenir', text: 'convenir'}
                        ],
                        dataType: 'json',
                        success: function(response)  
                          {
                            toastr["success"]("Datos actualizados");
                          }
                      });
                      $.fn.editable.defaults.mode = 'popup';
                    }

                    function make_editable_col(table_selector,column_selector,ajax_url,title) {
                    $(table_selector).editable({   
                        selector: column_selector,
                        url: ajax_url,
                        title: title,
                        type: "POST",
                        dataType: 'json',
                        success: function(response)  
                          {
                            toastr["success"]("datos actualizados")
                          }
                      });
                      $.fn.editable.defaults.mode = 'popup';
                    }


                    function make_editable_select(table_selector,column_selector,ajax_url,title) {
                    $(table_selector).editable({   
                        selector: column_selector,
                        url: ajax_url,
                        title: title,
                        type: "POST",
                        source: "include/combo_categoria.php",
                        dataType: 'json',
                        success: function(response)  
                          {
                            toastr["success"]("datos actualizados");
                          }
                      });
                      $.fn.editable.defaults.mode = 'popup';
                    }
                
                getEmployee();
                
                make_editable_select('#example tbody','a#pd_categoria','include/generar_productos.php?action=edit','Categoría');
                make_editable_selectDest('#example tbody','a#pd_destacado','include/generar_productos.php?action=edit','Destacado');
                make_editable_selectDest('#example tbody','a#pd_new','include/generar_productos.php?action=edit','¿Es nuevo?');
                make_editable_selectDest('#example tbody','a#pd_descuento','include/generar_productos.php?action=edit','Aplica descuento general');
                make_editable_selectEtiq('#example tbody','a#pd_etiqueta','include/generar_productos.php?action=edit','Etiqueta');
                make_editable_selectStatus('#example tbody','a#status','include/generar_productos.php?action=edit','Status');
                make_editable_selectEnvio('#example tbody','a#pd_categoria_envio','include/generar_productos.php?action=edit','Categoría envio');
                make_editable_col('#example tbody','a#pd_bulto_envio','include/generar_productos.php?action=edit','Bulto para envio');
                make_editable_col('#example tbody','a#pd_descuento_especial','include/generar_productos.php?action=edit','% Descuento');
                make_editable_col('#example tbody','a#pd_titulo','include/generar_productos.php?action=edit','Título');

            });

        </script>


        <script type="text/javascript">
            $( document ).ready(function() {


                <?php 
                if (isset($result)) {
                    switch ($result) {
                        case 'agregado': 
                            echo 'toastr["success"]("Datos cargados correctamente")';
                            break;
                        case 'eliminado':
                            echo 'toastr["success"]("Datos borrados correctamente")';
                            break;
                        default:
                            echo 'toastr["error"]("'.$result.'")';
                            break;
                    } 
                }?>

                toastr.options = {
                  "closeButton": false,
                  "debug": false,
                  "newestOnTop": false,
                  "progressBar": false,
                  "positionClass": "toast-top-center",
                  "preventDuplicates": false,
                  "onclick": null,
                  "showDuration": "300",
                  "hideDuration": "1000",
                  "timeOut": "5000",
                  "extendedTimeOut": "1000",
                  "showEasing": "swing",
                  "hideEasing": "linear",
                  "showMethod": "fadeIn",
                  "hideMethod": "fadeOut"
                }

        });
        </script>

    </body>
</html>