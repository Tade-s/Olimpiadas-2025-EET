<?php

/*
Este código PHP realiza la función de que el usuario pueda editar su 
email y nombre de usuario

@author Schimpf Tadeo
@version 1.0
@date -/06/2025
*/


session_start();
require 'your_database';

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

//Obtener datos actuales
$stmt = $conn->prepare('SELECT id, name, email FROM database WHERE id = :id');
$stmt->bindParam(':id', $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$message = '';

//Verifica si se envió el formulario
if (!empty($_POST['name']) && !empty($_POST['email'])) {
  $nuevoNombre = trim($_POST['name']);
  $nuevoEmail = trim($_POST['email']);

  // Verificar si ya existe otro usuario con ese nombre o email
  $verificar = $conn->prepare('SELECT id FROM database WHERE (email = :email OR name = :name) AND id != :id');
  $verificar->bindParam(':email', $nuevoEmail);
  $verificar->bindParam(':name', $nuevoNombre);
  $verificar->bindParam(':id', $_SESSION['user_id']);
  $verificar->execute();

  if ($verificar->rowCount() > 0) {
    $message = 'Ya existe un usuario con ese nombre o email.';
  } else {
    //Si no existe, actualiza
    $update = $conn->prepare('UPDATE database SET name = :name, email = :email WHERE id = :id');
    $update->bindParam(':name', $nuevoNombre);
    $update->bindParam(':email', $nuevoEmail);
    $update->bindParam(':id', $_SESSION['user_id']);

    //Si está todo correcto, muestra el mensaje y se actualizan los datos, sino muestra el mensaje de error
    if ($update->execute()) {
      $message = 'Datos actualizados correctamente.';
      $user['name'] = $nuevoNombre;
      $user['email'] = $nuevoEmail;
    } else {
      $message = 'Ocurrió un error al actualizar.';
    }
  }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Perfil</title>
  <link rel="stylesheet" href="assets/css/user.css">
</head>
<body>

    <div class="contenedor">
    
      <h1>Editar Perfil</h1>

      <?php if ($message): ?>
        <p><strong><?= htmlspecialchars($message) ?></strong></p>
      <?php endif; ?>
      
        <form method="POST">
            <p>
            <label for="name">Nombre:</label><br>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
            </p>
            <p>
            <label for="email">Email:</label><br>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </p>
            <button type="submit" class="boton5">Guardar Cambios</button>
        </form>
  
        <a href="user.php" class="boton6">Volver al perfil</a>
    </div>
  </div>
</body>
</html>
