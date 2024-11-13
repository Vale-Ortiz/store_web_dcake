<?php
// Verificar si se recibió el ID del usuario a eliminar
if (isset($_POST['idComentario'])) {
    $idComentario = $_POST['idComentario'];

    // Realizar la conexión a la base de datos
    require '../store_web_dcake/config/conexion_usuario.php';

    // Preparar la consulta para eliminar el comentario
    $sql = "DELETE FROM store_web_dcake.comentario WHERE id_comentario = ?";
    $stmt = $conn->prepare($sql);
    
    // Ejecutar la consulta
    if ($stmt->execute([$idComentario])) {
        // La eliminación fue exitosa
        echo "Comentario eliminado correctamente";
    } else {
        // Hubo un error en la eliminación
        echo "Error al intentar eliminar el comentario";
    }

    // Cerrar la conexión
    $conn = null;
} else {
    // No se proporcionó el ID del comentario
    echo "ID de comentario no proporcionado";
}
?>