<?php
// Inicia la sesión si no está iniciada
session_start();

// Destruye la sesión (cierra la sesión del usuario)
session_destroy();

// Redirige al usuario a index.php con un mensaje
header('Location: ../store_web_dcake/index.php');
exit();
?>
