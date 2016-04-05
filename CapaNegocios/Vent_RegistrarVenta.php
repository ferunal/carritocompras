<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//Obtenemos la sesion
session_start();
session_register('itemsEnCesta');
include_once '../CapaDatos/Venta.php';
//Creamos una clase venta
$ven=new Venta();
//Establecemos  el nombre del cliente
$ven->setCliente(strtoupper($_REQUEST['txtCliente']));
//Obtenemos el objeto que esta en la sesion
$itemsEnCesta=$_SESSION['itemsEnCesta'];
//Establece el detalle con la informacion de la sesion
$ven->setDetalleVenta($itemsEnCesta);
//Llamamos al metodo insertar ventar
$rpta=$ven->insertarVenta();
//Si rpta es verdadero es proque se inserto
if($rpta){
    //Eliminamos la sesion
    session_destroy();
    //Redireccionamos a un archivo para que nos muestre el mensaje
    header("Location: ../mensaje.php?mensaje=Se registro la venta de manera correcta");
}else{
    header("Location: ../mensaje.php?mensaje=No se pudo registrar la venta");
}
?>
