<?php
/*
IMPORTANTE!!!!

Este código PHP ES SUMAMENTE IMPORTANTE!!!!!
Es el código que se utiliza para que el usuario realizar el pago con tarjeta de débito o crédito
Además, cuando paga, se le envía un mail

@author Schimpf Tadeo
@version 1.0
@date 16/06/2025
*/
include_once "funciones.php";
iniciarSesionSiNoEstaIniciada();
require 'your_Database';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Obtener ID del usuario logueado
$user_id = $_SESSION['user_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre_tarjeta'] ?? '');
    $numero = preg_replace('/\D/', '', $_POST['numero_tarjeta'] ?? '');
    $mes = $_POST['mes_exp'] ?? '';
    $anio = $_POST['anio_exp'] ?? '';
    $cvv = $_POST['cvv'] ?? '';

    if (!$nombre || !$numero || !$mes || !$anio || !$cvv) {
        mostrarMensaje("Faltan datos obligatorios", false);
        exit;
    }

    if ($user_id) {
        $stmt = $conn->prepare("SELECT email, name FROM YOUR_DATABASE WHERE id = :id");
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        $email = $usuario['email'] ?? '';
        $name = $usuario['name'] ?? 'Usuario';
    } else {
        $email = '';
        $name = 'Usuario';
    }

    $productos = obtenerProductosEnCarrito();
    $total = 0;
    foreach ($productos as $producto) {
        $total += $producto->precio;
        $id = $producto->id;
        $cantidadComprada = (int)($producto->cantidad_comprada ?? 1);

        // Validar que haya stock suficiente
        if ($producto->stock < $cantidadComprada) {
            die("No hay stock suficiente para el producto: " . htmlspecialchars($producto->nombre));
        }

        // Restar del stock en la tabla productos
        $stmt = $conn->prepare("UPDATE productos SET cantidad = cantidad - :cantidadComprada WHERE id = :id");
        $stmt->bindParam(':cantidadComprada', $cantidadComprada, PDO::PARAM_INT);
        $stmt->bindParam(':id', $producto->id, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Enviar correo
    $mail = new PHPMailer(true);
    try {
        $mail->CharSet = 'UTF-8';
        $mail->addEmbeddedImage('C:/xampp/htdocs/php-login/phpmailer/logo.png', 'logoCID');
        $mail->isSMTP();
        $mail->Host       = '';
        $mail->SMTPAuth   = ;
        $mail->Username   = '';
        $mail->Password   = '';
        $mail->SMTPSecure = '';
        $mail->Port       = ;

        $mail->setFrom('EMAIL@GMAIL.COM', 'NAME');
        $mail->addAddress($email, $name);
        $mail->isHTML(true);
        $mail->Subject = "¡Gracias por tu compra!";
        $mail->Body = "
        <div style='font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 30px; text-align: center; color: #333;'>
            <img src='cid:logoCID' alt='' style='width: 150px; margin-bottom: 20px;'>
            <h2>Hola $name!</h2>
            <p>Gracias por comprar en <strong>Tripway</strong>.</p>
            <p>¡Esperamos que tengas las maletas hechas y estés listo para explorar junto a nosotros!</p>
            <p><strong>Total pagado:</strong> $" . number_format($total, 2) . "</p>
            <a href='https://www.youtube.com/' style='background-color: #007BFF; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Ir a la página de inicio</a>
            <br><br>
            <small>Si no fuiste vos quien realizó la compra, contactá a atención al cliente de nuestra página.</small>
        </div>";
        $mail->send();
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
    }

        // Mostrar comprobante en HTML
    $fecha = date("d/m/Y H:i");
    $ultimos_digitos = substr($numero, -4);
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Comprobante de Pago</title>
        <style>
            body {
                background-color: #eef1f7;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                margin: 0;
            }
            .ticket {
                background: #fff;
                padding: 40px;
                max-width: 500px;
                width: 100%;
                box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
                border-radius: 15px;
                text-align: left;
                color: #333;
            }
            .ticket-header {
                text-align: center;
                border-bottom: 2px dashed #ccc;
                padding-bottom: 20px;
                margin-bottom: 20px;
            }
            .ticket-header img {
                width: 120px;
                margin-bottom: 10px;
            }
            .ticket h2 {
                font-size: 1.5rem;
                margin: 0;
                color: #28a745;
            }
            .ticket-section {
                margin-bottom: 15px;
            }
            .ticket-section label {
                font-weight: bold;
                display: block;
                margin-bottom: 3px;
                color: #666;
            }
            .ticket-section p {
                margin: 0;
                font-size: 0.95rem;
            }
            .productos {
                margin-top: 15px;
                border-top: 1px solid #ddd;
                padding-top: 10px;
            }
            .productos ul {
                list-style: none;
                padding-left: 0;
                margin: 0;
            }
            .productos li {
                display: flex;
                justify-content: space-between;
                padding: 6px 0;
                border-bottom: 1px dotted #ccc;
                font-size: 0.95rem;
            }
            .total {
                text-align: right;
                margin-top: 15px;
                font-size: 1.2rem;
                font-weight: bold;
                color: #333;
            }
            .footer {
                text-align: center;
                margin-top: 25px;
            }
            .footer a {
                background-color: #007bff;
                color: white;
                padding: 10px 20px;
                text-decoration: none;
                border-radius: 6px;
                transition: background-color 0.3s;
            }
            .footer a:hover {
                background-color: #0056b3;
            }
        </style>
    </head>
    <body>
        <div class="ticket">
            <div class="ticket-header">
                <img src="phpmailer/logo.png" alt="">
                <h2>Comprobante de Pago</h2>
                <p><?= $fecha ?></p>
            </div>

            <div class="ticket-section">
                <label>Nombre del cliente:</label>
                <p><?= htmlspecialchars($name) ?></p>
            </div>

            <div class="ticket-section">
                <label>Tarjeta:</label>
                <p>**** **** **** <?= $ultimos_digitos ?></p>
            </div>

            <div class="ticket-section productos">
                <label>Productos comprados:</label>
                <ul>
                    <?php foreach ($productos as $p): ?>
                        <li>
                            <span><?= htmlspecialchars($p->nombre) ?></span>
                            <span>$<?= number_format($p->precio, 2) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="total">
                Total: $<?= number_format($total, 2) ?>
            </div>

            <div class="footer">
                <a href="inicio.html">Volver al Inicio</a>
                <a href="tienda.php">Volver a la tienda</a>
            </div>
        </div>
    </body>
    </html>
    <?php
}
