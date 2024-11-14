<?php 
//require '/home/fulmkodp/public_html/class/class.php';
require dirname(__FILE__).'/class/class.php';

$Obj = new mainClass();
$arr_feed =$Obj->productosFeed();

$objetoXML = new XMLWriter();




	// Estructura básica del XML
	$objetoXML->openURI("/home/fulmkodp/public_html/catalogoxml.xml");
	///$objetoXML->openURI(dirname(__FILE__)."catalogoxml2.xml");
	//$objetoXML->openURI("catalogoxml2.xml");
	//$objetoXML->openURI("/home/fulmkodp/public_html/catalogoxml2.xml");
	$objetoXML->setIndent(true);
	$objetoXML->setIndentString("\t");
	$objetoXML->startDocument('1.0', 'utf-8');
	// Inicio del nodo raíz
	$objetoXML->startElement("rss");
	$objetoXML->writeAttribute("version", "2.0");
	
	$objetoXML->startElement("channel");

	foreach ($arr_feed as $prod){
		
		$i = 0;

		if(empty($prod["pd_titulo"])) continue;
		if(empty($prod["precioFinal"])) continue;
		////if(empty($prod["imagen"])) continue;
		if(!count($prod["imagenes"])) continue;
		if(empty($prod["pd_caracteristicas"])) continue;
		if(empty($prod["pd_marca"])) continue;

		$objetoXML->startElement("item"); 

		$objetoXML->startElement("id");
		$objetoXML->text($prod["pd_id"]);
		$objetoXML->endElement();

		$objetoXML->startElement("title");
		$objetoXML->text($prod["pd_titulo"]);
		$objetoXML->endElement();

		$caracteristicas=str_replace('<br>',', ',$prod["pd_caracteristicas"]);
		$caracteristicas=strip_tags($caracteristicas);

		if($prod["pd_destacado"]=="si"){
			$objetoXML->startElement("custom_label_$i");
			$objetoXML->text("destacado");
			$objetoXML->endElement();	
			$i++;
		}
		
		if($prod["pd_etiqueta"]=="oferta"){
			$objetoXML->startElement("custom_label_$i");
			$objetoXML->text("oferta");
			$objetoXML->endElement();	
			$i++;
		}elseif($prod["pd_etiqueta"]=="fabrica"){
			$objetoXML->startElement("custom_label_$i");
			$objetoXML->text("fabrica");
			$objetoXML->endElement();	
			$i++;
		}elseif($prod["pd_etiqueta"]=="novedad"){
			$objetoXML->startElement("custom_label_$i");
			$objetoXML->text("novedad");
			$objetoXML->endElement();	
			$i++;
		}
		
		
		$objetoXML->startElement("description");
		$objetoXML->text($caracteristicas);
		$objetoXML->endElement();

		$objetoXML->startElement("availability");
		$objetoXML->text("in stock");
		$objetoXML->endElement();

		$objetoXML->startElement("condition");
		$objetoXML->text("new");
		$objetoXML->endElement();

		$objetoXML->startElement("price");
		$objetoXML->text($prod["precioOriginal"]);
		$objetoXML->endElement();

		$objetoXML->startElement("sale_price");
		$objetoXML->text($prod["precioFinal"]);
		$objetoXML->endElement();



		$objetoXML->startElement("link");
		$objetoXML->text($Obj->parseToXML($prod["linkProd"]));
		$objetoXML->endElement();



		$first_img = reset($prod["imagenes"]);
		$objetoXML->startElement("image_link");
		$objetoXML->text($Obj->parseToXML($first_img['imagen']));
		$objetoXML->endElement();

		array_shift($prod["imagenes"]);
		$second_img = reset($prod["imagenes"]);
		if($second_img){
			$objetoXML->startElement("additional_image_link");
			$objetoXML->text($Obj->parseToXML($second_img['imagen']));
			$objetoXML->endElement();		
		} 

		//$objetoXML->startElement("image_link");
		//$objetoXML->text($Obj->parseToXML($prod["imagen"]));
		//$objetoXML->endElement();

		$objetoXML->startElement("brand");
		$objetoXML->text($prod['pd_marca']);
		$objetoXML->endElement();

		$objetoXML->startElement("google_product_category");
		$objetoXML->text("594");
		$objetoXML->endElement();

		$objetoXML->startElement("product_type");
		$objetoXML->text($prod["ct_titulo"]);
		$objetoXML->endElement();

		$objetoXML->fullEndElement (); // Final del elemento "item".


		
	}
	
	$objetoXML->endElement(); // Final del nodo raíz, "channel"
	$objetoXML->endElement(); // Final del nodo raíz, "rss"
	$objetoXML->endDocument(); // Final del documento

	print('Ok, archivo generado');
	$Obj->insertarProceso('feed');
?>