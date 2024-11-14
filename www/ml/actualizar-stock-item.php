<?php

require 'Meli/meli.php';
require 'configApp.php';
require '/home/fulmkodp/public_html/class/sincroml.class.php';
$ObjSinc = new sincroML;

$datosmeli=$ObjSinc->DatosMeli();
$token=$datosmeli['ml_token'];
$refreshToken=$datosmeli['ml_refresh_token'];

	if (!empty($token) and !empty($refreshToken)) {


        for ($i=0; $i<$numItem; $i++) {
            extract($orderContent[$i]);

            $variacionTotal = $variacion;

            $pos = strpos($variacion, ':');
			$part1 = substr($variacion, 0, $pos);
            $variacion = trim(str_replace($part1, "", $variacion));
            $variacion = str_replace(": ", "", $variacion);
            $variacion = strtolower($variacion);

            $meli = new Meli($appId, $secretKey, $token, $refreshToken);

            $v=$pd_codigo_mla;

            $resp=$meli->get('/items/'.$v, array('access_token' => $token));


                    //variaciones
                    $arrVariations=$resp["body"]->variations;
                    

                    //Si no hay variaciones
                    if (empty($arrVariations)) {

                        $stockActual=$ObjCheckout->getStockItem($pd_id);
                        $nuevoStock=$stockActual-$cantidad;

                        $body = array(
                            "available_quantity" => $nuevoStock, //ACA VA LA CANTIDAD NUEVA A ACTUALIZAR
                        );

                        $params = array('access_token' => $token);
                        $reponse = $meli->put('/items/'.$v, $body, $params);

                    } else {//si hay variaciones

                        $cntArr=0;
                        foreach ($arrVariations as $varia) {
                            
                            $stockActual=$varia->available_quantity;
                            $nuevoStock=$stockActual-$cantidad;

                            if ($variacion=='0') {
                                    $arrVaria[] = array(
                                        "id"=> $varia->id,
                                        "available_quantity"=> $nuevoStock //ACA VA LA CANTIDAD NUEVA A ACTUALIZAR
                                    );

                            } else {

                                $countVaria=count($varia->attribute_combinations);
                            
                                for ($x=0; $x < $countVaria; $x++) { 

                                    $valor=$varia->attribute_combinations[$x]->value_name;
                                    $valor = strtolower($valor);

                                    if ($valor===$variacion) {

                                        $arrVaria[$cntArr] = array(
                                            "id"=> $varia->id,
                                            "available_quantity"=> $nuevoStock //ACA VA LA CANTIDAD NUEVA A ACTUALIZAR
                                        );
                                        break;

                                    } else {
                                        $arrVaria[$cntArr] = array(
                                            "id"=> $varia->id,
                                            "available_quantity"=> $varia->available_quantity
                                        );
                                    }
                                }

                            }
                            $cntArr++;
                        }

                        $body = array(
                            "variations"=> $arrVaria
                        );

                        $params = array('access_token' => $token);
                        $reponse = $meli->put('/items/'.$v, $body, $params);
                    }
       
        }
		
	}
?>
