<?php

require '../config/config.php';
require '../config/conexion_producto.php';
$db = new Database();
$con = $db->conectar();

$sql = $con-> prepare("SELECT Codigo,Nombre,Precio FROM store_web_dcake.producto WHERE Activo = 1 and id_categoria = 2");
$sql -> execute();
$resultado = $sql -> fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D'cake pasteleria</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/Style.css"> 
    <link rel="shortcut icon" href="/images2/icologo.ico">
</head>

<body class = "margin">
    <a href="https://api.whatsapp.com/send?phone=573008936926" 
     target="_blank">
    <img class = "whatsappicon"src="../images2/whagifs.gif" alt="whatsapp"></a>
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

             <!-- BUSCADOR -->
             <div class="col-md-4 buscador">
                <form id="search-form">
                    <div class="input-group">
                    <input type="text" name="busqueda" id="busqueda" class="form-control" placeholder="¿Qué deseas degustar hoy?" oninput="realizarBusqueda()">
                        <div class="input-group-append">
                            <input class="btn btn-outline-light" type="submit" name="buscar" value="Buscar">
                        </div>
                    </div>
                </form>
            </div>
            <div id="resultados"></div>

            <br><br><br>
    <!-- CONSULTA PARA LA BUSQUEDA -->
     <?php
    // Verificar si se ha enviado el formulario de búsqueda
     if (isset($_GET['buscar'])) {
    //  Recuperar el término de búsqueda ingresado por el usuario
        $busqueda = $_GET['busqueda'];

         $conexion3 = new mysqli("localhost", "root", "", "store_web_dcake");

        if ($conexion3->connect_error) {
            die("Error en la conexión a la base de datos: " . $conexion3->connect_error);
         }

        $sql = "SELECT * FROM producto WHERE Nombre LIKE '%" . $busqueda . "%'";
        $resultado = $conexion3->query($sql);

        // Verificar si se encontraron resultados
        // if ($resultado->num_rows > 0) {
        //     echo "<h6>Resultados de la búsqueda:</h6>";
        //      while ($row = $resultado->fetch_assoc()) {
        //          echo $row['Nombre'] . "<br>";
        //      }
        //   } else {
        //      echo "<p>No se encontraron resultados para '$busqueda'.</p>";
        //  }

         $conexion3->close();
    }
    ?> 

            <!-- ANCLAS -->
            <div class="col-md-5 text-right"> <!-- Columna para anclas e iconos -->
                <nav class= "anclas">
                    <!-- Anclas -->
                    <a class="text-white mb-2 d-inline mr-3 small" href="#"><i class="fas fa-phone"></i> Teléfono: (604) 34 233 23</a>
                    <a class="text-white mb-2 d-inline mr-3 small" href="/dcakepasteleria/servicioalcliente.php"><i class="fas fa-user"></i> Servicio al cliente</a>
                    <a class="text-white mb-2 d-inline mr-3 small" href="/dcakepasteleria/help.php"><i class="fas fa-question-circle"></i> Ayuda</a>
                </nav>      
                    <!-- Iconos -->
                <nav>
                        <a href="/checkout.php"><img  class = "sizei carrito" src="../images2/carrito.png" ></img></a>
                        </a><span class="text-white ml-2"><a href="/checkout.php">Mi carrito</a><span id="num_cart" class="badge bg-secondary"><?php echo $num_cart; ?></span></a>
                    
                        <a href="#"><img class="sizei estrella"src="../images2/estrella.png" alt=""></a>
                        <span class="text-white ml-2"><a href="../dcakepasteleria/fidelizacion.php">Fidelizate</a></span>
                     
                        <a href="../dcakepasteleria/ingreso_vendedor.php"><img class="sizei vendedor"src="../images2/vendedor.png" alt=""></a>
                        <span class="text-white ml-2"><a href="../dcakepasteleria/ingreso_vendedor.php">Portal vendedor</a></span> 
                </nav>
            </div>
        </div>
    </div>
</header>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <!-- barra de navegación -->
    <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/dcakepasteleria/bizcochos.php">Bizcochos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../dcakepasteleria/bizcochos+con+relleno.php">Bizcochos con relleno</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Bizcochos con relleno y cobertura</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Postres</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Cupcakes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Brownies</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

    <div class = "BIZCOCHOS">
        <h2 >
            BIZCOCHOS CON RELLENO
        </h2>
    </div>

    <section class = " torta_naranja">
    <?php  foreach($resultado as $row){?>
        <div class = "col_producto">
            <?php 
            $id = $row["Codigo"];
            $imagen = "../images/producto/bizcochosr/$id/principalimg.jpg";

            if(!file_exists($imagen)){
                $imagen = "../images/nofoto.jpg";
            }
            ?>
            <img class = "bizcocho img-fluid img-thumbnail"src="<?php echo $imagen; ?>" alt="">
            <h3 class = text_bizcocho_naranja>
                <?php echo $row['Nombre'] ?>
            </h3>
            <p class = "precio">
                <?php echo number_format ($row['Precio'],2, ',','.'); ?>
            </p>

            <button class = "btn btn-primary btn-lg center" type = "button">
                 <a href="detalles.php?Codigo=<?php 
                 echo $row['Codigo']; ?>&token=<?php echo hash_hmac('sha1', $row['Codigo'], KEY_TOKEN); ?>" 
                 class="text_detalle">Detalles</a>
            </button>
            
            <button class ="btn btn-outline-success btn-lg center" type="button" onclick = "addProducto
            (<?php echo $row['Codigo']; ?>, '<?php echo hash_hmac('sha1', $row['Codigo'], 
            KEY_TOKEN); ?>')">Añadir al carrito</button>
        
            <?php } ?>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>
</html>