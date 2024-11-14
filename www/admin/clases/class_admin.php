<?php 

abstract class Conexion
{
	public function con()
	{	
		//$puntero=new mysqli('localhost', 'root', 'root', 'bd_irisilum');     //DATOS DE CONEXION LOCAL
		$puntero=new mysqli('localhost', 'fulmkodp_webmaster', 'h9[P}e_Cd2cq', 'fulmkodp_iristienda');      //DATOS DE CONEXION SERVIDOR
		$puntero->set_charset("utf8");
		return $puntero;
	}
}


class LoginAdmin extends Conexion
{
	private $user_admin;
	private $pass_admin;
	private $arr_administradores=array(); 
	
	public function esAdmin($ad_usuario, $ad_password){
		
		$ad_usuario = mysqli_real_escape_string(parent::con(), $ad_usuario);
		$ad_password = mysqli_real_escape_string(parent::con(), $ad_password);
		
		$this->user_admin=$ad_usuario;
		$this->pass_admin=$ad_password;
		
		$result=mysqli_query(parent::con(),"SELECT * FROM administradores WHERE ad_usuario='$this->user_admin' ");
		
			
			$this->arr_administradores = $result->fetch_array();
			
			$password_from_db = $this->arr_administradores['ad_password'];
			
			if ( $password_from_db == $this->pass_admin ) {
				return $this->arr_administradores;
			} else return false;

	}
}

class Varias
{
	protected static $texto;
	protected static $filtro;
	protected static $titulo;
	
	public static function limitar_caracteres($text)
	{
		self::$texto=$text;
		if (strlen(self::$texto)>120) {
    		self::$texto = wordwrap(self::$texto, 120, '<|*|*|>'); // separar en $max_long con ruptura sin cortar palabras. 
    		$posicion = strpos(self::$texto, '<|*|*|>'); // encontrar la primera aparición de la ruptura. 
    		self::$texto = substr(self::$texto, 0, $posicion).'...'; // tomar la porción antes de la ruptura y agregar '...' 
		}
    	return self::$texto;
	}
	
	public static function crear_url($tit)
	{
		self::$titulo=mb_strtolower($tit, 'UTF-8');
		self::$titulo=str_replace("-","",self::$titulo);
		self::$titulo=str_replace(" ","-",self::$titulo);
		$caracteres_raros = array("ã","à","â","ç","ê","õ","ô","Ã","À","Â","Ç","Ê","Õ","Ô","á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","Ñ","ü","Ü","'",'"',"(",")","?","¿","¡","!","º",",",".",":",";","/");
		$caracteres_remp = array("a","a","a","c","e","o","o","A","A","A","C","E","O","O","a","e","i","o","u","A","E","I","O","U","n","N","u","U","","","","","","","","","","","","","","-");
 		return str_replace($caracteres_raros, $caracteres_remp, self::$titulo);
	}
	
	public static function nombreFotos($str)
	{
		self::$texto=mb_strtolower($str, 'UTF-8');
		self::$texto=str_replace(" ","",self::$texto);
		$caracteres_raros = array("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","Ñ","ü","Ü","'",'"',"(",")","?","¿","¡","!","º",",",".",":",";","/");
		$caracteres_remp = array("a","e","i","o","u","A","E","I","O","U","n","N","u","U","","","","","","","","","","","","","","-");
 		return str_replace($caracteres_raros, $caracteres_remp, self::$texto);
	}

	public static function parseToText($htmlStr) 
	{ 
		self::$texto=$htmlStr;
		self::$texto=str_replace("'",'&apos;',self::$texto); 
		return self::$texto; 
	}
	
}

class Configuracion extends Conexion
{
	public function ComboCategorias($cat)
	{
		$result=mysqli_query(parent::con(),"SELECT * FROM tbl_categorias WHERE ct_id!=0 ORDER BY ct_titulo ASC");
			echo '<option value="">Selecione la categoría</option>';
		while ($ct=$result->fetch_assoc())
		{
            echo '<option value="'.$ct["ct_mla"].'" '; if (isset($cat) and $cat==$ct["ct_mla"]) { echo 'selected'; }; echo ' >'.$ct["ct_titulo"].'</option>';
		}
	}
	public function comboProvincias($prov)
	{
		$result=mysqli_query(parent::con(),"SELECT * FROM provincias ORDER BY provincia ASC");
			echo '<option value="">Selecione la provincia</option>';
		while ($pr=$result->fetch_assoc())
		{
            echo '<option value="'.$pr["id"].'" '; if (isset($prov) and $prov==$pr["id"]) { echo 'selected'; }; echo ' >'.$pr["provincia"].'</option>';
		}
	}

	public function ComboCategoriasTable()
	{
		$query="SELECT `ct_mla`, `ct_titulo` FROM `tbl_categorias` ORDER BY ct_titulo ASC";
		$result=mysqli_query(parent::con(),"$query");
			while ($row=$result->fetch_assoc())
			{
				$output[] = $row;
			}
		return $output;
	}
	public function ComboSubCategoriasTable()
	{
		$query="SELECT `sct_mla`, `sct_titulo` FROM `tbl_subcategorias` ORDER BY sct_titulo ASC";
		$result=mysqli_query(parent::con(),"$query");
			while ($row=$result->fetch_assoc())
			{
				$output[] = $row;
			}
		return $output;
	}

	public function ObtenerVariaciones($id_producto)
	{
		$this->id=$id_producto;
		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_productos_parent` WHERE pr_producto='$this->id'");
		$row_cnt = $result->num_rows;

		if ($row_cnt>0) {
			$i=1;
			while ($pr=$result->fetch_assoc())
			{
				echo '<div class="form-inline col-sm-10 col-sm-offset-2" id="div_'.$i.'"><hr />
							
					<label for="varia" class="m-l-sm">Variación:</label>
					<input type="text" class="form-control" name="varia[]" value="'.$pr["pr_variacion"].'" size="5">

					<label for="valor" class="m-l-sm">Valor:</label>
					<input type="text" class="form-control" name="valor[]" value="'.$pr["pr_valor"].'" size="5">

					<label for="precio" class="m-l-sm">Precio:</label>
					<input type="text" class="form-control" name="precio[]" value="'.$pr["pr_precio"].'" size="8">
															
					<label for="stock" class="m-l-sm">Stock:</label>
					<input type="text" class="form-control" name="stock[]" value="'.$pr["pr_stock"].'" size="3">

					<label for="cod" class="m-l-sm">Código:</label>
					<input type="text" class="form-control" name="cod[]" value="'.$pr["pr_codigo"].'" size="12"> ';

					if ($row_cnt==$i) {
						echo '<input class="btn btn-primary bt_plus" id="'.$i.'" type="button" value="+ Agregar variación" />';
					} else {
						echo '<input class="btn btn-primary bt_menos" id="'.$i.'" type="button" value="- Quitar variación" />';
					}
					echo '<input type="hidden" class="form-control" name="cont[]" value="1" ></div>';
				$i++;
			}
		} else {
			echo '<div class="form-inline col-sm-10 col-sm-offset-2" id="div_1"><hr />

					<label for="varia" class="m-l-sm">Variación:</label>
					<input type="text" class="form-control" name="varia[]" value="" size="5">

					<label for="valor" class="m-l-sm">Valor:</label>
					<input type="text" class="form-control" name="valor[]" value="" size="5">

					<label for="precio" class="m-l-sm">Precio:</label>
					<input type="text" class="form-control" name="precio[]" value="" size="8">

					<label for="stock" class="m-l-sm">Stock:</label>
					<input type="text" class="form-control" name="stock[]" value="" size="3">

					<label for="cod" class="m-l-sm">Código:</label>
					<input type="text" class="form-control" name="cod[]" value="" size="12">

					<input class="btn btn-primary bt_plus" id="1" type="button" value="+ Agregar variación" />
					<input type="hidden" class="form-control" name="cont[]" value="1" >
				</div>';
		}
		
	}

	public function variaciones()
	{
		$arra_varia = $_POST['varia'];
		$arra_valor = $_POST['valor'];
		$arra_precio = $_POST['precio'];
		$arra_stock = $_POST['stock'];
		$arra_codigo = $_POST['cod'];
		$arr_cont = $_POST['cont'];

		$arr_length_prod = count($arr_cont);

		$a=1;
		for($i=0;$i<$arr_length_prod;$i++) {

			echo '<div class="form-inline col-sm-10 col-sm-offset-2" id="div_'.$a.'"><hr />

                <label for="varia" class="m-l-sm">Variación:</label>
                <input type="text" class="form-control" name="varia[]" value="'.$arra_varia[$i].'" size="5">

				<label for="valor" class="m-l-sm">Valor:</label>
                <input type="text" class="form-control" name="valor[]" value="'.$arra_valor[$i].'" size="5">

                <label for="precio" class="m-l-sm">Precio:</label>
                <input type="text" class="form-control" name="precio[]" value="'.$arra_precio[$i].'" size="8">

                <label for="stock" class="m-l-sm">Stock:</label>
                <input type="text" class="form-control" name="stock[]" value="'.$arra_stock[$i].'" size="3">

                <label for="cod" class="m-l-sm">Código:</label>
                <input type="text" class="form-control" name="cod[]" value="'.$arra_codigo[$i].'" size="12"> ';

                if ($arr_length_prod==$a) {
                    echo '<input class="btn btn-primary bt_plus" id="'.$a.'" type="button" value="+ Agregar variación" />';
                } else {
                    echo '<input class="btn btn-primary bt_menos" id="'.$a.'" type="button" value="- Quitar variación" />';
                }
                echo '<input type="hidden" class="form-control" name="cont[]" value="1" ></div>';
            $a++;

		}
	}

}


class accesosCategorias extends Conexion
{
	public $id;
	public $accion;
	public $imagen;

	public function lista()
	{

		$query="SELECT * FROM `tbl_categorias` WHERE ct_mostrar_home='si' ORDER BY ct_orden_home ASC";
		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				$rutaFoto = '../../img/categorias/'.$row['ct_id'].'-'.$row['ct_alias'].'.jpg'; 
				if (file_exists($rutaFoto)) {
					$row['foto']=$row['ct_id'].'-'.$row['ct_alias'].'.jpg';
				} else {
					$row['foto']="sin-imagen.jpg";
				}

				$output[] = $row;
			}
	
		echo json_encode($output);
	}

	public function editar($nombre,$value,$pk)
	{
		$data = array();
		$sql = "UPDATE `tbl_categorias` set ".$nombre." = '".$value."' WHERE ct_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {

			if ($nombre=='ct_titulo') {
				$resultAlias=mysqli_query(parent::con(),"SELECT `ct_alias`, `ct_mostrar_home` FROM `tbl_categorias` WHERE ct_id='".$pk."'");
				$row=$resultAlias->fetch_assoc();
				$aliasviejo=$row['ct_alias'];

				$alias=Varias::crear_url($value);
				$query = "UPDATE `tbl_categorias` set ct_alias='$alias' WHERE ct_id='".$pk."'";
				$result = mysqli_query(parent::con(), $query);
				if ($row['ct_mostrar_home']=='si') {
					rename ("../../img/categorias/".$pk."-".$aliasviejo.".jpg", "../../img/categorias/".$pk."-".$alias.".jpg");
				}
			}

			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}


	public function borrar($id)
	{
		$this->id=$id;

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_categorias` WHERE ct_id='$this->id'")) {
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

	public function listaOrdenCategorias()
	{

		$result=mysqli_query(parent::con(),"SELECT * FROM tbl_categorias 
		WHERE ct_mostrar_home='si' ORDER BY ct_orden_home ASC");
		
		$i=1;
		while ($reg=$result->fetch_assoc())
		{
			echo '<li class="ui-state-default" id="elemento-'.$reg["ct_id"].'"><img style="width:100px; height: 100px;" src="../img/categorias/'.$reg['ct_id'].'-'.$reg['ct_alias'].'.jpg"/></li>';
			$i++;
		}
	}
	public function reordenarCategorias($id, $orden)
	{
		$result=mysqli_query(parent::con(),"UPDATE tbl_categorias SET ct_orden_home = '$orden' WHERE ct_id = '$id' ");
	}
}

class Categorias extends Conexion
{
	public $id;
	public $accion;
	private $output=array();

	public function lista()
	{

		$query="SELECT * FROM `tbl_categorias` WHERE ct_id!=0 ORDER BY ct_orden ASC";
		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				$categoria = $row["ct_mla"];
				$resultProd=mysqli_query(parent::con(),"SELECT COUNT(pd_id) as cantProd FROM tbl_productos WHERE pd_categoria='$categoria'");
				$reg=$resultProd->fetch_assoc();
				$row['cantProd'] = $reg['cantProd'];

				$output[] = $row;
			}
	
		echo json_encode($output);
	}

	public function agregar()
	{
		// definimos las variables
		if ( !empty($_POST['campo1']) )			$campo1 = $_POST['campo1']; else return 'Ingrese el nombre de la categoría';

		$alias=Varias::crear_url($campo1);
		$codCategoria = 'IC'.rand(0,8000);
		if (mysqli_query(parent::con(),"INSERT INTO `tbl_categorias`(`ct_alias`,`ct_titulo`,`ct_mla`) VALUES ('$alias','$campo1','$codCategoria')")) {
			return 'agregado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

	public function editar($nombre,$value,$pk)
	{
		$data = array();
		$sql = "UPDATE `tbl_categorias` set ".$nombre." = '".$value."' WHERE ct_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {

			if ($nombre=='ct_titulo') {

				$alias=Varias::crear_url($value);

				$query = "UPDATE `tbl_categorias` set ct_alias='$alias' WHERE ct_id='".$pk."'";
				$result = mysqli_query(parent::con(), $query);
			}

			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}


	public function borrar($id)
	{
		$this->id=$id;

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_categorias` WHERE ct_id='$this->id'")) {
			return 'eliminado';
		} else {
			return 'Ocurrió un error, intente nuevamente';
		}
	}

	public function listaOrdenCategorias()
	{

		$result=mysqli_query(parent::con(),"SELECT * FROM tbl_categorias WHERE ct_id!=0 ORDER BY ct_orden ASC");
		$i=1;
		while ($reg=$result->fetch_assoc())
		{
			echo '<li class="ui-state-hover" id="elemento-'.$reg["ct_id"].'">'.$reg["ct_titulo"].'</li>';
			$i++;
		}
	}
	public function reordenarCategorias($id, $orden)
	{
		$result=mysqli_query(parent::con(),"UPDATE tbl_categorias SET ct_orden = '$orden' WHERE ct_id = '$id' ");
	}
	
}


class subCategorias extends Conexion
{
	public $id;
	public $accion;
	public $categoria;
	private $output=array();

	public function lista($cat)
	{
		$this->categoria=$cat;

		$query="SELECT * FROM `tbl_subcategorias`
		INNER JOIN tbl_categorias ON tbl_categorias.ct_mla=tbl_subcategorias.sct_categoria 
		WHERE sct_categoria='$this->categoria' AND sct_id!=0 ORDER BY sct_orden ASC";
		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				$output[] = $row;
			}
	
		echo json_encode($output);
	}

	public function editar($nombre,$value,$pk)
	{
		$data = array();
		$sql = "UPDATE `tbl_subcategorias` set ".$nombre." = '".$value."' WHERE sct_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {

			if ($nombre=='sct_titulo') {

				$alias=Varias::crear_url($value);

				$query = "UPDATE `tbl_subcategorias` set sct_alias='$alias' WHERE sct_id='".$pk."'";
				$result = mysqli_query(parent::con(), $query);
			}

			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}

	public function listaOrdenSubcategorias($cat)
	{
		$this->categoria=$cat;

		$result=mysqli_query(parent::con(),"SELECT * FROM tbl_subcategorias WHERE sct_categoria='$this->categoria' AND sct_id!=0 ORDER BY sct_orden ASC");
		$i=1;
		while ($reg=$result->fetch_assoc())
		{
			echo '<li class="ui-state-hover" id="elemento-'.$reg["sct_id"].'">'.$reg["sct_titulo"].'</li>';
			$i++;
		}
	}
	public function reordenarSubcategorias($id, $orden)
	{
		$result=mysqli_query(parent::con(),"UPDATE tbl_subcategorias SET sct_orden = '$orden' WHERE sct_id = '$id' ");
	}
	
}



class Productos extends Conexion
{
	public $id;
	public $linea;
	public $orden;
	public $imagen;
	public $imagen_ficha;
	private $output=array();
	public $proceso;
	public $categoria;

	public function lista($cat)
	{
		$this->categoria=$cat;

		$query="SELECT pd_id, pd_codigo_mla, pd_thumbnail, pd_titulo, pd_marca, ct_titulo, ct_mla, pd_etiqueta, pd_destacado, pd_new, pd_descuento, pd_descuento_especial, pd_categoria_envio, pd_bulto_envio, status, pd_orden FROM  `tbl_productos`
		LEFT JOIN tbl_categorias ON tbl_productos.pd_categoria=tbl_categorias.ct_mla
		LEFT JOIN tbl_subcategorias ON tbl_subcategorias.sct_mla=tbl_productos.pd_subcategoria";

		if (!empty($this->categoria)) {
			$query.=" WHERE pd_categoria='$this->categoria'";
		}

		$query.=" ORDER BY pd_id DESC";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				if (empty($row['pd_thumbnail'])) {
					$row['pd_thumbnail']='../img/sin-imagen.jpg';
				}
				$output[] = $row;
			}
	
		echo json_encode($output);
	}

	public function editar($nombre,$value,$pk)
	{
		$data = array();
		$sql = "UPDATE `tbl_productos` set ".$nombre." = '".$value."' WHERE pd_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}

	public function Traer($id)
	{
		$this->id=$id;

		$query="SELECT * FROM `tbl_productos` WHERE pd_id='$this->id' ";
		$result=mysqli_query(parent::con(),"$query");

		return $result->fetch_assoc();
	}

	public function Agregar()
	{
		
		// definimos las variables
		if ( !empty($_POST['nombre']) )			$nombre = $_POST['nombre']; else return 'Ingrese título';
		if ( !empty($_POST['categoria']) )			$categoria = $_POST['categoria']; else return 'Seleccione la categoría';
		if ( !empty($_POST['descripcion']) )			$descripcion = $_POST['descripcion']; else return 'Ingrese descripción';
		$descripcion=Varias::parseToText($descripcion);

		$caracteristicas = $_POST['caracteristicas'];
		$caracteristicas=Varias::parseToText($caracteristicas);

		if ( !empty($_POST['destacado']) ) 			$destacado = $_POST['destacado']; else $destacado = 'no';
		if ( !empty($_POST['estado']) ) 			$estado = $_POST['estado']; else return 'Seleccione el estado de la publicación';

				
		$alias=Varias::crear_url($nombre);
		$idMLA='MLA000';
		$subcategoria='sin-cat';
		$disponibilidad='inmediata';

		$variaVacio=false;


		$arra_varia = $_POST['varia'];
		$arra_valor = $_POST['valor'];
		$arra_precio = $_POST['precio'];
		$arra_stock = $_POST['stock'];
		$arra_cod = $_POST['cod'];

		if (isset($_POST['cont'])) { 
			$arra_prod = $_POST['cont'];
			$arr_length_pro = count($arra_prod);
			for($i=0;$i<$arr_length_pro;$i++)
				{
					if (empty($arra_varia[$i]) || empty($arra_valor[$i]) || empty($arra_precio[$i]) || empty($arra_stock[$i]) || empty($arra_cod[$i])) {
						$variaVacio=true;
					}
				}
		}

		if ($variaVacio) {
			return 'error en las variaciones';
		}

		$query="INSERT INTO `tbl_productos`(`pd_codigo_mla`, `pd_titulo`, `pd_descripcion`, `pd_caracteristicas`, `pd_categoria`, `pd_subcategoria`, `pd_disponibilidad`, `pd_etiqueta`, `pd_destacado`, `pd_orden_dest`, `status`, `pd_orden`) 
		VALUES ('$idMLA','$nombre','$descripcion','$caracteristicas','$categoria','$subcategoria','$disponibilidad','normal','no',0,'$estado',0)";

		if (mysqli_query(parent::con(),"$query")) {

			$result=mysqli_query(parent::con(),"SELECT pd_id FROM tbl_productos WHERE pd_codigo_mla='$idMLA' ORDER BY pd_id DESC LIMIT 1");
			$nid=$result->fetch_assoc();
				
			$id_new=$nid["pd_id"];
	
			if (!$variaVacio) {
				for($i=0;$i<$arr_length_pro;$i++)
				{
					mysqli_query(parent::con(),"INSERT INTO `tbl_productos_parent`(`pr_producto`, `pr_codigo`, `pr_precio`, `pr_variacion`, `pr_valor`, `pr_stock`) VALUES ('$id_new','$arra_cod[$i]','$arra_precio[$i]','$arra_varia[$i]','$arra_valor[$i]','$arra_stock[$i]')");
				}
			}

			return 'agregado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}

	}
	
	public function EditarProducto($id)
	{
		$this->id=$id;

		// definimos las variables
		if ( !empty($_POST['nombre']) )			$nombre = $_POST['nombre']; else return 'Ingrese título';
		if ( !empty($_POST['categoria']) )			$categoria = $_POST['categoria']; else return 'Seleccione la categoría';
		if ( !empty($_POST['descripcion']) )			$descripcion = $_POST['descripcion']; else return 'Ingrese descripción';
		$descripcion=Varias::parseToText($descripcion);
		if ( !empty($_POST['peso']) )			$peso = $_POST['peso']; else return 'ingrese el peso';
		$descuento = $_POST['descuento'];
		if ( !empty($_POST['destacado']) ) 			$destacado = $_POST['destacado']; else $destacado = 'no';
		if ( !empty($_POST['exclusivo']) ) 			$exclusivo = $_POST['exclusivo']; else $exclusivo = 'no';
		if ( !empty($_POST['estado']) ) 			$estado = $_POST['estado']; else return 'Seleccione el estado de la publicación';

		$alias=Varias::crear_url($nombre);

		$variaVacio=false;

		$arra_varia = $_POST['varia'];
		$arra_valor = $_POST['valor'];
		$arra_precio = $_POST['precio'];
		$arra_stock = $_POST['stock'];
		$arra_cod = $_POST['cod'];

		if (isset($_POST['cont'])) { 
			$arra_prod = $_POST['cont'];
			$arr_length_pro = count($arra_prod);
			for($i=0;$i<$arr_length_pro;$i++)
				{
					if (empty($arra_varia[$i]) || empty($arra_valor[$i]) || empty($arra_precio[$i]) || empty($arra_stock[$i]) || empty($arra_cod[$i])) {
						$variaVacio=true;
					}
				}
		}

		if ($variaVacio) {
			return 'error en las variaciones';
		}

		mysqli_query(parent::con(),"DELETE FROM `tbl_productos_parent` WHERE `pr_producto`='$this->id'");


		if (!$variaVacio) {
			for($i=0;$i<$arr_length_pro;$i++)
			{
				mysqli_query(parent::con(),"INSERT INTO `tbl_productos_parent`(`pr_producto`, `pr_codigo`, `pr_precio`, `pr_variacion`, `pr_valor`, `pr_stock`) VALUES ('$this->id','$arra_cod[$i]','$arra_precio[$i]','$arra_varia[$i]','$arra_valor[$i]','$arra_stock[$i]')");
			}
		}


		$query = "UPDATE `tbl_productos` set `pd_alias`='$alias', `pd_titulo`='$nombre',`pd_descripcion`='$descripcion',`pd_categoria`='$categoria',`pd_peso`='$peso',`pd_destacado`='$destacado',`pd_exclusivo`='$exclusivo',`pd_descuento`='$descuento',`status`='$estado' WHERE pd_id='$this->id'";
		
		if($result = mysqli_query(parent::con(), $query)) {
			return 'agregado';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}

	public function EditarDescripcion($id)
	{
		$this->id=$id;

		// definimos las variables
		if ( !empty($_POST['descripcion']) )			$descripcion = $_POST['descripcion']; else return 'Ingrese descripción';
		$descripcion=Varias::parseToText($descripcion);

		$query = "UPDATE `tbl_productos` set `pd_descripcion_editable`='$descripcion' WHERE pd_id='$this->id'";
		
		if($result = mysqli_query(parent::con(), $query)) {
			return 'agregado';
		} else {
			return 'Ocurrió un error';
		}
	}


	public function listaOrdenDestaques()
	{

		$result=mysqli_query(parent::con(),"SELECT * FROM tbl_productos 
		WHERE pd_destacado='si' ORDER BY pd_orden_dest ASC");
		
		$i=1;
		while ($reg=$result->fetch_assoc())
		{
			echo '<li class="ui-state-default" id="elemento-'.$reg["pd_id"].'"><img style="width:100px; height: 100px;" src="'.$reg['pd_thumbnail'].'"/></li>';
			$i++;
		}
	}
	public function reordenarDestaques($id, $orden)
	{
		mysqli_query(parent::con(),"UPDATE tbl_productos SET pd_orden_dest = '$orden' WHERE pd_id = '$id' ");
	}

	public function listaOrdenNuevos()
	{

		$result=mysqli_query(parent::con(),"SELECT * FROM tbl_productos 
		WHERE pd_new='si' ORDER BY pd_orden_new ASC");
		
		$i=1;
		while ($reg=$result->fetch_assoc())
		{
			echo '<li class="ui-state-default" id="elemento-'.$reg["pd_id"].'"><img style="width:100px; height: 100px;" src="'.$reg['pd_thumbnail'].'"/></li>';
			$i++;
		}
	}
	public function reordenarNuevos($id, $orden)
	{
		mysqli_query(parent::con(),"UPDATE tbl_productos SET pd_orden_new = '$orden' WHERE pd_id = '$id' ");
	}

	public function listaOrdenOfertas()
	{

		$result=mysqli_query(parent::con(),"SELECT * FROM tbl_productos 
		WHERE pd_etiqueta='oferta' ORDER BY pd_orden_oferta ASC");
		
		$i=1;
		while ($reg=$result->fetch_assoc())
		{
			echo '<li class="ui-state-default" id="elemento-'.$reg["pd_id"].'"><img style="width:100px; height: 100px;" src="'.$reg['pd_thumbnail'].'"/></li>';
			$i++;
		}
	}

	public function reordenarOfertas($id, $orden)
	{
		mysqli_query(parent::con(),"UPDATE tbl_productos SET pd_orden_oferta = '$orden' WHERE pd_id = '$id' ");
	}

	public function listaOrdenProductos()
	{
		$result=mysqli_query(parent::con(),"SELECT * FROM tbl_productos 
		WHERE status='publicado' ORDER BY pd_orden ASC, pd_id DESC");
		
		$i=1;
		while ($reg=$result->fetch_assoc())
		{
			echo '<li class="ui-state-default" id="elemento-'.$reg["pd_id"].'"><img style="width:80px; height: 80px;" src="'.$reg['pd_thumbnail'].'"/></li>';
			$i++;
		}
	}

	public function reordenarProductos($id, $orden)
	{
		mysqli_query(parent::con(),"UPDATE tbl_productos SET pd_orden = '$orden' WHERE pd_id = '$id' ");
	}
	

	public function ultimoProceso($proceso)
	{
		$this->proceso=mysqli_real_escape_string(parent::con(), $proceso);
		$result=mysqli_query(parent::con(),"SELECT MAX(pr_fecha) as fecha FROM `tbl_procesos` WHERE pr_proceso='$this->proceso' ");
		$reg=$result->fetch_assoc();

		echo $reg['fecha'];
	}

	public function sincronizarTodosProductos() {
		if ($result=mysqli_query(parent::con(),"SELECT pd_codigo_mla FROM `tbl_productos` WHERE status='publicado'")) {
			$row_cnt = $result->num_rows;
			if ($row_cnt>0) {
				while($row=$result->fetch_assoc()) {
					$item='/items/'.$row['pd_codigo_mla'];
					mysqli_query(parent::con(),"INSERT INTO `tbl_notificaciones`(`nt_item`) VALUES ('$item')");
				}
				return true;
			} else {
				return false;
			}

		} else {
			return false;
		}
	}

	private $arr_n_fotos=array();

	public function BotonesImgProd($id_prod) {

		$this->id=$id_prod;
		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_img` WHERE im_producto='$this->id' ORDER BY im_orden ASC");
		$row_cnt=$result->num_rows;

		$_SESSION['id_producto']=$this->id;

		$result_prod=mysqli_query(parent::con(),"SELECT pd_id, pd_titulo FROM `tbl_productos` WHERE pd_id='$this->id'");
		$prod=$result_prod->fetch_assoc();

		if($row_cnt==0){
			$nom_foto=$prod['pd_id'].'-iris-'.Varias::crear_url($prod['pd_titulo']).'-'.date('YmdHms'); 

			echo '<p class="alert alert-danger">No hay fotos aplicadas a este producto</p><br><a href="upload_crop.php?nom_fot='.$nom_foto.'&orden=1" class="btn btn-danger btn-addon btn-rounded btn-lg"><i class="fa fa-plus"></i> Agregar foto</a><hr>';
		} else {

			$ft=1;
			while($reg=$result->fetch_assoc()) {

					$info = pathinfo($reg["im_400x400"]);
					$nom_foto =  basename($reg["im_400x400"],'.'.$info['extension']);

					$n_foto=strrchr($nom_foto,"_");
					if ($n_foto) {
						$n_foto = substr($n_foto, 1);
					} else {
						$n_foto=0;
					}
					$arr_n_fotos[]=$n_foto;

            	$ft++;
			}

			$ft=max($arr_n_fotos)+1;
			$nom_foto=$prod['pd_id'].'-iris-'.Varias::crear_url($prod['pd_titulo']).'-'.date('YmdHms').'_'.$ft; 
			$orden=$ft+1;
			echo '<a href="upload_crop.php?nom_fot='.$nom_foto.'&orden='.$orden.'" class="btn btn-success btn-addon btn-rounded btn-lg"><i class="fa fa-plus"></i> Agregar otra foto</a><hr>';
		}
	}

	public function ImagenesProd($id_prod) {

		$this->id=$id_prod;
		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_img` WHERE im_producto='$this->id' AND im_externa='si' ORDER BY im_orden ASC");
		$row_cnt=$result->num_rows;

		if($row_cnt>0){

			while($row=$result->fetch_assoc()) {

				$info = pathinfo($row["im_400x400"]);
				$nom_foto =  basename($row["im_400x400"],'.'.$info['extension']);
                $row['nombreFot']=$nom_foto;

				$output[] = $row;
			}

			echo json_encode($output);
		}
	}

	public function ImagenPrincipal($id_prod) {

		$this->id=$id_prod;
		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_img` WHERE im_producto='$this->id' ORDER BY im_orden ASC LIMIT 1");
		$row_cnt=$result->num_rows;

		if($row_cnt>0){

			while($row=$result->fetch_assoc()) {

				echo '<img id="thumbnil" src="'.$row["im_400x400"].'" class="img-thumbnail" width="100%"/>';

			}

		}
	}

	public function editarOrdenFoto($nombre,$value,$pk)
	{
		$data = array();
		$sql = "UPDATE `tbl_img` set ".$nombre." = '".$value."' WHERE im_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}

	public function gestionImg($img,$id_prod,$orden) {
		
		$this->imagen=HTTP_SERVER.'img/productos/'.$img;
		$this->imagen_ficha=HTTP_SERVER.'img/productos/fichas/'.$img;
		$this->id=$id_prod;
		$this->orden=$orden;
			
		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_img` WHERE im_producto='$this->id' AND im_400x400='$this->imagen'");
		$row_cnt=$result->num_rows;
		
			if($row_cnt==0) {
				mysqli_query(parent::con(),"INSERT INTO `tbl_img`(`im_800x800`, `im_400x400`, `im_producto`, `im_orden`, `im_externa`) VALUES ('$this->imagen_ficha','$this->imagen','$this->id','$this->orden','si')");
			}

			if ($this->orden==1) {
				mysqli_query(parent::con(),"UPDATE tbl_productos SET pd_thumbnail = '$this->imagen' WHERE pd_id = '$this->id' ");
			}

	}


	public function borrarImg($img) {
		$this->imagen=$img;

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_img` WHERE im_400x400='$this->imagen'")) {

			unlink("../img/productos/".$this->imagen);
			unlink("../img/productos/fichas/".$this->imagen);
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

}



class Slides extends Conexion
{	
	
	public $id;
	public $accion;
	public $imagen;

	public function gestionImg($img) {
		
		$this->imagen=$img;

		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_slides` WHERE sl_nombre='$this->imagen'");
		$row_cnt=$result->num_rows;
		
			if($row_cnt==0) {
				mysqli_query(parent::con(),"INSERT INTO `tbl_slides`(`sl_nombre`) VALUES ('$this->imagen')");
			}
	}

	public function getEmployees()
	{

		$query="SELECT * FROM `tbl_slides` ORDER BY sl_orden ASC";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				$output[] = $row;
			}
	
		echo json_encode($output);
	}


	public function updateEmployee($nombre,$value,$pk)
	{
		$data = array();
		$sql = "UPDATE `tbl_slides` set ".$nombre." = '".$value."' WHERE sl_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}


	public function borrarImg($imagen) {

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_slides` WHERE sl_nombre='$imagen'")) {
			unlink("../img/slide/".$imagen);
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

	public function borrarImgUpload($imagen) {
		unlink("../../img/slide/".$imagen);
		mysqli_query(parent::con(),"DELETE FROM `tbl_slides` WHERE sl_nombre='$imagen'");
	}
}

class Ordenes extends Conexion
{
	
	public $id;
	public $accion;
	public $status;
	
	public function listaOrdenes($status)
	{

		$this->status=mysqli_real_escape_string(parent::con(), $status);
		
		$query=	"SELECT * FROM ordenes 
		INNER JOIN status_ordenes ON status_ordenes.st_id=ordenes.or_estado
		INNER JOIN envio_orden ON envio_orden.id_orden=ordenes.id_orden";

		if(!empty($this->status)){
			if(!strstr($query,"WHERE")){
				$query .= " WHERE or_estado='$this->status'";
			}else{
				$query .= " AND or_estado='$this->status'";
			}
		} else {
			if(!strstr($query,"WHERE")){
				$query .= " WHERE or_estado>'10' AND or_estado<'70'";
			}else{
				$query .= " AND or_estado>'10' AND or_estado<'70'";
			}
		}

		if(!strstr($query,"WHERE")){
			$query.=" ORDER BY or_estado ASC, fecha_alta DESC";
		}else{
			$query.=" ORDER BY or_estado ASC, fecha_alta DESC";
		}
										
		$result=mysqli_query(parent::con(),"$query");


		while ($reg=$result->fetch_assoc())
			{

				if ($reg["or_medio_pago"]=='mp') {
					$reg["or_medio_pago"]= '<img src="assets/images/mp-icon.svg" width="18">';
				}elseif($reg["or_medio_pago"]=='tp'){
					$reg["or_medio_pago"]= '<img src="assets/images/tp-icon.svg" width="18">';
				}elseif($reg["or_medio_pago"]=='transferencia'){
					$reg["or_medio_pago"]= '<img src="assets/images/transf-icon.svg" width="18">';
				}

				switch ($reg["env_tipo"]) {
					case 'D':
						$reg["env_tipo"]='<i class="fa fa-truck fa-lg m-l-md"></i> ($'.$reg["env_valor"].')';
						break;
					
					case 'S':
						$reg["env_tipo"]='<i class="fa fa-home fa-lg m-l-md"></i> ($'.$reg["env_valor"].')';
						break;
				}

				$reg["fecha_alta"]= date("d M Y, G:i", strtotime($reg["fecha_alta"]));

				$reg["total_compra"]= number_format($reg["total_compra"],2,',','.');

				require_once("../../class/checkout.class.php");
				$ObjCheckout = new Checkout();

				$orderContent = $ObjCheckout->GetOrderContent($reg["id_orden"]);
				$numItem=count($orderContent); 
				$cant_prod=0;
						
				for ($i=0; $i<$numItem; $i++) {
					extract($orderContent[$i]);
					$cant_prod += $cantidad;
				}

				$reg["productos"] = '<div class="dropdown">
				<a href="#" class="dropdown-toggle waves-effect waves-button waves-classic icon-cart-tabla" data-toggle="dropdown"><i class="glyphicon glyphicon-shopping-cart fa-lg m-r-xs"></i><span class="badge badge-danger pull-right">'.$cant_prod.'</span></a>
				<ul class="dropdown-menu title-caret dropdown-lg" role="menu">
					<li><p class="drop-title">'.$cant_prod.' Productos</p></li>
					<li class="dropdown-menu-list slimscroll messages">
						<ul class="list-unstyled">';
						
						for ($i=0; $i<$numItem; $i++) {
							extract($orderContent[$i]);
							$reg["productos"] .= '<li>
										<a href="#">
											<div class="msg-img"><img class="img-circle" src="'.$pd_thumbnail.'" alt=""></div>
											<p class="msg-text">'.$pd_titulo.' '.$codigo.' '.$sku.'</p>
											<p class="msg-time">('.$cantidad.') x '.number_format($precio,2,',','.').'</p>
										</a>
									</li>';
						}

				$reg["productos"] .= '</ul>
						</li>
					</ul>
				</div>';

				$output[] = $reg;
			}
	
		echo json_encode($output);
		
	}

	public function updateEmployee($nombre,$value,$pk)
	{
		$sql = "UPDATE `ordenes` set ".$nombre." = '".$value."' WHERE id_orden='".$pk."'";
		
		if(mysqli_query(parent::con(), $sql)) {

			if ($nombre=="or_estado") {
				if ($value>30 && $value<70) {
					require_once("../../class/checkout.class.php");
					$ObjCheckout = new Checkout();
					$ObjCheckout->enviaEmailStatus($pk,$value);
				}
			}
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}
	
	
	public function statusOrden($id_orden,$status) {
		
		$this->id=$id_orden;
		$this->status=$status;
		
		$query = "UPDATE `ordenes` SET `or_estado`='$this->status' WHERE id_orden='$this->id'";
		if(mysqli_query(parent::con(), $query)) {

			if ($this->status>2 && $this->status<7) {
				require_once("../class/checkout.class.php");
				$ObjCheckout = new Checkout();
				$ObjCheckout->enviaEmailStatus($this->id,$this->status);
			}

			return 'agregado';
		} else {
			die();
		}
	}

	public function actualizarPago($id_orden)
	{
		$this->id=mysqli_real_escape_string(parent::con(), $id_orden);

		require_once("../class/checkout.class.php");
		$ObjCheckout = new Checkout();

		// definimos las variables
		if ( !empty($_POST['id_pago']) )			$id_pago = $_POST['id_pago']; else return 'Ingrese ID de pago';
		if ( !empty($_POST['forma_pago']) )			$forma_pago = $_POST['forma_pago']; else return 'Ingrese la forma de pago';
		if ( !empty($_POST['estado_pago']) )			$estado_pago = $_POST['estado_pago']; else return 'Seleccione el estado del pago';
		if ( !empty($_POST['total_pagado']) )			$total_pagado = $_POST['total_pagado']; else return 'Ingrese el total pagado';

			if($ObjCheckout->ActualizarOrder($this->id,$id_pago,$estado_pago,$forma_pago,$total_pagado,3)) {
				
				$ObjCheckout->enviaEmailStatus($this->id,3);

				return 'agregado';
			} else {
				return 'error';
			}
	}

	public function enviarCodigo($id_orden)
	{
		$this->id=mysqli_real_escape_string(parent::con(), $id_orden);

		require_once("../class/checkout.class.php");
		$ObjCheckout = new Checkout();

		// definimos las variables
		if ( !empty($_POST['cod_seguimiento']) )			$cod_seguimiento = $_POST['cod_seguimiento']; else return 'Ingrese código de seguimiento';
		if ( !empty($_POST['link_seguimiento']) )			$link_seguimiento = $_POST['link_seguimiento']; else return 'Ingrese el link para el seguimiento';


		$query = "UPDATE `envio_orden` SET `cod_seguimiento`='$cod_seguimiento', `link_seguimiento`='$link_seguimiento' WHERE id_orden='$this->id'";
		if(mysqli_query(parent::con(), $query)) {

			$ObjCheckout->enviaEmailCodigo($this->id,$cod_seguimiento,$link_seguimiento);

			return 'agregado';
		} else {
			return 'error';
		}
	}

	public function comboEstados($id_orden,$estado) {
		
		$this->id=$id_orden;
		$this->status=$estado;
		
		$result=mysqli_query(parent::con(),"SELECT * FROM status_ordenes WHERE st_id='$this->status' ");
		$row=$result->fetch_assoc();
	
		

		switch ($row["st_id"]) {
			case '10':
				$color = 'btn-danger';
				$progresBar= '<div class="progress progress-sm">
					<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width: 10%"></div>
				</div>';
				break;
				case '20':
					$color = 'btn-danger';
					$progresBar= '<div class="progress progress-sm">
					<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%"></div>
				</div>';
					break;
					case '30':
						$color = 'btn-info';
						$progresBar= '<div class="progress progress-sm">
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%"></div>
						</div>';
						break;
						case '40':
							$color = 'btn-primary';
							$progresBar= '<div class="progress progress-sm">
								<div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%"></div>
							</div>';
							break;
							case '50':
								$color = 'btn-warning';
								$progresBar= '<div class="progress progress-sm">
									<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div>
								</div>';
								break;
								case '51':
									$color = 'btn-warning';
									$progresBar= '<div class="progress progress-sm">
										<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div>
									</div>';
									break;
								case '60':
									$color = 'btn-success';
									$progresBar= '<div class="progress progress-sm">
										<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
									</div>';
									break;
									case '70':
										$color = 'btn-default';
										$progresBar= '<div class="progress progress-sm">
											<div class="progress-bar progress-bar-default" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
										</div>';
										break;
										case '80':
											$color = 'btn-danger';
											$progresBar= '<div class="progress progress-sm">
												<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%"></div>
											</div>';
											break;
											case '90':
												$color = 'btn-danger';
												$progresBar= '<div class="progress progress-sm">
													<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%"></div>
												</div>';
												break;
		}

			echo $progresBar;

			echo '<div class="btn-group"><button type="button" class="btn ';
			echo $color;
			
			echo ' btn-lg dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$row["st_nombre"].' <span class="caret"></span>
			</button>
			<ul class="dropdown-menu">';
			$result=mysqli_query(parent::con(),"SELECT * FROM status_ordenes");
			while ($reg=$result->fetch_assoc())
			{
				echo '<li';
				if ($reg["st_id"]==$this->status) {
					echo ' class="active"';
				}
				echo  '><a href="ver-orden.php?action='.$reg["st_id"].'&id_orden='.$this->id.'">'.$reg["st_nombre"].'</a></li>';
			}

        echo '</ul></div>';
                  	
		
	}

	public function countStatus($status)
	{
		$this->status=mysqli_real_escape_string(parent::con(), $status);

		$result=mysqli_query(parent::con(),"SELECT count(id_orden) as cant FROM `ordenes` WHERE or_estado='$this->status' ");
		$row_cnt=$result->num_rows;
		if ($row_cnt>0) {
			$row=$result->fetch_assoc();
			echo $row['cant'];
		} else {
			echo 0;
		}

	}

	public function agregarNota($id)
	{
		$this->id=mysqli_real_escape_string(parent::con(), $id);

		// definimos las variables
		$notas = $_POST['notas'];

			$query = "UPDATE `ordenes` set `or_notas`='$notas' WHERE id_orden='$this->id'";
		
			if($result = mysqli_query(parent::con(), $query)) {
				return 'agregado';
			} else {
				die("error to update '".$params["name"]."' with '".$params["value"]."'");
			}
	}

	
	public function CostoEnvio() {
		
		$result=mysqli_query(parent::con(),"SELECT * FROM `costo_envio`");
		$row_cnt=$result->num_rows;
		
		
		while ($cost=$result->fetch_assoc())
		{
			if (empty($_POST["{$cost['id_costo']}"]))	return 'Complete el costo para '.$cost['zona'];         	
		}
		
		// si no hay errores
		mysqli_data_seek($result, 0);
			
			while ($cost=$result->fetch_assoc())
			{
				$precio=$_POST["{$cost['id_costo']}"];
				$id_costo=$cost['id_costo'];
				$query = "UPDATE `costo_envio` SET `precio`='$precio' WHERE id_costo='$id_costo' ";	

				if(!$resultErr = mysqli_query(parent::con(), $query)) {
					$error[]=1;
					return 'ocurrió un error';
				} 	
			}

		if (empty($error)) {
			return 'agregado';
		} else {
			return 'ocurrió un error';
		}
			
	}

	public function traerFacturacion($orderId)
	{

		$query = "SELECT * FROM ordenes 
		INNER JOIN factura_orden ON factura_orden.id_orden=ordenes.id_orden 
		WHERE ordenes.id_orden = '$orderId'";
		$result=mysqli_query(parent::con(),$query);
		return $result->fetch_assoc();
	}

	public function traerDatosTransferencia()
	{
		
		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_datos_transferencia` WHERE id=1");
		return $result->fetch_assoc();
	}
	
	public function datosTransferencia()
	{
		
		// definimos las variables
		if ( !empty($_POST['banco']) )			$banco = $_POST['banco']; else return 'Ingrese el nombre del banco';
		if ( !empty($_POST['tipo']) )			$tipo = $_POST['tipo']; else return 'Ingrese el tipo de cuenta'; 
		if ( !empty($_POST['num_cuenta']) )			$num_cuenta = $_POST['num_cuenta']; else return 'Ingrese el número de cuenta';
		if ( !empty($_POST['cbu']) )			$cbu = $_POST['cbu']; else return 'Ingrese el CBU';
		if ( !empty($_POST['titular']) )			$titular = $_POST['titular']; else return 'Ingrese el titular o razón social';
		if ( !empty($_POST['cuit']) )			$cuit = $_POST['cuit']; else return 'Ingrese el CUIT';

		// si no hay errores
		if (mysqli_query(parent::con(),"UPDATE `tbl_datos_transferencia` SET `banco`='$banco',`tipo`='$tipo',`num_cuenta`='$num_cuenta',`cbu`='$cbu',`titular`='$titular',`cuit`='$cuit' WHERE id=1")) {

			return 'agregado';
		} else {
			return 'Ocurrió un error, intente nuevamente luego';
		}
	}

	public function traerCuotas()
	{
		
		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_cuotas` WHERE id=1");
		return $result->fetch_assoc();
	}
	
	public function editarCuotas()
	{
		
		// definimos las variables
		$cuotas = $_POST['cuotas']; 

		// si no hay errores
		if (mysqli_query(parent::con(),"UPDATE `tbl_cuotas` SET `cuotas`='$cuotas' WHERE id=1")) {
			return 'agregado';
		} else {
			return 'Ocurrió un error, intente nuevamente luego';
		}
	}

	public function traerDescuentoTransf()
	{
		
		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_descuento_transferencias` WHERE id=1");
		return $result->fetch_assoc();
	}
	
	public function editarDescuentoTransf()
	{
		
		// definimos las variables
		$porcentaje_descuento = $_POST['descuento']; 

		// si no hay errores
		if (mysqli_query(parent::con(),"UPDATE `tbl_descuento_transferencias` SET `porcentaje_descuento`='$porcentaje_descuento' WHERE id=1")) {
			return 'agregado';
		} else {
			return 'Ocurrió un error, intente nuevamente luego';
		}
	}
	
}


class Preguntas extends Conexion
{
	
	public $id;
	public $accion;
	public $status;
	
	public function lista($status)
	{

		$this->status=mysqli_real_escape_string(parent::con(), $status);
		
		$query=	"SELECT * FROM comments INNER JOIN tbl_productos ON tbl_productos.pd_id=comments.comment_post_ID";

		if(!empty($this->status)){
			if(!strstr($query,"WHERE")){
				$query .= " WHERE comment_approved='$this->status' AND comment_parent=0 ";
			}else{
				$query .= " AND comment_approved='$this->status' AND comment_parent=0 ";
			}
		} else {
			$query .= " WHERE comment_approved='pendiente' AND comment_parent=0 ";
		}

		$query.=" ORDER BY comment_date DESC";
										
		$result=mysqli_query(parent::con(),"$query");


		while ($reg=$result->fetch_assoc())
			{

					$idComment=$reg['comment_ID'];
					$query=	"SELECT * FROM comments WHERE comment_parent='$idComment'";
					$resultParent=mysqli_query(parent::con(),"$query");
					$row_cnt=$resultParent->num_rows;
					if($row_cnt>0) {
						$reg['respuesta']='';
						while ($par=$resultParent->fetch_assoc())
						{
							$reg['respuesta'].='<strong>R:</strong> ';
							$reg['respuesta'].=$par['comment_content'];
							$reg['respuesta'].='<br>';
						}
					} else {
						$reg['respuesta']='';
					}	

				$output[] = $reg;
			}
	
		echo json_encode($output);
	}

	public function listaResumen()
	{
		$query=	"SELECT * FROM comments INNER JOIN tbl_productos ON tbl_productos.pd_id=comments.comment_post_ID 
		WHERE comment_approved='pendiente' AND comment_parent=0
		ORDER BY comment_date DESC LIMIT 5";
										
		$result=mysqli_query(parent::con(),"$query");


		while ($reg=$result->fetch_assoc())
			{
				echo '<li>
						<a href="contestar-pregunta.php?id_preg='.$reg['comment_ID'].'">
							<p class="msg-name">'.$reg['comment_author'].'</p>
							<p class="msg-text">'.$reg['comment_content'].'</p>
							<p class="msg-time">'.$reg['comment_date'].'</p>
						</a>
					</li>';
			}
	}

	public function GetRespuestas($id)
	{
		$this->id=mysqli_real_escape_string(parent::con(), $id);

		$query=	"SELECT * FROM comments WHERE comment_parent='$this->id'";
		$result=mysqli_query(parent::con(),"$query");
		$row_cnt=$result->num_rows;
		if($row_cnt>0) {
			while ($par=$result->fetch_assoc())
			{
				echo '<div class="alert alert-success m-t-lg" role="alert">
							<h4>Iris contestó:</h4>
							'.$par['comment_content'].'
					</div>';
			}
		} 
	}

	public function updateEmployee($nombre,$value,$pk)
	{
		$sql = "UPDATE `comments` set ".$nombre." = '".$value."' WHERE comment_ID='".$pk."'";
		
		if(mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			echo 'Error';
		}
	}
	
	public function GetPregunta($id)
	{
		$this->id=$id;

		$query="SELECT * FROM `comments` WHERE comment_ID='$this->id' ";
		$result=mysqli_query(parent::con(),"$query");

		return $result->fetch_assoc();
	}

	public function countStatus($status)
	{
		$this->status=mysqli_real_escape_string(parent::con(), $status);

		$result=mysqli_query(parent::con(),"SELECT count(comment_ID) as cant FROM `comments` WHERE comment_approved='$this->status' AND comment_parent=0");
		$row_cnt=$result->num_rows;
		if ($row_cnt>0) {
			$row=$result->fetch_assoc();
			echo $row['cant'];
		} else {
			echo 0;
		}
	}

	public function countPreguntas($status)
	{
		$this->status=mysqli_real_escape_string(parent::con(), $status);

		$result=mysqli_query(parent::con(),"SELECT count(comment_ID) as cant FROM `comments` WHERE comment_approved='$this->status' AND comment_parent=0");
		$row_cnt=$result->num_rows;
		if ($row_cnt>0) {
			$row=$result->fetch_assoc();
			return $row['cant'];
		} else {
			return 0;
		}
	}

	public function responder($id)
	{

		$this->id=mysqli_real_escape_string(parent::con(), $id);

		// definimos las variables
		$respuesta = $_POST['respuesta'];

		$query=	"SELECT * FROM comments WHERE comment_ID='$this->id'";
		$result=mysqli_query(parent::con(),"$query");
		$preg=$result->fetch_assoc();

		$producto=$preg['comment_post_ID'];
		$autor=$preg['comment_author'];
		$comment_date = date("Y-m-d H:i:s");
		$emailTo=$preg['comment_author_email'];

		$query="INSERT INTO `comments` (`comment_post_ID`, `comment_author`, `comment_author_email`, `comment_date`, `comment_content`, `comment_approved`, `comment_parent`) VALUES ('$producto','$nombre','$email','$comment_date', '$respuesta', 'contestada', '$this->id')";
		mysqli_query(parent::con(), $query);

		$query = "UPDATE `comments` set `comment_approved`='contestada' WHERE comment_ID='$this->id'";

		

		//Envio de correo por Postmark
        $url ="https://api.postmarkapp.com/email/withTemplate";
        $headers = array(
            "Content-Type: application/json",
            "Accept: application/json",
            "X-Postmark-Server-Token: 11c3f3a2-e5c5-4845-ac1a-1ebadc00990a"
        );
        

        $parametros_post = '{
            "From": "ventasweb@irisiluminacion.com.ar",
            "To": "'.$emailTo.'",
            "TemplateAlias": "comment-notification",
            "TemplateModel": {
                "site_url": "'.HTTP_SERVER.'",
                "company_name": "Iris Iluminación",
                "company_address": "Blvr. Buenos Aires 1520, Luis Guillón",
                "commenter_name": "'.$autor.'",
                "pregunta": "'.$preg['comment_content'].'",
				"respuesta": "'.$respuesta.'",
				"producto_url": "'.HTTP_SERVER.'producto/'.$producto.'-comments",
                "fecha_respuesta": "'.$comment_date.'"
            }
        }';

        $sesion = curl_init($url);
        curl_setopt($sesion, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($sesion, CURLOPT_CAINFO, 0);
        curl_setopt($sesion, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
        curl_setopt($sesion, CURLOPT_HTTPHEADER, $headers);
        curl_setopt ($sesion, CURLOPT_POST, true);
        curl_setopt ($sesion, CURLOPT_POSTFIELDS, $parametros_post);
        curl_setopt($sesion, CURLOPT_HEADER, false);
        curl_setopt($sesion, CURLOPT_RETURNTRANSFER, true);
        $respuesta = curl_exec($sesion);
        curl_close($sesion);
        //fin Envio de correo por Postmark
		
			if($result = mysqli_query(parent::con(), $query)) {
				return 'agregado';
			} else {
				return 'error';
			}
	}
	
}

class Descuento extends Conexion
{
	public $id;
	public $accion;

	public function lista()
	{

		$query="SELECT * FROM `tbl_descuento`";
		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				$output[] = $row;
			}
	
		echo json_encode($output);
	}

	public function editar($nombre,$value,$pk)
	{
		$sql = "UPDATE `tbl_descuento` set ".$nombre." = '".$value."' WHERE id='".$pk."'";
		
		if(mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			echo 'Error';
		}
	}

	public function traerCuotas()
	{
		
		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_cuotas` WHERE id=1");
		return $result->fetch_assoc();
	}
	
	public function editarCuotas()
	{
		
		// definimos las variables
		$cuotas = $_POST['cuotas']; 

		// si no hay errores
		if (mysqli_query(parent::con(),"UPDATE `tbl_cuotas` SET `cuotas`='$cuotas' WHERE id=1")) {
			return 'agregado';
		} else {
			return 'Ocurrió un error, intente nuevamente luego';
		}
	}
	
}

class Envios extends Conexion
{
	public $id;
	public $accion;
	public $imagen;

	public function lista()
	{
		$query="SELECT * FROM `tbl_envios` 
		INNER JOIN provincias ON provincias.id=tbl_envios.env_provincia 
		ORDER BY provincias.provincia ASC";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				$output[] = $row;
			}
	
		echo json_encode($output);
	}

	public function editar($nombre,$value,$pk)
	{
		$data = array();
		$sql = "UPDATE `tbl_envios` set ".$nombre." = '".$value."' WHERE env_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}


	public function agregar()
	{
		
		// definimos las variables
		if ( !empty($_POST['provincia']) )			$provincia = $_POST['provincia']; else return 'Ingrese la provincia/región';
		if ( !empty($_POST['nombre']) )			$nombre = $_POST['nombre']; else return 'Ingrese el nombre';
		$descripcion = $_POST['descripcion']; 
		$horas_entrega = $_POST['horas_entrega'];
		if ( !empty($_POST['precio_normal']) )			$price_normal = $_POST['precio_normal']; else return 'Ingrese el precio normal';
		if ( !empty($_POST['precio_especial']) )			$price_especial = $_POST['precio_especial']; else return 'Ingrese el precio especial';


		if (mysqli_query(parent::con(),"INSERT INTO `tbl_envios`(`env_provincia`, `env_nombre`, `env_descripcion`, `env_horas_entrega`, `price_normal`, `price_especial`) VALUES ('$provincia','$nombre','$descripcion','$horas_entrega','$price_normal','$price_especial')")) {
			return 'agregado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}

	}

	public function Traer($id)
	{
		$this->id=$id;

		$query="SELECT * FROM `tbl_envios` WHERE env_id='$this->id' ";
		$result=mysqli_query(parent::con(),"$query");

		return $result->fetch_assoc();
	}

	public function EditarEnvio($id)
	{
		$this->id=$id;

		// definimos las variables
		if ( !empty($_POST['provincia']) )			$provincia = $_POST['provincia']; else return 'Ingrese la provincia/región';
		if ( !empty($_POST['nombre']) )			$nombre = $_POST['nombre']; else return 'Ingrese el nombre';
		$descripcion = $_POST['descripcion']; 
		$horas_entrega = $_POST['horas_entrega'];
		if ( !empty($_POST['precio_normal']) )			$price_normal = $_POST['precio_normal']; else return 'Ingrese el precio normal';
		if ( !empty($_POST['precio_especial']) )			$price_especial = $_POST['precio_especial']; else return 'Ingrese el precio especial';


		$query = "UPDATE `tbl_envios` SET `env_provincia`='$provincia', `env_nombre`='$nombre',`env_descripcion`='$descripcion',`env_horas_entrega`='$horas_entrega',`price_normal`='$price_normal',`price_especial`='$price_especial' WHERE env_id='$this->id'";
		
		if($result = mysqli_query(parent::con(), $query)) {
			return 'agregado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

	public function borrar($id)
	{
		$this->id=$id;

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_envios` WHERE env_id='$this->id'")) {
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

	public function ComboProvincias()
	{

		$query="SELECT * FROM `provincias` ORDER BY provincia ASC";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				$output[] = $row;
			}
	
		return $output;
	}
}

class marcas extends Conexion
{	
	
	public $id;
	public $accion;
	public $archivo;


	public function getEmployees()
	{
		$query="SELECT DISTINCT `mc_marca`,`feed` FROM `tbl_marcas`";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				$output[] = $row;
			}
	
		echo json_encode($output);
	}


	public function updateEmployee($nombre,$value,$pk)
	{
		$sql = "UPDATE `tbl_marcas` set ".$nombre." = '".$value."' WHERE mc_marca='".$pk."'";
		
		if(mysqli_query(parent::con(), $sql)) {
			echo 'exito';
		} else {
			echo 'error';
		}
	}	
}

class Estadisticas extends Conexion
{
	public $id;
	private $valDesdeHasta = array();

	public function periodoFechas($periodo)
	{
		$fechaPersonalizada=false;

		switch ($periodo) {
			case 'hoy':
				$valDesd = date('Y-m-d H:i:s', mktime(00,00,00, date('m'), date('d'), date('Y')));
				$valHast = date('Y-m-d H:i:s', mktime(00,00,00, date('m'), date('d'), date('Y')));
				break;
				case 'semana':
					$valDesd = date('Y-m-d H:i:s', mktime(00,00,00, date('m'), date('d') - 7, date('Y')));
					$valHast = date('Y-m-d H:i:s', mktime(00,00,00, date('m'), date('d'), date('Y')));
					break;
					case 'mes':
						$valDesd = date('Y-m-d H:i:s', mktime(00,00,00, date('m') - 1, date('d'), date('Y')));
						$valHast = date('Y-m-d H:i:s', mktime(00,00,00, date('m'), date('d'), date('Y')));
						break;
						case 'trimestre':
							$valDesd = date('Y-m-d H:i:s', mktime(00,00,00, date('m') - 3, date('d'), date('Y')));
							$valHast = date('Y-m-d H:i:s', mktime(00,00,00, date('m'), date('d'), date('Y')));
							break;
							case 'anio':
								$valDesd = date('Y-m-d H:i:s', mktime(00,00,00, date('m'), date('d'), date('Y') - 1 ));
								$valHast = date('Y-m-d H:i:s', mktime(00,00,00, date('m'), date('d'), date('Y')));
								break;

								default:
								$fechaPersonalizada=true;
								$valDesd = substr($periodo, 6, 10);
								$valHast = substr($periodo, -10, 10);

								$date = DateTime::createFromFormat('d/m/Y', $valDesd);
								$valDesd = $date->format('Y-m-d H:i:s');

								$date = DateTime::createFromFormat('d/m/Y', $valHast);
								$valHast = $date->format('Y-m-d H:i:s');
								break;
		}
		$this->valDesdeHasta[] = $valDesd;
		$this->valDesdeHasta[] = $valHast;
		$this->valDesdeHasta[] = $fechaPersonalizada;
		return implode(",", $this->valDesdeHasta);
	}

	public function countOrdenesPagas($periodo)
	{
		
		$fechas = explode(",", $this->periodoFechas($periodo));
		$valDesd=$fechas[0];
		$valHast=$fechas[1];
		$fechaPersonalizada=$fechas[2];

		$query = "SELECT COUNT(id_orden) as cant FROM `ordenes` ";
		
		if ($fechaPersonalizada) {
			$query .= "WHERE fecha_alta >= '$valDesd' AND fecha_alta <= '$valHast' AND or_estado>20 AND or_estado<80 ";
		} else {
			$query .= "WHERE fecha_alta >= '$valDesd' AND fecha_alta <= NOW() AND or_estado>20 AND or_estado<80 ";
		}

		$result=mysqli_query(parent::con(),$query);
		$data=0;
		while ($row=$result->fetch_assoc())
			{
				$data+= $row['cant'];
			}

		return $data;
	}

	public function OrdenesPorDia($periodo)
	{
		$fechas = explode(",", $this->periodoFechas($periodo));
		$valDesd=$fechas[0];
		$valHast=$fechas[1];
		$fechaPersonalizada=$fechas[2];

		if ($periodo=='hoy') {
			return 1;
		} else {		
			$datetime1 = new DateTime($valDesd);
			$datetime2 = new DateTime($valHast);
			return $interval = $datetime1->diff($datetime2)->days;
		}
	}

	public function countFacturacion($periodo)
	{
		$fechas = explode(",", $this->periodoFechas($periodo));
		$valDesd=$fechas[0];
		$valHast=$fechas[1];
		$fechaPersonalizada=$fechas[2];

		$query = "SELECT SUM(total_compra) as total, fecha_alta FROM `ordenes` ";

		if ($fechaPersonalizada) {
			$query .= "WHERE fecha_alta >= '$valDesd' AND fecha_alta <= '$valHast' AND or_estado>20 AND or_estado<80 ";
		} else {
			$query .= "WHERE fecha_alta >= '$valDesd' AND fecha_alta <= NOW() AND or_estado>20 AND or_estado<80 ";
		}

		$result=mysqli_query(parent::con(),$query);
		$row_cnt=$result->num_rows;

		$data=0;

		if ($row_cnt>0) {
			while ($row=$result->fetch_assoc())
			{
				$data+= $row['total'];
			}
		}

		return $data;
	}

	public function ordenesPagas($periodo)
	{

		$fechas = explode(",", $this->periodoFechas($periodo));
		$valDesd=$fechas[0];
		$valHast=$fechas[1];
		$fechaPersonalizada=$fechas[2];
		
		$datetime1 = new DateTime($valDesd);
		$datetime2 = new DateTime($valHast);
		$interval = $datetime1->diff($datetime2)->days;

		$query = "SELECT COUNT(id_orden) as cant, fecha_alta FROM `ordenes` ";

		if ($fechaPersonalizada) {
			$query .= "WHERE fecha_alta >= '$valDesd' AND fecha_alta <= '$valHast' AND or_estado>20 AND or_estado<80 ";
		} else {
			$query .= "WHERE fecha_alta >= '$valDesd' AND fecha_alta <= NOW() AND or_estado>20 AND or_estado<80 ";
		}


		if ($interval>92) {
			$query .= "GROUP BY YEAR(fecha_alta), MONTH(fecha_alta) ";
		} elseif ($interval>28 && $interval<93) {
			$query .= "GROUP BY YEAR(fecha_alta), WEEK(fecha_alta) ";
		} else {
			$query .= "GROUP BY YEAR(fecha_alta), MONTH(fecha_alta), DAY(fecha_alta) ";
		}
		$query .= "ORDER BY fecha_alta ASC";
		

		$result=mysqli_query(parent::con(),$query);
		$data='';
		while ($row=$result->fetch_assoc())
			{
				if ($interval>92) {
					$fecha=date("Y-m", strtotime($row['fecha_alta']));
				} elseif ($interval>28 && $interval<93) {
					$fecha=date("Y-m-d", strtotime($row['fecha_alta']));
				} else {
					$fecha=date("Y-m-d", strtotime($row['fecha_alta']));
				}

				$data.= "{ day: '".$fecha."', ordenes: ".$row['cant']." },";
			}
			$data = substr($data, 0, -1);
			echo $data;
	}

	public function facturacion($periodo)
	{
		$fechas = explode(",", $this->periodoFechas($periodo));
		$valDesd=$fechas[0];
		$valHast=$fechas[1];
		$fechaPersonalizada=$fechas[2];
		
		$datetime1 = new DateTime($valDesd);
		$datetime2 = new DateTime($valHast);
		$interval = $datetime1->diff($datetime2)->days;

		$query = "SELECT SUM(total_compra) as total, fecha_alta FROM `ordenes` ";

		if ($fechaPersonalizada) {
			$query .= "WHERE fecha_alta >= '$valDesd' AND fecha_alta <= '$valHast' AND or_estado>20 AND or_estado<80 ";
		} else {
			$query .= "WHERE fecha_alta >= '$valDesd' AND fecha_alta <= NOW() AND or_estado>20 AND or_estado<80 ";
		}

		if ($interval>92) {
			$query .= "GROUP BY YEAR(fecha_alta), MONTH(fecha_alta) ";
		} elseif ($interval>28 && $interval<93) {
			$query .= "GROUP BY YEAR(fecha_alta), WEEK(fecha_alta) ";
		} else {
			$query .= "GROUP BY YEAR(fecha_alta), MONTH(fecha_alta), DAY(fecha_alta) ";
		}


		$result=mysqli_query(parent::con(),$query);
		$data='';
		while ($row=$result->fetch_assoc())
			{
				if ($interval>92) {
					$fecha=date("Y-m", strtotime($row['fecha_alta']));
				} elseif ($interval>28 && $interval<93) {
					$fecha=date("Y-m-d", strtotime($row['fecha_alta']));
				} else {
					$fecha=date("Y-m-d", strtotime($row['fecha_alta']));
				}

				$data.= "{ day: '".$fecha."', total: ".$row['total']." },";
			}
			$data = substr($data, 0, -1);
			echo $data;
	}

	public function productosMasVendidos($periodo)
	{
		$fechas = explode(",", $this->periodoFechas($periodo));
		$valDesd=$fechas[0];
		$valHast=$fechas[1];
		$fechaPersonalizada=$fechas[2];

		$query = "SELECT COUNT(pd_id) as cant, SUM(cantidad) as total, pd_id, pd_titulo, pd_thumbnail FROM ordenes
		INNER JOIN items_orden ON items_orden.id_orden=ordenes.id_orden 
		INNER JOIN tbl_productos ON tbl_productos.pd_id=items_orden.producto_id 
		WHERE items_orden.producto_id = tbl_productos.pd_id ";

		if ($fechaPersonalizada) {
			$query .= "AND fecha_alta >= '$valDesd' AND fecha_alta <= '$valHast' AND or_estado>20 AND or_estado<80 GROUP BY pd_id ORDER BY total DESC";
		} else {
			$query .= "AND fecha_alta >= '$valDesd' AND fecha_alta <= NOW() AND or_estado>20 AND or_estado<80 GROUP BY pd_id ORDER BY total DESC";
		}

		$result=mysqli_query(parent::con(),$query);

		while ($row=$result->fetch_assoc())
			{

				echo '<div class="inbox-item">
						<div class="inbox-item-img"><img src="'.$row['pd_thumbnail'].'" class="img-circle" alt=""></div>
						<p class="inbox-item-author">'.$row['pd_titulo'].'</p>
						<p class="inbox-item-date">'.$row['total'].'</p>
					</div>';

			}
	}

	public function productosMasFacturacion($periodo)
	{
		$fechas = explode(",", $this->periodoFechas($periodo));
		$valDesd=$fechas[0];
		$valHast=$fechas[1];
		$fechaPersonalizada=$fechas[2];

		$query = "SELECT SUM(precio*cantidad) as total, pd_id, pd_titulo, pd_thumbnail FROM ordenes
		INNER JOIN items_orden ON items_orden.id_orden=ordenes.id_orden 
		INNER JOIN tbl_productos ON tbl_productos.pd_id=items_orden.producto_id 
		WHERE items_orden.producto_id = tbl_productos.pd_id ";

		if ($fechaPersonalizada) {
			$query .= "AND fecha_alta >= '$valDesd' AND fecha_alta <= '$valHast' AND or_estado>20 AND or_estado<80 GROUP BY pd_id ORDER BY total DESC";
		} else {
			$query .= "AND fecha_alta >= '$valDesd' AND fecha_alta <= NOW() AND or_estado>20 AND or_estado<80 GROUP BY pd_id ORDER BY total DESC";
		}

		$result=mysqli_query(parent::con(),$query);

		while ($row=$result->fetch_assoc())
			{

				echo '<div class="inbox-item">
						<div class="inbox-item-img"><img src="'.$row['pd_thumbnail'].'" class="img-circle" alt=""></div>
						<p class="inbox-item-author">'.$row['pd_titulo'].'</p>
						<p class="inbox-item-date">$'.number_format($row['total'],2,',','.').'</p>
					</div>';

			}
	}
	
}

class Seo extends Conexion
{	
	
	public $id;
	public $accion;


	public function getEmployees()
	{

		$query="SELECT * FROM `tbl_seo`";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				$output[] = $row;
			}
	
		echo json_encode($output);
	}


	public function updateEmployee($nombre,$value,$pk)
	{
		$sql = "UPDATE `tbl_seo` set ".$nombre." = '".$value."' WHERE seo_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}

	public function agregar()
	{
		
		// definimos las variables
		if (!empty($_POST['nombre']))			$nombre = $_POST['nombre']; else return 'Ingrese el nombre de la página';

		$alias=Varias::crear_url($nombre);

		if (mysqli_query(parent::con(),"INSERT INTO `tbl_seo`(`seo_pagina`) VALUES ('$nombre')")) {
			return 'agregado';
		} else {
			return 'Ocurrió un error, intente nuevamente';
		}

	}

	public function borrar($id)
	{
		$this->id=$id;

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_seo` WHERE seo_id='$this->id'")) {
			return 'eliminado';
		} else {
			return 'Ocurrió un error, intente nuevamente';
		}
	}

	public function traerScripts()
	{
		return $result=mysqli_query(parent::con(),"SELECT * FROM `tbl_scripts`");
	}
	
	public function scripts() {

		$scriptHead=mysqli_real_escape_string(parent::con(), $_POST["scriptHead"]);
		$scriptBody=mysqli_real_escape_string(parent::con(), $_POST["scriptBody"]);

		$query = "UPDATE `tbl_scripts` SET `scr_head`='$scriptHead', `scr_body`='$scriptBody' WHERE scr_id=1 ";	

		if(!$resultErr = mysqli_query(parent::con(), $query)) {
			return 'Ocurrió un error';
		} else {
			return 'agregado';
		}
			
	}

}

class EstadosOrdenes extends Conexion
{	
	
	public $id;
	public $accion;
	public $orden;
	public $imagen;
	
	public function lista()
	{
		$result=mysqli_query(parent::con(),"SELECT * FROM status_ordenes");
		while ($reg=$result->fetch_assoc())
		{
			echo '<tr>';
            echo '<td>'.$reg["st_nombre"].'</td>';
			echo '<td>'.$reg["st_text_email"].'</td>';
			echo '<td align="center"><a href="editar-estado.php?id='.$reg["st_id"].'" class="btn btn-success btn-sm">Editar texto email</a></td>';						
			echo '</tr>';         	
		}
		
	}

	
	public function traer($id)
	{
		$this->id=$id;
		$result=mysqli_query(parent::con(),"SELECT * FROM `status_ordenes` WHERE st_id='$this->id'");
		return $result->fetch_assoc();
	}
	
	
	public function editar($id_edicion)
	{
		$this->id=$id_edicion;
		
		// definimos las variables
		if ( !empty($_POST['texto']) ) 				$texto = $_POST['texto']; else return 'Ingrese un texto';
	
		$query="UPDATE `status_ordenes` SET `st_text_email`='$texto' WHERE st_id='$this->id'";

		if (mysqli_query(parent::con(),"$query")) {
			return 'agregado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}


	}
	
}

class Noticias extends Conexion
{	
	
	public $id;
	public $accion;
	public $orden;
	public $imagen;
	
	public function lista_noticias()
	{
		
		$result=mysqli_query(parent::con(),"SELECT * FROM tbl_noticias");
		
		while ($reg=$result->fetch_assoc())
		{
			
			echo '<tr>';


						$this->id=$reg["nt_id"];
						$resultImg=mysqli_query(parent::con(),"SELECT * FROM `tbl_img_noticias` WHERE im_producto='$this->id' AND im_orden=1 ");
						$row_cnt=$resultImg->num_rows;

						if ($row_cnt>0) {
							$img=$resultImg->fetch_assoc();
							echo '<td><img class="card-img-top img-fluid" src="../img/proyectos/'.$img["im_nombre"].'" height="100"></td>';
						} else {
							echo '<td><img class="card-img-top img-fluid" src="../img/proyectos/sin-imagen.jpg" height="80"></td>';
						}



                  	echo '<td>'.$reg["nt_titulo"].'</td>';


					echo '<td><a href="fotos-noticias.php?id='.$reg["nt_id"].'" class="btn btn-info"><i class="fa fa-file-image-o"></i> Imagenes</a></td>';

					echo '<td align="center"><a href="editar-noticia.php?nt_id='.$reg["nt_id"].'" class="btn btn-success btn-sm">Editar</a></td>
					<td align="center"><a href="noticias.php?action=delete&id='.$reg["nt_id"].'" data-confirm="Está seguro que desea eliminar?" class="btn btn-danger btn-sm">Eliminar</a></td>';

					echo '<td><div class="btn-group">
							<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$reg["status"].' <span class="caret"></span>
							</button>
							<ul class="dropdown-menu">';
											  
								if($reg["status"]=='papelera') {
									echo '<li><a href="noticias.php?action=publicado&id='.$reg["nt_id"].'">Publicado</a></li>';
								} else {
									echo '<li><a href="noticias.php?action=papelera&id='.$reg["nt_id"].'">A papelera</a></li>';
								}		
					echo '</ul></div></td></tr>';
                  	
		}
		
	}
	
	
	public function borrar_noticia($id_noticia)
	{
		$this->id=$id_noticia;
		mysqli_query(parent::con(),"DELETE FROM `tbl_noticias` WHERE nt_id='$this->id'");
		mysqli_query(parent::con(),"DELETE FROM `tbl_img_noticias` WHERE im_producto='$this->id'");

		return "El proyecto fue borrado";
	}

	
	public function traer_noticia($id_noticia)
	{
		$this->id=$id_noticia;
		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_noticias` WHERE nt_id='$this->id'");
		return $result->fetch_assoc();
	}
	
	
	public function editar_noticia($id_edicion)
	{
		$this->id=$id_edicion;
		
		$contenido=$this->traer_noticia($this->id);
		
		
		// definimos las variables
		if ( !empty($_POST['titulo']) )			$titulo = $_POST['titulo']; else return 'Ingrese el título';
		if ( !empty($_POST['texto']) ) 				$texto = $_POST['texto']; else return 'Ingrese un texto';
		if ( !empty($_POST['estado']) ) 			$estado = $_POST['estado']; else return 'Seleccione el estado de la publicación';
		
		$alias=Varias::crear_url($titulo);
		
		$query="UPDATE `tbl_noticias` SET `nt_alias`='$alias', `nt_titulo`='$titulo', `nt_texto`='$texto', `status`='$estado' WHERE nt_id='$this->id'";


		if (mysqli_query(parent::con(),"$query")) {
			return 'agregado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}


	}
	
	public function agregar_noticia()
	{
		
		// definimos las variables
		if ( !empty($_POST['titulo']) )			$titulo = $_POST['titulo']; else return 'Ingrese el título';
		if ( !empty($_POST['texto']) ) 				$texto = $_POST['texto']; else return 'Ingrese un texto';
		if ( !empty($_POST['estado']) ) 			$estado = $_POST['estado']; else return 'Seleccione el estado de la publicación';
		
			
		$alias=Varias::crear_url($titulo);


		$query="INSERT INTO `tbl_noticias`(`nt_alias`, `nt_titulo`, `nt_texto`, `status`) VALUES ('$alias','$titulo','$texto','$estado')";

		if (mysqli_query(parent::con(),"$query")) {
			return 'agregado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}


	public function status_noticia($id_not,$acc) {
		
		$this->id=$id_not;
		$this->accion=$acc;
		mysqli_query(parent::con(),"UPDATE `tbl_noticias` SET `status`='$this->accion' WHERE nt_id='$this->id' ");
		
		return "actualizado";
			
	}


	public function BotonesImgNot($id_not) {

		$this->id=$id_not;
		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_img_noticias` WHERE im_producto='$this->id' ORDER BY im_orden ASC");
		$row_cnt=$result->num_rows;

		$_SESSION['id_producto']=$this->id;

		$result_not=mysqli_query(parent::con(),"SELECT nt_alias FROM `tbl_noticias` WHERE nt_id='$this->id'");
		$not=$result_not->fetch_assoc();

		if($row_cnt==0){
			$nom_foto=$not['nt_alias']; 

			echo '<p class="alert alert-danger">No hay imágenes en el proyecto</p><br><a href="upload_crop-not.php?nom_fot='.$nom_foto.'&orden=1" class="btn btn-danger btn-addon btn-rounded btn-lg"><i class="fa fa-plus"></i> Agregar imagen</a><hr>';
		} else {

			$ft=1;
			while($reg=$result->fetch_assoc()) {

					$info = pathinfo($reg["im_nombre"]);
					$nom_foto =  basename($reg["im_nombre"],'.'.$info['extension']);

					$n_foto=strrchr($nom_foto,"_");
					if ($n_foto) {
						$n_foto = substr($n_foto, 1);
					} else {
						$n_foto=0;
					}
					$arr_n_fotos[]=$n_foto;

            	$ft++;
			}

			$ft=max($arr_n_fotos)+1;
			$nom_foto=$not['nt_alias'].'_'.$ft; 
			$orden=$ft+1;
			echo '<a href="upload_crop-not.php?nom_fot='.$nom_foto.'&orden='.$orden.'" class="btn btn-success btn-addon btn-rounded btn-lg"><i class="fa fa-plus"></i> Agregar otra imagen</a><hr>';
		}
	}

	public function ImgNot($id_prod) {

		$this->id=$id_prod;
		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_img_noticias` WHERE im_producto='$this->id' ORDER BY im_orden ASC");
		$row_cnt=$result->num_rows;

		if($row_cnt>0){

			while($row=$result->fetch_assoc()) {

				$info = pathinfo($row["im_nombre"]);
				$nom_foto =  basename($row["im_nombre"],'.'.$info['extension']);
                $row['nombreFot']=$nom_foto;

				$output[] = $row;
			}

			echo json_encode($output);
		}
	}

	public function editarOrdenImg($nombre,$value,$pk)
	{
		$sql = "UPDATE `tbl_img_noticias` set ".$nombre." = '".$value."' WHERE im_id='".$pk."'";
		
		if(mysqli_query(parent::con(), $sql)) {
			echo 'exito';
		} else {
			echo 'error';
		}
	}

	public function gestionImg($img,$id_not,$orden) {
		
		$this->imagen=$img;
		$this->id=$id_not;
		$this->orden=$orden;
			
		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_img_noticias` WHERE im_producto='$this->id' AND im_nombre='$this->imagen'");
		$row_cnt=$result->num_rows;
		
			if($row_cnt==0) {
				mysqli_query(parent::con(),"INSERT INTO `tbl_img_noticias`(`im_nombre`, `im_producto`, `im_orden`) VALUES ('$this->imagen','$this->id','$this->orden')");
			}

	}


	public function borrarImg($img) {
		$this->imagen=$img;

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_img_noticias` WHERE im_nombre='$this->imagen'")) {

			unlink("../img/proyectos/".$this->imagen);
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

	public function listaOrden()
	{

		$result=mysqli_query(parent::con(),"SELECT * FROM tbl_noticias 
		INNER JOIN tbl_img_noticias ON tbl_img_noticias.im_producto=tbl_noticias.nt_id
		WHERE im_orden=1 ORDER BY nt_orden ASC");
		
		$i=1;
		while ($reg=$result->fetch_assoc())
		{
			echo '<li class="ui-state-default" id="elemento-'.$reg["nt_id"].'"><img style="width:100px; height: 100px;" src="../img/proyectos/'.$reg['im_nombre'].'"/></li>';
			$i++;
		}
	}
	public function reordenar($id, $orden)
	{
		mysqli_query(parent::con(),"UPDATE tbl_noticias SET nt_orden = '$orden' WHERE nt_id = '$id' ");
	}
	
}
?>