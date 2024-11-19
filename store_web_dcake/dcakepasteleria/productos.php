<?php
require_once '../config/conexion.php';

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);


$id_producto = isset($_GET['id_producto']) ? $_GET['id_producto'] : null;
$mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : "";

// Limpiar el mensaje de sesión después de mostrarlo
unset($_SESSION['mensaje']);

// Insertar productos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insertar_producto'])) {
    try {
        // Recuperar y sanitizar datos
        $nombre = htmlspecialchars($_POST['nombre'], ENT_QUOTES, 'UTF-8');
        $precio = htmlspecialchars($_POST['precio'], ENT_QUOTES, 'UTF-8');
        $descuento = htmlspecialchars($_POST['descuento'], ENT_QUOTES, 'UTF-8');
        $descripcion = htmlspecialchars($_POST['descripcion'], ENT_QUOTES, 'UTF-8');
        $id_categoria = htmlspecialchars($_POST['id_categoria'], ENT_QUOTES, 'UTF-8');
        $activo = isset($_POST['activo']) ? 1 : 0;

        // Preparar la consulta
        $sql = "INSERT INTO producto (Nombre, Precio, Descuento, Descripcion, id_categoria, Activo) 
        VALUES (?, ?, ?, ?, ?, ?)"; $stmt = $conexion->prepare($sql);
        $stmt = $conexion->prepare($sql);

        // Vincular parámetros
        $stmt->bind_param("ssssii", $nombre, $precio, $descuento, $descripcion, $id_categoria, $activo);

        // Ejecutar la consulta
        if ($stmt->execute() && $conexion->affected_rows > 0) {
            $_SESSION['mensaje'] = "Producto insertado con éxito.";
        } else {
            $_SESSION['mensaje'] = "Error al insertar el producto.";
        }
    } catch (Exception $e) {
        $_SESSION['mensaje'] = "Error: " . $e->getMessage();
    }

    // Redirigir
    header("Location: productos.php");
    exit();
}

// Actualizar productos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modificar_producto'])) {
    try {
        // Recuperar los datos del formulario
        $id_producto = $_POST['id_producto'];
        $nombre = htmlspecialchars($_POST['nombre'], ENT_QUOTES, 'UTF-8');
        $precio = htmlspecialchars($_POST['precio'], ENT_QUOTES, 'UTF-8');
        $descuento = htmlspecialchars($_POST['descuento'], ENT_QUOTES, 'UTF-8');
        $descripcion = htmlspecialchars($_POST['descripcion'], ENT_QUOTES, 'UTF-8');
        $id_categoria = htmlspecialchars($_POST['id_categoria'], ENT_QUOTES, 'UTF-8');
        $activo = isset($_POST['activo']) ? 1 : 0;

        // Preparar la consulta SQL para actualizar el producto
        $sql = "UPDATE producto 
                SET Nombre = ?, Precio = ?, Descuento = ?, Descripcion = ?, 
                    id_categoria = ?, Activo = ? 
                WHERE Codigo = ?";
        $stmt = $conexion->prepare($sql);

        if($stmt === false) {
            die ("Error en la preparacion de la consulta: " . $conexion->error);
        }      
        // Vincular parámetros
        $stmt->bind_param("ssssiii", $nombre, $precio, $descuento, $descripcion, $id_categoria, $activo, $id_producto);

        // Ejecutar la consulta
        if ($stmt->execute() && $conexion->affected_rows > 0 ) {
            $_SESSION['mensaje'] = "Producto modificado con éxito.";
        } else {
            $_SESSION['mensaje'] = "Error al modificar el producto.";
        }
    } catch (Exception $e) {
        $_SESSION['mensaje'] = "Error: " . $e->getMessage();
    }

    // Redirigir
    header("Location: productos.php");
    exit();
}

// Eliminar productos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar_producto'])) {
    try {
        // Recuperar el ID del producto a eliminar
        $id_producto = $_POST['id_producto'];

        // Preparar la consulta SQL para eliminar el producto
        $sql = "DELETE FROM producto WHERE Codigo = ?";
        $stmt = $conexion->prepare($sql);

        // Vincular el parámetro
        $stmt->bind_param("i", $id_producto);

        // Ejecutar la consulta
        if ($stmt->execute() && $conexion->affected_rows > 0) {
            $_SESSION['mensaje'] = "Producto eliminado con éxito.";
        } else {
            $_SESSION['mensaje'] = "Error al eliminar el producto.";
        }
    } catch (Exception $e) {
        $_SESSION['mensaje'] = "Error: " . $e->getMessage();
    }

    // Redirigir
    header("Location: productos.php");
    exit();
}

// Obtener y mostrar productos
$sql = "SELECT Codigo, Nombre, Precio, Descuento, Descripcion, id_categoria, Activo FROM producto";
$result = $conexion->query($sql);

if ($result === false) {
    die ("Error en la consulta: " . $conexion->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Lista de Productos</h2>

    <!-- Botón para abrir el modal de inserción -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalInsertar">
        Insertar Nuevo Producto
    </button>

    <!-- Tabla de productos -->
    <table class="table table-bordered table-hover mt-3">
        <thead class="thead-dark">
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Descuento</th>
                <th>Descripción</th>
                <th>ID Categoría</th>
                <th>Activo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?= htmlspecialchars($row['Codigo']) ?></td>
                <td><?= htmlspecialchars($row['Nombre']) ?></td>
                <td><?= htmlspecialchars($row['Precio']) ?></td>
                <td><?= htmlspecialchars($row['Descuento']) ?></td>
                <td><?= htmlspecialchars($row['Descripcion']) ?></td>
                <td><?= htmlspecialchars($row['id_categoria']) ?></td>
                <td><?= $row['Activo'] ? 'Sí' : 'No' ?></td>
                <td>
                    <form method="post" class="d-inline" onsubmit="return confirm ('¿Estas seguro de que deseas eliminar el producto?');">
                        <input type="hidden" name="id_producto" value="<?= $row['Codigo'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm" name="eliminar_producto">Eliminar</button>
                    </form>
                    <!-- Botón para modificar -->
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalModificar<?= $row['Codigo'] ?>">
                        Modificar
                    </button>
                </td>
            </tr>
            <!-- Modal de Modificación -->
            <div class="modal fade" id="modalModificar<?= $row['Codigo'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Modificar Producto</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form method="post">
                                <input type="hidden" name="id_producto" value="<?= $row['Codigo'] ?>">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($row['Nombre']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="precio" class="form-label">Precio</label>
                                    <input type="number" class="form-control" name="precio" value="<?= htmlspecialchars($row['Precio']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="descuento" class="form-label">Descuento</label>
                                    <input type="number" class="form-control" name="descuento" value="<?= htmlspecialchars($row['Descuento']) ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    <textarea class="form-control" name="descripcion" rows="3"><?= htmlspecialchars($row['Descripcion']) ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="id_categoria" class="form-label">ID Categoría</label>
                                    <input type="number" class="form-control" name="id_categoria" value="<?= htmlspecialchars($row['id_categoria']) ?>" required>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="activo" <?= $row['Activo'] ? 'checked' : '' ?>>
                                    <label class="form-check-label">Activo</label>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success" name="modificar_producto">Guardar Cambios</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal de Inserción -->
<div class="modal fade" id="modalInsertar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Insertar Nuevo Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio</label>
                        <input type="number" class="form-control" name="precio" required>
                    </div>
                    <div class="mb-3">
                        <label for="descuento" class="form-label">Descuento</label>
                        <input type="number" class="form-control" name="descuento">
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="id_categoria" class="form-label">ID Categoría</label>
                        <input type="number" class="form-control" name="id_categoria" required>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="activo">
                        <label class="form-check-label">Activo</label>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="insertar_producto">Guardar Producto</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>