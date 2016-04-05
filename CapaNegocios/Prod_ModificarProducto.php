<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once '../CapaDatos/Producto.php';
//Crea un objeto producto
$pro=new Producto();
//Establece el codigo del producto
$pro->setCodigoProducto($_REQUEST['txtCodigo']);
//Establece el nombre del producto
$pro->setNombre(strtoupper($_REQUEST['txtNombre']));
//Establece el precio del producto
$pro->setPrecio($_REQUEST['txtPrecio']);
//Llama al metodo actualizarProducto
$rpta=$pro->actualizarProducto();
//Si rpta es verdadero es porque se actualizo el Producto
if($rpta){
    header("Location: ../mensaje.php?mensaje=Se modifico el producto de manera correcta");
}else{
    header("Location: ../mensaje.php?mensaje=No se pudo modificar el producto");
}
?>