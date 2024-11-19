<?php
// Conexión a la base de datos
$conexion4 = new mysqli("localhost", "root", "", "store_web_dcake");

if ($conexion4->connect_error) {
    die("Error en la conexión a la base de datos: " . $conexion4->connect_error);
}

// Consulta para obtener los productos
$sql = "SELECT Código, Nombre, Descripción, Precio, id_categoria, Activo FROM producto";
$resultado = $conexion4->query($sql);

$productos = array();

if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $productos[] = $row;
    }
}
$conexion4->close();
header('Content-Type: application/json');
echo json_encode($productos);
?>