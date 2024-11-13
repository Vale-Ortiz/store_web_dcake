<?php
require '../config/conexion_usuario.php';

// Consulta para obtener todos los usuarios
$sql = "SELECT id, correo_electronico, usuario, contraseña FROM usuario";
$result = $conn->query($sql);

// Comprobar si hay resultados
if ($result->rowCount() > 0) {
    $usuarios = $result->fetchAll(PDO::FETCH_ASSOC);
} else {
    $usuarios = array(); // Si no hay usuarios, crea un array vacío
}

$conn = null; // Cerrar la conexión
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        input[type="text"] {
            padding: 8px;
            margin-bottom: 10px;
            width: 200px;
        }

        button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        .eliminar-btn {
            background-color: #f44336;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .modal-btn {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        .modal-btn.cancelar {
            background-color: #f44336;
        }
    </style>
</head>
<body>
    <div style="margin: 20px;">
        <h1>Lista de Usuarios</h1>
        <input type="text" id="buscador" placeholder="Buscar por ID, Usuario o Correo Electrónico">
        <button onclick="buscar()">Buscar</button>

        <table id="tablaUsuarios">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Usuario</th>
                    <th>Id</th>
                    <th>Correo Electrónico</th>
                    <th>Contraseña</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $contador = 1;
                foreach ($usuarios as $usuario) {
                    echo "<tr id='row_{$usuario['id']}'>";
                    echo "<td>$contador</td>";
                    echo "<td>{$usuario['usuario']}</td>";
                    echo "<td>{$usuario['id']}</td>";
                    echo "<td>{$usuario['correo_electronico']}</td>";
                    echo "<td>{$usuario['contraseña']}</td>";
                    echo "<td><button class='eliminar-btn' onclick='confirmarEliminar({$usuario['id']})'>Eliminar</button></td>";
                    echo "</tr>";
                    $contador++;
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal de confirmación para eliminar usuario -->
    <div id="modalEliminar" class="modal">
        <div class="modal-content">
            <p>¿Estás seguro de eliminar a este usuario? Tu acción no podrá ser revertida.</p>
            <input type="hidden" id="idUsuarioEliminar" value="">
            <button class="modal-btn" onclick="eliminarUsuario()">Sí</button>
            <button class="modal-btn cancelar" onclick="cerrarModal()">No</button>
        </div>
    </div>

    <script>
        function buscar() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("buscador");
            filter = input.value.toUpperCase();
            table = document.getElementById("tablaUsuarios");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1]; // Cambia a [2] para buscar por la columna de Usuario
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        function confirmarEliminar(idUsuario) {
            var modal = document.getElementById("modalEliminar");
            modal.style.display = "block";

            // Guardar el ID del usuario a eliminar en un campo oculto del modal
            document.getElementById("idUsuarioEliminar").value = idUsuario;
        }

        function eliminarUsuario() {
            // Obtener el ID del usuario a eliminar desde el campo oculto
            var idUsuario = document.getElementById("idUsuarioEliminar").value;

            // Realizar una solicitud AJAX al servidor para eliminar el usuario
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../config/eliminar_usuario.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Actualizar la tabla después de eliminar el usuario
                    var tableRow = document.getElementById("row_" + idUsuario);
                    if (tableRow) {
                        tableRow.remove();
                    }

                    // Una vez implementada la eliminación, cierra el modal
                    cerrarModal();
                }
            };

            xhr.send("idUsuario=" + idUsuario);
        }

        function cerrarModal() {
            var modal = document.getElementById("modalEliminar");
            modal.style.display = "none";
        }
    </script>
</body>
</html>
