<?php
$menuCategorias=true;

require_once('clases/class_admin.php');
require_once('libreria/config.php');
$objAdmin=new LoginAdmin;
// verificamos que no este conectado el usuario
if ( !empty( $_SESSION['ad_usuario'] ) && !empty($_SESSION['ad_password']) ) {
    $arrAdministradores =$objAdmin->esAdmin($_SESSION['ad_usuario'],$_SESSION['ad_password']);
}
// verificamos si esta logeado
if (empty($arrAdministradores)) {
    header( 'Location: login.php' );
    die;
}

$Obj=new Categorias; 
$ObjConfig=new Configuracion;


$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : 'view';

if (isset($_GET['id']) && (int)$_GET['id'] > 0) {
    $id=filter_input(INPUT_GET,'id', FILTER_SANITIZE_NUMBER_INT);
}

if (!empty($_POST['submit'])) {
    $result=$Obj->agregar();
}

switch ($action) {
    case 'delete' :
        $result = $Obj->borrar($id);
        break;
}

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
                    <h1>Categorías</h1>
                    <div class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li><a href="index.html">Home</a></li>
                            <li class="active">Categorías</li>
                        </ol>
                    </div>
                </div>
                <div id="main-wrapper">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-white">
                                <div class="panel-body">

                                    <a href="ordenar-categorias.php" class="btn btn-warning m-b-sm btn-addon btn-rounded btn-lg"><i class="fa fa-arrows-v"></i> Cambiar orden de categorias</a>
                                    <button type="button" class="btn btn-success btn-addon m-b-sm btn-rounded btn-lg" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Agregar Categoría</button>
                                    <a href="accesos-categorias.php" class="btn btn-info btn-addon m-b-sm btn-rounded btn-lg"><i class="fa fa-mail-forward"></i> Accesos en home</a>
                                    <hr>

                                    
                                    <!-- Modal -->
                                                    
                                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="post" enctype="multipart/form-data" action="categorias.php">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Agregar Categoría</h4>
                                                </div>
                                                <div class="modal-body">
                                                        <div class="form-group">
                                                            <input type="text" name="campo1" class="form-control" placeholder="Nombre de la categoría" required>
                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                                    <button type="submit" name="submit" value="agregar" class="btn btn-success">Agregar</button>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                   <div class="table-responsive">
                                    <table id="example" class="display table" style="width: 100%; cellspacing: 0;">
                                        <thead>
                                            <tr>
                                                <th>Título de la categoría</th>
                                                <th>Acceso en home</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
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
        <script src="assets/plugins/toastr/toastr.min.js"></script>
        <script src="assets/plugins/datatables/js/jquery.datatables.min.js"></script>
        <script src="assets/js/modern.js"></script>
        

        <script type="text/javascript">
            $(document).ready(function() {

                function getEmployee() {
                    $.ajax({
                      type: "GET",  
                      url: "include/generar_categoria.php",
                      dataType: "json",       
                      success: function(response)  
                      {
                        var html_data = '';
                        for (var i = 0; i < response.length; i++) {

                             html_data += '<tr><td><a href="#" data-name="ct_titulo" id="ct_titulo" data-type="text" data-pk="'+response[i].ct_id+'" class="editable editable-click ';
                             if(response[i].ct_titulo=="") html_data += 'editable-empty">Empty</a></td>'; else html_data += '">'+response[i].ct_titulo+'</a></td>';

                             html_data += '<td><a href="#" id="ct_mostrar_home" data-type="select" data-pk="'+response[i].ct_id+'" data-value="'+response[i].ct_mostrar_home+'" data-title="Destacado" class="editable editable-click">'+response[i].ct_mostrar_home+'</a></td>';

                             html_data += '<td><a href="productos.php?cat='+response[i].ct_mla+'" target="_blank"><strong class="text-danger">('+response[i].cantProd+')</strong> Productos con esta categoria</a></td>';

                             html_data += '<td><a href="categorias.php?action=delete&id='+response[i].ct_id+'" class="btn btn-danger btn-sm">Eliminar</a></td></tr>';

                         };
                         $('#example tbody').html(html_data);
                         $('#example').DataTable({
                                "displayLength": 50,
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
                
                function make_editable_col(table_selector,column_selector,ajax_url,title) {
                    $(table_selector).editable({   
                        selector: column_selector,
                        url: ajax_url,
                        title: title,
                        type: "POST",
                        dataType: 'json',
                        success: function(response)  
                          {
                            toastr["success"]("datos actualizados");
                          }
                      });
                      $.fn.editable.defaults.mode = 'inline';
                    }

                function make_editable_select(table_selector,column_selector,ajax_url,title) {
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
                
                getEmployee();
                
                make_editable_col('#example tbody','a#ct_titulo','include/generar_categoria.php?action=edit','Titulo');
                make_editable_select('#example tbody','a#ct_mostrar_home','include/generar_categoria.php?action=edit','Mostrar acceso en el home');

            });

        </script>

        <script type="text/javascript">
            $( document ).ready(function() {

                <?php 
                if (isset($result)) {
                    switch ($result) {
                        case 'agregado': 
                            echo 'toastr["success"]("Los datos fueron agregados")';
                            break;
                        case 'eliminado':
                            echo 'toastr["success"]("Se eliminaron los datos correctamente")';
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