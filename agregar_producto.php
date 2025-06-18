<?php

/*
Este código PHP realiza la función de agregar un producto a la web y mostrarlo.
Además tiene verificación de usuario o nivel de acceso
Y confirmación de datos

@author Schimpf Tadeo
@version 1.1
@date 17/06/2025
*/

include_once "encabezado.php";
include_once "funciones.php";

// Verifica si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Verifica si tiene rol autorizado
if (!isset($_SESSION['user_rol']) || !in_array($_SESSION['user_rol'], ['admin', 'empleado'])) {
    header("Location: acceso_denegado.php");
    exit;
}

$mensaje = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"] ?? '';
    $descripcion = $_POST["descripcion"] ?? '';
    $precio = floatval($_POST["precio"] ?? 0);
    $cantidad = intval($_POST["cantidad"] ?? 0);

    if ($nombre && $descripcion && $precio > 0 && $cantidad >= 0) {
        require "database.php";

        $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, cantidad) VALUES (:nombre, :descripcion, :precio, :cantidad)");
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":descripcion", $descripcion);
        $stmt->bindParam(":precio", $precio);
        $stmt->bindParam(":cantidad", $cantidad);

        if ($stmt->execute()) {
            $mensaje = "Producto guardado exitosamente.";
        } else {
            $mensaje = "Error al guardar el producto.";
        }
    } else {
        $mensaje = "Todos los campos son obligatorios y deben tener valores válidos.";
    }
}
?>

<section class="section" style="margin-top: 200px">
  <div class="container">
    <div class="columns is-centered">
      <div class="column is-half">
        <div class="box">
          <h2 class="title is-3 has-text-centered">Agregar nuevo producto</h2>

          <?php if ($mensaje): ?>
            <div class="notification is-info"><?= htmlspecialchars($mensaje) ?></div>
          <?php endif; ?>

          <form action="" method="post">
            <div class="field">
              <label class="label" for="nombre">Nombre</label>
              <div class="control">
                <input required id="nombre" class="input" type="text" placeholder="Nombre" name="nombre">
              </div>
            </div>

            <div class="field">
              <label class="label" for="descripcion">Descripción</label>
              <div class="control">
                <textarea name="descripcion" class="textarea" id="descripcion" placeholder="Descripción" required></textarea>
              </div>
            </div>

            <div class="field">
              <label class="label">Cantidad de paquetes disponibles</label>
              <div class="control">
                <input class="input" type="number" name="cantidad" min="0" required>
              </div>
            </div>

            <div class="field">
              <label class="label" for="precio">Precio</label>
              <div class="control">
                <input required id="precio" name="precio" class="input" type="number" placeholder="Precio">
              </div>
            </div>

            <div class="field is-grouped is-justify-content-space-between">
              <div class="control">
                <button class="button is-success" type="submit">Guardar</button>
              </div>
              <div class="control">
                <a href="productos.php" class="button is-warning">Ir a administración de productos</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include_once "pie.php"; ?>
