<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
       /* Estilos para centrar el contenido vertical y horizontalmente */
       body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        /* Estilos para el formulario */
        .form-container {
            max-width: 400px;
            padding: 20px;
            border: 2px solid #ccc;
            border-radius: 5px;
        }
        /* Estilos para la tabla */
        .table-container {
            max-width:800px;
            margin-top: 20px;
        }
    </style>

</head>

<body>
    <?php include ('../config/insertar_producto.php');?>
<div class="form-container form-responsive">
        <h1 class="mb-4">Formulario de Ingreso de Producto</h1>
        <form action="../config/insertar_producto.php" method="POST">
            <div class="mb-3">
                <label for="codigo" class="form-label">Código:</label>
                <input type="text" class="form-control" id="codigo" name="codigo" required>
            </div>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción:</label>
                <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
            </div>
            <div class="mb-3">
                <label for="precio" class="form-label">Precio:</label>
                <input type="number" class="form-control" id="precio" name="precio" required>
            </div>
            <div class="mb-3">
                <label for="categoria" class="form-label">Categoría:</label>
                <input type="text" class="form-control" id="categoria" name="categoria" required>
            </div>
            <div class="mb-3">
                <label for="activo" class="form-label">Activo:</label>
                <select class="form-control" id="activo" name="activo" required>
                    <option value="1">1</option>
                    <option value="0">0</option>
                </select>
            </div>
             <!-- Botones alineados en una fila -->
             <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Agregar </button>
                <button type="submit" class="btn btn-danger">Eliminar </button>
                <button type="submit" class="btn btn-info">Consultar </button>
                <button type="submit" class="btn btn-warning">Modificar </button>
            </div>
        </form>
    </div>

    <div class="container">
         <div class="row">
            <div class="col-md-12">
                 <div class="table-responsive">
                    <h1 class="mb-14">Lista de Productos</h1>
                    <table class="table table-success table-striped">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Categoría</th>
                                <th>Activo</th>
                            </tr>
                        </thead>
                        <tbody>

              
                
                        </tbody>
                     </table>
                </div>
            </div>
        </div>
    </div>
  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script> 
    
    <script>
$(document).ready(function () {
    // Realiza una solicitud AJAX para obtener los productos desde el servidor
    $.ajax({
        url: "../config/recuperarprod.php", // Reemplaza con la ruta correcta a tu archivo PHP
        method: "GET",
        dataType: "json",
        success: function (data) {
            if (data) {
                var tbody = document.querySelector("table.table.table-success.table-striped tbody");

                data.forEach(function (producto) {
                    var row = document.createElement("tr");
                    row.innerHTML = `
                        <td>${producto.Código}</td>
                        <td>${producto.Nombre}</td>
                        <td>${producto.Descripción}</td>
                        <td>${producto.Precio}</td>
                        <td>${producto.id_categoria}</td>
                        <td>${producto.Activo}</td>
                    `;
                    tbody.appendChild(row);
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
    console.error("Error al cargar los productos:");
    console.log("jqXHR:", jqXHR);
    console.log("Status:", textStatus);
    console.log("Error:", errorThrown);
}
    });
});
</script>
</body>
</html>