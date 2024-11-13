<?php
// Verificar si se recibió el ID del usuario a eliminar
if (isset($_POST['idUsuario'])) {
    $idUsuario = $_POST['idUsuario'];

    // Realizar la conexión a la base de datos
    require '../store_web_dcake/config/conexion_usuario.php';

    // Preparar la consulta para eliminar al usuario
    $sql = "DELETE FROM store_web_dcake.usuario WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    // Ejecutar la consulta
    if ($stmt->execute([$idUsuario])) {
        // La eliminación fue exitosa
        echo "Usuario eliminado correctamente";
    } else {
        // Hubo un error en la eliminación
        echo "Error al intentar eliminar el usuario";
    }

    // Cerrar la conexión
    $conn = null;
} else {
    // No se proporcionó el ID del usuario
    echo "ID de usuario no proporcionado";
}
?>
