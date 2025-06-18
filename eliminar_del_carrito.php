<?php
/*
Este código PHP realiza la función de que el usuario pueda eliminar
productos del carrito

@author Schimpf Tadeo
@version 1.0
@date 16/06/2025
*/

include_once "funciones.php";
if (!isset($_POST["id_producto"])) {
    exit("No hay id_producto");
}
quitarProductoDelCarrito($_POST["id_producto"]);

if (isset($_POST["redireccionar_carrito"])) {
    header("Location: ver_carrito.php");
} else {
    header("Location: tienda.php");
}