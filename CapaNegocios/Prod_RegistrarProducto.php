<?php

include_once '../CapaDatos/Producto.php';
//Crea un objeto producto
$pro = new Producto();
//Establece el nombre del producto y lo convierte a mayusculas
$pro->setNombre(strtoupper($_REQUEST['txtNombre']));
//Establece el precio
$pro->setPrecio($_REQUEST['txtPrecio']);
//Llama al metodo insertar producto de producto
$rpta = $pro->insertarProducto();
//Si la respuesta es verdadera es porque se registro el producto
if ($rpta) {
    //Redireccionamos a un archivo que se llama mensaje para mostrar el resultado
    //del registro
    header("Location: ../mensaje.php?mensaje=Se registro el producto de manera correcta");
} else {
    header("Location: ../mensaje.php?mensaje=No se pudo registrar el producto");
}
?>