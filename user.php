<?php
session_start();
require 'YOUR_DATABASE';

$user = null;

if (isset($_SESSION['user_id'])) {
    $records = $conn->prepare('SELECT id, email, name, password, rol FROM users WHERE id = :id');
    $records->bindParam(':id', $_SESSION['user_id']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    if ($results) {
        $user = $results;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Perfil del Usuario</title>
    <link rel="stylesheet" href="assets/css/user.css" />
</head>
<body>
    
    <?php if (!empty($user)): ?>
        <div class="contenedor">
            <img src="assets/Sample_User_Icon.png" alt="" class="foto" />
            <h1>¡Hola, <?= htmlspecialchars($user['name']) ?>!</h1>
            <h4>Datos</h4>
            <p><strong>ID:</strong> <?= htmlspecialchars($user['id']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Contraseña:</strong> <em>••••••••</em></p>

            <?php
            // Mostrar botones si el rol es admin o empleado
            if (isset($user['rol']) && ($user['rol'] === 'admin' || $user['rol'] === 'empleado')): ?>
                <a href="productos.php" class="boton">Gestionar productos</a>
                <a href="agregar_producto.php" class="boton">Agregar producto</a>
            <?php endif; ?>

            <a href="inicio.html" class="boton">Ir al inicio</a>
            <a href="editar_usuario.php" class="boton3">Editar usuario</a>
            <a href="logout.php" class="boton2">Cerrar sesión</a>
        </div>
    <?php else: ?>
        <p>No hay usuario logueado. <a href="login.php">Iniciar sesión</a></p>
    <?php endif; ?>

</body>
</html>
