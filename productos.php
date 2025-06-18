<?php
include_once "encabezado.php";
include_once "funciones.php";

// Verifica que el usuario esté logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Verifica que sea admin
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'admin') {
    header("Location: acceso_denegado.php");
    exit;
}

$productos = obtenerProductos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Gestión de Productos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            background-color: #f5f5f5;
        }
        .section {
            padding-top: 150px;
        }
        .title {
            font-weight: 700;
        }
        
    </style>
</head>
<body>

<section class="section">
    <div class="container">
        <div class="box has-background-light">
            <div class="level mb-4">
                <div class="level-left">
                    <h2 class="title is-3 has-text-dark">Productos existentes</h2>
                </div>
                <div class="level-right">
                    <a class="button is-success is-rounded" href="agregar_producto.php">
                        <span class="icon"><i class="fa fa-plus"></i></span>
                        <span>Agregar producto</span>
                    </a>
                </div>
            </div>

            <?php if (is_array($productos) && count($productos) > 0): ?>
                <div class="table-container">
                    <table class="table is-fullwidth is-striped is-hoverable">
                        <thead class="has-background-primary-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($productos as $producto): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($producto->nombre) ?></strong></td>
                                    <td><?= htmlspecialchars($producto->descripcion) ?></td>
                                    <td>$<?= number_format($producto->precio, 2) ?></td>
                                    <td><?= htmlspecialchars($producto->cantidad) ?></td>
                                    <td>
                                        <form action="eliminar_producto.php" method="post" onsubmit="return confirm('¿Estás seguro de eliminar este producto?');">
                                            <input type="hidden" name="id_producto" value="<?= $producto->id ?>">
                                            <button class="button is-danger is-light is-small is-rounded" type="submit">
                                                <span class="icon"><i class="fa fa-trash-o"></i></span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="has-text-grey is-italic">No hay productos cargados aún.</p>
            <?php endif; ?>
        </div>
    </div>
</section>
</body>
</html>
