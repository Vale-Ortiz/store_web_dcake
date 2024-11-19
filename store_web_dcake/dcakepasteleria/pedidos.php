<?php
require_once '../config/conexion.php';


$sql = "SELECT id_pedido, fecha, id_usuario, total, nombre_usuario FROM pedido";

$result = $conexion->query("SELECT * FROM pedido");

// Comprobar si hay resultados
if ($result->num_rows > 0) {
    $pedido = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $pedido = array(); // Si no hay pedidos, crea un array vacío
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/Style.css"> 
    <link rel="shortcut icon" href="/images2/icologo.ico">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
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
        <h1>Pedidos</h1>
        <input type="text" id="buscador" placeholder="Buscar por ID o por fecha">
        <button onclick="buscar()">Buscar</button>

        <table id="tablaPedidos">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Id Usuario</th>
                    <th>Nombre de usuario</th>
                    <th>Id Pedido</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $contador = 1;
                foreach ($pedido as $pedidoItem) {
                    echo "<tr id='row_{$pedidoItem['id_pedido']}'>";
                    echo "<td>$contador</td>";
                    echo "<td>{$pedidoItem['fecha']}</td>";
                    echo "<td>{$pedidoItem['id_usuario']}</td>";
                    echo "<td>{$pedidoItem['nombre_usuario']}</td>";
                    echo "<td>{$pedidoItem['id_pedido']}</td>";
                    echo "<td>{$pedidoItem['total']}</td>";
                    echo "<td>
                    <button class='eliminar-btn' onclick='confirmarEliminar({$pedidoItem['id_pedido']})'>Eliminar</button>
                    <button class='detalles-btn' onclick='DetallesConfirmarPedido({$pedidoItem['id_pedido']})'>Ver detalles y confirmar</button>
                </td>";
                    echo "</tr>";
                    $contador++;
                }
                ?>
            </tbody>
        </table>
    </div>

    <div id="modalConfirmar" class="modal">
    <div class="modal-content" style="max-width: 600px; margin: auto;">
        <h2 class="text-center mb-4">Detalles del Pedido<span id="pedidoId"></span></h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Codigo producto</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                </tr>
            </thead>
            <tbody id="detallesPedidoBody"></tbody>
        </table>

        <h2 class="text-center mt-4">Confirmar Pedido</h2>
        <form id="confirmarForm">
            <div class="form-group">
                <label for="confirmarNombre" class="text-center">Confirmar nombre:</label>
                <input type="text" class="form-control" id="confirmarNombre" name="confirmarNombre" required>
            </div>

            <div class="form-group">
                <label for="confirmarTelefono" class="text-center">Confirmar teléfono:</label>
                <input type="text" class="form-control" id="confirmarTelefono" name="confirmarTelefono" required>
            </div>

            <div class="form-group">
                <label for="confirmarDireccion" class="text-center">Confirmar dirección:</label>
                <input type="text" class="form-control" id="confirmarDireccion" name="confirmarDireccion" required>
            </div>

            <div class="form-group">
                <label for="medioPago" class="text-center">Medio de pago:</label>
                <select class="form-control" id="medioPago" name="medioPago">
                    <option value="efectivo">Efectivo</option>
                    <option value="transferencia">Transferencia Bancolombia</option>
                </select>
            </div>

            <input type="hidden" id="idPedido" name="idPedido" value="">
            
            <div class="text-center mt-4">
                <button type="button" class="btn btn-success btn-sm" onclick="confirmarPedido()">Confirmar Pedido</button>
                <button class="btn btn-danger btn-sm ml-2" onclick="cerrarModal2()">Cerrar</button>
            </div>
        </form>
    </div>
</div>

 <!-- Modal de confirmación para eliminar pedido -->
 <div id="modalEliminar" class="modal">
        <div class="modal-content">
            <p>¿Estás seguro de eliminar este pedido? Tu acción no podrá ser revertida.</p>
            <input type="hidden" id="idPedidoEliminar" value="">
            <button class="modal-btn-ml" onclick="eliminarPedido()">Sí</button>
            <button class="modal-btn cancelar" onclick="cerrarModal()">No</button>
        </div>
    </div>

    <script> 

// ABRIR MODAL VER DETALLES Y CONFIRMAR PEDIDO
function DetallesConfirmarPedido(idPedido) {
    var modal = document.getElementById("modalConfirmar");
    modal.style.display = "block";

     // Establecer el ID del pedido en el elemento
     var pedidoIdElement = document.getElementById("pedidoId");
    if (pedidoIdElement) {
        pedidoIdElement.textContent = " - Pedido # " + idPedido;
    }

    // Realizar una solicitud AJAX para obtener los detalles del pedido
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "pedidos.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            try {
                var response = JSON.parse(xhr.responseText);
                if (response.ok) {
                    // Construir la tabla de detalles del pedido
                    var detallesTableBody = document.getElementById("detallesPedidoBody");
                    detallesTableBody.innerHTML = ""; // Limpiar contenido anterior

                    response.detalles.forEach(function (detalle, index) {
                        var row = detallesTableBody.insertRow(index);
                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);
                        var cell3 = row.insertCell(2);
                        var cell4 = row.insertCell(3);

                        cell1.innerHTML = detalle.codigo_producto;
                        cell2.innerHTML = detalle.nombre_producto;
                        cell3.innerHTML = detalle.cantidad;
                        cell4.innerHTML = detalle.precio;
                    });
                } else {
                    console.error("Error en la respuesta JSON:", response.error);
                    alert("Error al obtener detalles del pedido");
                }
            } catch (error) {
                console.error("Error al analizar la respuesta JSON:", error);
                alert("Error al analizar la respuesta JSON");
            }
        } else {
            // console.error("Error en la solicitud AJAX, estado: " + xhr.status);
            // alert("Error en la solicitud AJAX, estado: " + xhr.status);
        }
    };

    xhr.send("id_pedido=" + idPedido);
}
function cerrarModal2() {
            var modal = document.getElementById("modalConfirmar");
            modal.style.display = "none";
        }

// BUSCAR
        function buscar() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("buscador");
            filter = input.value.toUpperCase();
            table = document.getElementById("tablaPedidos");
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
        // ELIMINAR PEDIDO
        function confirmarEliminar(idPedido) {
            var modal = document.getElementById("modalEliminar");
            modal.style.display = "block";

            // Guardar el ID del usuario a eliminar en un campo oculto del modal
            document.getElementById("idPedidoEliminar").value = idPedido;
        }

        function eliminarPedido() {
            // Obtener el ID del usuario a eliminar desde el campo oculto
            var idPedido = document.getElementById("idPedidoEliminar").value;

            // Realizar una solicitud AJAX al servidor para eliminar el usuario
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../config/eliminar_pedido.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Actualizar la tabla después de eliminar el usuario
                    var tableRow = document.getElementById("row_" + idPedido);
                    if (tableRow) {
                        tableRow.remove();
                    }

                    // Una vez implementada la eliminación, cierra el modal
                    cerrarModal();
                }
            };

            xhr.send("idPedido=" + idPedido);
        }

        function cerrarModal() {
            var modal = document.getElementById("modalEliminar");
            modal.style.display = "none";
        }
    </script>
</body>
</html>