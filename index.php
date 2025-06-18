<?php

/*
IMPORTANTE!!!!

Este código PHP ES SUMAMENTE IMPORTANTE!!!!!
Es el código que se utiliza para que el usuario pueda redireccionarse 
para registrarse o para loguearse

@author Schimpf Tadeo
@version 1.0
@date 11/06/2025
*/
  session_start();

  require 'your_database';

  if (isset($_SESSION['user_id'])) {
    #Conecto la base de datos, la preparo y selecciono los campos id, email, name y password de la tabla users
    $records = $conn->prepare('SELECT id, email, name, password FROM database WHERE id = :id');
    $records->bindParam(':id', $_SESSION['user_id']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    $user = null;

    if ($results) {
      $user = $results;
    }
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Index</title>
    <link rel="stylesheet" href="assets/css/index.css">
  </head>
  <body>

  <div class="contenedor">

    <?php if(!empty($user)): ?>

      <h1>Bienvenido/a <?= $user['name']; ?></h1>
      <p>Has iniciado sesión correctamente.</p>
      <a class="boton" href="logout.php">Cerrar sesión</a>
      <a class="boton-secundario" href="inicio.html">Ir a la página de inicio</a>
    
    <?php else: ?>

      <h1>¡Bienvenido! Por favor, inicia sesión o regístrate</h1>
      <a class="boton" href="login.php">Iniciar sesión</a>
      <a class="boton-secundario" href="signup.php">Registrarse</a>
    
    <?php endif; ?>

  </div>

</body>
</html>
