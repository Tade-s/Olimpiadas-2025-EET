<?php
/*
IMPORTANTE!!!!

Este código PHP ES SUMAMENTE IMPORTANTE!!!!!
Es el código que se utiliza para que el usuario pueda loguearse

@author Schimpf Tadeo
@version 1.0
@date 11/06/2025
*/
session_start();

if (isset($_SESSION['user_id'])) {
  header('Location: /php-login');
  exit;
}

require 'database.php';
require 'funciones.php';

$message = '';

if (!empty($_POST['email']) && !empty($_POST['password'])) {
  $records = $conn->prepare('SELECT id, email, password, rol FROM users WHERE email = :email');
  $records->bindParam(':email', $_POST['email']);
  $records->execute();
  $results = $records->fetch(PDO::FETCH_ASSOC);

  if ($results && password_verify($_POST['password'], $results['password'])) {
    $_SESSION['user_id'] = $results['id'];
    $_SESSION['user_rol'] = $results['rol']; 
    asociarCarritoASuario($results['id']);

    if ($results['rol'] === 'admin') {
      header("Location: productos.php"); // Panel admin
    } else {
      header("Location: index.php"); // Usuario común
    }
    exit;
  } else {
    $message = 'Lo siento, las credenciales no coinciden';
  }
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
  </head>
  <body>
 
    <div class="contenedor"> 
      <?php if(!empty($message)): ?>
        <p> <?= $message ?></p>
      <?php endif; ?>
      <h1>¡Bienvenido/a de nuevo!</h1>
      

      <form action="login.php" method="POST">
        <input name="email" type="text" placeholder="Ingrese su email">
        <input name="password" type="password" placeholder="Ingrese su contraseña">
        <input type="submit" value="Inicar sesión">
        <br>
      </form>

      <span><a href="signup.php" class="boton">Registrese</a></span>
    </div>  
  </body>
</html>