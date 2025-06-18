<?php

/*
IMPORTANTE!!!!

Este código PHP ES SUMAMENTE IMPORTANTE!!!!!
Es el código que se utiliza para que el usuario pueda loguearse en la página


@author Schimpf Tadeo
@version 1.0
@date 11/06/2025
*/
require 'database.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';
require_once 'funciones.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = '';

if (!empty($_POST['email']) && !empty($_POST['name']) && !empty($_POST['password'])) {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  //Validaciones
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $message = 'El email ingresado no es válido.';
  } elseif (strlen($password) < 6 || strlen($password) > 8) {
    $message = 'La contraseña debe tener entre 6 y 8 caracteres.';
  } else {
    //Verifica si ya existe un usuario con ese email o nombre
    $check = $conn->prepare('SELECT id FROM users WHERE email = :email OR name = :name');
    $check->bindParam(':email', $email);
    $check->bindParam(':name', $name);
    $check->execute();

    if ($check->rowCount() > 0) {
      $message = 'Ya existe un usuario con ese nombre o email.';
    } else {
      //Si todo está bien, insertamos al usuario
      $sql = "INSERT INTO users (email, name, password) VALUES (:email, :name, :password)";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':name', $name);

      $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
      $stmt->bindParam(':password', $hashedPassword);

      if ($stmt->execute()) {
        $message = '¡Su usuario se creó de manera correcta!';

        //Obtener el id del nuevo usuario
        $idUsuario = $conn->lastInsertId();

        //Enviar correo de bienvenida
        $mail = new PHPMailer(true);
        try {
          $mail->CharSet = 'UTF-8';
          $mail->addEmbeddedImage('C:/xampp/htdocs/php-login/phpmailer/logo.png', 'logoCID');
          $mail->isSMTP();
          $mail->Host       = 'smtp.gmail.com';
          $mail->SMTPAuth   = true;
          $mail->Username   = 'tripwayturismo.oficial@gmail.com';
          $mail->Password   = 'giehrkfdqhcwmfpb';  
          $mail->SMTPSecure = 'tls';
          $mail->Port       = 587;

          $mail->setFrom('tripwayturismo.oficial@gmail.com', 'Tripway');
          $mail->addAddress($email, $name);

          $mail->isHTML(true);
          $mail->Subject = "¡Bienvenido!";
          $mail->Body = "
            <div style='
              font-family: Arial, sans-serif;
              background-color: #f4f4f4;
              padding: 30px;
              text-align: center;
              color: #333;
            '>
              <img src='cid:logoCID' alt='Tripway Logo' style='width: 150px; margin-bottom: 20px;'>
              <h2>Hola $name!</h2>
              <p>Gracias por registrarte en <strong>Tripway</strong>.</p>
              <p>Empezá a explorar tus próximos destinos haciendo clic acá:</p>
              <a href='https://www.youtube.com/' style='
                background-color: #007BFF;
                color: white;
                padding: 10px 20px;
                text-decoration: none;
                border-radius: 5px;
                display: inline-block;'>Explorar destinos</a>
              <br><br>
              <small>Si no fuiste vos quien se registró, podés ignorar este mensaje.</small>
            </div>";

          $mail->send();
        } catch (Exception $e) {
          echo "Error al enviar el correo: {$mail->ErrorInfo}";
        }

        //Iniciar sesión automáticamente
        session_start();
        $_SESSION['user_id'] = $idUsuario;

        //Asociar carrito al nuevo usuario
        asociarCarritoASuario($idUsuario);

        //Redirigir
        header("Location: /php-login");
        exit;
      } else {
        $message = 'Lo siento, hubo un error al crear su usuario.';
      }
    }
  }
}
?>



<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>SignUp</title>
    <link rel="stylesheet" href="assets/css/style.css">
  </head>
  <body>

    <div class="contenedor">
      <?php if(!empty($message)): ?>
        <div style="background-color: #ffe0e0; color: #a94442; padding: 10px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #ebccd1;">
          <p> <?= $message ?></p>
        </div>
      <?php endif; ?>
      <h1>¡Bienvenido/a a TripWays!</h1>
      

      <form action="signup.php" method="POST">
        <input name="email" type="text" placeholder="Email">
        <input name="name" type="text" placeholder="Nombre de usuario">
        <input name="password" type="password" placeholder="Contraseña (6-8 caracteres)">
        <input type="submit" value="Registrarse">
      </form>
      <span>o <a href="login.php" class="boton">Inicia sesión</a></span>
    </div>  

  </body>
</html>