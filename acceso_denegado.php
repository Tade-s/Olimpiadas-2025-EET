<!--
Este código HTML muestra una interfaz al usuario
que no puede acceder ya que no tiene el nivel de acceso necesario 
(admin/empleado)

@author Schimpf Tadeo
@version 1.0
@date 16/06/2025
-->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/acceso_denegado.css">
    <title>Document</title>
</head>
<body>
    <section class="section">
  <div class="container">
    <h1 class="title has-text-danger">Acceso denegado</h1>
    <p>No tenés permisos para acceder a esta sección.</p>
    <a href="inicio.html" class="button is-link">Volver al inicio</a>
    <a href="agregar_producto.php" class="button2">Cargar productos</a>
  </div>
</section>
<?php include_once "pie.php"; ?>

</body>
</html>
