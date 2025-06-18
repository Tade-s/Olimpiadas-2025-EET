<?php
/*
Este código PHP realiza la función de que el admin pueda eliminar
productos de la web

@author Schimpf Tadeo
@version 1.0
@date 16/06/2025
*/

if (!isset($_POST["id_producto"])) {
    exit("No hay datos");
}

include_once "funciones.php";
eliminarProducto($_POST["id_producto"]);
header("Location: productos.php");