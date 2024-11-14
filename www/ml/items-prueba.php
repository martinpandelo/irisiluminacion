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


            $v='MLA1154793410';

            // $resp=$meli->get('/items/'.$v, array('access_token' => $token));

            // $resp = json_encode($resp);
            // print_r($resp);


            $desc=$meli->get('/items/'.$v.'/description');
			$description=$desc["body"]->plain_text;

            if (strstr($description, '*Premium*')) $estado='pausado';

            print_r($description);
            print_r($estado);



            // $v='MLA935215675';

            // $varia=$meli->get('/items/'.$v.'/variations', array('access_token' => $token));

            // // $resp = json_encode($resp);
            // // print_r($resp);

            // //SKU de las variaciones
            // $idVariacion = $varia->id;
            // $respVaria=$meli->get('/items/'.$v.'/variations/'.$idVariacion.'?include_attributes=all', array('access_token' => $token));
            // $arrAtribVaria = $respVaria["body"]->attributes;
            // foreach ($arrAtribVaria as $atri) {
            //     if ($atri->id=='SELLER_SKU') {
            //         $sku = $atri->value_name;
            //         print_r('SELLER_SKU (variaciones): '.$sku.'<br>');
            //     }
            // }
		
		
	}
?>
