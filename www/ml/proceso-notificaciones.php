<?php

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
					$arrAtributes=$resp["body"]->attributes;
					$caracteristicas='';
					$sku = '';
					$marca = '';
					$codigo = '-';
					$sku = $resp["body"]->seller_custom_field;
					foreach ($arrAtributes as $atri) {
						//SKU si no tiene variaciones
						if ($atri->id=='SELLER_SKU') {
							$sku = $atri->value_name;
						}
						if (empty($atri->value_name)) {
							continue;
						}
						if ($atri->name=='Marca') {
							$marca=$atri->value_name;
						}
						$caracteristicas.='<strong>'.$atri->name.': </strong>'.$atri->value_name.'<br>';
						if ($atri->id=='MODEL') {
							$codigo=$atri->value_name;
						}
					}
					$desc=$meli->get('/items/'.$idMLA.'/description');
					$description=$desc["body"]->plain_text;
					if (strstr($description, '*Premium*')) $estado='pausado';

					//disponibilidad
					$arrSaleTerms=$resp["body"]->sale_terms;
					$disponibilidad="inmediata";
					foreach ($arrSaleTerms as $saleTerms) {
						if ($saleTerms->id=="MANUFACTURING_TIME") {
							$disponibilidad=$saleTerms->value_name;
						} 
					}

					$categoria = $resp["body"]->category_id;
					$subcategoria = $subcategoria='sin-cat';;


					$jsonCat=$meli->get('/categories/'.$categoria);
					$categoriaName=$jsonCat["body"]->name;
					$ObjSinc->altaCategorias($categoria,$categoriaName);

					if ($idProd=$ObjSinc->ConsultaItem($idMLA)) { //si el producto ya se encuentra cargado: actualizar

						$ObjSinc->ActualizarItems($idProd,$thumbnail,$description,$caracteristicas,$marca,$disponibilidad,$estado);

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
									$listPictures=$meli->get('/pictures/'.$idFoto);
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
											$listPictures=$meli->get('/pictures/'.$idFoto);
											
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
											$listPictures=$meli->get('/pictures/'.$idFoto);
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
									$respVaria=$meli->get('/items/'.$idMLA.'/variations/'.$idVariacion.'?include_attributes=all', array('access_token' => $token));
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
							
						} else {
							continue;
						}

					} else { //si el producto no existe en el sitio: insertar

						if ($ObjSinc->CargarItems($idMLA,$thumbnail,$title,$description,$caracteristicas,$marca,$categoria,$subcategoria,$disponibilidad,$estado)) {
							
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
										$listPictures=$meli->get('/pictures/'.$idFoto);
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
												$listPictures=$meli->get('/pictures/'.$idFoto);
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
												$listPictures=$meli->get('/pictures/'.$idFoto);
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
										$respVaria=$meli->get('/items/'.$idMLA.'/variations/'.$idVariacion.'?include_attributes=all', array('access_token' => $token));
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
					$ObjSinc->notificacionProcesada($noti["nt_item"]);
				}

			}
		}
?>
