<?php
require '../config/config.php';
require '../config/conexion_producto.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D'cake pasteleria</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/Style.css"> 
    <link rel="shortcut icon" href="../images2/icologo.ico">
</head>

<body class = "margin general_font1">
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

            <!-- MENSAJE DE BIENVENIDA -->
            <div class="col-md-4 text-center mensaje-bienvenida mensaje">
                <h2 class="text-white">¡Bienvenido a D'cake!</h2>
                <p class="lead text-white">Descubre nuestras deliciosas opciones para satisfacer tus antojos.</p>
                <a href="/dcakepasteleria/bizcochos.php" class="btn btn-primary btn-sm">¡Empezar a explorar!</a>
            </div>

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

    <h2 class = "text_sugerir">
    Que sugerir no sea un miedo, hazlo con confianza.
    </h2>

    <form action="" method ="POST" class ="formulario1">
        <p class = "text_sugerir">HAZ TU COMENTARIO O SUGERENCIA PARA QUE D'CAKE SEA MEJOR</p>
        <?php
            include("../config/conexion_comentario.php");
            include("../config/comentarios_registro.php");
        ?>

        <input class = "control1" type="text" name ="correo_electronico" id ="correo" placeholder = "Tu correo (opcional)" >    
    
        <input class = "control2" type="text" name = "texto" id ="comentario" placeholder = "Haz tu comentario aquí">

        <p class = "text_sugerir"> Envie su comentario cuando este listo </p>

        <input class ="boton" type="submit" name ="envio">

        <p class = text_sugerir> ¿Por qué debo comentar?</p>
    </form>  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>

    