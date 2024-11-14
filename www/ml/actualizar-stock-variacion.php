<?php

require 'Meli/meli.php';
require 'configApp.php';
require '/home/fulmkodp/public_html/class/sincroml.class.php';
$ObjSinc = new sincroML;

$datosmeli=$ObjSinc->DatosMeli();
$token=$datosmeli['ml_token'];
$refreshToken=$datosmeli['ml_refresh_token'];

	if (!empty($token) and !empty($refreshToken)) {

        $meli = new Meli($appId, $secretKey, $token, $refreshToken);


		$v='MLA866044790';

			$body = array(
            	"variations"=> array(
                    array(
            			"id"=> 59278477701,
                        "available_quantity"=> 5
            		)
                )
            );


            $params = array('access_token' => $token);
	       	
	       	$reponse = $meli->put('/items/'.$v, $body, $params);
            
            echo json_encode($reponse);

	}
?>
