<?php
include_once "encabezado.php";
require_once "funciones.php";

iniciarSesionSiNoEstaIniciada();

$productos = obtenerProductosEnCarrito();
$user = null;

if (isset($_SESSION['user_id'])) {
    $conn = obtenerConexion();
    $stmt = $conn->prepare('SELECT name, email FROM users WHERE id = :id');
    $stmt->bindParam(':id', $_SESSION['user_id']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<style>
    body {
        background-color: #f5f5f5;
    }
    .section {
        padding-top: 150px;
    }
</style>

<div class="section">
    <div class="container">
        <?php if (!$user): ?>
            <div class="notification is-warning">
                Debes iniciar sesión para continuar. <a href="login.php">Iniciar sesión</a>
            </div>
        <?php elseif (count($productos) === 0): ?>
            <div class="notification is-info">
                Tu carrito está vacío. <a href="tienda.php">Ir a la tienda</a>
            </div>
        <?php else: ?>
            <h1 class="title">Finalizar compra</h1>
            <form action="procesar_pago.php" method="post">
                <div class="field">
                    <label class="label">Nombre en la tarjeta</label>
                    <div class="control">
                        <input class="input" type="text" name="nombre_tarjeta" placeholder="Nombre completo" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Número de tarjeta</label>
                    <div class="control">
                        <input class="input" type="text" name="numero_tarjeta" placeholder="1234567812345678" maxlength="19" required pattern="[0-9]{13,19}">
                    </div>
                </div>

                <div class="field is-grouped">
                    <div class="control">
                        <label class="label">Mes de expiración</label>
                        <input class="input" type="text" name="mes_exp" placeholder="MM" maxlength="2" pattern="[0-9]{2}" required>
                    </div>
                    <div class="control" style="margin-left:10px;">
                        <label class="label">Año de expiración</label>
                        <input class="input" type="text" name="anio_exp" placeholder="AA" maxlength="2" pattern="[0-9]{2}" required>
                    </div>
                    <div class="control" style="margin-left:10px;">
                        <label class="label">CVV</label>
                        <input class="input" type="password" name="cvv" placeholder="123" maxlength="4" pattern="[0-9]{3,4}" required>
                    </div>
                </div>

                <div class="field mt-4">
                    <div class="control">
                        <button class="button is-success is-medium is-rounded" type="submit">
                            <span><i class="fa fa-credit-card"></i></span>
                            <span>Pagar</span>
                        </button>
                        <a href="ver_carrito.php" class="button is-warning is-success is-medium is-rounded ">Volver al carrito</a>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php include_once "pie.php"; ?>
