<?php
require '../config/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $confirmar_email = $_POST['confirmar_email'];
    $nombre_usuario = $_POST['nombre_usuario'];
    $password = $_POST['password'];
    $confirmar_password = $_POST['confirmar_password'];

    if ($password !== $confirmar_password) {
        echo "Las contraseñas no coinciden. Por favor, verifica y vuelve a intentar.";
    } else {
        $stmt = $conexion->prepare("INSERT INTO usuario (correo_electronico, nombre_usuario, contraseña) VALUES (?, ?, ?)");

        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $conexion->error);
        }
        
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt->bind_param("sss", $email, $nombre_usuario, $hashed_password);
        $result = $stmt->execute();

        if ($result) {
            echo "Registro exitoso. ¡Bienvenido!";
            header("Location: ../index.php");
        } else {
            echo "Error al registrar. Por favor, inténtalo nuevamente.";
        }

        $stmt->close();
    }

    $conn->close();
}