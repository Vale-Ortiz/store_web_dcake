<?php
require 'config/config.php';
require 'config/conexion_producto.php';
require 'config/config2.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$nombre_usuario = $_SESSION['usuario'] ?? null;

// Obtener detalles del pedido
$nombre = $_POST['nombre'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];
$correo = $_POST['correo'];

// Obtener productos del carrito
$productos = $_SESSION['carrito']['productos'];

// Calcular el total del pedido
$total = 0;
foreach ($productos as $codigo => $cantidad) {
    // Obtener la información del producto desde la base de datos
    $sql = $con->prepare("SELECT Precio FROM store_web_dcake.producto WHERE Codigo = ?");
    $sql->execute([$codigo]);
    $producto = $sql->fetch(PDO::FETCH_ASSOC);

    // Calcular el subtotal y sumarlo al total del pedido
    $subtotalProducto = $producto['Precio'] * $cantidad;
    $total += $subtotalProducto;
}

// Guardar pedido en la base de datos
$sqlPedido = $con->prepare("INSERT INTO pedido (fecha, id_usuario, total) VALUES (NOW(), ?, ?)");
$sqlPedido->execute([$nombre_usuario['id'], $total]);

// Obtener el ID del pedido insertado
$idPedido = $con->lastInsertId();

// Insertar detalles en la tabla 'detalles_pedido'
foreach ($productos as $codigo => $cantidad) {
    $sqlDetalles = $con->prepare("INSERT INTO detalles_pedido (id_pedido, codigo_producto, cantidad) VALUES (?, ?, ?)");
    $sqlDetalles->execute([$idPedido, $codigo, $cantidad]);
}

// Limpiar el carrito después de completar el pedido
unset($_SESSION['carrito']);

// Redirigir o realizar otras acciones según sea necesario
header('Location: confirmacion_pedido.php');
exit();
?>
