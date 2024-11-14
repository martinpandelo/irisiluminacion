<?php 

class Envio 
{

	private $conn;

	function __construct() {
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
		$this->conn = $db->connect();
    }
	
	
	private $codPostal;
	private $totalEnvio;
	private $cantProd;
	private $catEnvio;
	private $provincia;


	public function seleccionarProvincia($codPostal) {

		$query = $this->conn->prepare("SELECT * FROM codigos_postales 
		INNER JOIN provincias ON provincias.id = codigos_postales.provincia_id
		WHERE codigopostal = ? LIMIT 1");
        $query->bindParam(1, $codPostal, PDO::PARAM_INT);
        $query->execute();

        if ($query->rowCount()>0) {
            return $query->fetchAll();
        } else {
            return null;
        }
	}

	public function codigoProvincia($prov) {
		$query = $this->conn->prepare("SELECT codigo FROM provincias WHERE provincia = ?");
		$query->bindParam(1, $prov, PDO::PARAM_STR);

        if ($query->execute()) {
            if ($query->rowCount()>0) {
                $row = $query->fetch();
				return $row['codigo'];
            } else {
				return false;
			}
		} 
	}

	private $arrEnv=array();

	public function enviosPresonalizado($prov,$cant,$cat,$total) {

		$query = $this->conn->prepare("SELECT * FROM tbl_envios 
		INNER JOIN provincias ON provincias.id = tbl_envios.env_provincia
		WHERE env_provincia = ? ");
        $query->bindParam(1, $prov, PDO::PARAM_INT);
        $query->execute();

        if ($query->rowCount()>0) {
            
			while($row = $query->fetch())
            {  
				if ($total > $row['monto_mayor_a'] && $cat == 'normal') {
					$price=0;
					$row['price']=$price;
				} else {
					$precioNormal=$row['price_normal'];
					$precioEspecial=$row['price_especial'];

					if ($cat=='especial') {
						$price=$precioEspecial;
					} else {
						$price=$precioNormal;
					}
					$cincuenta=(50*$price)/100;
					$veinticinco=(25*$price)/100;

					if ($cant==1) {
						$row['price']=$price;
					} elseif ($cant==2) {
						$row['price']=$price+$cincuenta;
					} else {
						$row['price']=$price+$cincuenta+$veinticinco;
					}
				}
				if ($price==0) {
					$row['valor'] = 'Gratis';
				} else {
					$row['valor'] = '$'.number_format(round($row['price']),0,',','.');
				}
				$this->arrEnv[] = $row;
			}
            return $this->arrEnv;

        } else {
            return null;
        }

	}

	public function calcularEnvio()
	{
		$arrProvincia = array();

		$this->codPostal = strip_tags($_REQUEST['c_postal'], ENT_QUOTES);
		$this->cantProd = strip_tags($_REQUEST['cantproductos'], ENT_QUOTES);
		$this->catEnvio = strip_tags($_REQUEST['categoria'], ENT_QUOTES);
		$this->totalEnvio = strip_tags($_REQUEST['total'], ENT_QUOTES);
		
		$_SESSION['codPostal'] = $this->codPostal;
		$arrProvincia = $this->seleccionarProvincia($this->codPostal);

		if ($arrProvincia) {
			$this->provincia = $arrProvincia[0]['provincia_id'];
			return $this->enviosPresonalizado($this->provincia,$this->cantProd,$this->catEnvio,$this->totalEnvio);
		} else {
			return null;
		}
		
	}

	
}

?>