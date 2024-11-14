<?php

require 'Meli/meli.php';
require 'configApp.php';
require '/home/fulmkodp/public_html/class/sincroml.class.php';
$ObjSinc = new sincroML;

$datosmeli=$ObjSinc->DatosMeli();
$token=$datosmeli['ml_token'];
$refreshToken=$datosmeli['ml_refresh_token'];

$meli = new Meli($appId, $secretKey, $token, $refreshToken);


		// $listado=$meli->get('/users/46392985/items/search?status=active&access_token='.$token);
		$listado=$meli->get('/users/63400367/items/search?status=active&search_type=scan&access_token='.$token);
		
		$totalItems=$listado["body"]->paging->total;
		$scroll_id=$listado["body"]->scroll_id;
		$iteraciones=ceil($totalItems/50);

		for ($it=0; $it < $iteraciones; $it++) { 
			// $listado=$meli->get('/users/46392985/items/search?status=active&offset='.$offset.'&access_token='.$token);
			$listado=$meli->get('/users/63400367/items/search?status=active&search_type=scan&access_token='.$token.'&scroll_id='.$scroll_id);
			$items=$listado["body"]->results;	
		
			foreach ($items as $v) { //recorro el array de items de ML

				$resp=$meli->get('/items/'.$v, array('access_token' => $token));
				
				$idMLA=$resp["body"]->id;
				$title=$resp["body"]->title;
				$price=$resp["body"]->price;
				$estado=$resp["body"]->status;
				if ($estado=='active') $estado='publicado'; else $estado='pausado';
				$link=$resp["body"]->permalink;
				$thumbnail=$resp["body"]->secure_thumbnail;
				$arrAtributes=$resp["body"]->attributes;
				$caracteristicas='';
				$sku = '';
				$sku = $resp["body"]->seller_custom_field;
				foreach ($arrAtributes as $atri) {
					if (empty($atri->value_name)) {
						continue;
					}
					$caracteristicas.='<strong>'.$atri->name.': </strong>'.$atri->value_name.'<br>';
					if ($atri->id=='MODEL') {
						$codigo=$atri->value_name;
					}
				}
				$desc=$meli->get('/items/'.$idMLA.'/description');
				$description=$desc["body"]->plain_text;

				//disponibilidad
				$arrSaleTerms=$resp["body"]->sale_terms;
				$disponibilidad="inmediata";
				foreach ($arrSaleTerms as $saleTerms) {
					if ($saleTerms->id=="MANUFACTURING_TIME") {
						$disponibilidad=$saleTerms->value_name;
					} 
				}

				$categoria=$resp["body"]->category_id;
				$subcategoria=$resp["body"]->category_id;

				if ($subcategoria=='MLA31333' or $subcategoria=='MLA431779' or $subcategoria=='MLA4749' or $subcategoria=='MLA401802' or $subcategoria=='MLA4754') {
					$categoria='MLA436380';
				} elseif ($categoria=='MLA1588') {
					foreach ($arrAtributes as $atri) {
						if ($atri->id=='CEILING_LIGHT_TYPE') {
							$subcategoria=$atri->value_id;
						}
					}
				} elseif ($subcategoria=='MLA9988' or $subcategoria=='MLA9991' or $subcategoria=='MLA74581' or $subcategoria=='MLA74580' or $subcategoria=='MLA74591' or $subcategoria=='MLA74592' or $subcategoria=='MLA74796' or $subcategoria=='MLA74797' or $subcategoria=='MLA74798' or $subcategoria=='MLA74799') {
					$categoria='MLA1586';
					$subcategoria='sin-cat';
				} elseif ($subcategoria=='MLA388863' or $subcategoria=='MLA30208' or $subcategoria=='MLA380665') {
					$categoria='MLA2467';
				} elseif ($subcategoria=='MLA30207') {
					$categoria='MLA1588';
					$subcategoria='3137290';
				} else {
					$categoria=$subcategoria;
					$subcategoria='sin-cat';
				}

				$jsonCat=$meli->get('/categories/'.$categoria);
				$categoriaName=$jsonCat["body"]->name;
				$ObjSinc->altaCategorias($categoria,$categoriaName);


					if ($ObjSinc->CargarItems($idMLA,$thumbnail,$title,$description,$caracteristicas,$categoria,$subcategoria,$disponibilidad,$estado)) {
						
						if ($idProd=$ObjSinc->ConsultaItem($idMLA)) {

							//variaciones
							$arrVariations = null;
								$varia = null;
							$arrVariations=$resp["body"]->variations;

							if (empty($arrVariations)) {
								
								//fotos
								$arrPictures=$resp["body"]->pictures;
								$arr_length_pict = count($arrPictures);
								if ($arr_length_pict>4) {
									$arr_length_pict=$arr_length_pict-3;
								}
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
										if ($arr_length_pict>4) {
											$arr_length_pict=$arr_length_pict-3;
										}
										
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

									$price=$varia->price;
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
				

			}//fin foreach items ML
		}

?>     
