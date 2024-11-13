<?php
require '../store_web_dcake/config/conexion_comentario.php';
?>
<?php
// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera los datos del formulario
    $email = $_POST['email'];
    $confirmar_email = $_POST['confirmar_email'];
    $nombre_usuario = $_POST['nombre_usuario'];
    $password = $_POST['password'];
    $confirmar_password = $_POST['confirmar_password'];

    // Realiza las validaciones necesarias (puedes reutilizar tu lógica de validación aquí)

    // Verifica que las contraseñas coincidan
    if ($password !== $confirmar_password) {
        echo "Las contraseñas no coinciden. Por favor, verifica y vuelve a intentar.";
    } else {
        // Las contraseñas coinciden, procede a guardar en la base de datos
        // la tabla de la base de datos se llama 'usuario'
        // y tiene los campos: correo_electronico, usuario, contraseña
        $sql = "INSERT INTO usuario (correo_electronico, usuario, contraseña) VALUES (?, ?, ?)";
        $stmt = $conexion2->prepare($sql);
        
        // Encripta la contraseña antes de almacenarla (puedes ajustar tu lógica de encriptación aquí)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Asocia los parámetros y ejecuta la consulta
        $stmt->bind_param("sss", $email, $nombre_usuario, $hashed_password);
        $result = $stmt->execute();

        // Verifica si la consulta se ejecutó correctamente
        if ($result) {
            echo "Registro exitoso. ¡Bienvenido!";
            header ("Location: ../index.php");
        } else {
            echo "Error al registrar. Por favor, inténtalo nuevamente.";
        }

        // Cierra la declaración
        $stmt->close();
        
    }
    $conexion2->close();
}
?>