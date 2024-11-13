<?php
// Verificar si el usuario está autenticado, si no, redirigirlo al formulario de inicio de sesión
session_start();
if (empty($_SESSION['usuario'])) {
    header('Location: seccionfide.php');
    exit();
}

$nombre_usuario = $_SESSION['usuario'];

// Obtener el historial de pedidos
function obtenerHistorialPedidos($nombre_usuario)
{
    require '../config/config2.php';

    $db = new Database2();
    $con = $db->conectar();

    try {
        $query_usuario = $con->prepare("SELECT id FROM store_web_dcake.usuario WHERE usuario = ?");
        $query_usuario->execute([$nombre_usuario]);
        $usuario = $query_usuario->fetch(PDO::FETCH_ASSOC);

        // Lógica para recibir y guardar el pedido
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_pedido'])) {
            $idPedido = $_POST['id_pedido'];

            // Agrega lógica aquí para guardar el pedido en la base de datos
            try {
                // Ejemplo de inserción en la base de datos (ajusta según tu estructura)
                $query_insert_pedido = $con->prepare("INSERT INTO store_web_dcake.pedido (id_pedido, id_usuario) VALUES (?, ?)");
                $query_insert_pedido->execute([$idPedido, $usuario['id']]);

                echo 'Éxito'; // Puedes devolver cualquier mensaje que desees
            } catch (PDOException $e) {
                echo 'Error al guardar el pedido en la base de datos: ' . $e->getMessage();
            }
        }

        $id_usuario = $usuario['id'];

        $query = $con->prepare("
            SELECT pedido.*, detalles_pedido.*, producto.Nombre AS nombre_producto
            FROM pedido
            JOIN detalles_pedido ON pedido.id_pedido = detalles_pedido.id_pedido
            JOIN producto ON detalles_pedido.codigo_producto = producto.Codigo
            WHERE pedido.id_usuario = ?
            ORDER BY pedido.fecha DESC
        ");

        $query->execute([$id_usuario]);

        $historial_pedidos = $query->fetchAll(PDO::FETCH_ASSOC);

        return $historial_pedidos;
    } catch (PDOException $e) {
        // Manejar errores de la base de datos
        echo "Error al obtener el historial de pedidos: " . $e->getMessage();
        return array();
    }
}

// Llamar a la función para obtener el historial de pedidos
$historial_pedidos = obtenerHistorialPedidos($nombre_usuario);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Avance</title>
    <!-- Agrega los estilos de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container mt-2">
    <div class="row">
        <div class="col-md-6">
            <!-- Bienvenida al usuario -->
            <h2>Bienvenido a tus configuraciones, <?php echo $nombre_usuario; ?>.</h2>

            <!-- Editar correo electrónico -->
            <h4><a href="#" class="small text-muted">Editar Correo Electrónico</a></h4>
            <!-- Agrega un formulario o enlace para editar el correo electrónico -->

            <!-- Cambiar contraseña -->
            <h4><a href="#" class="small text-muted">Cambiar Contraseña</a></h4>
            <!-- Agrega un formulario o enlace para cambiar la contraseña -->
        </div>

        <div class="col-md-6 text-right">
            <!-- Botón para abrir el modal del historial de pedidos -->
            <button type="button" class="btn btn-primary btn-sm mt-2 ml-2" data-toggle="modal" data-target="#historialModal">
                Ver Historial de Pedidos
            </button>
            <!-- Botón de Cerrar Sesión en la parte superior derecha -->
            <a href="../config/close_section_usuario.php" class="btn btn-danger btn-sm mt-2">Cerrar Sesión</a>
        </div>
    </div>

    <div class="row mt-4">
    <div class="col-md-2">
        <!-- Recuadro para seleccionar foto de perfil -->
        <div id="fotoRecuadro" class="border p-4" style="cursor: pointer;">
            <input type="file" id="seleccionarFoto" style="display: none;" accept="image/*">
            <img id="imagenSeleccionada" src="ruta/a/tu/foto/de/perfil.jpg" alt="Foto de perfil" class="img-fluid">
            <p class="mt-2 small text-muted">Haz clic para seleccionar una foto</p>
        </div>

        <!-- Botones debajo del recuadro -->
        <div class="mt-2">
            <button class="btn btn-primary ml-3" data-toggle="modal" data-target="#subirFotoModal">Cambiar Foto</button>
        </div>
        <div class="mt-2">
        <button class="btn btn-danger ml-3" id="eliminarFotoBtn">Eliminar Foto</button>
        </div>
    </div>
</div>

<!-- Modal para mostrar el historial de pedidos -->
<div class="modal" id="historialModal" tabindex="-1" role="dialog" aria-labelledby="historialModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historialModalLabel">Historial de Pedidos de <?php echo $nombre_usuario; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                $currentDate = null; // Variable para rastrear la fecha actual

                foreach ($historial_pedidos as $pedido) {
                    if ($pedido['fecha'] !== $currentDate) {
                        // Si la fecha es diferente, muestra la nueva fecha y reinicia la lista de productos
                        if ($currentDate !== null) {
                            echo '</tbody></table>';
                            echo '<p>Total del Pedido: $' . number_format($total_pedido, 2, '.', ',') . '</p>';
                        }
                        echo '<p>ID Pedido: ' . $pedido['id_pedido'] . '</p>';
                        echo '<p>Fecha: ' . $pedido['fecha'] . '</p>';
                        echo '<table class="table">';
                        echo '<thead><tr><th>Producto</th><th>Cantidad</th><th>Precio</th></tr></thead>';
                        echo '<tbody>';
                        $currentDate = $pedido['fecha'];
                        $total_pedido = 0;
                    }

                    // Muestra los detalles del pedido
                    echo '<tr>';
                    echo '<td>' . $pedido['nombre_producto'] . '</td>';
                    echo '<td>' . $pedido['cantidad'] . '</td>';
                    
                    // Obtén el descuento del producto
                    $descuento_producto = isset($pedido['descuento']) ? $pedido['descuento'] : 0;

                    // Calcula el precio con descuento
                    $precio_con_descuento = $pedido['precio'] - (($pedido['precio'] * $descuento_producto) / 100);

                    echo '<td>$' . number_format($precio_con_descuento, 2, '.', ',');
                    if ($descuento_producto > 0) {
                        echo ' (Descuento: ' . $descuento_producto . '%)';
                    }
                    echo '</td></tr>';

                    // Suma el total del producto al total del pedido
                    $total_pedido += $pedido['cantidad'] * $precio_con_descuento;
                }

                // Cierra la tabla y muestra el total del pedido
                if (!empty($historial_pedidos)) {
                    echo '</tbody></table>';
                    echo '<p>Total del Pedido: $' . number_format($total_pedido, 2, '.', ',') . '</p>';
                }
                ?>
                
                <!-- Botón para realizar un nuevo pedido -->
                <button type="button" class="btn btn-success" onclick="realizarNuevoPedidoDesdeHistorial()">Realizar Nuevo Pedido</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>



<script>
    // Función para iniciar el proceso de realizar un nuevo pedido desde el historial
    function realizarNuevoPedidoDesdeHistorial() {
        // Agrega aquí la lógica para realizar un nuevo pedido utilizando los productos del historial
        // Puedes redirigir al usuario a la página de nuevo pedido o mostrar un formulario en el modal, por ejemplo
        // ...
        alert('Iniciar proceso para realizar nuevo pedido');
    }
</script>


<!-- Agrega los scripts y estilos de Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.4.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<!-- Script adicional para manejar la selección de la foto y los botones -->
<script>
    // Función para manejar la selección de la foto
    function handleFileSelect(event) {
        const fileInput = event.target;
        const fotoRecuadro = document.getElementById('fotoRecuadro');
        const imagenSeleccionada = document.getElementById('imagenSeleccionada');

        const file = fileInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                imagenSeleccionada.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    // Función para cambiar la foto de perfil
    function cambiarFotoPerfil() {
        // Puedes agregar aquí lógica adicional para enviar la foto al servidor si es necesario
        // Por ejemplo, puedes usar AJAX para enviar la foto al servidor y actualizar la foto en la base de datos
        alert('Cambiar Foto de Perfil');
    }

    // Función para eliminar la foto de perfil
    function eliminarFotoPerfil() {
        // Puedes agregar aquí lógica adicional para eliminar la foto del servidor y actualizar la foto en la base de datos
        imagenSeleccionada.src = ''; // Elimina la imagen mostrada
        document.getElementById('seleccionarFoto').value = ''; // Limpia el campo de archivo
        alert('Eliminar Foto de Perfil');
    }

    // Asocia la función al cambio del campo de archivo
    document.getElementById('seleccionarFoto').addEventListener('change', handleFileSelect);

    // Asocia el clic en el recuadro para activar el campo de archivo
    document.getElementById('fotoRecuadro').addEventListener('click', function () {
        document.getElementById('seleccionarFoto').click();
    });

    // Asocia el clic al botón "Cambiar Foto de Perfil"
    document.getElementById('cambiarFotoBtn').addEventListener('click', cambiarFotoPerfil);

    // Asocia el clic al botón "Eliminar Foto de Perfil"
    document.getElementById('eliminarFotoBtn').addEventListener('click', eliminarFotoPerfil);

    // Función para manejar la selección de la foto en el modal
    function handleFileSelectModal(event) {
        const fileInput = event.target;
        const recuadroVisualizacion = document.getElementById('recuadroVisualizacion');

        const file = fileInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                recuadroVisualizacion.style.backgroundImage = `url('${e.target.result}')`;
            };
            reader.readAsDataURL(file);
        }
    }

    // Asocia la función al cambio del campo de archivo en el modal
    document.getElementById('subirDesdePC').addEventListener('change', handleFileSelectModal);

    // Función para guardar la foto y cerrar el modal
    function guardarFoto() {
        // Puedes agregar aquí lógica adicional para guardar la foto en el servidor
        // Por ejemplo, puedes usar AJAX para enviar la foto al servidor y actualizar la foto en la base de datos
        alert('Guardar Foto');
        $('#subirFotoModal').modal('hide'); // Cierra el modal
    }
</script>
</body>
</html>