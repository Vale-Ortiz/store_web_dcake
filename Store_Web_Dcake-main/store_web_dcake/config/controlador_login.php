<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

</head>
<body>
</body>
</html>

<?php

$usuario = $_POST['usuario'];
$contraseña = $_POST['contraseña'];

//conectar a la base de datos
$conexion = mysqli_connect("localhost","root","","store_web_dcake");
$consulta = "SELECT * FROM store_web_dcake.vendedor WHERE usuario = '$usuario' and contraseña = '$contraseña'";

$resultado = mysqli_query($conexion,$consulta);

$filas = mysqli_num_rows($resultado);

if($filas > 0) {
    header("location: ../store_web_dcake/dcakepasteleria/iniciovendedor.php");
} else { ?>

<div class="container center-message">
    <div class="alert alert-primary text-center" role="alert">
        <h4 class="alert-heading">Datos incorrectos :( </h4>
        <p>¿ERES VENDEDOR DE D'CAKE?</p>
        <hr>
        <p class="mb-0"> Si eres cliente y estas intentando ingresar, no tienes acceso a esta área.</p>
        <p class="mb-0">Para mayor información comunicate al: 3008936926</p>
        <p class="mb-0"> ****Si eres vendedor y olvidaste tu usuario y/o contraseña comunicate con tu administrador***</p>
    </div>
</div>
<?php } ?>

<?php
mysqli_free_result($resultado);
mysqli_close($conexion);
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
