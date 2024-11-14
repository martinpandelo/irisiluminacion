<?php 

class Checkout
{
	private $conn;
	private $orderId;
	private $orderAmount;
	private $discount;
	public $id;
	public $stock;
	public $cantidad;
	public $variacion;
	public $status;
	public $sid;
	public $link;
	public $codigo;


	function __construct() {
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
		$this->conn = $db->connect();
    }
	

	public function saveOrder()
	{		
		extract($_POST);

			if ($envio=='D') {
				if (isset($_POST['chkDatos'])) {
					$per_nombre=$envio_nombre;
					$per_apellido=$envio_apellido;
					$per_dni=$envio_dni;
					$per_telefono=$envio_telefono;
					$per_direccion=$envio_direccion;
					$per_calle_num=$envio_calle_num;
					$per_piso=$envio_piso;
					$per_dpto=$envio_dpto;
					$per_ciudad=$envio_ciudad;
					$per_provincia=$envio_provincia;
					$per_codpostal=$env_codpostal;
				}
			} elseif ($envio=='S') {
				$envio_nombre=$per_nombre;
				$envio_apellido=$per_apellido;
				$envio_dni=$per_dni;
				$envio_telefono=$per_telefono;
				$envio_direccion=$per_direccion;
				$envio_calle_num=$per_calle_num;
				$envio_piso=$per_piso;
				$envio_dpto=$per_dpto;
				$envio_ciudad=$per_ciudad;
				$envio_provincia=$per_provincia;
				$env_codpostal=$per_codpostal;
			}

			$sid = session_id();
			$per_nombre = ucwords($per_nombre);
			$per_apellido = ucwords($per_apellido);
			$orderAmount=$totalSinEnvio+$costo_envio;

			// descuento por pago transferencia
			if ($opcion_pago=='transferencia') {
				$arrDescTransf = $this->descuentoTransferencia();
				if ($arrDescTransf) {
					$porcentaje_descuento = $arrDescTransf['porcentaje_descuento'];
					$descuento = ($porcentaje_descuento * $totalSinEnvio) / 100;
					$orderAmount = $totalSinEnvio + $costo_envio - $descuento;
				} 
			}
			
			$sql = $this->conn->prepare("INSERT INTO `ordenes` (`session_id`, `fecha_alta`, `or_nombre`, `or_apellido`, `or_dni`, `or_telefono`, `or_email`, `or_calle`, `or_calle_num`, `or_piso`, `or_depto`, `or_ciudad`, `or_provincia`, `or_codpostal`, `or_medio_pago`, `total_compra`, `or_estado`, `or_notas`) VALUES ('$sid',NOW(),?,?,?,?,?,?,?,?,?,?,?,?,?,?,10,?)");
			$sql->bindParam(1, $per_nombre);
			$sql->bindParam(2, $per_apellido);
			$sql->bindParam(3, $per_dni);
			$sql->bindParam(4, $per_telefono);
			$sql->bindParam(5, $per_email);
			$sql->bindParam(6, $per_direccion);
			$sql->bindParam(7, $per_calle_num);
			$sql->bindParam(8, $per_piso);
			$sql->bindParam(9, $per_dpto);
			$sql->bindParam(10, $per_ciudad);
			$sql->bindParam(11, $per_provincia);
			$sql->bindParam(12, $per_codpostal);
			$sql->bindParam(13, $opcion_pago);
			$sql->bindParam(14, $orderAmount);
			$sql->bindParam(15, $mensaje);
			if ($sql->execute()) { 
				$orderId = $this->conn->lastInsertId();
				if ($orderId) {

					$ObjCart = new Cart();
					$cartContent = $ObjCart->getCartContent();
					$numItem = count($cartContent);
					
					// save order items
					for ($i = 0; $i < $numItem; $i++) {
						extract($cartContent[$i]);

						$sql = $this->conn->prepare("INSERT INTO `items_orden` (id_orden, producto_id, codigo, variacion, sku, cantidad, precio) VALUES (?,?,?,?,?,?,?)");
						$sql->bindParam(1, $orderId);
						$sql->bindParam(2, $producto_id);
						$sql->bindParam(3, $pr_codigo);
						$sql->bindParam(4, $variacion);
						$sql->bindParam(5, $pr_sku);
						$sql->bindParam(6, $cantidad);
						$sql->bindParam(7, $precioFinalSinFormat);
						if (!$sql->execute()) {
							$query = $this->conn->prepare("DELETE FROM ordenes WHERE id_orden = ?");
							$query->bindParam(1, $orderId);
							$query->execute();
							return false;
						}
					}
					
					// save shipping info
					$sql = $this->conn->prepare("INSERT INTO `envio_orden` (`id_orden`, `env_tipo`, `env_id_correo`, `env_nom_correo`, `env_descripcion`, `env_despacho`, `env_modalidad`, `env_servicio`, `env_horas_entrega`, `env_valor`, `env_nombre`, `env_apellido`, `env_telefono`, `env_dni`, `env_calle`, `env_numero`, `env_piso`, `env_depto`, `env_codpostal`, `env_localidad`, `env_provincia`, `env_estado`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,'B')");
					$sql->bindParam(1, $orderId);
					$sql->bindParam(2, $envio);
					$sql->bindParam(3, $id_correo);
					$sql->bindParam(4, $nombre_correo);
					$sql->bindParam(5, $descripcion_correo);
					$sql->bindParam(6, $despacho);
					$sql->bindParam(7, $modalidad);
					$sql->bindParam(8, $servicio);
					$sql->bindParam(9, $horas_entrega);
					$sql->bindParam(10, $costo_envio);
					$sql->bindParam(11, $envio_nombre);
					$sql->bindParam(12, $envio_apellido);
					$sql->bindParam(13, $envio_telefono);
					$sql->bindParam(14, $envio_dni);
					$sql->bindParam(15, $envio_direccion);
					$sql->bindParam(16, $envio_calle_num);
					$sql->bindParam(17, $envio_piso);
					$sql->bindParam(18, $envio_dpto);
					$sql->bindParam(19, $env_codpostal);
					$sql->bindParam(20, $envio_ciudad);
					$sql->bindParam(21, $envio_provincia);

					if (!$sql->execute()) {
						$query = $this->conn->prepare("DELETE FROM ordenes WHERE id_orden = ?");
						$query->bindParam(1, $orderId);
						$query->execute();
						return false;
					}


					// save item descuento
					if ($opcion_pago=='transferencia') {
						if ($arrDescTransf) {
							$desc_codigo='PETRANSF';
							$desc_descripcion='Descuento de '.$porcentaje_descuento.'% por transferencia bancaria';

							$sql = $this->conn->prepare("INSERT INTO `descuentos_orden` (id_orden, desc_codigo, desc_descripcion, desc_precio) VALUES (?,?,?,?)");
							$sql->bindParam(1, $orderId);
							$sql->bindParam(2, $desc_codigo);
							$sql->bindParam(3, $desc_descripcion);
							$sql->bindParam(4, $descuento);
							if (!$sql->execute()) {
								return false;
							}
						} 
					}
					return $orderId;
				}

			} else {
				return false;
			}
	}

	public function removeItemsCart()
	{
		$ObjCart = new Cart();
		$cartContent = $ObjCart->getCartContent();
		$numItem = count($cartContent);
					
		for ($i = 0; $i < $numItem; $i++) {
			$query = $this->conn->prepare("DELETE FROM tbl_cart WHERE id = ?");
			$query->bindParam(1, $cartContent[$i]['id']);
        	$query->execute();				
		}
	}

	public function getOrderAmount($orderId)
	{
		$this->orderAmount = 0;
		$this->discount = 0;

		$query = $this->conn->prepare("SELECT SUM(items_orden.precio * items_orden.cantidad) AS costos FROM items_orden WHERE items_orden.id_orden = ?
		UNION
		SELECT env_valor FROM envio_orden WHERE id_orden = ? ");
		$query->bindParam(1, $orderId);
		$query->bindParam(2, $orderId);

		if ($query->execute()) {
            if ($query->rowCount()>0) {
                while($reg = $query->fetch())
				{   
					$this->orderAmount += $reg['costos'];
				}

				$query = $this->conn->prepare("SELECT desc_precio FROM descuentos_orden WHERE id_orden = ?");
				$query->bindParam(1, $orderId);

				if ($query->execute()) {
					if ($query->rowCount()>0) {
						while($row = $query->fetch())
						{   
							$this->discount += $row['desc_precio'];
						}
		
					} 
				}

				$this->orderAmount = $this->orderAmount - $this->discount;

            } 
		}

		return $this->orderAmount;	
	}
	
	private $arrOrderContent=array();

	public function GetOrderContent($orderId)
	{
		$query = $this->conn->prepare("SELECT id_orden, item_id, codigo, variacion, sku, cantidad, precio, pd_id, pd_codigo_mla, pd_thumbnail, pd_titulo FROM items_orden
		INNER JOIN tbl_productos ON tbl_productos.pd_id=items_orden.producto_id 
		WHERE items_orden.producto_id = tbl_productos.pd_id AND items_orden.id_orden = ?");
        $query->bindParam(1, $orderId);
        $query->execute();

        if ($query->rowCount()>0) {
			while($reg = $query->fetch())
            {  
				$this->arrOrderContent[] = $reg;
			}
            return $this->arrOrderContent;
        } else {
            return null;
        }	
	}
	
	public function GetOrderInfo($orderId)
	{
		$query = $this->conn->prepare("SELECT * FROM ordenes 
		INNER JOIN envio_orden ON envio_orden.id_orden=ordenes.id_orden 
		WHERE ordenes.id_orden = ?");
        $query->bindParam(1, $orderId);
        $query->execute();

        if ($query->rowCount()>0) {
            return $query->fetch();
        } else {
            return null;
        }
	}

	public function GetOrderDiscount($orderId)
	{
		$query = $this->conn->prepare("SELECT * FROM descuentos_orden WHERE id_orden = ?");
        $query->bindParam(1, $orderId);
        $query->execute();

        if ($query->rowCount()>0) {
            return $query->fetchAll();
        } else {
            return null;
        }	
	}

	public function comb_provincias()
	{
		$query = $this->conn->prepare("SELECT * FROM provincias ORDER BY provincia ASC");
        $query->execute();

            if ($query->rowCount()>0) {
                return $query->fetchAll();
            } else {
                return null;
            }
	}

	public function codProvincia($prov)
	{
		$query = $this->conn->prepare("SELECT codigo FROM provincias WHERE provincia= ? ");
		$query->bindParam(1, $prov, PDO::PARAM_STR);

        if ($query->execute()) {
            if ($query->rowCount()>0) {
                $row = $query->fetch();
				return $row["codigo"];
            } else {
				return null;
			}
		} 
	}
	
	public function ActualizarOrder($orderId,$pago_id,$pago_status,$pago_forma,$total,$estado)
	{
		$sql = $this->conn->prepare("UPDATE ordenes SET `pago_id`=?,`pago_status`=?,`pago_forma`=?,`total_pagado`=?,`or_estado`=?
		WHERE id_orden = ?");
		$sql->bindParam(1, $pago_id);
		$sql->bindParam(2, $pago_status);
		$sql->bindParam(3, $pago_forma);
		$sql->bindParam(4, $total);
		$sql->bindParam(5, $estado);
		$sql->bindParam(6, $orderId);

		if($sql->execute()) {
			return true;
		} else {
			return false;
		}
	}

	public function enviaEmailStatus($id_orden,$status) {
		
		$this->orderId = $id_orden;
		$this->status = $status;

		$orderInfo = $this->GetOrderInfo($this->orderId);

		$query = $this->conn->prepare("SELECT * FROM status_ordenes WHERE st_id = ? ");
		$query->bindParam(1, $this->status, PDO::PARAM_INT);

        if ($query->execute()) {
            if ($query->rowCount()>0) {
                $row = $query->fetch();
				$estado_orden = $row["st_nombre"];
				$text_email = preg_replace("/[\r\n|\n|\r]+/", " ", $row["st_text_email"]);
            } 
		}
		
		//Envio de correo por Postmark
        $url ="https://api.postmarkapp.com/email/withTemplate";
        $headers = array(
            "Content-Type: application/json",
            "Accept: application/json",
            "X-Postmark-Server-Token: 11c3f3a2-e5c5-4845-ac1a-1ebadc00990a"
		);
		$parametros_post = '{
            "From": "ventasweb@irisiluminacion.com.ar",
            "To": "'.$orderInfo["or_email"].'",
            "TemplateAlias": "estados-orden",
			"TemplateModel": {
				"site_url": "'.constant('URL').'",
				"estado_orden": "'.$estado_orden.'",
				"orden_id": "'.$this->orderId.'",
				"fecha_orden": "'.date("d M Y", strtotime($orderInfo["fecha_alta"])).'",
				"name": "'.$orderInfo["or_nombre"].'",
				"text": "'.$text_email.'",
				"company_name": "Iris Iluminación",
                "company_address": "Blvr. Buenos Aires 1520, Luis Guillón"
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
	}

	public function enviaEmailCodigo($id_orden,$codigo,$link) {
		
		$this->orderId = $id_orden;
		$this->codigo = $codigo;
		$this->link = $link;

		$orderInfo=$this->GetOrderInfo($this->orderId);

		$text_email = "En 24 hs ya podés consultar el estado de tu envío en el siguiente link.";
		

		//Envio de correo por Postmark
		$url ="https://api.postmarkapp.com/email/withTemplate";
		$headers = array(
			"Content-Type: application/json",
			"Accept: application/json",
			"X-Postmark-Server-Token: 11c3f3a2-e5c5-4845-ac1a-1ebadc00990a"
		);
		$parametros_post = '{
            "From": "ventasweb@irisiluminacion.com.ar",
            "To": "'.$orderInfo["or_email"].'",
            "TemplateAlias": "codigo-seguimiento",
			"TemplateModel": {
				"site_url": "'.constant('URL').'",
				"cod_seguimiento": "'.$this->codigo.'",
				"link_seguimiento": "'.$this->link.'",
				"name": "'.$orderInfo["or_nombre"].'",
				"text": "'.$text_email.'",
				"company_name": "Iris Iluminación",
                "company_address": "Blvr. Buenos Aires 1520, Luis Guillón"
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
	}

	public function statusOrder($id_orden,$status) {
		$this->orderId = $id_orden;
		$this->status = $status;
		
		$sql = $this->conn->prepare("UPDATE ordenes SET `or_estado`= ?
		WHERE id_orden = ? ");
		$sql->bindParam(1, $this->status);
		$sql->bindParam(2, $this->orderId);

		if($sql->execute()) {
			if ($this->status > 20 && $this->status < 70) {
				$this->enviaEmailStatus($this->orderId,$this->status);
			}
			return true;
		} else {
			return false;
		}
	}

	public function CheckOrder($id_orden) {
		
		$this->orderId = $id_orden;
		$sid = session_id();
		
		$query = $this->conn->prepare("SELECT id_orden FROM ordenes WHERE id_orden = ? AND session_id='$sid' AND or_estado=10 ");
		$query->bindParam(1, $this->orderId, PDO::PARAM_INT);

        if ($query->execute()) {
            if ($query->rowCount()>0) {
                return true;
            } else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function CheckOrderConfirmada($id_orden) {
		
		$this->orderId = $id_orden;
		
		$query = $this->conn->prepare("SELECT id_orden FROM ordenes WHERE id_orden = ? AND session_id!='010' AND or_estado=20 ");
		$query->bindParam(1, $this->orderId, PDO::PARAM_INT);

        if ($query->execute()) {
            if ($query->rowCount()>0) {
                return true;
            } else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function confirmarOrder($id_orden) {
		
		$this->orderId = $id_orden;
		
		$sql = $this->conn->prepare("UPDATE ordenes SET `session_id`='010' WHERE id_orden = ? ");
		$sql->bindParam(1, $this->orderId);

		if($sql->execute()) {
			return true;
		} else {
			return false;
		}
	}
	
	public function datosTransferencia()
	{
		$query = $this->conn->prepare("SELECT * FROM tbl_datos_transferencia WHERE id=1");
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


	public function getStockItem($producto_id)
	{
		$query = $this->conn->prepare("SELECT pr_stock FROM `tbl_productos_parent` WHERE pr_producto = ? AND pr_stock!=0 ");
		$query->bindParam(1, $producto_id, PDO::PARAM_INT);

        if ($query->execute()) {
            if ($query->rowCount()>0) {
                $row = $query->fetch();
				return $row['pr_stock'];
            }
		} 
	}


	public function actualizarStockItem($producto_id,$stock,$variacion)
	{
		$this->id = $producto_id;
		$this->stock = $stock;
		$this->variacion = $variacion;

		$stockActual = $this->getStockItem($this->id);
        $nuevoStock = $stockActual - $this->stock;

		if ($this->variacion == '0') {
			$sql = $this->conn->prepare("UPDATE tbl_productos_parent SET `pr_stock`= ? WHERE pr_producto = ? AND pr_stock!=0");
			$sql->bindParam(1, $nuevoStock);
			$sql->bindParam(2, $this->id);
			$sql->execute();
		} else {
			$sql = $this->conn->prepare("UPDATE tbl_productos_parent SET `pr_stock`= ? WHERE pr_producto = ? AND pr_variacion = ? AND pr_stock!=0");
			$sql->bindParam(1, $nuevoStock);
			$sql->bindParam(2, $this->id);
			$sql->bindParam(3, $this->variacion);
			$sql->execute();
		}

	}
	
}

?>