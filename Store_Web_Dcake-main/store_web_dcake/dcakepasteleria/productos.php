<?php
require '../config/conexion_usuario.php';
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Definir $id_producto al principio del script
$id_producto = isset($_GET['id_producto']) ? $_GET['id_producto'] : null;

var_dump($_POST);


// Variable para almacenar el mensaje
$mensaje = "";

// Después de establecer el mensaje
$_SESSION['mensaje'] = $mensaje;

// En productos.php, antes de mostrar la página
if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    // Limpia la variable de sesión después de mostrar el mensaje
    unset($_SESSION['mensaje']);
}


// Insertar productos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insertar_producto'])) {
    // Recuperar los datos del formulario
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $descuento = $_POST['descuento'];
    $descripcion = $_POST['descripcion'];
    $id_categoria = $_POST['id_categoria'];
    $activo = isset($_POST['activo']) ? 1 : 0;

    // Preparar la consulta SQL para insertar el producto
    $sql = "INSERT INTO store_web_dcake.producto (Nombre, Precio, Descuento, Descripción, id_categoria, Activo)
            VALUES (:nombre, :precio, :descuento, :descripcion, :id_categoria, :activo)";

    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':precio', $precio);
    $stmt->bindParam(':descuento', $descuento);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':id_categoria', $id_categoria);
    $stmt->bindParam(':activo', $activo);

    // Ejecutar la consulta
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $mensaje = "Producto insertado con éxito.";
        var_dump($mensaje); // Verifica el mensaje
        // Redirigir para evitar la repetición del envío del formulario
        header("Location: productos.php");
        exit();
    } else {
        $mensaje = "Error al insertar el producto.";
    }    

    // Restablecer los datos del formulario
    $_POST = array();

    // Redirigir para evitar la repetición del envío del formulario
    header("Location: productos.php");
    exit();
}

// Actualizar productos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['guardar_cambios'])) {
    // Recuperar los datos del formulario
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $descuento = $_POST['descuento'];
    $descripcion = $_POST['descripcion'];
    $id_categoria = $_POST['id_categoria'];
    $activo = isset($_POST['activo']) ? 1 : 0;

    // Definir $id_producto antes de usarlo
    $id_producto = $_POST['id_producto'];

    $stmt->bindParam(':id_producto', $id_producto);

    // Preparar la consulta SQL para actualizar el producto
    $sql = "UPDATE store_web_dcake.producto 
            SET Nombre = :nombre, 
                Precio = :precio, 
                Descuento = :descuento, 
                Descripción = :descripcion, 
                id_categoria = :id_categoria, 
                Activo = :activo
            WHERE Codigo = :id_producto";

    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':id_producto', $id_producto);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':precio', $precio);
    $stmt->bindParam(':descuento', $descuento);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':id_categoria', $id_categoria);
    $stmt->bindParam(':activo', $activo);

    // Ejecutar la consulta
    $stmt->execute();

    // Mostrar mensaje de éxito o error
    if ($stmt->rowCount() > 0) {
        $mensaje = "Producto modificado con éxito.";
    } else {
        $mensaje = "Error al modificar el producto.";
        var_dump($stmt->errorInfo()); // Muestra detalles del error
    }
    // Redirigir para evitar la repetición del envío del formulario
    header("Location: productos.php");
    exit();
}
// Eliminar productos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar_producto'])) {
    // Recuperar el ID del producto a eliminar
    $id_producto = $_POST['id_producto'];

    // Preparar la consulta SQL para eliminar el producto
    $sql = "DELETE FROM store_web_dcake.producto WHERE Codigo = :id_producto";
    $stmt = $conn->prepare($sql);

    // Bind parameter
    $stmt->bindParam(':id_producto', $id_producto);

    // Ejecutar la consulta
    $stmt->execute();

    // Mostrar mensaje de éxito o error
    if ($stmt->rowCount() > 0) {
        $mensaje = "Producto eliminado con éxito.";
    } else {
        $mensaje = "Error al eliminar el producto.";
    }

    // Redirigir para evitar la repetición del envío del formulario
    header("Location: productos.php");
    exit();
}

// Obtener y mostrar Productos
$sql = "SELECT Codigo, Nombre, Precio, Descuento, Descripción, id_categoria, Activo FROM producto";
$result = $conn->query($sql);

// Mostrar el modal de mensaje si hay un mensaje disponible
if ($mensaje) {
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                var myModal = new bootstrap.Modal(document.getElementById('modalMensaje'));
                myModal.show();
            });
          </script>";
}

// Obtener y Mostrar Productos
$sql = "SELECT Codigo, Nombre, Precio, Descuento, Descripción, id_categoria, Activo FROM producto";
$result = $conn->query($sql);

// Modal de Mensajes
echo "<div class='modal fade' id='modalMensaje' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title' id='exampleModalLabel'>Mensaje del Sistema</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body'>
                    $mensaje
                </div>
            </div>
        </div>
    </div>";

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Productos</title>
    <!-- Agregar enlace a la hoja de estilos de Bootstrap -->
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <style>
        /* Estilos adicionales si es necesario */
    </style>
</head>
<body>

    <div class='container mt-4'>
        <h2>Lista de Productos</h2>

        <!-- Botón para abrir el modal de inserción -->
        <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#modalInsertar'>
            Insertar Nuevo Producto
        </button>

        <!-- Tabla de productos -->
        <table class='table table-bordered table-hover'>
            <thead class='thead-dark'>
                <tr>
                    <th>Codigo</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Descuento</th>
                    <th>Descripción</th>
                    <th>id_categoría</th>
                    <th>Activo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>";
// Dentro del bucle while
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>
            <td>{$row['Codigo']}</td>
            <td>{$row['Nombre']}</td>
            <td>{$row['Precio']}</td>
            <td>{$row['Descuento']}</td>
            <td>{$row['Descripción']}</td>
            <td>{$row['id_categoria']}</td>
            <td>{$row['Activo']}</td>
            <td>
                <form method='post'>
                    <input type='hidden' name='id_producto' value='{$row['Codigo']}'>
                    <button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#modalModificar{$row['Codigo']}' id='btnModificar{$row['Codigo']}'>Modificar</button>

                    <button type='submit' class='btn btn-danger btn-sm' name='eliminar_producto'>Eliminar</button>
                </form>
            </td>
          </tr>";

// Modal de Modificación

echo "<div class='modal fade' id='modalModificar{$row['Codigo']}' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
<div class='modal-dialog'>
    <div class='modal-content'>
        <div class='modal-header'>
            <h5 class='modal-title' id='exampleModalLabel'>Modificar Producto</h5>
            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
        </div>
        <div class='modal-body'>
            <!-- Formulario para modificar producto -->
            <form method='post'>
                <div class='mb-3'>
                    <label for='nombre' class='form-label'>Nombre</label>
                    <input type='text' class='form-control' id='nombre' name='nombre' value='{$row['Nombre']}' required>
                </div>
                <div class='mb-3'>
                    <label for='precio' class='form-label'>Precio</label>
                    <input type='text' class='form-control' id='precio' name='precio' value='{$row['Precio']}' required>
                </div>
                <div class='mb-3'>
                    <label for='descuento' class='form-label'>Descuento</label>
                    <input type='text' class='form-control' id='descuento' name='descuento' value='{$row['Descuento']}'>
                </div>
                <div class='mb-3'>
                    <label for='descripcion' class='form-label'>Descripción</label>
                    <textarea class='form-control' id='descripcion' name='descripcion' rows='3'>{$row['Descripción']}</textarea>
                </div>
                <div class='mb-3'>
                    <label for='id_categoria' class='form-label'>ID Categoría</label>
                    <input type='text' class='form-control' id='id_categoria' name='id_categoria' value='{$row['id_categoria']}'>
                </div>
                <div class='mb-3 form-check'>
                    <input type='checkbox' class='form-check-input' id='activo' name='Activo' ".($row['Activo'] ? 'checked' : '').">
                    <label class='form-check-label' for='activo'>Activo</label>
                </div>
                <input type='hidden' name='id_producto' value='{$row['Codigo']}'>
                <button type='submit' class='btn btn-primary' name='modificar_producto'>Guardar Cambios</button>
            </form>
        </div>
    </div>
</div>
</div>";

}
echo "</tbody></table></div>";

// Modal de Inserción
echo "<div class='modal fade' id='modalInsertar' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title' id='exampleModalLabel'>Insertar Nuevo Producto</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body'>
                    <!-- Formulario para insertar nuevo producto -->
                    <form method='post'>
                        <div class='mb-3'>
                            <label for='nombre' class='form-label'>Nombre</label>
                            <input type='text' class='form-control' id='nombre' name='nombre' required>
                        </div>
                        <div class='mb-3'>
                            <label for='precio' class='form-label'>Precio</label>
                            <input type='text' class='form-control' id='precio' name='precio' required>
                        </div>
                        <div class='mb-3'>
                            <label for='descuento' class='form-label'>Descuento</label>
                            <input type='text' class='form-control' id='descuento' name='descuento'>
                        </div>
                        <div class='mb-3'>
                            <label for='descripcion' class='form-label'>Descripción</label>
                            <textarea class='form-control' id='descripcion' name='descripcion' rows='3'></textarea>
                        </div>
                        <div class='mb-3'>
                            <label for='id_categoria' class='form-label'>ID Categoría</label>
                            <input type='text' class='form-control' id='id_categoria' name='id_categoria'>
                        </div>
                        <div class='mb-3 form-check'>
                            <input type='checkbox' class='form-check-input' id='activo' name='activo'>
                            <label class='form-check-label' for='activo'>Activo</label>
                        </div>
                        <button type='submit' class='btn btn-primary' name='insertar_producto'>Insertar Producto</button>
                    </form>
                </div>
            </div>
        </div>
    </div>";


// Cerrar conexión a la base de datos
$conn = null;

// Mostrar el modal de mensaje si hay un mensaje disponible
if ($mensaje) {
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                var myModal = new bootstrap.Modal(document.getElementById('modalMensaje'));
                myModal.show();
            });
          </script>";
}


// Recuperar el ID del producto a modificar de la URL
$id_producto = isset($_GET['id_producto']) ? $_GET['id_producto'] : null;

// Imprime el ID del producto en la consola para verificar
echo "<script>console.log('ID del producto:', '$id_producto');</script>";
echo "<script>
   document.addEventListener('DOMContentLoaded', function() {
       // Si hay un ID de producto en la URL, abrir el modal correspondiente
       if ('$id_producto') {
           const modalId = '#modalModificar' + '$id_producto';
           const myModal = new bootstrap.Modal(document.querySelector(modalId));
           myModal.show();
       }

       // También puedes agregar un listener para manejar el evento de ocultar modal
       // Esto evitará la recarga de la página al cerrar el modal
       document.querySelectorAll('.modal').forEach(function(modal) {
           modal.addEventListener('hidden.bs.modal', function() {
               window.history.replaceState({}, document.title, window.location.pathname);
           });
       });
   });
</script>";


//  scripts de Bootstrap y JavaScript
echo "<script src='https://code.jquery.com/jquery-3.6.4.min.js'></script>";
echo "<script src='https://code.jquery.com/jquery-3.6.4.slim.min.js'></script>";
echo "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>";


echo "</body></html>";

?>
</html>