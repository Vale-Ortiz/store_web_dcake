<?php
// Verificar si se ha enviado el formulario para insertar productos
if (isset($_POST['codigo']) && isset($_POST['nombre']) && isset($_POST['descripcion']) && isset($_POST['precio']) && isset($_POST['categoria'])) {
    // Recoger los datos ingresados por el usuario
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $categoria = $_POST['categoria'];
    $activo = isset($_POST['activo']) ? 1 : 0; // Si el checkbox está marcado, establecer activo en 1; de lo contrario, en 0

    $conexion = mysqli_connect("localhost","root","","store_web_dcake");

    $consulta = "INSERT INTO store_web_dcake.producto (Codigo, Nombre, Descripción, Precio, id_categoria, Activo) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("sssdis", $codigo, $nombre, $descripcion, $precio, $categoria, $activo);
    
    if ($stmt->execute()) {
        // La inserción fue exitosa, redirigir a portalvendedor.php
        header("Location: ../store_web_dcake/dcakepasteleria/portalvendedor.php");
        exit();
    } else {
        echo "Error al insertar el producto.";
    }

    // Cerrar la conexión a la base de datos.
    $stmt->close();
    $conexion->close();
}
?>