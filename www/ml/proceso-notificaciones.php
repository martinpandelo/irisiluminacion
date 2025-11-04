<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'Meli/meli.php';
require 'configApp.php';
require '/home/fulmkodp/public_html/class/sincroml.class.php';
$ObjSinc = new sincroML;

$datosmeli = $ObjSinc->DatosMeli();
$token = $datosmeli['ml_token'];
$refreshToken = $datosmeli['ml_refresh_token'];


	if (!empty($token) and !empty($refreshToken)) {

			$meli = new Meli($appId, $secretKey, $token, $refreshToken);

			$arrNoti=$ObjSinc->ConsultaNotificaciones();


			if ($arrNoti) {
			
				foreach ($arrNoti as $noti) {

					$resp=$meli->get($noti["nt_item"], array('access_token' => $token));


					echo '<br><br>';
					echo 'Procesando '.$noti["nt_item"].'<br/>';



					
					if ($resp["httpCode"]!= 200) {
						echo 'Error code: '.$resp["httpCode"].'<br/>';
						echo 'Error: '.$resp["body"]->message.'<br/>';
						if($idProd=$ObjSinc->ConsultaItem($noti["nt_mla"])){
							$estado='pausado';
							$ObjSinc->ActualizarEstado($idProd,$estado);
						}
						$ObjSinc->notificacionProcesada($noti["nt_item"]);
						echo 'Se pausa la publicacion y se cambia la notificacion a procesada<br/>';
							
						continue;
					}

					//datos basicos del producto
					$idMLA=$resp["body"]->id;
					$title=$resp["body"]->title;
					if (empty($resp["body"]->original_price)) {
						$price=$resp["body"]->price;
					} else {
						$price=$resp["body"]->original_price;
					}
					
					$estado=$resp["body"]->status;
					if ($estado=='active') $estado='publicado'; else $estado='pausado';
					if ($resp["body"]->catalog_listing == true) $estado='pausado';

					$thumbnail = str_replace('http:','https:',$resp["body"]->thumbnail);
					

  					$marca = '';
					$modelo= '';
					$caracteristicas = '';


					if(isset($resp["body"]->attributes)){
						foreach($resp["body"]->attributes as $atrib) {

							if (empty($atrib->value_name)) {
								continue;
							}
							$caracteristicas.='<strong>'.$atrib->name.': </strong>'.$atrib->value_name.'<br>';

							if ($atrib->id=='BRAND') {
								$marca = $atrib->value_name;
								
							}
							if ($atrib->id=='MODEL') {
								$modelo = $atrib->value_name;
								
							}
						}
					}
					


					///variaciones color, sku, stock y fotos
					$variat=$meli->get('/items/'.$idMLA.'/variations', array('access_token' => $token));
					


					echo 'ID MLA: '.$idMLA.'<br>';
					echo 'Title: '.$title.'<br>';
					echo 'Price: '.$price.'<br>';
					echo 'Estado: '.$estado.'<br>';
					echo 'Es de catalogo: ';
					echo ($resp["body"]->catalog_listing==true)?'SI':'NO';
					echo '<br>';


					///print '<pre>';
					///var_dump($variat);	

					//exit;
					
  					$codigo = '-';
					$sku = '';
					foreach($variat["body"] as $varia){


						 $idVariacion = $varia->id;
                		 $respVaria=$meli->get('/items/'.$idMLA.'/variations/'.$idVariacion, array('access_token' => $token, 'include_attributes' => 'all'));


						  $arrAtribVaria = $respVaria["body"]->attributes;
               			 
											 
						
						
						  foreach ($arrAtribVaria as $at) {



									if (empty($at->value_name)) {
										continue;
									}

									//SKU si no tiene variaciones
									if ($at->id=='SELLER_SKU') {
										$sku = $at->value_name;
									}
									

									if ($at->id=='COLOR') {
										$color = $at->value_name;
										$caracteristicas.='<strong>'.$at->name.': </strong>'.$at->value_name.'<br>';

										////print_r('COLOR (variaciones): '.$color.'<br>');
									}

									
									

									

							}


						}

					
					

					echo 'SKU: '.$sku.'<br>';
					echo 'Marca: '.$marca.'<br>';
					echo 'Modelo: '.$modelo.'<br>';


					

					$desc=$meli->get('/items/'.$idMLA.'/description', array('access_token' => $token));
					$description=$desc["body"]->plain_text;
					if (strstr($description, '*Premium*')) $estado='pausado';



					echo 'Descripcion: '.$description.'<br>';
					echo 'Encontrado *Premium* ? ';
					echo (strstr($description, '*Premium*'))?'SI':'NO';
					echo '<br>';


					//disponibilidad
					$arrSaleTerms=$resp["body"]->sale_terms;
					$disponibilidad="inmediata";
					foreach ($arrSaleTerms as $saleTerms) {
						if ($saleTerms->id=="MANUFACTURING_TIME") {
							$disponibilidad=$saleTerms->value_name;
						} 
					}

					echo 'Disponibilidad: '.$disponibilidad.'<br>';	


					$categoria = $resp["body"]->category_id;
					$subcategoria = $subcategoria='sin-cat';;


					echo 'Categoria: '.$categoria.'<br>';
					echo 'Sub categoria '.$subcategoria.'<br>';


					$jsonCat=$meli->get('/categories/'.$categoria, array('access_token' => $token));
					$categoriaName=$jsonCat["body"]->name;
					
					echo 'Categoria Name: '.$categoriaName.'<br/>';
					echo 'Caracteristicas :' .$caracteristicas.'<br/>';
					
					$ObjSinc->altaCategorias($categoria,$categoriaName);


					///exit;

					if ($idProd=$ObjSinc->ConsultaItem($idMLA)) { //si el producto ya se encuentra cargado: actualizar


						echo 'EL PRODUCTO YA EXITE. SE Actualiza '.'<br/>';

							

						$resp_actualizar = $ObjSinc->ActualizarItems($idProd,
												$thumbnail,
												$description,
												$caracteristicas,
												$marca,
												$modelo,
												$sku,
												$disponibilidad,
												$estado);


						echo 'Resultado actualizar DB :';
						echo ($resp_actualizar) ? 'OK' : 'NO';
						echo '<br>';	


						
						$variaBorradas=$ObjSinc->BorraVariaciones($idProd);
						$fotosBorradas=$ObjSinc->BorraFotos($idProd);

						if ($variaBorradas and $fotosBorradas) {

							//variaciones
							$arrVariations = null;
							$varia = null;
							$arrVariations=$resp["body"]->variations;

							if (empty($arrVariations)) {
								
								//fotos
								$arrPictures=$resp["body"]->pictures;
								$arr_length_pict = count($arrPictures);

								for($i=0;$i<$arr_length_pict;$i++) {
									$idFoto=$arrPictures[$i]->id;
									$listPictures=$meli->get('/pictures/'.$idFoto, array('access_token' => $token));
									$foto400x400=$listPictures["body"]->variations[2]->secure_url;
									$foto800x800=$listPictures["body"]->variations[16]->secure_url;

									list($imagewidth, $imageheight) = getimagesize($foto400x400);
									$x=$imagewidth/$imageheight;
									if ($x > 1) {
										$margen = ($imagewidth - $imageheight)/2;
										$margenPerc = ($margen * 100) / $imagewidth;
										$padding = 'padding: '.$margenPerc.'% 0'; 
									} else {
										$margen = ($imageheight - $imagewidth)/2;
										$margenPerc = ($margen * 100) / $imageheight;
										$padding = 'padding: 0 '.$margenPerc.'%'; 
									}

									$ObjSinc->CargarFotos($idProd,$foto400x400,$foto800x800,$i,$idFoto,$padding);
								}


								
								$stock=$resp["body"]->available_quantity;
								
								
								echo 'Stock: '.$stock.'<br/>';
								

								
								$variacion='-';
								$fotoVaria='-';

								$ObjSinc->CargarVariaciones($idProd,$codigo,$price,$variacion,$stock,$fotoVaria,$sku);


								



							} else {

								$cont=0;
								foreach ($arrVariations as $varia) {
									
									//fotos
									if ($cont==0) {
										$arrPictures=$varia->picture_ids;
										$arr_length_pict = count($arrPictures);

										for($i=0;$i<$arr_length_pict;$i++) {
											$idFoto=$arrPictures[$i];
											$listPictures=$meli->get('/pictures/'.$idFoto, array('access_token' => $token));
											
											$foto400x400=$listPictures["body"]->variations[2]->secure_url;
											$foto800x800=$listPictures["body"]->variations[16]->secure_url;

											list($imagewidth, $imageheight) = getimagesize($foto400x400);
											$x=$imagewidth/$imageheight;
												if ($x > 1) {
													$margen = ($imagewidth - $imageheight)/2;
													$margenPerc = ($margen * 100) / $imagewidth;
													$padding = 'padding: '.$margenPerc.'% 0'; 
												} else {
													$margen = ($imageheight - $imagewidth)/2;
													$margenPerc = ($margen * 100) / $imageheight;
													$padding = 'padding: 0 '.$margenPerc.'%'; 
												}

											$ObjSinc->CargarFotos($idProd,$foto400x400,$foto800x800,$i,$idFoto,$padding);
										}
									} else {
										$arrPictures=$varia->picture_ids;

											$idFoto=$arrPictures[0];
											$listPictures=$meli->get('/pictures/'.$idFoto, array('access_token' => $token));
											$foto400x400=$listPictures["body"]->variations[2]->secure_url;
											$foto800x800=$listPictures["body"]->variations[16]->secure_url;

											list($imagewidth, $imageheight) = getimagesize($foto400x400);
											$x=$imagewidth/$imageheight;
												if ($x > 1) {
													$margen = ($imagewidth - $imageheight)/2;
													$margenPerc = ($margen * 100) / $imagewidth;
													$padding = 'padding: '.$margenPerc.'% 0'; 
												} else {
													$margen = ($imageheight - $imagewidth)/2;
													$margenPerc = ($margen * 100) / $imageheight;
													$padding = 'padding: 0 '.$margenPerc.'%'; 
												}

											$ObjSinc->CargarFotos($idProd,$foto400x400,$foto800x800,$i,$idFoto,$padding);
									}									

									// $price=$varia->price;
									$stock=$varia->available_quantity;


									echo 'Stock: '.$stock.'<br/>';

									$idFoto=$arrPictures[0];
									$listPictures=$meli->get('/pictures/'.$idFoto, array('access_token' => $token));
									$fotoVaria=$listPictures["body"]->variations[14]->secure_url;



									//SKU de las variaciones
									$idVariacion = $varia->id;
									$respVaria=$meli->get('/items/'.$idMLA.'/variations/'.$idVariacion, array('access_token' => $token, 'include_attributes' => 'all'));
									$arrAtribVaria = $respVaria["body"]->attributes;
									foreach ($arrAtribVaria as $atri) {
										if ($atri->id=='SELLER_SKU') {
											$sku = $atri->value_name;
										}
									}

									echo 'SKU Variacion: '.$sku.'<br/>';

									$countVaria = 0;
									$countVaria = count($varia->attribute_combinations);
									$variacion = '';
									for ($x=0; $x < $countVaria; $x++) { 
										$variacion .= ucfirst(strtolower($varia->attribute_combinations[$x]->name)).': ';
										$variacion .= ucfirst(strtolower($varia->attribute_combinations[$x]->value_name)).',';
									}
									$variacion = substr($variacion, 0, -1);
									$ObjSinc->CargarVariaciones($idProd,$codigo,$price,$variacion,$stock,$fotoVaria,$sku);

									$cont++;
								}
							}
							
						} else {
							continue;
						}

					} else { //si el producto no existe en el sitio: insertar



					echo 'EL PRODUCTO NO EXITE. SE INSERTA '.'<br/>';

						

						if ($ObjSinc->CargarItems($idMLA,
							$thumbnail,
							$title,
							$description,
							$caracteristicas,
							$marca,
							$modelo,
									$sku,
							$categoria,
							$subcategoria,
							$disponibilidad,
							$estado)) {

									echo 'Resultado DB :  OK '.'<br/>';
							
							if ($idProd=$ObjSinc->ConsultaItem($idMLA)) {

								//variaciones
								$arrVariations = null;
								$varia = null;
								$arrVariations=$resp["body"]->variations;

								if (empty($arrVariations)) {
									
									//fotos
									$arrPictures=$resp["body"]->pictures;
									$arr_length_pict = count($arrPictures);

									for($i=0;$i<$arr_length_pict;$i++) {
										$idFoto=$arrPictures[$i]->id;
										$listPictures=$meli->get('/pictures/'.$idFoto, array('access_token' => $token));
										$foto400x400=$listPictures["body"]->variations[2]->secure_url;
										$foto800x800=$listPictures["body"]->variations[16]->secure_url;

										list($imagewidth, $imageheight) = getimagesize($foto400x400);
											$x=$imagewidth/$imageheight;
											if ($x > 1) {
												$margen = ($imagewidth - $imageheight)/2;
												$margenPerc = ($margen * 100) / $imagewidth;
												$padding = 'padding: '.$margenPerc.'% 0'; 
											} else {
												$margen = ($imageheight - $imagewidth)/2;
												$margenPerc = ($margen * 100) / $imageheight;
												$padding = 'padding: 0 '.$margenPerc.'%'; 
											}

										$ObjSinc->CargarFotos($idProd,$foto400x400,$foto800x800,$i,$idFoto,$padding);
									}

									$stock=$resp["body"]->available_quantity;
									
									$variacion='-';
									$fotoVaria='-';
									$ObjSinc->CargarVariaciones($idProd,$codigo,$price,$variacion,$stock,$fotoVaria,$sku);

								} else {

									$cont=0;
									foreach ($arrVariations as $varia) {
										
										//fotos

										if ($cont==0) {
											$arrPictures=$varia->picture_ids;
											$arr_length_pict = count($arrPictures);

											for($i=0;$i<$arr_length_pict;$i++) {
												$idFoto=$arrPictures[$i];
												$listPictures=$meli->get('/pictures/'.$idFoto, array('access_token' => $token));
												$foto400x400=$listPictures["body"]->variations[2]->secure_url;
												$foto800x800=$listPictures["body"]->variations[16]->secure_url;

												list($imagewidth, $imageheight) = getimagesize($foto400x400);
												$x=$imagewidth/$imageheight;
													if ($x > 1) {
														$margen = ($imagewidth - $imageheight)/2;
														$margenPerc = ($margen * 100) / $imagewidth;
														$padding = 'padding: '.$margenPerc.'% 0'; 
													} else {
														$margen = ($imageheight - $imagewidth)/2;
														$margenPerc = ($margen * 100) / $imageheight;
														$padding = 'padding: 0 '.$margenPerc.'%'; 
													}

												$ObjSinc->CargarFotos($idProd,$foto400x400,$foto800x800,$i,$idFoto,$padding);
											}
										} else {
											$arrPictures=$varia->picture_ids;

												$idFoto=$arrPictures[0];
												$listPictures=$meli->get('/pictures/'.$idFoto, array('access_token' => $token));
												$foto400x400=$listPictures["body"]->variations[2]->secure_url;
												$foto800x800=$listPictures["body"]->variations[16]->secure_url;

												list($imagewidth, $imageheight) = getimagesize($foto400x400);
												$x=$imagewidth/$imageheight;
													if ($x > 1) {
														$margen = ($imagewidth - $imageheight)/2;
														$margenPerc = ($margen * 100) / $imagewidth;
														$padding = 'padding: '.$margenPerc.'% 0'; 
													} else {
														$margen = ($imageheight - $imagewidth)/2;
														$margenPerc = ($margen * 100) / $imageheight;
														$padding = 'padding: 0 '.$margenPerc.'%'; 
													}

												$ObjSinc->CargarFotos($idProd,$foto400x400,$foto800x800,$i,$idFoto,$padding);
										}


										// $price=$varia->price;
										$stock=$varia->available_quantity;

										$idFoto=$arrPictures[0];
										$listPictures=$meli->get('/pictures/'.$idFoto);
										$fotoVaria=$listPictures["body"]->variations[14]->secure_url;

										//SKU de las variaciones
										$idVariacion = $varia->id;
										$respVaria=$meli->get('/items/'.$idMLA.'/variations/'.$idVariacion, array('access_token' => $token,'include_attributes' => 'all'));
										$arrAtribVaria = $respVaria["body"]->attributes;
										foreach ($arrAtribVaria as $atri) {
											if ($atri->id=='SELLER_SKU') {
												$sku = $atri->value_name;
											}
										}

										$countVaria = 0;
										$countVaria = count($varia->attribute_combinations);
										$variacion = '';
										for ($x=0; $x < $countVaria; $x++) { 
											$variacion .= ucfirst(strtolower($varia->attribute_combinations[$x]->name)).': ';
											$variacion .= ucfirst(strtolower($varia->attribute_combinations[$x]->value_name)).',';
										}
										$variacion = substr($variacion, 0, -1);
										$ObjSinc->CargarVariaciones($idProd,$codigo,$price,$variacion,$stock,$fotoVaria,$sku);

										$cont++;
									}
								}

							}

						}
					}


					echo 'Actualiza estado notificacion a PROCESADA'; 
					echo '<br/>--------------------------<br/>';
					$ObjSinc->notificacionProcesada($noti["nt_item"]);
				}

			}else{
				echo "NO HAY NOTIFICACIONES PENDIENTES PARA PROCESAR.";
			}
		}
?>
