<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fidelización</title>
    <!-- Agregar enlaces a Bootstrap CSS y JS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: 004080;
            color: #000; 
        }

        .container {
            margin-top: 50px;
            max-width: 400px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            color: #495057;
            border: 2px solid #007bff;
            border-radius: 4px;
            transition: border-color 0.3s ease-in-out;
        }

        .form-control:focus {
            border-color: #0056b3;
            box-shadow: none;
        }

        .btn-primary {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-primary:hover {
            background-color: #004080;
            border-color: #004080;
        }

        /* Estilos para los botones de "Registrarme con Gmail" y "Registrarme con Hotmail" */
        .social-buttons a {
            display: inline-block;
            margin-right: 10px; /* Espaciado entre botones */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Fidelización</h2>
        <form action="../config/procesar_registro.php" method="post" id="registroForm">
    
            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="confirmar_email">Confirmar Correo Electrónico:</label>
                <input type="email" class="form-control" id="confirmar_email" name="confirmar_email" required>
            </div>
            <!-- Campos de inicio de sesión -->
            <div class="form-group">
                <label for="nombre_usuario">Nombre de Usuario:</label>
                <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <small id="passwordHelp" class="form-text text-muted"></small>
            </div>
            <div class="form-group">
                <label for="confirmar_password">Confirmar Contraseña:</label>
                <input type="password" class="form-control" id="confirmar_password" name="confirmar_password" required>
                <small id="confirmPasswordHelp" class="form-text text-muted"></small>
            </div>
              <!-- Barra de progreso de seguridad de la contraseña -->
            <div class="form-group">
                <div class="progress">
                    <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%;"></div>
                </div>
            </div>
            <!-- Botón de Registrarme -->
            <button type="submit" class="btn btn-primary">Registrarme</button>
        </form>
        <!-- Botón de "Ya tengo una cuenta" y Opciones de Registro -->
        <p class="mt-3">¿Ya tienes una cuenta? <a href="seccionfide.php">Iniciar sesión</a></p>
        <p class="mt-3">O regístrate con:</p>
        <a href="#" class="btn btn-danger ">Gmail</a>
        <a href="#" class="btn btn-primary "> Hotmail</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<!-- Script de validación de contraseña y barra de progreso -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var passwordInput = document.getElementById("password");
        var confirmPasswordInput = document.getElementById("confirmar_password");
        var registrationForm = document.getElementById("registroForm");
        var confirmPasswordHelp = document.getElementById("confirmPasswordHelp");

registrationForm.addEventListener("submit", function(event) {
    // Obtener el valor de la contraseña
    var password = passwordInput.value;

    // Realizar la validación de contraseña
    var isValidPassword = esValidaContrasena(password);

    // Verificar que las contraseñas coincidan
    var confirmar_password = confirmPasswordInput.value;
    var isValidConfirmation = password === confirmar_password;

    // Actualizar el borde y mostrar el mensaje en el campo de confirmar contraseña
    actualizarBordeCampo(confirmPasswordInput, isValidConfirmation);

    // Mostrar mensaje de confirmación en el campo de contraseñas coincidentes
    confirmPasswordHelp.innerText = isValidConfirmation ? "Las contraseñas coinciden." : "Las contraseñas no coinciden. Por favor, verifica y vuelve a intentar.";

    // Evitar el envío del formulario si hay algún problema con las contraseñas
    if (!isValidPassword || !isValidConfirmation) {
        // Mostrar un mensaje general indicando que no se puede registrar
        alert("No te puedes registrar. Por favor, verifica los campos del formulario.");

        // Evitar el envío del formulario
        event.preventDefault();
    }
});
        
        passwordInput.addEventListener("input", function() {
            // Obtener el valor de la contraseña
            var password = this.value;

            // Realizar la validación de contraseña
            var isValid = esValidaContrasena(password);

            // Actualizar el borde del campo de contraseña y mostrar el mensaje si no es válido
            actualizarBordeCampo(this, isValid);
            document.getElementById("passwordHelp").innerText = isValid ? "Contraseña válida" : "La contraseña debe contener al menos 8 dígitos, con letras mayúsculas, números y símbolos.";

            // Verificar la longitud de la contraseña y actualizar la barra de progreso
            var progress = calcularProgreso(password);
            actualizarBarraProgreso(progress);

            // Limpiar mensaje de confirmación de contraseña
            document.getElementById("confirmPasswordHelp").innerText = "";
        });

        confirmPasswordInput.addEventListener("input", function() {
            // Obtener el valor de la confirmación de contraseña
            var confirmar_password = this.value;

            // Obtener el valor de la contraseña
            var password = passwordInput.value;

            // Verificar que las contraseñas coincidan
            if (password !== confirmar_password) {
                // Las contraseñas no coinciden
                document.getElementById("confirmPasswordHelp").innerText = "Las contraseñas no coinciden. Por favor, verifica y vuelve a intentar.";
                actualizarBordeCampo(this, false);
            } else {
                // Las contraseñas coinciden
                document.getElementById("confirmPasswordHelp").innerText = "Las contraseñas coinciden.";
                actualizarBordeCampo(this, true);
            }
        });
        // Desactivar la capacidad de copiar y pegar en el campo de contraseña
        passwordInput.addEventListener("paste", function(e) {
            e.preventDefault();
        });

        // Desactivar la capacidad de copiar y pegar en el campo de confirmar contraseña
        confirmPasswordInput.addEventListener("paste", function(e) {
            e.preventDefault();
        });
    });

    function esValidaContrasena(contrasena) {
        // Utilizar una expresión regular para validar la combinación de letras, números y símbolos
        var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        return regex.test(contrasena);
    }

    function calcularProgreso(contrasena) {
        // Calcular el progreso en función de la longitud y complejidad de la contraseña
        var longitud = contrasena.length;
        var complejidad = esValidaContrasena(contrasena) ? 1 : 0;

        // Ajustar el progreso según la longitud y la complejidad
        var progress = Math.min(100, (longitud + complejidad * 2) * 10);

        // Actualizar el color de la barra de progreso
        var progressBar = document.getElementById("progressBar");
        if (longitud < 8 || complejidad === 0) {
            progressBar.classList.remove("bg-success", "bg-warning");
            progressBar.classList.add("bg-danger");
        } else if (progress < 50) {
            progressBar.classList.remove("bg-success");
            progressBar.classList.add("bg-warning");
        } else {
            progressBar.classList.remove("bg-warning", "bg-danger");
            progressBar.classList.add("bg-success");
        }

        return progress;
    }

    function actualizarBarraProgreso(progress) {
        // Actualizar la barra de progreso
        document.getElementById("progressBar").style.width = progress + "%";
    }

    function actualizarBordeCampo(campo, isValid) {
        // Actualizar el borde del campo según su validez
        campo.style.border = isValid ? "1px solid #28a745" : "1px solid #dc3545";
    }
</script>

</body>

</html>
