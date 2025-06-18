<?php

/*
Este código PHP tiene todas las funciones necesarias para que el código 
compile correctamente según las necesidades

COMENTARIO
PODRÍA HABER AGREGADO ALGUNAS FUNCIONES MÁS COMO LA DE VERIFICACÍON 
DEL FORMULARIO

@author Schimpf Tadeo
@version 1.0
@date 16/06/2025
*/

function obtenerIdUsuario()
{
    iniciarSesionSiNoEstaIniciada();
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
    return $_SESSION['user_id'];
}
function esAdmin() {
    return isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'admin';
}

function esEmpleado() {
    return isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'empleado';
}

function esCliente() {
    return isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'cliente';
}
function mostrarMensaje(string $mensaje, bool $exito = true): void {
    iniciarSesionSiNoEstaIniciada();
    $_SESSION['flash_message'] = [
        'texto' => $mensaje,
        'exito' => $exito
    ];
    header("Location: mensaje.php");
    exit;
}
function obtenerProductosEnCarrito()
{
    $bd = obtenerConexion();
    $idUsuario = obtenerIdUsuario();
    $sentencia = $bd->prepare("SELECT p.id, p.nombre, p.descripcion, p.precio, p.cantidad AS stock, cu.cantidad AS cantidad_comprada
        FROM productos p
        INNER JOIN carrito_usuarios cu ON p.id = cu.id_producto
        WHERE cu.id_usuario = ?");
    $sentencia->execute([$idUsuario]);
    return $sentencia->fetchAll(PDO::FETCH_OBJ);
}
function quitarProductoDelCarrito($idProducto)
{
    $bd = obtenerConexion();
    $idUsuario = obtenerIdUsuario();
    $sentencia = $bd->prepare("DELETE FROM carrito_usuarios WHERE id_usuario = ? AND id_producto = ?");
    return $sentencia->execute([$idUsuario, $idProducto]);
}


function obtenerProductos()
{
    $bd = obtenerConexion();
    $sentencia = $bd->query("SELECT id, nombre, descripcion, precio, cantidad FROM productos");
    return $sentencia->fetchAll(PDO::FETCH_OBJ);
}
function productoYaEstaEnCarrito($idProducto)
{
    $ids = obtenerIdsDeProductosEnCarrito();
    foreach ($ids as $id) {
        if ($id == $idProducto) return true;
    }
    return false;
}

function obtenerIdsDeProductosEnCarrito()
{
    $bd = obtenerConexion();
    $idUsuario = obtenerIdUsuario();
    $sentencia = $bd->prepare("SELECT id_producto FROM carrito_usuarios WHERE id_usuario = ?");
    $sentencia->execute([$idUsuario]);
    return $sentencia->fetchAll(PDO::FETCH_COLUMN);
}

function agregarProductoAlCarrito($idProducto)
{
    $bd = obtenerConexion();
    $idUsuario = obtenerIdUsuario();
    $sentencia = $bd->prepare("INSERT IGNORE INTO carrito_usuarios(id_usuario, id_producto) VALUES (?, ?)");
    return $sentencia->execute([$idUsuario, $idProducto]);
}


function iniciarSesionSiNoEstaIniciada()
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function eliminarProducto($id)
{
    $bd = obtenerConexion();
    $sentencia = $bd->prepare("DELETE FROM productos WHERE id = ?");
    return $sentencia->execute([$id]);
}

function guardarProducto($nombre, $descripcion, $precio)
{
    $bd = obtenerConexion();
    $sentencia = $bd->prepare("INSERT INTO productos(nombre, descripcion, precio) VALUES(?, ?, ?)");
    return $sentencia->execute([$nombre, $descripcion, $precio]);
}


function obtenerVariableDelEntorno($key)
{
    if (defined("_ENV_CACHE")) {
        $vars = _ENV_CACHE;
    } else {
        $file = "env.php";
        if (!file_exists($file)) {
            throw new Exception("El archivo de las variables de entorno ($file) no existe. Favor de crearlo");
        }
        $vars = parse_ini_file($file);
        define("_ENV_CACHE", $vars);
    }
    if (isset($vars[$key])) {
        return $vars[$key];
    } else {
        throw new Exception("La clave especificada (" . $key . ") no existe en el archivo de las variables de entorno");
    }
}
function obtenerConexion()
{
    $password = obtenerVariableDelEntorno("MYSQL_PASSWORD");
    $user = obtenerVariableDelEntorno("MYSQL_USER");
    $dbName = obtenerVariableDelEntorno("MYSQL_DATABASE_NAME");
    $database = new PDO('mysql:host=localhost;dbname=' . $dbName, $user, $password);
    $database->query("set names utf8;");
    $database->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $database->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    return $database;
}
function asociarCarritoASuario($idUsuario) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $bd = obtenerConexion(); // esta función ya está en funciones.php
    $idSesion = session_id();

    $sentencia = $bd->prepare("UPDATE carrito_usuarios SET id_usuario = ? WHERE id_sesion = ?");
    $sentencia->execute([$idUsuario, $idSesion]);
}
function verificarAdmin() {
    iniciarSesionSiNoEstaIniciada();
    if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'admin') {
        header("Location: acceso_denegado.php");
        exit;
    }
}
function vaciarCarrito() {
    if (isset($_SESSION['carrito'])) {
        unset($_SESSION['carrito']);
    }
}