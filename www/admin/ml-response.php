<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
$menuMLResp = true;

///require_once('clases/class_admin.php');
///require_once('libreria/config.php');

    require '../ml/Meli/meli.php';
    require '../ml/configApp.php';
    require '/home/fulmkodp/public_html/class/sincroml.class.php';


if (isset($_POST['submit']) && isset($_POST['mla'])) {
	
    

    $ObjSinc = new sincroML;

    $datosmeli = $ObjSinc->DatosMeli();
    $token = $datosmeli['ml_token'];
    $refreshToken = $datosmeli['ml_refresh_token'];
    
        if (!empty($token) and !empty($refreshToken)) {
    
                $meli = new Meli($appId, $secretKey, $token, $refreshToken);
    
                $v = $_POST['mla'];
    
                $respItem = $meli->get('/items/'.$v, array('access_token' => $token));
                $respItem = json_encode($respItem, JSON_PRETTY_PRINT);


                $respDesc = $meli->get('/items/'.$v.'/description', array('access_token' => $token));
                $respDesc = json_encode($respDesc, JSON_PRETTY_PRINT);


    
                $respPrices = $meli->get('/items/'.$v.'/prices', array('access_token' => $token));
                $respPrices = json_encode($respPrices, JSON_PRETTY_PRINT);


              


            
        }
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

            <?php ///require_once('include/nav-top.php'); ?>
            <?php ///require_once('include/nav.php'); ?>


            
            <div class="page-inner">
                <div class="page-title">
                    <h1>Datos Item Mercado Libre</h1>
                    <div class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li><a href="index.php">Home</a></li>
                            <li class="active">Datos Item Mercado Libre</li>
                        </ol>
                    </div>
                </div>
                <div id="main-wrapper">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-white">
                                <div class="panel-body">
                                    
                                    <form method="post" enctype="multipart/form-data" class="form-horizontal">
                                        <div class="col-xs-7">
                                                    <div class="form-group">
                                                        <label for="cuotas" class="col-sm-3 control-label">ID Mercado Libre (MLA00000)</label>
                                                        <div class="col-sm-6">
                                                            <input name="mla" type="text" value="<?php if(isset($_POST['mla'])) echo $_POST['mla']; ?>" class="form-control"/>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <button name="submit" type="submit" value="consultar" class="btn btn-danger m-b-lg">CONSULTAR</button>
                                                        </div>
                                                    </div>
                                        </div>
                                    </form>


                                </div>
                            </div>

                            <?php if (isset($_POST['submit']) && isset($_POST['mla'])) { ?>
                                <div class="panel panel-white">
                                    <div class="panel-body">
                                        <div class="col-xs-12">
                                            <h2>Precios</h2>
                                            <pre> <?php echo $respPrices ?></pre>
                                        
                                                <hr>


                                            <h2>Descripcion</h2>
                                            <pre> <?php echo $respDesc ?></pre>
                                                    


                                            <hr>
                                            
                                            <h2>Item completo</h2>
                                            <pre> <?php echo $respItem ?></pre>



                                          




                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

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

        
    </body>
</html>