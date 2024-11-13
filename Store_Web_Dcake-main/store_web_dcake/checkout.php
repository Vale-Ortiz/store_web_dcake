<?php
require '../store_web_dcake/config/config.php';
require '../store_web_dcake/config/conexion_producto.php';
require '../store_web_dcake/config/config2.php';
$db = new Database();
$con = $db->conectar();
// Verificar y iniciar la sesión
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Definicion de algunas variables
$lista_carrito = array();
$total = 0.00;
$subtotal = 0.00;
// Inicializa $id_pedido fuera del bucle
$id_pedido = null;

// Obtener el nombre de usuario, el carrito e id_usuario de la sesión
$nombre_usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
$id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;
$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

// Crear un identificador único para el carrito del usuario
$carritoUsuario = 'carrito_' . $nombre_usuario;
// Verificar si el carrito ya existe para este usuario
if (!isset($_SESSION[$carritoUsuario])) {
    $_SESSION[$carritoUsuario] = array();
}

// Guardar el carrito en la base de datos cuando el usuario agrega productos
if (!empty($_SESSION['carrito']['productos']) && !empty($id_usuario)) {
    foreach ($_SESSION['carrito']['productos'] as $clave => $cantidad) {
        $sql_guardar_carrito = $con->prepare("INSERT INTO store_web_dcake.carrito (id_usuario, codigo_producto, cantidad) VALUES (?, ?, ?)");
        $sql_guardar_carrito->execute([$id_usuario, $clave, $cantidad]);
    }
}

// Recuperar el carrito de la base de datos cuando el usuario inicia sesión
if (!empty($nombre_usuario)) {
    $sql_recuperar_carrito = $con->prepare("SELECT codigo_producto, cantidad FROM store_web_dcake.carrito WHERE id_usuario = ?");
    $sql_recuperar_carrito->execute([$id_usuario]);
    // Inicializar el carrito en la sesión
    $_SESSION['carrito']['productos'] = array();
    // Agregar productos al carrito en la sesión
    while ($fila = $sql_recuperar_carrito->fetch(PDO::FETCH_ASSOC)) {
        $_SESSION['carrito']['productos'][$fila['codigo_producto']] = $fila['cantidad'];
    }
}
// Verifica si hay productos en el carrito o si hay un usuario autenticado
if ($productos != null || !empty($nombre_usuario)) {
    // Si hay productos en el carrito, procede con la lógica del carrito
    if ($productos != null) {
        foreach ($productos as $clave => $cantidad) {
            $sql = $con->prepare("SELECT Codigo, Nombre, Precio, Descuento FROM store_web_dcake.producto WHERE Codigo = ? AND Activo = 1 AND id_categoria = 1");
            $sql->execute([$clave]);
            $producto = $sql->fetch(PDO::FETCH_ASSOC);
            // Verifica si se obtuvo un producto antes de usarlo
            $subtotal = 0;

            if (is_array($producto) && array_key_exists('Precio', $producto)) {
                $subtotal = $cantidad * $producto['Precio'];

                // Verifica si la columna Descuento existe en el resultado de la consulta
                $descuento = isset($producto['Descuento']) ? $producto['Descuento'] : 0;

                // Calcula el precio con descuento (si lo tiene) y el precio real
                $precio_desc = isset($producto['Precio']) && isset($producto['Descuento']) ? $producto['Precio'] - (($producto['Precio'] * $descuento) / 100) : 0;
                $precio_real = isset($producto['Precio']) ? $producto['Precio'] : 0;

                $total += $subtotal;

                    $lista_carrito[] = array(
                        'Codigo' => $producto['Codigo'],
                        'Nombre' => $producto['Nombre'],
                        'Precio' => $producto['Precio'],
                        'Descuento' => $producto['Descuento'],
                        'cantidad' => $cantidad
                );
            }

        }
    }

    // Verificar si la recarga ya se ha realizado
    if (!isset($_SESSION['recarga_realizada'])) {
        // Recargar la página automáticamente después de un breve tiempo
        echo '<script>
                setTimeout(function() {
                    location.reload(true);
                }, 500); // Ajusta el tiempo según tus necesidades
              </script>';
        // Establecer la variable de sesión para indicar que la recarga ya se ha realizado
        $_SESSION['recarga_realizada'] = true;
    }
    
    date_default_timezone_set('America/Bogota');
    $fecha_actual = date('Y-m-d h:i:s A');
    // Verifica si hay un usuario autenticado
    if (!empty($nombre_usuario)) {
       // var_dump($_SESSION['id_usuario']); // Añade esta línea para verificar el valor
        // Inserta en la tabla "pedido" con la nueva columna "nombre_usuario"
        $sql_pedido = $con->prepare("INSERT INTO store_web_dcake.pedido (fecha, id_usuario, nombre_usuario, total) VALUES (?,?, ?, ?)");
        $sql_pedido->execute([$fecha_actual, $id_usuario, $nombre_usuario, $total]);
        // Obtén el ID generado por la inserción
        $id_pedido = $con->lastInsertId();
        // Inserta en la tabla "detalles_pedido" después de procesar todos los productos
        foreach ($productos as $clave => $cantidad) {
            $sql = $con->prepare("SELECT Codigo, Nombre, Precio, Descuento FROM store_web_dcake.producto WHERE Codigo = ? AND Activo = 1 AND id_categoria = 1");
            $sql->execute([$clave]);
            $producto = $sql->fetch(PDO::FETCH_ASSOC);

            // Verifica si se obtuvo un producto antes de usarlo
            if ($producto) {
                $sql_detalle = $con->prepare("INSERT INTO store_web_dcake.detalles_pedido (id_pedido, codigo_producto, nombre_producto, cantidad, precio) VALUES (?, ?, ?, ?, ?)");

                if ($id_pedido !== null) {
                    $sql_detalle->execute([$id_pedido, $clave, $producto['Nombre'], $cantidad, $producto['Precio']]);
                } else {
                    echo "Error: ID de pedido no válido.";
                }
            }
        }
    } else {
       // Mostrar modal en lugar de imprimir directamente en la página
    echo '<div id="fidelizacionModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Fidelízate para obtener:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Promociones y cupones de descuentos</p>
            </div>
            <div class="modal-footer">
                <a href="fidelizacion.php" class="btn btn-primary">Fidelizate</a>
            </div>
        </div>
    </div>
</div>';
// Lanzar el modal usando JavaScript
echo '<script>
    $(document).ready(function() {
        $("#fidelizacionModal").modal("show");
    });
  </script>';
}
} else {
    // Mostrar modal en lugar de imprimir directamente en la página
    echo '<div id="carritoVacioModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Carrito Vacío</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>El carrito está vacío. Puedes navegar y agregar productos.</p>
                </div>
                <div class="modal-footer">
                    <a href="../dcakepasteleria/bizcochos.php" class="btn btn-primary">Ver Bizcochos</a>
                </div>
            </div>
        </div>
    </div>';
    // Lanzar el modal usando JavaScript
    echo '<script>
        $(document).ready(function() {
            $("#carritoVacioModal").modal("show");
        });
    </script>';
}
// Cerrar la conexión
$con = null;
?>


<!DOCTYPE html>  
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D'cake pasteleria</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <link rel="stylesheet" href="/css/Style.css"> 
    <link rel="shortcut icon" href="/images2/icologo.ico">
</head>
<script>
$(document).ready(function() {
    $("#fidelizacionModal, #carritoVacioModal").modal("show");
});
</script>
<body class = "margin">
    <a href="https://api.whatsapp.com/send?phone=573008936926" 
     target="_blank">
    <img class = "whatsappicon"src="/images2/whagifs.gif" alt="whatsapp"></a>
    <!-- cabecera de la pagina web, parte superior -->
    <h3 class= "contactar font_contactar">
        WhatsApp
    </h3>
    <header class="py-2 text-white">
        <div class="container">
            <div class="row justify-content-between align-items-center">
             <!-- LOGO -->
                <div class="col-md-2">
                    <a href="../index.php">
                        <img class="logo zoomlogo" src="/images2/dcakelogo.png" alt="dcake logo oficial">
                    </a>
                </div>
    <!-- ANCLAS -->
    <div class="col-md-5 text-right"> <!-- Columna para anclas e iconos -->
    <nav class= "anclas">
        <!-- Anclas -->
            <a class="text-white mb-2 d-inline mr-3 small" href="#"><i class="fas fa-phone"></i> Teléfono: (604) 34 233 23</a>
            <a class="text-white mb-2 d-inline mr-3 small" href="#"><i class="fas fa-user"></i> Servicio al cliente</a>
            <a class="text-white mb-2 d-inline mr-3 small" href="#"><i class="fas fa-question-circle"></i> Ayuda</a>
                </nav>      
                    <!-- Iconos -->
                <nav>
                        <a href="/checkout.php"><img  class = "sizei carrito" src="../images2/carrito.png" ></img></a>
                        </a><span class="text-white ml-2"><a href="/checkout.php">Mi carrito</a><span id="num_cart" class="badge bg-secondary"><?php echo $num_cart; ?></span></a>
                    
                        <a href="#"><img class="sizei estrella"src="../images2/estrella.png" alt=""></a>
                        <?php if (!empty($nombre_usuario)) : ?>
                            <a href="../dcakepasteleria/apartado_cliente.php"><span class="text-white ml-2"><?php echo $nombre_usuario; ?></span></a>
                        <?php else : ?>
                            <span class="text-white ml-2"><a href="../dcakepasteleria/fidelizacion.php">Fidelizate</a></span>
                        <?php endif; ?>   
                        <a href="../dcakepasteleria/ingreso_vendedor.php"><img class="sizei vendedor"src="../images2/vendedor.png" alt=""></a>
                        <span class="text-white ml-2"><a href="../dcakepasteleria/ingreso_vendedor.php">Portal vendedor</a></span> 
                </nav>
            </div>
        </div>
    </div>
    </header>
    <main >
        <div class = "container"> 
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Producto</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Subtotal</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($lista_carrito == null){
                        echo '<tr><td colspan= "5" class = "text-center"><b>Lista vacia</b></td></tr>';
                    }else{
                        $total = 0;
                        foreach($lista_carrito as $producto){
                            $_id = $producto['Codigo'];
                            $nombre = $producto['Nombre'];
                            $precio = $producto['Precio'];
                            $descuento = $producto['Descuento'];
                            $cantidad = $producto['cantidad'];
                            $precio_desc = $precio -(($precio * $descuento) / 100);
                            $subtotal = $cantidad * $precio_desc;
                            $total += $subtotal;
                            ?>
                
                    <tr>
                        <td class="nombre_producto"> <?php echo $nombre?></td>
                        <td class = "precio-producto"> <?php echo MONEDA . number_format($precio_desc, 2, ',' , '.'); ?> </td>
                        <td>
                            <input class = "cantidad-producto"type ="number" min="1" max ="10" step="1" value= "<?php echo 
                            $cantidad ?>" size ="5" id="cantidad_<?php echo $_id; ?>" 
                            onchange="actualizaCantidad(this.value, <?php echo $_id; ?>)">
                        </td>
                        <td class ="subtotal" > 
                            <div id="subtotal_<?php echo $_id ?>" name = "subtotal[]"><?php echo 
                            MONEDA . number_format($subtotal, 2, ',' , '.'); ?></div>
                        </td>    
                        <td><a href="#" id= "eliminar" class= "btn btn-warning btn-sm" data-bs-id ="<?php 
                        echo $_id ?>" data-bs-toggle="modal" data-bs-target="#eliminaModal">Eliminar</a></td>                    </tr>
                    <?php } ?>
                    <tr>
                        <td colspan = "3"></td>
                        <td colspan = "2">
                        <p class = "h3" id = "total"><?php echo MONEDA . number_format($total, 2 , ',', '.' ); ?></p>
                        </td>
                    </tr>      
                </tbody>
            <?php } ?>                
            </table>    
        </div>
        <div class = "row">
            <div class = "col-md-4 offset-md-7 d-grid gap-2"> 
            <a href="#" id= "pedido" class= "btn btn-success btn-block btn-sm" data-bs-id ="<?php 
                        echo $_id ?>" data-bs-toggle="modal" data-bs-target="#pedidoModal">Pagar por WhatsApp</a></td>
            </div>
        </div>
    </main>
     <!-- Modal1  -->
        <div class="modal fade" id="eliminaModal" tabindex="-1" aria-labelledby="eliminaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="eliminaModalLabel">Borrar producto</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estas seguro de eliminar este producto?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Volver</button>
                <button id = "btn-elimina"type="button" class="btn btn-danger" onclick="eliminar()">Eliminar</button>            </div>
            </div>
        </div>
        </div>
     <!-- CONTIENE COMPRA A REALIZAR    -->
    <!-- Modal2 -->
    <div class="modal fade" id="pedidoModal" tabindex="-1" aria-labelledby="pedidoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="pedidoModalLabel">Datos del pedido</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="procesar_pedido.php" method="post">
                    <!-- Campos de nombre, dirección, teléfono, correo, etc. -->
                    <label class= "input-group flex-nowrap " for="nombre">Nombre:</label>
                    <input class="form-control" type="text" name="nombre" required><br><br>
                    <label class= "input-group flex-nowrap" for="direccion">Dirección:</label>
                    <input class="form-control" type="text" name="direccion" required><br><br>
                    <label class= "input-group flex-nowrap" for="telefono">Télefono:</label>
                    <input class="form-control" type="text" name="telefono" required><br><br>
                    <label class= "input-group flex-nowrap" for="correo">Correo:</label>
                    <input class="form-control" type="text" name="correo" required><br><br>
                    <!-- Agrega una lista para mostrar los productos seleccionados -->
                    <h2>Productos Seleccionados:</h2>
                    <ul id="lista-productos"></ul>
                    <main >
        <div class = "container"> 
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Producto</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Subtotal</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($lista_carrito == null){
                        echo '<tr ><td colspan= "5" class = "text-center"><b>Lista vacia</b></td></tr>';
                    }else{
                          $total = 0;
                        foreach($lista_carrito as $producto){
                            $_id = $producto['Codigo'];
                            $nombre = $producto['Nombre'];
                            $precio = $producto['Precio'];
                            $descuento = $producto['Descuento'];
                            $cantidad = $producto['cantidad'];
                            $precio_desc = $precio -(($precio * $descuento) / 100);
                            $subtotal = $cantidad * $precio_desc;
                            $total += $subtotal;
                            ?>
                
                    <tr class="producto-seleccionado">
                        <td class="product"> <?php echo $nombre?></td>
                        <td class="price"> <?php echo MONEDA . number_format($precio_desc,2, ',' , '.'); ?></td>
                        <td class="cant"> <?php echo $cantidad ?></td>
                        <td class ="subtotal"> 
                            <div id="subtotal_<?php echo $_id ?>" name = "subtotal[]"><?php echo 
                            MONEDA . number_format($subtotal, 2, ',' , '.'); ?></div>
                        </td>    
                        
                    <?php }?>
                    <tr>
                        <td colspan = "3"></td>
                        <td colspan = "2">
                        <p class = "h3" id = "total"><?php echo MONEDA . number_format($total, 2 , ',', '.' ); ?></p>
                        </td>
                    </tr>      
                </tbody>
            <?php } ?>                
            </table>    
        </div>
    </main>                   
</form>
</div>
            <div class="modal-footer">
                <!-- Botón "Ir a WhatsApp" -->
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Volver</button>
                <button id="btn-ir-whatsapp" type="button" class="btn btn-success">Ir a WhatsApp</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
<script>

        let eliminaModal = document.getElementById('eliminaModal')
        eliminaModal.addEventListener('show.bs.modal', function(event){

            let button = event.relatedTarget
            let id = button.getAttribute('data-bs-id')
            let buttonElimina = eliminaModal.querySelector('.modal-footer #btn-elimina')
            buttonElimina.value = id
        })          

        function actualizaCantidad(cantidad, id)
        {
            let url = 'clases/actualizar_carrito.php'
            let formData = new FormData()
            formData.append('action', 'agregar')
            formData.append('Codigo', id)
            formData.append('cantidad', cantidad)

            fetch(url,{
                method: 'POST',
                body: formData,
                mode: 'cors'
            }).then(response => response.json())
            .then(data => {
                if(data.ok){
                    let divsubtotal = document.getElementById('subtotal_' + id)
                    divsubtotal.innerHTML = data.sub

                    let total = 0.00
                    let list = document.getElementsByName('subtotal[]')

                    for(let i = 0; i < list.length; i++){
                        total += parseFloat(list[i].innerHTML.replace(/[$,]/g, ''))
                    }

                    total = new Intl.NumberFormat('en-IN',{
                        minimumFractionDigits: 3
                    }).format(total)
                    document.getElementById('total').innerHTML = '<?php echo MONEDA;?>'+ total
                } 
            })
        }

        function eliminar()
        {
            let botonElimina = document.getElementById('btn-elimina')
            let id = botonElimina.value

            let url = 'clases/actualizar_carrito.php'
            let formData = new FormData()
            formData.append('action', 'eliminar')
            formData.append('Codigo', id)

            fetch(url,{
                method: 'POST',
                body: formData,
                mode: 'cors'
            }).then(response => response.json())
            .then(data => {
                if(data.ok){
                    location.reload()
                } 
            })
        }
    </script>
    
</body>
</html>