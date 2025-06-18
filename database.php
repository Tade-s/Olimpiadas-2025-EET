<?php

/*
Este código PHP realiza la función de conectar la base de datos

@author Schimpf Tadeo
@version 1.0
@date 11/06/2025
*/

$server = '';
$username = 'root';
$password = '';
$database = 'database';

try {
  $conn = new PDO("mysql:host=$server;dbname=$database;charset=utf8", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die('Connection Failed: ' . $e->getMessage());
}
?>
