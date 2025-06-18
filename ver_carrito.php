<?php
include_once "encabezado.php";
require_once "database.php";
require_once "funciones.php";

iniciarSesionSiNoEstaIniciada();

$user = null;
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $stmt = $conn->prepare('SELECT name, email FROM users WHERE id = :id');
    $stmt->bindParam(':id', $userId);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

$productos = obtenerProductosEnCarrito();

?>
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
<div class="section">
    <div class="container">
        <?php if ($user): ?>
            <div class="notification is-primary is-light">
                Bienvenido/a, <strong><?= htmlspecialchars($user['name'] ?: $user['email']) ?></strong>!
            </div>
        <?php else: ?>
            <div class="notification is-warning is-light">
                No estás logueado. <a href="login.php"><strong>Iniciar sesión</strong></a>
            </div>
        <?php endif; ?>

        <?php if (count($productos) <= 0): ?>
            <section class="hero is-info is-light">
                <div class="hero-body has-text-centered">
                    <p class="title">Tu carrito está vacío</p>
                    <p class="subtitle">Agrega productos desde la tienda</p>
                    <a href="tienda.php" class="button is-warning is-rounded mt-4">Ir a la tienda</a>
                </div>
            </section>
        <?php else: ?>
            <div class="box">
                <h2 class="title is-3 has-text-grey-dark">Mi carrito de compras</h2>
                <div class="table-container">
                    <table class="table is-fullwidth is-hoverable is-striped">
                        <thead class="has-background-primary-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Quitar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total = 0; ?>
                            <?php foreach ($productos as $producto): ?>
                                <?php $total += $producto->precio; ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($producto->nombre) ?></strong></td>
                                    <td><?= htmlspecialchars($producto->descripcion) ?></td>
                                    <td>$<?= number_format($producto->precio, 2) ?></td>
                                    <td>
                                        <form action="eliminar_del_carrito.php" method="post" onsubmit="return confirm('¿Eliminar este producto del carrito?');">
                                            <input type="hidden" name="id_producto" value="<?= htmlspecialchars($producto->id) ?>">
                                            <button class="button is-danger is-light is-small is-rounded">
                                                <span class="icon"><i class="fa fa-trash-o"></i></span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="has-text-right has-text-weight-bold is-size-5">Total:</td>
                                <td colspan="2" class="has-text-weight-bold is-size-5">$<?= number_format($total, 2) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="has-text-right mt-4">
                    <a href="terminar_compra.php" class="button is-success is-medium is-rounded">
                        <span><i class="fa fa-check"></i></span>
                        <span>Terminar compra</span>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include_once "pie.php"; ?>

