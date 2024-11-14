<?php
require_once 'envio.class.php';

class Varias
{
	protected static $texto;
	protected static $caracteres;
	protected static $filtro;
	protected static $titulo;
	
	public static function limpiar_txt($txt)
	{	
		self::$texto=$txt;
		$caracteres_raros = array("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","Ñ","ü","Ü","'"," ");
		$caracteres_remp = array("a","e","i","o","u","A","E","I","O","U","n","N","u","U","","-");
		$result = str_replace($caracteres_raros, $caracteres_remp, self::$texto);
		$result=strtolower($result);
		return $result;
		
	}
	public static function LimitarCaracteres($text,$caract)
	{
		self::$texto=$text;
		self::$caracteres=$caract;
		if (strlen(self::$texto)>self::$caracteres) {
    		self::$texto = wordwrap(self::$texto, self::$caracteres, '<|*|*|>'); // separar en $max_long con ruptura sin cortar palabras. 
    		$posicion = strpos(self::$texto, '<|*|*|>'); // encontrar la primera aparición de la ruptura. 
    		self::$texto = substr(self::$texto, 0, $posicion).' ...'; // tomar la porción antes de la ruptura y agregar '...' 
		}
    	return self::$texto;
	}
	public static function TitProductos($text)
	{
		self::$texto=$text;
		if (strlen(self::$texto)>18) { 
    		self::$texto = substr(self::$texto, 0, 18).'...'; // tomar la porción antes de la ruptura y agregar '...' 
		}
    	return self::$texto;
	}
	public static function crear_url($tit)
	{

		self::$titulo=mb_strtolower($tit, 'UTF-8');
		self::$titulo = trim(self::$titulo);
        self::$titulo = str_replace( 
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), 
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), 
            self::$titulo
        ); 
        self::$titulo = str_replace( 
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), 
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), 
            self::$titulo
        ); 
        self::$titulo = str_replace( 
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), 
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), 
            self::$titulo
        ); 
        self::$titulo = str_replace( 
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), 
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), 
            self::$titulo
        ); 
        self::$titulo = str_replace( 
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), 
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), 
            self::$titulo
        ); 
        self::$titulo = str_replace( 
            array('ñ', 'Ñ', 'ç', 'Ç'), 
            array('n', 'N', 'c', 'C'), 
            self::$titulo
        ); 
        self::$titulo = str_replace(' ', '-', self::$titulo); 
        self::$titulo = str_replace('&', 'y', self::$titulo); 
        self::$titulo = str_replace( 
            array("", "¨", "º", "~", "#", "@", "|", "!",'"', "·", "$", "%", "/", "(", ")", "?", "'", "¡", "¿", "[", "^", "`", "]", "+", "}", "{", "¨", "´", ">", "<", ";", ":" ,",", "."), 
            '', 
            self::$titulo
		); 
		self::$titulo=str_replace("----","-",self::$titulo);
		self::$titulo=str_replace("---","-",self::$titulo);
		self::$titulo=str_replace("--","-",self::$titulo);

        return self::$titulo; 
	}
}


class mainClass {
 
    private $conn;
    private $id;
    private $cat;
    private $subcat;
    private $orden;
    private $busqueda;
 
    function __construct() {
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
		$this->conn = $db->connect();
    }

    public function scriptsHead(){
		$sql = "SELECT scr_head FROM `tbl_scripts`";

        $query = $this->conn->prepare($sql);
        $query->execute();

        if ($query->rowCount()>0) {
            return $query->fetchAll();
        } 
    }

    public function scriptsBody(){
		$sql = "SELECT scr_body FROM `tbl_scripts`";

        $query = $this->conn->prepare($sql);
        $query->execute();

        if ($query->rowCount()>0) {
            return $query->fetchAll();
        } 
    }

	private $arrSlide=array(); 

	public function getSlides() {

		$query = $this->conn->prepare("SELECT * FROM `tbl_slides` 
		WHERE sl_version='desktop' ORDER BY `sl_orden` ASC");

        $query->execute();

        if ($query->rowCount()>0) {
            $i=0;
            while($row = $query->fetch()) {
                $row['active'] = ($i==0) ? 'active' : '';
                $row['imgid'] = $i;
                $row['imagen_desktop'] = $row['sl_nombre'];
                
                $queryMbl = $this->conn->prepare("SELECT * FROM `tbl_slides` 
				WHERE sl_version='mobile' AND sl_orden=?");
				$queryMbl->bindParam(1, $row['sl_orden'], PDO::PARAM_INT);
                $queryMbl->execute();

				if ($queryMbl->rowCount()>0) {
					$rowMob=$queryMbl->fetch();
					$row['imagen_mobile'] = $rowMob['sl_nombre'];
				} else {
					$row['imagen_mobile'] = $row['sl_nombre'];
				}

                $row['imgid'] = $i;
                $this->arrSlide[] = $row;
                $i++;
            }
        }
        return $this->arrSlide;

    }

    private $arrCat=array();

	public function getCategoriasHome()
	{
        $sql="SELECT DISTINCT ct_alias, ct_titulo, ct_id, ct_orden FROM `tbl_categorias` 
		INNER JOIN tbl_productos ON tbl_productos.pd_categoria=tbl_categorias.ct_mla 
		WHERE ct_id!=0 AND status='publicado' AND ct_mostrar_home='si' ORDER BY ct_orden_home ASC";
		$query = $this->conn->prepare($sql);
        $query->execute();

		while($reg = $query->fetch())
			{
				$this->arrCat[] = $reg;
			}

        return $this->arrCat;
	}

    public function getCategorias()
	{
        $sql="SELECT DISTINCT ct_alias, ct_titulo, ct_id, ct_orden FROM `tbl_categorias` 
		INNER JOIN tbl_productos ON tbl_productos.pd_categoria=tbl_categorias.ct_mla 
		WHERE ct_id!=0 AND status='publicado' ORDER BY ct_orden,ct_id ASC";
		$query = $this->conn->prepare($sql);
        $query->execute();

		while($reg = $query->fetch())
			{
				$this->arrCat[] = $reg;
			}

        return $this->arrCat;
	}

    public function getCategoriaActiva($cat)
	{
        $query = $this->conn->prepare("SELECT * FROM `tbl_categorias` WHERE ct_alias=?");
		$query->bindParam(1, $cat, PDO::PARAM_STR);

        if ($query->execute()) {
            if ($query->rowCount()>0) {
                return $query->fetch();
            }
		} 
	}

    public function getSubCategoriaActiva($cat)
	{
        $query = $this->conn->prepare("SELECT * FROM `tbl_subcategorias` WHERE sct_alias=?");
		$query->bindParam(1, $cat, PDO::PARAM_STR);

        if ($query->execute()) {
            if ($query->rowCount()>0) {
                return $query->fetch();
            }
		} 
	}

    public function getDescuento(){

        $query = $this->conn->prepare("SELECT * FROM `tbl_descuento`");
        $query->execute();

            if ($query->rowCount()>0) {
                return $query->fetch();
            } else {
                return null;
            }
    }

    public function getCuotas(){

        $query = $this->conn->prepare("SELECT cuotas FROM `tbl_cuotas` WHERE id=1");
        $query->execute();

            if ($query->rowCount()>0) {
                return $query->fetch();
            } else {
                return null;
            }
    }

    public function descuentoTransferencia()
	{
        $query = $this->conn->prepare("SELECT * FROM tbl_descuento_transferencias WHERE porcentaje_descuento!=0");
        $query->execute();

            if ($query->rowCount()>0) {
                return $query->fetch();
            } else {
                return null;
            }
	}

	private $arrProd=array();

	public function getDestacados(){

        unset($this->arrProd);

        $desc = $this->getDescuento();
        $cuot = $this->getCuotas();

        $sql = "SELECT * FROM `tbl_productos` 
		WHERE pd_destacado='si' AND status='publicado' ORDER BY pd_orden_dest ASC LIMIT 28";
        $query = $this->conn->prepare($sql);
        $query->execute();

        if ($query->rowCount()>0) {
            while($reg = $query->fetch())
            {   
                $this->arrProd[] = $this->getDatosItemProd($reg, $desc, $cuot);
            }
            return $this->arrProd;
        } 
    }


    public function getNovedades(){

        unset($this->arrProd);

        $desc = $this->getDescuento();
        $cuot = $this->getCuotas();

        $sql = "SELECT * FROM `tbl_productos` 
		WHERE pd_new='si' AND status='publicado' ORDER BY pd_orden_new ASC LIMIT 10";
        $query = $this->conn->prepare($sql);
        $query->execute();

        if ($query->rowCount()>0) {
            while($reg = $query->fetch())
            {   
                $this->arrProd[] = $this->getDatosItemProd($reg, $desc, $cuot);
            }
            return $this->arrProd;
        } 
    }


    private $arrGridProd=array();

    public function GrillaProductos()
	{
		$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
        unset($this->arrGridProd);
        unset($this->arrProd);

        $desc = $this->getDescuento();
        $cuot = $this->getCuotas();

		if($action == 'ajax'){

			$this->busqueda = strip_tags($_REQUEST['query'], ENT_QUOTES);
			$this->cat = strip_tags($_REQUEST['cat'], ENT_QUOTES);
			$this->subcat = strip_tags($_REQUEST['subcat'], ENT_QUOTES);
			$this->orden = strip_tags($_REQUEST['orden'], ENT_QUOTES);

			$sql="SELECT DISTINCT(pd_id), pd_titulo, pd_etiqueta, pd_descuento, pd_descuento_especial, pd_orden, ct_orden, IF(pd_destacado='si', pd_orden_dest, 2000) AS orden, IF(pd_new='si', pd_orden_new, 2000) AS ordennew , IF(pd_etiqueta='novedad', 0, pd_orden) AS ordennovedad, IF(pd_etiqueta='oferta', pd_orden_oferta, 2000) AS ordenoferta FROM `tbl_productos`
			LEFT JOIN tbl_productos_parent ON tbl_productos.pd_id=tbl_productos_parent.pr_producto
			LEFT JOIN tbl_categorias ON tbl_productos.pd_categoria=tbl_categorias.ct_mla
			LEFT JOIN tbl_subcategorias ON tbl_subcategorias.sct_mla=tbl_productos.pd_subcategoria ";

			if(!empty($this->cat)){
				if(!strstr($sql,"WHERE")){
					$sql .= " WHERE ct_alias = :cat ";
				}else{
					$sql .= " AND ct_alias = :cat ";
				}
			}

			if(!empty($this->subcat)){
				if(!strstr($sql,"WHERE")){
					$sql .= " WHERE sct_alias = :subcat ";
				}else{
					$sql .= " AND sct_alias = :subcat ";
				}
			}

			if(!empty($this->busqueda)){
				if(!strstr($sql,"WHERE")){
					$sql .= " WHERE pd_titulo LIKE :term ";
				}else{
					$sql .= " AND pd_titulo LIKE :term ";
				}
			}

			if(!strstr($sql,"WHERE")){
				$sql.=" WHERE status='publicado'";
			}else{
				$sql.=" AND status='publicado'";
			}


			if(!empty($this->orden)){
				switch ($this->orden) {
					case 'predeterminado':
						$sql.=" ORDER BY pd_orden ASC";
						break;
					case 'nuevo':
						$sql.=" ORDER BY ordennew ASC, pd_orden ASC";
						break;
					case 'novedad':
						$sql.=" ORDER BY ordennovedad ASC, pd_orden ASC";
						break;
					case 'destacado':
						$sql.=" ORDER BY orden ASC, pd_orden ASC";
						break;
					case 'oferta':
						$sql.=" ORDER BY ordenoferta ASC, pd_orden ASC";
						break;
					case 'alpha-ascending':
						$sql.=" ORDER BY pd_titulo ASC";
						break;
					case 'alpha-descending':
						$sql.=" ORDER BY pd_titulo DESC";
						break;
					case 'price-ascending':
						$sql.=" ORDER BY pr_precio ASC";
						break;
					case 'price-descending':
						$sql.=" ORDER BY pr_precio DESC";
						break;
				}

			}

			$sql_paginado=$sql;      

            $queryPag = $this->conn->prepare($sql_paginado);
            if(!empty($this->cat)){
				$queryPag->bindParam(':cat', $this->cat);
			}
			if(!empty($this->subcat)){
				$queryPag->bindParam(':subcat', $this->subcat);
			}
			if(!empty($this->busqueda)){
                $this->busqueda = "%" . $this->busqueda . "%";
                $queryPag->bindParam(':term', $this->busqueda);
			}
            $queryPag->execute();
            $numrows = $queryPag->rowCount();

            //pagination variables
			$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
			$per_page = intval($_REQUEST['per_page']); 
			$total_pages = ceil($numrows/$per_page);
			$cantidadProd = $per_page * $page;

			$sql.=" LIMIT $cantidadProd";


			//main query to fetch the data
            $query = $this->conn->prepare($sql);
            if(!empty($this->cat)){
				$query->bindParam(':cat', $this->cat);
			}
			if(!empty($this->subcat)){
				$query->bindParam(':subcat', $this->subcat);
			}
			if(!empty($this->busqueda)){
                $this->busqueda = "%" . $this->busqueda . "%";
                $query->bindParam(':term', $this->busqueda);
			}
            $query->execute();

            if ($query->rowCount()>0) {
                while($reg = $query->fetch())
                {   
                    $this->arrGridProd['productos'][] = $this->getDatosItemProd($reg, $desc, $cuot);
                }
                if ($total_pages > 1) {
                    $this->arrGridProd['page'] = $page;
                    $this->arrGridProd['total_pages'] = $total_pages;
                }
                return $this->arrGridProd;
            } 

		}

	}


	public function getProducto($id){
            $query = $this->conn->prepare("SELECT * FROM `tbl_productos` 
            LEFT JOIN tbl_categorias ON tbl_productos.pd_categoria = tbl_categorias.ct_mla
            LEFT JOIN tbl_productos_parent ON tbl_productos.pd_id = tbl_productos_parent.pr_producto
            WHERE pd_id = ? AND status='publicado' ORDER BY pr_precio ASC LIMIT 1");
            $query->bindParam(1, $id, PDO::PARAM_INT);
            $query->execute();

            if ($query->rowCount()>0) {
                
                $desc = $this->getDescuento();
                $cuot = $this->getCuotas();

                $reg = $query->fetch();

                    $reg['precioOriginal'] = $reg['pr_precio'];

                    if ($reg['pd_descuento_especial']!=0) {
                        $reg['descuento'] = $reg['pd_descuento_especial'];
                        $descuento = ($reg['pd_descuento_especial'] * $reg['pr_precio']) / 100;
                        $reg['precioFinal'] = $reg['pr_precio'] - $descuento;
                    } else {
                        $reg['descuento'] = $desc['descuento_visual'];
                        if ($desc['status']=='activado' and $reg['pd_descuento']=='si') {
                            $descuento = ($desc['descuento'] * $reg['pr_precio']) / 100;
                            $reg['precioFinal'] = $reg['pr_precio'] - $descuento;
                            $reg['precioOriginal'] = ($reg['precioFinal']*100) / (100-$desc['descuento_visual']);
                        } else {
                            $reg['precioFinal'] = $reg['pr_precio'];
                        }
                    }
    
                    if ($cuot['cuotas'] > 0) {
                        $valorCuota = $reg['precioFinal'] / $cuot['cuotas'];
                        $reg['cantCuotas'] = $cuot['cuotas'];
                        $reg['valorCuota'] = number_format(round($valorCuota),0,',','.');
                    } else {
                        $reg['cantCuotas'] = 0;
                    }
                    $reg['precioOriginal'] = number_format(round($reg['precioOriginal']),0,',','.');
                    $reg['precioFinalSinFormat'] = $reg['precioFinal'];
                    $reg['precioFinal'] = number_format(round($reg['precioFinal']),0,',','.');
    
                return $reg;

            } else {
                return null;
            }
    }

    public function getDatosVariacion($id){

        $query = $this->conn->prepare("SELECT pr_id, pr_precio, pr_codigo, pr_stock, pd_descuento_especial, pd_descuento FROM `tbl_productos_parent` 
        INNER JOIN tbl_productos ON tbl_productos.pd_id=tbl_productos_parent.pr_producto
        WHERE pr_id=? AND pr_stock!=0");
        $query->bindParam(1, $id, PDO::PARAM_INT);
        $query->execute();

        if ($query->rowCount()>0) {
            
            $desc = $this->getDescuento();
            $cuot = $this->getCuotas();

            $reg = $query->fetch();

                $reg['precioOriginal'] = $reg['pr_precio'];

                if ($reg['pd_descuento_especial']!=0) {
                    $reg['descuento'] = $reg['pd_descuento_especial'];
                    $descuento = ($reg['pd_descuento_especial'] * $reg['pr_precio']) / 100;
                    $reg['precioFinal'] = $reg['pr_precio'] - $descuento;
                } else {
                    $reg['descuento'] = $desc['descuento_visual'];
                    if ($desc['status']=='activado' and $reg['pd_descuento']=='si') {
                        $descuento = ($desc['descuento'] * $reg['pr_precio']) / 100;
                        $reg['precioFinal'] = $reg['pr_precio'] - $descuento;
                        $reg['precioOriginal'] = ($reg['precioFinal']*100) / (100-$desc['descuento_visual']);
                        
                    } else {
                        $reg['precioFinal'] = $reg['pr_precio'];
                    }
                }

                if ($cuot['cuotas'] > 0) {
                    $valorCuota = $reg['precioFinal'] / $cuot['cuotas'];
                    $reg['cantCuotas'] = $cuot['cuotas'];
                    $reg['valorCuota'] = number_format(round($valorCuota),0,',','.');
                } else {
                    $reg['cantCuotas'] = 0;
                }
                $reg['precioOriginal'] = number_format(round($reg['precioOriginal']),0,',','.');
                $reg['precioFinal'] = number_format(round($reg['precioFinal']),0,',','.');

            return $reg;

        } else {
            return null;
        }
}

    public function getVariaciones($id){

        $query = $this->conn->prepare("SELECT * FROM `tbl_productos_parent`
		WHERE pr_producto = ? AND pr_stock!=0 AND pr_variacion!='-' ORDER BY pr_variacion ASC");
        $query->bindParam(1, $id, PDO::PARAM_STR);
        $query->execute();

        if ($query->rowCount()>0) {
            return $query->fetchAll();
        } else {
            return null;
        }
    }

    public function getImagenes($id){

        $query = $this->conn->prepare("SELECT im_800x800, im_400x400, im_padding, IF(im_externa='si', -1, im_orden) AS orden FROM `tbl_img` 
        WHERE im_producto = ? ORDER BY orden ASC");
        $query->bindParam(1, $id, PDO::PARAM_STR);
        $query->execute();

        if ($query->rowCount()>0) {
            return $query->fetchAll();
        } else {
            return null;
        }
    }


    public function getRelacionados($id,$cat,$subcat){

        unset($this->arrProd);

        $desc = $this->getDescuento();
        $cuot = $this->getCuotas();

        $this->id=$id;
		$this->cat=$cat;
		$this->subcat=$subcat;

			if ($this->subcat=='sin-cat') {
				$sql = "SELECT * FROM `tbl_productos` 
				WHERE pd_categoria='$this->cat' AND pd_id!='$this->id' AND status='publicado' ORDER BY pd_orden ASC LIMIT 16";
			} else {
				$sql = "SELECT * FROM `tbl_productos` 
				WHERE pd_categoria='$this->cat' AND pd_subcategoria='$this->subcat' AND pd_id!='$this->id' AND status='publicado' ORDER BY pd_orden ASC LIMIT 16";
			}

        $query = $this->conn->prepare($sql);
        $query->execute();

        if ($query->rowCount()>0) {
            while($reg = $query->fetch())
            {   
                $this->arrProd[] = $this->getDatosItemProd($reg, $desc, $cuot);
            }
            return $this->arrProd;
        } 
    }

	public function getDatosItemProd($reg,$desc,$cuot){

                $idProducto=$reg['pd_id'];

                $queryImg = $this->conn->prepare("SELECT im_400x400, im_padding, IF(im_externa='si', -1, im_orden) AS orden FROM `tbl_img` WHERE im_producto=? ORDER BY orden ASC LIMIT 1");
                $queryImg->bindParam(1, $idProducto, PDO::PARAM_INT);
                $queryImg->execute();

                if ($queryImg->rowCount()>0) {
                    $img = $queryImg->fetch();
                    $reg['imagen']=$img['im_400x400'];
                    $reg['padding']=$img['im_padding'];
                } else {
                    $reg['imagen'] = 'sin-imagen.jpg';
                }

                $queryPrec = $this->conn->prepare("SELECT MIN(pr_precio) as precMin FROM `tbl_productos_parent` WHERE pr_producto=? ");
                $queryPrec->bindParam(1, $idProducto, PDO::PARAM_INT);
                $queryPrec->execute();

                if ($queryPrec->rowCount()>0) {
                    $prec = $queryPrec->fetch();
                    $reg['precioOriginal'] = $prec['precMin'];
                } else {
                    $reg['precioOriginal'] = 0;
                }


                if ($reg['pd_descuento_especial']!=0) {
                    $reg['descuento'] = $reg['pd_descuento_especial'];
                    $descuento = ($reg['pd_descuento_especial'] * $prec['precMin']) / 100;
                    $reg['precioFinal'] = $prec['precMin'] - $descuento;
                } else {
                    $reg['descuento'] = $desc['descuento_visual'];
                    if ($desc['status']=='activado' and $reg['pd_descuento']=='si') {
                        $descuento = ($desc['descuento'] * $prec['precMin']) / 100;
                        $reg['precioFinal'] = $prec['precMin'] - $descuento;
						$reg['precioOriginal'] = ($reg['precioFinal']*100) / (100-$desc['descuento_visual']);

                    } else {
                        $reg['precioFinal'] = $prec['precMin'];
                    }
                }

                if ($cuot['cuotas'] > 0) {
                    $valorCuota = $reg['precioFinal'] / $cuot['cuotas'];
                    $reg['cantCuotas'] = $cuot['cuotas'];
                    $reg['valorCuota'] = number_format(round($valorCuota),0,',','.');
                } else {
                    $reg['cantCuotas'] = 0;
                }
                $reg['linkProd'] = constant('URL').'producto/'.$reg['pd_id'].'-'.Varias::crear_url($reg['pd_titulo']);
                $reg['precioOriginal'] = number_format(round($reg['precioOriginal']),0,',','.');
                $reg['precioFinal'] = number_format(round($reg['precioFinal']),0,',','.');

                return $reg;
    }

    public function insertarProceso($proceso)
	{
        $sql = $this->conn->prepare("INSERT INTO `tbl_procesos` (`pr_proceso`) VALUES (?)");
		$sql->bindParam(1, $proceso);
        $sql->execute();
	}
	

	public function parseToXML($htmlStr) 
	{ 
		$xmlStr=str_replace('<','&lt;',$htmlStr); 
		$xmlStr=str_replace('>','&gt;',$xmlStr); 
		$xmlStr=str_replace('"','&quot;',$xmlStr); 
		$xmlStr=str_replace("'",'&apos;',$xmlStr); 
		$xmlStr=str_replace("&",'&amp;',$xmlStr); 
		$xmlStr=str_replace("ø",'',$xmlStr); 
		$xmlStr=str_replace("°",'',$xmlStr); 
		$xmlStr=str_replace("&nbsp;",'',$xmlStr); 
		return $xmlStr; 
	}


	public function productosFeed()
	{
        unset($this->arrProd);

        $desc = $this->getDescuento();

		$sql = "SELECT pd_id, pd_titulo, pd_caracteristicas, pd_marca, pd_descuento, pd_descuento_especial, ct_titulo FROM `tbl_productos`
        INNER JOIN tbl_categorias ON tbl_productos.pd_categoria=tbl_categorias.ct_mla
        INNER JOIN tbl_marcas ON tbl_productos.pd_marca=tbl_marcas.mc_marca
        WHERE status='publicado' AND tbl_marcas.feed='publicado' ";


        $query = $this->conn->prepare($sql);
        $query->execute();

        if ($query->rowCount()>0) {
            while($reg = $query->fetch())
            {   
                $idProducto=$reg['pd_id'];

                $queryImg = $this->conn->prepare("SELECT im_400x400, im_padding, IF(im_externa='si', -1, im_orden) AS orden FROM `tbl_img` WHERE im_producto=? ORDER BY orden ASC LIMIT 1");
                $queryImg->bindParam(1, $idProducto, PDO::PARAM_INT);
                $queryImg->execute();

                if ($queryImg->rowCount()>0) {
                    $img = $queryImg->fetch();
                    $reg['imagen']=$img['im_400x400'];
                    $reg['padding']=$img['im_padding'];
                } else {
                    $reg['imagen'] = 'sin-imagen.jpg';
                }

                $queryPrec = $this->conn->prepare("SELECT MIN(pr_precio) as precMin FROM `tbl_productos_parent` WHERE pr_producto=? ");
                $queryPrec->bindParam(1, $idProducto, PDO::PARAM_INT);
                $queryPrec->execute();

                if ($queryPrec->rowCount()>0) {
                    $prec = $queryPrec->fetch();
                    $reg['precioOriginal'] = $prec['precMin'];
                } else {
                    $reg['precioOriginal'] = 0;
                }


                if ($reg['pd_descuento_especial']!=0) {
                    $reg['descuento'] = $reg['pd_descuento_especial'];
                    $descuento = ($reg['pd_descuento_especial'] * $prec['precMin']) / 100;
                    $reg['precioFinal'] = $prec['precMin'] - $descuento;
                } else {
                    $reg['descuento'] = $desc['descuento_visual'];
                    if ($desc['status']=='activado' and $reg['pd_descuento']=='si') {
                        $descuento = ($desc['descuento'] * $prec['precMin']) / 100;
                        $reg['precioFinal'] = $prec['precMin'] - $descuento;
                        $reg['precioOriginal'] = ($reg['precioFinal']*100) / (100-$desc['descuento_visual']);
                    } else {
                        $reg['precioFinal'] = $prec['precMin'];
                    }
                }

                $reg['linkProd'] = constant('URL').'producto/'.$reg['pd_id'].'-'.Varias::crear_url($reg['pd_titulo']);
                $reg['precioOriginal'] = number_format(round($reg['precioOriginal']),0,'.','').' ARS';
                $reg['precioFinal'] = number_format(round($reg['precioFinal']),0,'.','').' ARS';

                $this->arrProd[] = $reg;
            }
            return $this->arrProd;
        } 

	}

	
}

 
?>