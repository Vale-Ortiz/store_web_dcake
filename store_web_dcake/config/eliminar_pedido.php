<?php
// Verificar si se recibió el ID del usuario a eliminar
if (isset($_POST['idPedido'])) {
    $idPedido = $_POST['idPedido'];

    // Realizar la conexión a la base de datos
    require '../store_web_dcake/config/conexion_usuario.php';

    // Preparar la consulta para eliminar al usuario
    $sql = "DELETE FROM store_web_dcake.pedido WHERE id_pedido = ?";
    $stmt = $conn->prepare($sql);
    
    // Ejecutar la consulta
    if ($stmt->execute([$idPedido])) {
        // La eliminación fue exitosa
        echo "Pedido eliminado correctamente";
    } else {
        // Hubo un error en la eliminación
        echo "Error al intentar eliminar el pedido";
    }

    // Cerrar la conexión
    $conn = null;
} else {
    // No se proporcionó el ID del usuario
    echo "ID de pedido no proporcionado";
}
?>