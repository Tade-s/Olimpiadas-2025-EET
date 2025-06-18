<?php
session_start();
require_once "your_database";
$user = null;

if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT name, email FROM database WHERE id = :id");
    $stmt->bindParam(':id', $_SESSION['user_id']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>TripWay Turismo</title>
    <link rel="stylesheet" href="https://unpkg.com/bulma@0.9.1/css/bulma.min.css" />
    <link rel="stylesheet" href="./assets/css/productos.css" />
    <script src="script.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Amaranth:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet" />
</head>

<body>

<nav>
  <style>
      @media screen and (max-width: 768px) {
    nav {
        flex-direction: column;
        height: auto;
        padding: 20px;
        gap: 15px;
    }

    .section {
      margin-top: 300px;
    }

    .nav-left, .nav-right {
        justify-content: center;
        flex-wrap: nowrap;
    }

    .nav-center {
        position: static;
        transform: none;
        gap: 20px;
        flex-direction: row;
        align-items: center;
    }

    .logo {
        width: 60px;
        height: 60px;
    }

    h1 {
        font-size: 16px;
        text-align: center;
    }

    #buscador {
        width: 100%;
        max-width: 300px;
    }
  }  
  </style>  
  <div class="nav-left">
    <img src="./assets/css/TripWay.png" alt="Logo" class="logo" />
    <h1 class="titulo">TripWay<br />Turismo</h1>
  </div>

  <div class="nav-center">
    <a href="inicio.html">INICIO</a>
    <a href="resenas.html">RESEÑAS</a>

    <div class="dropdown">
      <button class="dropbtn">VIAJES</button>
      <div class="dropdown-content">
        <a href="nacional.html">NACIONAL</a>
        <a href="internacional.html">INTERNACIONAL</a>
      </div>
    </div>

    <div class="dropdown">
      <button class="dropbtn">ALQUILER</button>
      <div class="dropdown-content">
        <a href="hoteles.html">ESTADIA</a>
        <a href="vehiculos.html">VEHÍCULOS</a>
      </div>
    </div>
  </div>

  <div class="nav-right">
    <input type="text" id="buscador" placeholder="Buscar destino..." />
    <button onclick="buscarPaquete()">
      <img src="./assets/css/buscar.png" alt="Buscar" class="icono" />
    </button>
    <a href="ver_carrito.php"><img src="./assets/css/carrito.png" alt="Carrito" class="icono" /></a>
    <a href="user.php"><img src="./assets/css/icono.png" class="icono" alt="Perfil" /></a>
    <?php if ($user): ?>
      <span class="user-name"><?= htmlspecialchars($user['name'] ?: $user['email']) ?></span>
    <?php endif; ?>
  </div>
</nav>
<script></script>
<!-- Aquí abrimos el contenedor principal que usaremos para separar el contenido del nav fijo -->
<div class="main-content" >
