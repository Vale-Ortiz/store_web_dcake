<?php
// Iniciar sesión
session_start();

// Conexión a la base de datos (utilizando PDO)
$dsn = 'mysql:host=localhost;dbname=store_web_dcake';
$username = 'root';
$password = '';

try {
    $db = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    die('Error de conexión a la base de datos: ' . $e->getMessage());
}

// Obtener datos del formulario
$usuario = $_POST['usuario'];
$contrasena = $_POST['password'];

// Consulta para obtener la información del usuario, incluido el id_usuario
$stmt = $db->prepare("SELECT id, usuario, contraseña FROM store_web_dcake.usuario WHERE usuario = :usuario");
$stmt->bindParam(':usuario', $usuario);
$stmt->execute();

// Verificar credenciales
if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $hashed_password = $row['contraseña'];

    if (password_verify($contrasena, $hashed_password)) {
        // Las credenciales son correctas, iniciar sesión
        $_SESSION['usuario'] = $row['usuario'];
        
        // Utiliza el nombre de columna correcto para el ID del usuario
        $_SESSION['id_usuario'] = $row['id'];

        // Imprime el valor de id_usuario para verificar
        var_dump($_SESSION['id_usuario']);

        // Recuperar el carrito desde la base de datos y establecerlo en la sesión
        $_SESSION['carrito']['productos'] = recuperarCarritoDesdeBaseDeDatos($row['usuario']);

        // Redirigir a la página principal
        header('Location: ../index.php');
    } else {
        // Contraseña incorrecta, redirigir al formulario de inicio de sesión
        header('Location: seccionfide.php');
    }
} else {
    // Usuario no encontrado, redirigir al formulario de inicio de sesión
    header('Location: seccionfide.php');
}



// Cerrar conexión a la base de datos
$db = null;

// Función para recuperar el carrito desde la base de datos
function recuperarCarritoDesdeBaseDeDatos($nombreUsuario) {
    // Conexión a la base de datos (utilizando PDO)
    $dsn = 'mysql:host=localhost;dbname=store_web_dcake';
    $username = 'root';
    $password = '';

    try {
        $db = new PDO($dsn, $username, $password);
    } catch (PDOException $e) {
        die('Error de conexión a la base de datos: ' . $e->getMessage());
    }

    // Consulta para obtener los productos en el carrito del usuario
    $stmt = $db->prepare("SELECT codigo_producto, cantidad FROM store_web_dcake.carrito WHERE id_usuario = :usuario");
    $stmt->bindParam(':usuario', $nombreUsuario);
    $stmt->execute();

    // Array para almacenar los productos del carrito
    $productosCarrito = array();

    // Recorre los resultados de la consulta y agrega los productos al array
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $productosCarrito[$row['codigo_producto']] = $row['cantidad'];
    }

    // Cerrar conexión a la base de datos
    $db = null;

    return $productosCarrito;
}
?>
