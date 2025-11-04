<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


require 'Meli/meli.php';
require 'configApp.php';
require '/home/fulmkodp/public_html/class/sincroml.class.php';
$ObjSinc = new sincroML;

$datosmeli=$ObjSinc->DatosMeli();
$token=$datosmeli['ml_token'];
$refreshToken=$datosmeli['ml_refresh_token'];

	if (!empty($token) and !empty($refreshToken)) {

            $meli = new Meli($appId, $secretKey, $token, $refreshToken);


            if(!isset($_GET['id'])) {
                exit('No item ID provided.');
            } else {
                $v=filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
            }


            ///$v='MLA1154793410';

            // $resp=$meli->get('/items/'.$v, array('access_token' => $token));

            // $resp = json_encode($resp);
            // print_r($resp);


            ///$desc=$meli->get('/items/'.$v.'/description');
			//$description=$desc["body"]->plain_text;

           /// if (strstr($description, '*Premium*')) $estado='pausado';

            //print_r($description);
            //print_r($estado);



            // $v='MLA935215675';

             $variat=$meli->get('/items/'.$v.'/variations', array('access_token' => $token));

             $resp = json_encode($variat);
             
             print '<pre>';
             
             echo 'Variaciones del producto: '.$v.'<br><br>';
             
             
             //var_dump($variat);



             foreach($variat["body"] as $varia){
                // //SKU de las variaciones
                $idVariacion = $varia->id;
                $respVaria=$meli->get('/items/'.$v.'/variations/'.$idVariacion, array('access_token' => $token, 'include_attributes' => 'all'));
                
                
               /// print_r('<br>Stock '.$respVaria["body"]["available_quantity"]);
               /// print_r('<br>Vendidos '.$respVaria["body"]["sold_quantity"]);
                
                
                $arrAtribVaria = $respVaria["body"]->attributes;
                foreach ($arrAtribVaria as $atri) {


                    


                    if ($atri->id=='SELLER_SKU') {
                        $sku = $atri->value_name;
                        print_r('SELLER_SKU (variaciones): '.$sku.'<br>');
                    }

                   //// print_r($atri->id.' (variaciones): '.$atri->value_name.'<br>');

                    if ($atri->id=='COLOR') {
                        $color = $atri->value_name;
                        print_r('COLOR (variaciones): '.$color.'<br>');
                    }

                    print_r('<br>');print_r('<br>');

                }
             }
		
		
	}
?>
