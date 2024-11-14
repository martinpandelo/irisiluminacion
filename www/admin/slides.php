<?php
$menuSlides=true;

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

$Obj=new Slides; 
$ObjConfig=new Configuracion;

$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : 'view';

if (isset($_GET['imagen'])) {
    $imagen=$_GET['imagen'];
}

switch ($action) {
    case 'delete' :
        $result = $Obj->borrarImg($imagen);
        break;
}

?>

<!DOCTYPE html>
<html>
    <head>
        
        <!-- Title -->
        <title>Modern | Forms - File Upload</title>
        
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
        <link href="assets/plugins/toastr/toastr.min.css" rel="stylesheet"/>
        <link href="assets/plugins/dropzone/dropzone.min.css" rel="stylesheet" type="text/css"/>
        
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
                    <h1>Slides</h1>
                    <div class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li><a href="index.php">Home</a></li>
                            <li class="active">Slides</li>
                        </ol>
                    </div>
                </div>
                <div id="main-wrapper">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-white">
                                <div class="panel-body">
                                    <p>TAMAÑO DEL SLIDE: <strong>(.JPG), (Desktop) 1920x430px, (Mobile) 768x1100px</strong></p>
                                    <form action="upload-slides.php" class="dropzone" id="myDropzone">
                                        <div class="fallback">
                                            <input name="file" type="file" multiple />
                                        </div>
                                    </form>
                                    <hr>
                                    <div class="table-responsive">
                                    <table id="example-editable" class="display table table-bordered table-striped" style="width: 100%; cellspacing: 0;">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Título</th>
                                                <th>Subtítulo</th>
                                                <th>Texto</th>
                                                <th>Link</th>
                                                <th>Versión</th>
                                                <th>Orden</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="employee_grid">
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
        <script src="assets/plugins/3d-bold-navigation/js/main.js"></script>
        <script src="assets/plugins/datatables/js/jquery.datatables.min.js"></script>
        <script src="assets/plugins/x-editable/bootstrap3-editable/js/bootstrap-editable.js"></script>
        <script src="assets/plugins/toastr/toastr.min.js"></script>
        <script src="assets/plugins/dropzone/dropzone.min.js"></script>
        <script src="assets/js/modern.js"></script>


        <script type="text/javascript">
            $(document).ready(function() {

                function getEmployee() {
                    $.ajax({
                      type: "GET",  
                      url: "include/slides.php",
                      dataType: "json",       
                      success: function(response)  
                      {
                        var html_data = '';
                        for (var i = 0; i < response.length; i++) {

                             html_data += '<tr><td><img src="../img/slide/'+response[i].sl_nombre+'" width="200px"></td>';

                             html_data += '<td><a href="#" data-name="sl_titulo" id="sl_titulo" data-type="text" data-pk="'+response[i].sl_id+'" class="editable editable-click ';
                             if(response[i].sl_titulo=="") html_data += 'editable-empty">Empty</a></td>'; else html_data += '">'+response[i].sl_titulo+'</a></td>';

                             html_data += '<td><a href="#" data-name="sl_subtitulo" id="sl_subtitulo" data-type="text" data-pk="'+response[i].sl_id+'" class="editable editable-click ';
                             if(response[i].sl_subtitulo=="") html_data += 'editable-empty">Empty</a></td>'; else html_data += '">'+response[i].sl_subtitulo+'</a></td>';

                             html_data += '<td><a href="#" data-name="sl_texto" id="sl_texto" data-type="text" data-pk="'+response[i].sl_id+'" class="editable editable-click ';
                             if(response[i].sl_texto=="") html_data += 'editable-empty">Empty</a></td>'; else html_data += '">'+response[i].sl_texto+'</a></td>';

                             html_data += '<td><a href="#" data-name="sl_link" id="sl_link" data-type="text" data-pk="'+response[i].sl_id+'" class="editable editable-click ';
                             if(response[i].sl_link=="") html_data += 'editable-empty">Empty</a></td>'; else html_data += '">'+response[i].sl_link+'</a></td>';

                             html_data += '<td><a href="#" data-name="sl_version" id="sl_version" data-type="select" data-pk="'+response[i].sl_id+'" class="editable editable-click ';
                             if(response[i].sl_version=="") html_data += 'editable-empty">Empty</a></td>'; else html_data += '">'+response[i].sl_version+'</a></td>';

                             html_data += '<td><a href="#" data-name="sl_orden" id="sl_orden" data-type="number" data-pk="'+response[i].sl_id+'" class="editable editable-click ';
                             if(response[i].sl_orden=="") html_data += 'editable-empty">Empty</a></td>'; else html_data += '">'+response[i].sl_orden+'</a></td>';
                             
                             html_data += '<td><a href="slides.php?action=delete&imagen='+response[i].sl_nombre+'" data-confirm="Está seguro que desea eliminar?" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Eliminar</a></td></tr>';
                         };
                         $('#employee_grid').html(html_data);
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
                            getEmployee();
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
                        source: [
                            {value: 'desktop', text: 'Desktop'},
                            {value: 'mobile', text: 'Mobile'}
                        ],
                        dataType: 'json',
                        success: function(response)  
                          {
                            toastr["success"]("datos actualizados");
                          }
                      });
                      $.fn.editable.defaults.mode = 'inline';
                    }
                
                getEmployee();
                
                make_editable_col('#employee_grid','a#sl_titulo','include/slides.php?action=edit','Título');
                make_editable_col('#employee_grid','a#sl_subtitulo','include/slides.php?action=edit','Subtitulo');
                make_editable_col('#employee_grid','a#sl_texto','include/slides.php?action=edit','Texto');
                make_editable_col('#employee_grid','a#sl_link','include/slides.php?action=edit','Link');
                make_editable_col('#employee_grid','a#sl_orden','include/slides.php?action=edit','Posición');
                make_editable_select('#employee_grid','a#sl_version','include/slides.php?action=edit','Versión');



                // myDropzone is the configuration for the element that has an id attribute
                // with the value my-dropzone (or myDropzone)
                Dropzone.options.myDropzone = {
                    
                    acceptedFiles: "image/jpeg",

                    init: function() {
                      this.on("addedfile", function(file) {

                        // Create the remove button
                        var removeButton = Dropzone.createElement("<button class='btn btn-danger btn-xs'><i class='fa fa-trash'></i> Eliminar</button>");
                        

                        // Capture the Dropzone instance as closure.
                        var _this = this;

                        // Listen to the click event
                        removeButton.addEventListener("click", function(e) {
                          // Make sure the button click doesn't submit the form:
                          e.preventDefault();
                          e.stopPropagation();

                          // Remove the file preview.
                          _this.removeFile(file);
                          // If you want to the delete the file on the server as well,
                          // you can do the AJAX request here.
                            var name = file.name;
                            $.ajax({ 
                              type: 'POST', 
                              url: 'include/borra-slides.php', 
                              data: "nombre="+name, 
                              dataType: 'html', 
                              success: function(data) { 
                                toastr["success"]("Se eliminaron los datos correctamente");
                                getEmployee();
                              } 
                            });

                        });

                        // Add the button to the file preview element.
                        file.previewElement.appendChild(removeButton);
                      });


                      this.on("complete", function(file) {
                        getEmployee();
                      });


                    }
                };



                
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