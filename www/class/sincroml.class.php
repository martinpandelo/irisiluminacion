<?php

class Utilidades
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


class sincroML {
 
    private $conn;
 
    function __construct() {
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
		$this->conn = $db->connect();
    }


    public function DatosMeli(){

        $query = $this->conn->prepare("SELECT * FROM `tbl_meli`");
        $query->execute();

            if ($query->rowCount()>0) {
                return $query->fetch();
            } else {
                return null;
            }
    }

    public function ActualizaToken($new_token,$new_refresh_token) {

        $sql = $this->conn->prepare("UPDATE `tbl_meli` SET `ml_token`=?,`ml_refresh_token`=? WHERE ml_id=1 ");
		$sql->bindParam(1, $new_token);
		$sql->bindParam(2, $new_refresh_token);

		return $sql->execute();
	}

    public function CargarNotificaciones($item,$mla,$fecha) {

        $sql = $this->conn->prepare("INSERT INTO `tbl_notificaciones` (`nt_item`,`nt_mla`,`nt_fecha`) VALUES (?,?,?)");
		$sql->bindParam(1, $item);
		$sql->bindParam(2, $mla);
		$sql->bindParam(3, $fecha);
		return $sql->execute();
	}

    private $arrNot=array();

	public function ConsultaNotificaciones(){

        unset($this->arrNot);

        $sql = "SELECT DISTINCT(nt_item) FROM `tbl_notificaciones` WHERE `nt_estado`='pendiente' AND `nt_item` NOT LIKE '%prices%'";
        $query = $this->conn->prepare($sql);
        $query->execute();

        if ($query->rowCount()>0) {
            while($reg = $query->fetch())
            {   
                $this->arrNot[] = $reg;
            }
            return $this->arrNot;
        } 
    }


	public function ActualizarTodosProductos() {

        $query = $this->conn->prepare("SELECT pd_codigo_mla FROM `tbl_productos` WHERE status='publicado'");

        if ($query->execute()) {
            if ($query->rowCount() > 0) {
                while($row = $query->fetch())
                {
                    $item='/items/'.$row['pd_codigo_mla'];

                    $sql = $this->conn->prepare("INSERT INTO `tbl_notificaciones`(`nt_item`) VALUES (?)");
                    $sql->bindParam(1, $item);
                    $sql->execute();
                }
            } 
		} 

	}

    private $idCat;
	private $nombreCat;

    public function altaCategorias($idCat,$nombreCat)
	{ 
		$this->idCat = $idCat;
		$this->nombreCat = $nombreCat;
		
		$query = $this->conn->prepare("SELECT `ct_id` FROM `tbl_categorias` WHERE `ct_mla` = ? ");
		$query->bindParam(1, $this->idCat);

        if ($query->execute()) {
            if ($query->rowCount() == 0) {
                $alias=Utilidades::crear_url($nombreCat);
                $sql = $this->conn->prepare("INSERT INTO `tbl_categorias`(`ct_alias`,`ct_titulo`,`ct_mla`,`ct_orden`) VALUES (?,?,?,0)");
				$sql->bindParam(1, $alias);
				$sql->bindParam(2, $this->nombreCat);
				$sql->bindParam(3, $this->idCat);
				return $sql->execute();
            } 
		} 
	}

	public function CargarItems($idMLA,$thumbnail,$title,$description,$caracteristicas,$marca,$categoria,$subcategoria,$disponibilidad,$estado) {

        $sql = $this->conn->prepare("INSERT INTO `tbl_productos` (`pd_codigo_mla`, `pd_thumbnail`, `pd_titulo`, `pd_descripcion`, `pd_caracteristicas`, `pd_marca`, `pd_categoria`, `pd_subcategoria`, `pd_disponibilidad`, `pd_etiqueta`, `pd_destacado`, `pd_orden_dest`, `status`, `pd_orden`) VALUES (?,?,?,?,?,?,?,?,?,'normal','no',0,?,0)");
		$sql->bindParam(1, $idMLA);
		$sql->bindParam(2, $thumbnail);
		$sql->bindParam(3, $title);
        $sql->bindParam(4, $description);
        $sql->bindParam(5, $caracteristicas);
        $sql->bindParam(6, $marca);
        $sql->bindParam(7, $categoria);
        $sql->bindParam(8, $subcategoria);
        $sql->bindParam(9, $disponibilidad);
        $sql->bindParam(10, $estado);
		return $sql->execute();
	}

	public function ActualizarItems($idProd,$thumbnail,$description,$caracteristicas,$marca,$disponibilidad,$estado) {

        $sql = $this->conn->prepare("UPDATE `tbl_productos` SET `pd_thumbnail`=?,`pd_descripcion`=?,`pd_caracteristicas`=?,`pd_marca`=?,`pd_disponibilidad`=?,`status`=? WHERE pd_id=?");
		$sql->bindParam(1, $thumbnail);
		$sql->bindParam(2, $description);
        $sql->bindParam(3, $caracteristicas);
        $sql->bindParam(4, $marca);
        $sql->bindParam(5, $disponibilidad);
        $sql->bindParam(6, $estado);
        $sql->bindParam(7, $idProd);
		return $sql->execute();
	}

	public function ConsultaItem($idMLA) {
        $query = $this->conn->prepare("SELECT pd_id FROM `tbl_productos` WHERE pd_codigo_mla = ? ");
		$query->bindParam(1, $idMLA, PDO::PARAM_INT);

        if ($query->execute()) {
            if ($query->rowCount()>0) {
                $row = $query->fetch();
				return $row['pd_id'];
            }
		} 
	}

	public function CargarFotos($idProd,$foto400x400,$foto800x800,$orden,$idFoto,$padding) {

        $query = $this->conn->prepare("SELECT im_400x400 FROM tbl_img WHERE im_producto = ? AND im_400x400 = ? ");
		$query->bindParam(1, $idProd);
        $query->bindParam(2, $foto400x400);

        if ($query->execute()) {
            if ($query->rowCount() == 0) {
                $sql = $this->conn->prepare("INSERT INTO `tbl_img`(`im_800x800`,`im_400x400`, `im_producto`, `im_orden`, `im_id_ml`, `im_padding`) VALUES (?,?,?,?,?,?)");
				$sql->bindParam(1, $foto800x800);
				$sql->bindParam(2, $foto400x400);
				$sql->bindParam(3, $idProd);
                $sql->bindParam(4, $orden);
                $sql->bindParam(5, $idFoto);
                $sql->bindParam(6, $padding);
				return $sql->execute();
            } 
		}
	}

	public function CargarVariaciones($idProd,$codigo,$price,$variacion,$stock,$foto,$sku) {

        $sql = $this->conn->prepare("INSERT INTO `tbl_productos_parent`(`pr_producto`, `pr_codigo`, `pr_precio`, `pr_variacion`, `pr_stock`, `pr_foto`, `pr_sku`) VALUES (?,?,?,?,?,?,?)");
		$sql->bindParam(1, $idProd);
		$sql->bindParam(2, $codigo);
		$sql->bindParam(3, $price);
        $sql->bindParam(4, $variacion);
        $sql->bindParam(5, $stock);
        $sql->bindParam(6, $foto);
        $sql->bindParam(7, $sku);
		return $sql->execute();
	}
	public function VerificarIdCarro($idProd) {

        $query = $this->conn->prepare("SELECT * FROM `tbl_cart` WHERE producto_id = ? ");
		$query->bindParam(1, $idProd, PDO::PARAM_INT);

        if ($query->execute()) {
            if ($query->rowCount()>0) {
                return true;
            }
		} 
	}
	public function BorraVariaciones($idProd) {
        $query = $this->conn->prepare("DELETE FROM `tbl_productos_parent` WHERE `pr_producto` = ? ");
		$query->bindParam(1, $idProd);
        return $query->execute();
	}
	public function BorraFotos($idProd) {
        $query = $this->conn->prepare("DELETE FROM `tbl_img` WHERE `im_producto` = ? AND im_externa='no' ");
		$query->bindParam(1, $idProd);
        return $query->execute();
	}
	public function notificacionProcesada($noti) {
        $sql = $this->conn->prepare("UPDATE `tbl_notificaciones` SET `nt_estado`='procesada' WHERE nt_item = ? ");
		$sql->bindParam(1, $noti);
		return $sql->execute();
	}

    public function procesarMarcas() {

        $query = $this->conn->prepare("SELECT DISTINCT `pd_marca` FROM `tbl_productos` WHERE status='publicado'");
		if ($query->execute()) {

                if ($query->rowCount() > 0) {
                    while($row = $query->fetch())
                    {
                        $nomMarca = $row['pd_marca'];
    
                        $sql = $this->conn->prepare("SELECT `mc_marca` FROM `tbl_marcas` WHERE `mc_marca` = ?");
                        $sql->bindParam(1, $nomMarca);
                        $sql->execute();
                        if ($sql->rowCount() == 0) {
                            $sql = $this->conn->prepare("INSERT INTO `tbl_marcas`(`mc_marca`) VALUES (?)");
                            $sql->bindParam(1, $nomMarca);
                            $sql->execute();
                        }
                    }
                } 

		} else {
			return false;
		}
	}
	
}

 
?>