<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Programando con Café - Carrito de compras con PHP</title>
    </head>
    <body>
        <?php include_once 'Cabezera.php'; ?>
        <form method="post" action="CapaNegocios/Prod_RegistrarProducto.php">
            <div>
                <table border="1">
                        <tr>
                            <td>Nombre</td>
                            <td><input type="text" name="txtNombre" value="" /></td>
                        </tr>
                        <tr>
                            <td>Precio</td>
                            <td><input type="text" name="txtPrecio" value="0" /></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input type="submit" value="Registrar" name="btnRegistrar" /></td>
                        </tr>
                </table>
                </div>
        </form>

    </body>
</html>