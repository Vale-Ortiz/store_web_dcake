<?php
require_once '../config/conexion.php';

// Consulta para obtener todos los comentarios
$sql = "SELECT nombre_usuario, id_usuario, id_comentario, texto, correo_electronico, fecha_registro FROM comentario";

$result = $conexion->query("SELECT * FROM comentario");

// Comprobar si hay resultados
if ($result->num_rows > 0) {
    $comentario = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $comentario = array(); // Si no hay comentarios, crea un array vacío
}

$conexion->close(); // Cierra la conexión
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comentarios</title>
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
        <h1>Comentarios</h1>
        <input type="text" id="buscador" placeholder="Buscar por ID, Usuario o Correo Electrónico">
        <button onclick="buscar()">Buscar</button>

        <table id="tablaComentario">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Comentario</th>
                    <th>Correo electronico</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $contador = 1;
                foreach ($comentario as $comentarioItem) {
                    echo "<tr id='row_{$comentarioItem['id_comentario']}'>";
                    echo "<td>$contador</td>";
                    echo "<td>{$comentarioItem['texto']}</td>";
                    echo "<td>{$comentarioItem['correo_electronico']}</td>";
                    echo "<td><button class='eliminar-btn' onclick='confirmarEliminar({$comentarioItem['id_comentario']})'>Eliminar</button></td>";
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
            <p>¿Estás seguro de eliminar  este comentario? Tu acción no podrá ser revertida.</p>
            <input type="hidden" id="idComentarioEliminar" value="">
            <button class="modal-btn" onclick="eliminarComentario()">Sí</button>
            <button class="modal-btn cancelar" onclick="cerrarModal()">No</button>
        </div>
    </div>


    <script>
        function buscar() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("buscador");
            filter = input.value.toUpperCase();
            table = document.getElementById("tablaComentario");
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

        function confirmarEliminar(idComentario) {
            var modal = document.getElementById("modalEliminar");
            modal.style.display = "block";

            document.getElementById("idComentarioEliminar").value = idComentario;
        }

        function eliminarComentario() {
            var idComentario = document.getElementById("idComentarioEliminar").value;

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../config/eliminar_comentario.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var tableRow = document.getElementById("row_" + idComentario);
                    if (tableRow) {
                        tableRow.remove();
                    }

                    cerrarModal();
                }
            };

            xhr.send("idComentario=" + idComentario);
        }

        function cerrarModal() {
            var modal = document.getElementById("modalEliminar");
            modal.style.display = "none";
        }
    </script>

    </body>
</html>
