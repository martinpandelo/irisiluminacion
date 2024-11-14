<?php 
require '/home/fulmkodp/public_html/class/class.php';

$Obj = new mainClass();
$arr_feed =$Obj->productosFeed();

$objetoXML = new XMLWriter();

	// Estructura básica del XML
	$objetoXML->openURI("/home/fulmkodp/public_html/catalogoxml.xml");
	$objetoXML->setIndent(true);
	$objetoXML->setIndentString("\t");
	$objetoXML->startDocument('1.0', 'utf-8');
	// Inicio del nodo raíz
	$objetoXML->startElement("rss");
	$objetoXML->writeAttribute("version", "2.0");
	
	$objetoXML->startElement("channel");

	foreach ($arr_feed as $prod){

		if(empty($prod["pd_titulo"])) continue;
		if(empty($prod["precioFinal"])) continue;
		if(empty($prod["imagen"])) continue;
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
		$objetoXML->text($prod["precioFinal"]);
		$objetoXML->endElement();

		$objetoXML->startElement("link");
		$objetoXML->text($Obj->parseToXML($prod["linkProd"]));
		$objetoXML->endElement();

		$objetoXML->startElement("image_link");
		$objetoXML->text($Obj->parseToXML($prod["imagen"]));
		$objetoXML->endElement();

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