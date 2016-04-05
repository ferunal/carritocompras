<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DetalleVenta
 *
 * @author lchacon
 */
include_once("Conexion.php");
class DetalleVenta {
    private $codigoVenta;
    private $codigoProducto;
    private $cantidad;
    private $descuento;
    //Metodo utilizado para insertar un detalle de venta a la base de datos
    //como variable pide la conexion que va a usar
    function insertarDetalleVenta($cn) {
        $rpta;
        try {
            //Elaboramos la sentencia
            $sql = "INSERT INTO DetalleVenta VALUES($this->codigoVenta, $this->codigoProducto,$this->cantidad,$this->descuento)";
            //Ejecutamos la sentencia
            $result = mysql_query($sql, $cn);
            if (!$result) {
                $rpta = false;
            } else {
                $rpta = true;
            }

        } catch (exception $e) {
            $rpta = false;
        }
        return $rpta;
    }

    function getCodigoVenta() {
        return $this->codigoVenta;
    }

    function getCodigoProducto() {
        return $this->codigoProducto;
    }

    function getCantidad() {
        return $this->cantidad;
    }
    function getDescuento() {
        return $this->descuento;
    }

    function setCodigoVenta($codigoVenta) {
        $this->codigoVenta= $codigoVenta;
    }

    function setCodigoProducto($codigoProducto) {
        $this->codigoProducto = $codigoProducto;
    }

    function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }

    function setDescuento($descuento) {
        $this->descuento = $descuento;
    }
}
?>

3.5. Clase Venta

El código fuente de la clase Venta seria el siguiente

<?php
include_once("Conexion.php");
include_once("DetalleVenta.php");

class Venta {
    private $codigoVenta;
    private $cliente;
    private $fecha;
    private $detalleVenta;
    //Metodo utilizado para obtener el codigo siguiente del producto
    function codigoSiguiente($cn) {
        $cod = 0;
        $sql = "SELECT IFNULL(MAX(codigoVenta),0)+1 as codigo FROM Venta";
        try {
            $result = mysql_query($sql, $cn);
            $registros = array();
            while ($reg = mysql_fetch_array($result)) {
                $cod = $reg['codigo'];
                break;
            }
        } catch (exception $e) {

        }
        return $cod;
    }
    //Metodo utilizado para insertar una venta a la base de datos
    function insertarVenta() {
        $rpta;
        try {
            //Creamos un objeto de la clase conexion
            $miconexion = new Conexion();
            //Obtenemos la conexion
            $cn = $miconexion->conectar();
            //Comenzamos la transaccion
            mysql_query("BEGIN", $cn);
            //Obtenemos el codigo del siguiente producto
            $this->codigoVenta=$this->codigoSiguiente($cn);
            //Elaboramos la sentencia
            $sql = "INSERT INTO Venta VALUES($this->codigoVenta,'$this->cliente',CURDATE())";
            //Ejecutamos la sentencia
            $result = mysql_query($sql, $cn);
            if (!$result) {
                //Si no obtiene resultados anulamos la transaccion
                mysql_query("ROLLBACK", $cn);
                $rpta = false;
            } else {
                //Recorremos el detalle y lo insertamos
                foreach($this->detalleVenta as $k => $v){
                    $detalle=new DetalleVenta();
                    $detalle->setCodigoVenta($this->codigoVenta);
                    $detalle->setCodigoProducto($v['codigo']);
                    $detalle->setCantidad($v['cantidad']);
                    $detalle->setDescuento($v['descuento']);
                    $rpta=$detalle->insertarDetalleVenta($cn);
                    if(!$rpta){
                        break;
                    }
                }
                if($rpta){
                    //Confirmamos la transaccion si se registra todos los detalles
                    mysql_query("COMMIT", $cn);
                }else{
                    //Negamos al transaccion si no se registra algun detalle
                    mysql_query("ROLLBACK", $cn);
                }
            }
            //Cerramos la conexion
            mysql_close($cn);
        } catch (exception $e) {
            try {
                mysql_query("ROLLBACK", $cn);
            } catch (exception $e1) {

            }
            try {
                mysql_close($cn);
            } catch (exception $e1) {

            }
            $rpta = false;
        }
        return $rpta;
    }

     //Metodo utilizado para obtener un producto
    function buscarVenta() {
        //Le deciamos que la locacion es lenguaje español
        setlocale(LC_CTYPE, 'es');
        //La sentencia a ejecutar
        $sql="SELECT ";
        $sql.="v.codigoVenta AS CodigoVenta, ";
        $sql.="v.cliente AS Cliente, ";
        $sql.="v.fecha AS Fecha, ";
        $sql.="d.codigoProducto AS CodigoProducto, ";
        $sql.="p.nombre AS Nombre, ";
        $sql.="p.precio AS Precio, ";
        $sql.="d.cantidad AS Cantidad, ";
        $sql.="d.descuento AS Descuento, ";
        $sql.="p.precio*d.cantidad AS Parcial, ";
        $sql.="((p.precio*d.cantidad)-d.descuento) AS SubTotal, ";
        $sql.="( ";
        $sql.="SELECT ";
        $sql.="SUM((dT.cantidad * pT.precio)-dT.descuento) AS TotalPagar ";
        $sql.="FROM ";
        $sql.="DetalleVenta AS dT INNER JOIN ";
        $sql.="Producto AS pT ON dT.codigoProducto = pT.codigoProducto ";
        $sql.="WHERE ";
        $sql.="dT.codigoVenta=v.codigoVenta ";
        $sql.=") AS TotalPagar ";
        $sql.="FROM ";
        $sql.="Venta AS v INNER JOIN ";
        $sql.="DetalleVenta AS d ON v.codigoVenta = d.codigoVenta INNER JOIN ";
        $sql.="Producto AS p ON d.codigoProducto = p.codigoProducto ";
        $sql.="ORDER BY ";
        $sql.="CodigoVenta, Nombre";
        try {
            //Creamos un objeto de la clase conexion
            $miconexion = new Conexion();
            //Obtenemos la conexion
            $cn = $miconexion->conectar();
            //Ejecutamos la sentencia
            $rs = mysql_query($sql, $cn);
            //Creamos un array que almacenara los datos de la sentencia
            $registros = array();
            //Recorremos el resultado de la consulta y lo almacenamos en el array
            while ($reg = mysql_fetch_array($rs)) {
                array_push($registros, $reg);
            }
            //Liberamos recursos
            mysql_free_result($rs);
            mysql_close($cn);
        } catch (exception $e) {
            try {
                mysql_free_result($rs);
            } catch (exception $e) {

            }
            try {
                mysql_close($cn);
            } catch (exception $e) {

            }
        }
        return $registros;
    }

    function getCodigoVenta() {
        return $this->codigoVenta;
    }

    function getCliente() {
        return $this->cliente;
    }
 
    function getFecha() {
        return $this->fecha;
    }

    function getDetalleVenta() {
        return $this->detalleVenta;
    }

    function setCodigoVenta($codigoVenta) {
        $this->codigoProducto = $codigoVenta;
    }

    function setCliente($cliente) {
        $this->cliente = $cliente;
    }

    function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    function setDetalleVenta($detalleVenta) {
        $this->detalleVenta = $detalleVenta;
    }

}
