
<?php
include_once "funciones.php";
if (!isset($_POST["id_producto"])) {
    exit("No hay id_producto");
}
agregarProductoAlCarrito($_POST["id_producto"]);
header("Location: tienda.php");

/*
Este código PHP realiza la función de agregar un producto al carrito

@author Schimpf Tadeo
@version 1.0
@date 16/06/2025
*/