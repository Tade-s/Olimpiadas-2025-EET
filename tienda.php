<?php
ob_start();
/*
IMPORTANTE!!!!

Este código PHP ES SUMAMENTE IMPORTANTE!!!!!
te muestra la tienda de la página web, donde están cargadas todos los paquetes o servicios

@author Schimpf Tadeo
@version 1.0
@date 16/06/2025
*/

include_once "funciones.php";

// Verificamos que el usuario esté logueado si fuera necesario
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit;
// }

$productos = obtenerProductos(); // Esta función puede redirigir, por eso va antes del HTML
?>

<?php include_once "encabezado.php"; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            background-color: #f5f5f5;
        }
        .section {
            padding-top: 200px;
        }
        .title {
            font-weight: 700;
        }
    </style>
</head>
<body>

<section class="section">
    <div class="container">
        <h2 class="title is-2 has-text-centered">Tienda</h2>

        <div class="columns is-multiline">
            <?php foreach ($productos as $producto): ?>
                <div class="column is-12-mobile is-6-tablet is-4-desktop">
                    <div class="card" style="height: 100%;">
                        <header class="card-header">
                            <p class="card-header-title is-size-5">
                                <?= htmlspecialchars($producto->nombre) ?>
                            </p>
                        </header>

                        <div class="card-content">
                            <div class="content">
                                <p><?= nl2br(htmlspecialchars($producto->descripcion)) ?></p>
                                <h3 class="title is-4 mt-4">$<?= number_format($producto->precio, 2) ?></h3>
                                <p><strong>Stock disponible:</strong> <?= (int)$producto->cantidad ?></p>
                            </div>

                            <div class="buttons mt-4">
                                <?php if (productoYaEstaEnCarrito($producto->id)) { ?>
                                    <form action="eliminar_del_carrito.php" method="post" class="is-flex is-flex-direction-column">
                                        <input type="hidden" name="id_producto" value="<?= $producto->id ?>">
                                        <span class="button is-static is-success mb-2">
                                            <i class="fa fa-check"></i>&nbsp; En el carrito
                                        </span>
                                        <button class="button is-danger">
                                            <i class="fa fa-trash-o"></i>&nbsp; Quitar
                                        </button>
                                    </form>
                                <?php } else { ?>
                                    <form action="agregar_al_carrito.php" method="post">
                                        <input type="hidden" name="id_producto" value="<?= $producto->id ?>">
                                        <?php if ($producto->cantidad > 0): ?>
                                            <button class="button is-primary" type="submit">Agregar al carrito</button>
                                        <?php else: ?>
                                            <button class="button is-danger is-light" type="button" disabled>Sin stock</button>
                                        <?php endif; ?>
                                    </form>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include_once "pie.php"; ?>
</body>
</html>
ob_end_flush();