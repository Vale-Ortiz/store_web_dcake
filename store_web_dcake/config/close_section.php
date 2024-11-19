<?php

session_start(); // Iniciar la sesión

if (isset($_SESSION['usuario'])) {
    // Eliminar todas las variables de sesión
    session_unset();

    // Destruir la sesión
    session_destroy();
}

// Redirigir al usuario a la página de inicio de sesión
header('Location: ../index.php');
exit;
?>
