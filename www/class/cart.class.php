<?php 

class Cart 
{
	private $conn;

	function __construct() {
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
		$this->conn = $db->connect();
    }
	
	private $arrCart=array();

	public function getCartContent()
	{
		$sid = session_id();

		unset($this->arrCart);

        $desc = $this->getDescuento();

        $sql = "SELECT id, producto_id, variacion, cantidad, pd_titulo, pd_descuento, pd_descuento_especial, pd_categoria_envio, pd_bulto_envio, pr_id, pr_codigo, pr_precio, pr_sku FROM tbl_cart
		INNER JOIN tbl_productos ON tbl_productos.pd_id=tbl_cart.producto_id
		INNER JOIN tbl_productos_parent ON tbl_productos_parent.pr_id=tbl_cart.precio 
		WHERE session_id = '$sid'";
        $query = $this->conn->prepare($sql);
        $query->execute();

        if ($query->rowCount()>0) {
            while($reg = $query->fetch())
            {   

                $queryImg = $this->conn->prepare("SELECT im_400x400 FROM tbl_img WHERE im_producto = ? ORDER BY im_orden ASC LIMIT 1");
                $queryImg->bindParam(1, $reg['producto_id'], PDO::PARAM_INT);
                $queryImg->execute();

                if ($queryImg->rowCount()>0) {
                    $img = $queryImg->fetch();
                    $reg['imagen']=$img['im_400x400'];
                } else {
                    $reg['imagen'] = 'sin-imagen.jpg';
                }


                if ($reg['pd_descuento_especial']!=0) {
                    $descuento = ($reg['pd_descuento_especial'] * $reg['pr_precio']) / 100;
                    $reg['precioFinal'] = $reg['pr_precio'] - $descuento;
                } else {
                    if ($desc['status']=='activado' and $reg['pd_descuento']=='si') {
                        $descuento = ($desc['descuento'] * $reg['pr_precio']) / 100;
                        $reg['precioFinal'] = $reg['pr_precio'] - $descuento;
                    } else {
                        $reg['precioFinal'] = $reg['pr_precio'];
                    }
                }
				$reg['totalItem'] = $reg['precioFinal'] * $reg['cantidad'];
				$reg['totalItemSinFormat'] = $reg['totalItem'];
				$reg['totalItem'] = number_format(round($reg['totalItem']),0,',','.');

				$reg['precioOriginalSinFormat'] = $reg['pr_precio'];
				$reg['precioOriginal'] = number_format(round($reg['pr_precio']),0,',','.');
				$reg['precioFinalSinFormat'] = $reg['precioFinal'];
				$reg['precioFinal'] = number_format(round($reg['precioFinal']),0,',','.');

                $this->arrCart[] = $reg;
            }
            return $this->arrCart;
        } 

	}
	
	private $productId;
	private $productVar;
	private $cantidad;
	private $productPrec;
	
	public function addToCart($prod,$variacion,$cant,$prec)
	{ 

		$this->productId = $prod;
		$this->productVar = $variacion;
		$this->cantidad = $cant;
		$this->productPrec = $prec;

		$sid = session_id();
		$this->productVar = str_replace(",", ", ", $this->productVar);
		
		$query = $this->conn->prepare("SELECT producto_id FROM tbl_cart WHERE producto_id = ? AND variacion = ? AND session_id = '$sid'");
		$query->bindParam(1, $this->productId, PDO::PARAM_INT);
        $query->bindParam(2, $this->productVar, PDO::PARAM_STR);

        if ($query->execute()) {
            if ($query->rowCount()>0) {

                $sql = $this->conn->prepare("UPDATE tbl_cart SET cantidad = ?
				WHERE session_id = '$sid' AND producto_id = ? AND variacion = ?");
				$sql->bindParam(1, $this->cantidad);
				$sql->bindParam(2, $this->productId);
				$sql->bindParam(3, $this->productVar);
				$sql->execute();
				
            } else {

				$sql = $this->conn->prepare("INSERT INTO tbl_cart (producto_id, variacion, cantidad, precio, session_id, date) VALUES (?,?,?,?,?,NOW())");
				$sql->bindParam(1, $this->productId);
				$sql->bindParam(2, $this->productVar);
				$sql->bindParam(3, $this->cantidad);
				$sql->bindParam(4, $this->productPrec);
				$sql->bindParam(5, $sid);
				$sql->execute();

			}
		} 
				
	}


	public function getProdAddCart($prec)
	{
		$desc = $this->getDescuento();
		$this->productPrec = $prec;

        $sql = "SELECT * FROM tbl_productos
		INNER JOIN tbl_productos_parent ON tbl_productos_parent.pr_producto=tbl_productos.pd_id 
		WHERE pr_id = ? ";
        $query = $this->conn->prepare($sql);
		$query->bindParam(1, $this->productPrec);
        $query->execute();

		
		if ($query->rowCount()>0) {
			$reg = $query->fetch();  

                if ($reg['pd_descuento_especial']!=0) {
                    $descuento = ($reg['pd_descuento_especial'] * $reg['pr_precio']) / 100;
                    $reg['precioFinal'] = $reg['pr_precio'] - $descuento;
                } else {
                    if ($desc['status']=='activado' and $reg['pd_descuento']=='si') {
                        $descuento = ($desc['descuento'] * $reg['pr_precio']) / 100;
                        $reg['precioFinal'] = $reg['pr_precio'] - $descuento;
                    } else {
                        $reg['precioFinal'] = $reg['pr_precio'];
                    }
                }

            return $reg;
		} else {
			return null;
		} 

	}

	public function verificarStockCart($cant,$id)
	{
		$query = $this->conn->prepare("SELECT pr_stock FROM `tbl_productos_parent` WHERE pr_id = ?");
		$query->bindParam(1, $id, PDO::PARAM_INT);

        if ($query->execute()) {
            if ($query->rowCount()>0) {
                $stock = $query->fetch();
				if ($stock['pr_stock'] < $cant) {
					return $stock['pr_stock'];
				} else {
					return 'ok';
				}
            }
		} 
	}

	private $idCart;

	public function updateCart()
	{ 		
		$this->idCart = $_REQUEST['idCart'];
		$this->cantidad = $_REQUEST['cant'];

		$sql = $this->conn->prepare("UPDATE tbl_cart SET cantidad = ? WHERE id = ? ");
		$sql->bindParam(1, $this->cantidad);
		$sql->bindParam(2, $this->idCart);
		$sql->execute();
	}

	public function deleteCart()
	{ 	
		$sid = session_id();
		$this->idCart = $_REQUEST['idCart'];

		$query = $this->conn->prepare("DELETE FROM tbl_cart WHERE session_id = '$sid' AND id = ?");
		$query->bindParam(1, $this->idCart);
        $query->execute();
	}

	public function getCountCart()
	{
		$sid = session_id();

		$query = $this->conn->prepare("SELECT SUM(cantidad) AS TotalItems FROM tbl_cart
		INNER JOIN tbl_productos ON tbl_productos.pd_id=tbl_cart.producto_id
		INNER JOIN tbl_productos_parent ON tbl_productos_parent.pr_id=tbl_cart.precio
		WHERE session_id = '$sid'");
        $query->execute();

        if ($query->rowCount() > 0) {
            $row = $query->fetch();
			if ($row['TotalItems']) {
				return $row['TotalItems'];
			} else {
				return 0;
			}
        } 
	}

	public function isCartEmpty()
	{
		$sid = session_id();

		$query = $this->conn->prepare("SELECT id FROM tbl_cart WHERE session_id = '$sid'");
        $query->execute();

        if ($query->rowCount()>0) {
            return false;
        } else {
            return true;
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


	public function deleteAbandonedCart()
	{
		$haceunahora = date('Y-m-d H:i:s', mktime(date('H') - 10,date('i'),date('s'), date('m'), date('d'), date('Y')));
		$query = $this->conn->prepare("DELETE FROM tbl_cart WHERE date < '$haceunahora'");
        $query->execute();
	}
}

?>