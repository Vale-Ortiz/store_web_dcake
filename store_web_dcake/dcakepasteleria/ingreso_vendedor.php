<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de vendedor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa; /* Color de fondo */
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 400px;
            margin: auto;
            margin-top: 50px; /* Espaciado superior */
            background-color: #ffffff; /* Color del contenedor */
            padding: 20px;
            border-radius: 10px; /* Bordes redondeados */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra suave */
        }

        .form-label {
            color: #007bff; /* Color del texto del label */
            font-weight: bold; /* Texto en negrita */
            margin-bottom: 0.5rem; /* Espaciado inferior */
            animation: labelAnimation 2s infinite; /* Animación */
        }

        .form-control {
            animation: inputAnimation 2s ease-out; /* Animación */
        }

        .btn-primary {
            background-color: #000000; /* Color de fondo negro */
            border-color: #000000; /* Color del borde negro */
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #007bff; /* Cambia el color de fondo al pasar el mouse */
            border-color: #007bff; /* Cambia el color del borde al pasar el mouse */
            transform: scale(1.1); /* Efecto de escala al pasar el mouse */
        }

        /* Efecto de reescritura para los campos de entrada */
        @keyframes inputAnimation {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Animación para el texto del label */
        @keyframes labelAnimation {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-5px);
            }
        }

        .btn-container {
            display: flex;
            justify-content: center;
            margin-top: 20px; /* Ajusta el espaciado superior según tus preferencias */
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="mb-4" style="color: #007bff; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);">Portal de vendedor</h1>
        <form action="../config/controlador_login.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Nombre de Usuario:</label>
                <input type="text" class="form-control" name="usuario" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" class="form-control" name="contraseña" required>
            </div>
            <div class="btn-container">
                <input name="btn_ingresar" type="submit" class="btn btn-primary" value="Iniciar sesión"></input>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>



