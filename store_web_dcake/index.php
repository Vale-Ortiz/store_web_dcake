<?php
require '../store_web_dcake/config/config.php'; 
require 'config/conexion.php';
require '../store_web_dcake/config/config2.php'; 

$nombre_usuario = isset($_SESSION['usuario']) ? htmlspecialchars($_SESSION['usuario'], ENT_QUOTES, 'UTF-8') : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D'cake pasteleria</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="../store_web_dcake/css/Style.css"> 
    <link rel="shortcut icon" href="../store_web_dcake/images2/icologo.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
</head>
<body class = "margin general_font1">
    <a href="https://api.whatsapp.com/send?phone=573008936926" 
     target="_blank">
    <img class = "whatsappicon"src="../store_web_dcake/images2/whagifs.gif" alt="whatsapp"></a>
    <!-- cabecera de la pagina web, parte superior -->
    <h3 class= "contactar font_contactar">
        WhatsApp
    </h3>

<header class="py-2 text-white">
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <!-- LOGO -->
            <div class="col-md-2">
                <a href="../store_web_dcake/index.php">
                    <img class="logo zoomlogo" src="../store_web_dcake/images2/dcakelogo.png" alt="dcake logo oficial">
                </a>
            </div>

            <!-- MENSAJE DE BIENVENIDA -->
            <div class="col-md-4 text-center mensaje-bienvenida mensaje">
                <h2 class="text-white">¡Bienvenido a D'cake!</h2>
                <p class="lead text-white">Descubre nuestras deliciosas opciones para satisfacer tus antojos.</p>
                <a href="../store_web_dcake/dcakepasteleria/bizcochos.php" class="btn btn-primary btn-sm">¡Empezar a explorar!</a>
            </div>

            <!-- ANCLAS -->
            <div class="col-md-5 text-right"> <!-- Columna para anclas e iconos -->
                <nav class= "anclas">
                    <!-- Anclas -->
                    <a class="text-white mb-2 d-inline mr-3 small" href="#"><i class="fas fa-phone"></i> Teléfono: (604) 34 233 23</a>
                    <a class="text-white mb-2 d-inline mr-3 small" href="../store_web_dcake/dcakepasteleria/servicioalcliente.html"><i class="fas fa-user"></i> Servicio al cliente</a>
                    <a class="text-white mb-2 d-inline mr-3 small" href="../store_web_dcake/dcakepasteleria/help.php"><i class="fas fa-question-circle"></i> Ayuda</a>
                </nav>      
                    <!-- Iconos -->
                <nav>
                        <a href="checkout.php"><img  class = "sizei carrito" src="images2/carrito.png" ></img></a>
                        <span class="text-white ml-2"><a href="checkout.php">Mi carrito</a><span id="num_cart" class="badge bg-secondary"><?php echo $num_cart; ?></span></a>
                    
                        <a href="#"><img class="sizei estrella"src="images2/estrella.png" alt=""></a>
                        <?php if (!empty($nombre_usuario)) : ?>
                            <a href="dcakepasteleria/apartado_cliente.php"><span class="text-white ml-2"><?php echo $nombre_usuario; ?></span></a>
                        <?php else : ?>
                            <span class="text-white ml-2"><a href="dcakepasteleria/fidelizacion.php">Fidelizate</a></span>
                        <?php endif; ?>                  
                        <a href="dcakepasteleria/ingreso_vendedor.php"><img class="sizei vendedor"src="images2/vendedor.png" alt=""></a>
                        <span class="text-white ml-2"><a href="dcakepasteleria/ingreso_vendedor.php">Portal vendedor</a></span> 
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
                    <a class="nav-link" href="../store_web_dcake/dcakepasteleria/bizcochos.php">Bizcochos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../store_web_dcake/dcakepasteleria/bizcochos+con+relleno.php">Bizcochos con relleno</a>
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
            <!-- Sección de ingreso a fidelización o nombre de usuario -->
        <?php if (!empty($_SESSION['nombre_suario'])) : ?>
            <!-- Mostrar el nombre del usuario -->
            <span class="text-success ml-2 font-weight-bold">¡Hola, <?php echo htmlspecialchars($_SESSION['nombre_usuario']);?>!</span>
        <?php else : ?>
            <!-- Mostrar el botón de ingreso a fidelización -->
            <a class="btn btn-primary mt-2" href="../store_web_dcake/dcakepasteleria/seccionfide.php">
                Ingreso a fidelización
            </a>
        <?php endif; ?>
    </div>
</nav>

<section class="text-center py-3 text-white">
    <div class="container">
        <h3 class="tortas1">Las mejores tortas son las de D'cake</h3>
    </div>
</section>
        <!-- sección 1 -->
    <section class = "general_section flex text_center">
        <hr>
        <div class = "columna">
             <img class = "sizephoto zoom2"src="../store_web_dcake/images2/torta.jpg" alt="torta de chocolate">
             <h6>Torta de chocolate</h6>
             <p> Que sugerir no sea un miedo, hazlo con confianza. ¡Deja tu comentario!</p>
                <img class= "sizephoto"src="../store_web_dcake/images2/suggestion.png" alt="mapa de dcake">
                <a class = "text"href="../store_web_dcake/dcakepasteleria/comentarios.php">Quiero hacer una sugerencia</a>
        </div>

        <div class = "columna">
             <img class = "sizephoto zoom2"src="../store_web_dcake/images2/torta2.jpg" alt="torta de chocolate">
             <h6>Torta de café</h6>
             <p>Llega a D'cake sin problemas, ponte en plan y ven a visitarnos</p>
             <img class= "sizephoto"src="../store_web_dcake/images2/maps.PNG" alt="mapa de dcake">
             <a class = "text "href="https://www.google.com/maps/place/Pasteleria+D'CAKE/@7.8816119,-76.6435956,14z/data=!4m10!1m2!2m1!1scake+pasteleria+apartado!3m6!1s0x8e500dd43ec95391:0xb9ce98772a19f379!8m2!3d7.8769634!4d-76.6091902!15sChhjYWtlIHBhc3RlbGVyaWEgYXBhcnRhZG9aGiIYY2FrZSBwYXN0ZWxlcmlhIGFwYXJ0YWRvkgEJY2FrZV9zaG9w4AEA!16s%2Fg%2F11tcd61t65?entry=ttu" target="_blank">¿Como llegar a D'cake?</a>
        </div>

        <div class = "columna">
             <img class = "sizephoto zoom2"src="../store_web_dcake/images2/torta3.jpg" alt="torta de cafe">
             <h6>Torta de chocolate explosión</h6>
             <p>Sabemos que hay muchas fechas importantes, quieres algo diferente, ¿cierto?</p>
             <img class= "sizephoto"src="../store_web_dcake/images2/especial.jpg" alt="mapa de dcake">
             <a class = "text "href="#">Personalizar mi pedido</a>
        </div>

        <div  class = "columna">
             <img class = "sizephoto zoom2"src="../store_web_dcake/images2/torta4.jpg" alt="torta de chocolate">  
             <h6>Torta de chocolate fusión</h6>
             <p> Obten bonos, gana premios y muchas cosas más. Para D'cake son muy importante sus clientes.</p>
             <img class= "sizephoto"src="../store_web_dcake/images2/fidelizacion.png" alt="mapa de dcake">
               <a class = "text "href="#">Fidelización</a>
        </div>
        <hr>
    </section>  

    <hr>

    <!-- seccion 2 -->
    <section class = "general_section flex text_center justifyicon">
        <!-- Columna1 -->
        <div>
            <a href="../dcakepasteleria/sobrenosotros.php">
                <img class= "sizeicon zoom"src="images2/tienda.gif" alt="shopdcake">
            </a>
            <h4> <a href="../dcakepasteleria/sobrenosotros.php">Sobre nosotros</h4> </a>
                <p class = "colort text-info ">Somos un negocio de pasteleria y reposteria casera</p>
        </div>
        <!-- Columna2 -->
        <div>
            <a href="https://mail.google.com/mail/u/2/#inbox" target="_blank">
               <img class= "sizeicon zoom"src="images2/email2.gif" alt="emaildcake">
            </a>
            <h4><a href="https://mail.google.com/mail/u/2/#inbox" target="_blank">Correo</h4>
                <p class = "text-info">
                    <a class ="detailemail" href="https://mail.google.com/mail/u/2/#inbox" target="_blank">pasteleriadcake.ap@gmail.com</a>
                </p>
        </div>
        <!-- Columna3 -->
        <div>
            <a href="tel:+573008936926" target="_blank">
                <img class= "sizeicon zoom"src="images2/telefono.gif" alt="phonedcake">
            </a>
            <h4><a href="tel:+573008936926" target="_blank">Télefono</a></h4>
                 <p class = "text-info"><a href="tel:+573008936926" target="_blank"></a>+573008936926</p>
        </div>
    </section>

    <hr>

    <!-- sección 3 -->
    <section class= "styles_section3 general_section flex text_center justifytextsection3">
        <!-- Columna1 -->
        <div>
            <h5>
                Otros servicios
            </h5>
                <h5>
                    <p class = "text-info">
                        Tortas para bodas
                    </p>
                    <p class = "text-info">
                        Tortas para Baby Shower
                    </p>
                    <p class = "text-info">
                        Contrataciones para eventos
                    </p>
                    <p class = "text-info">
                        Convenios con empresas
                    </p>
                    <p class = "text-info">
                        Cursos formales
                    </p>
                </h5>

        </div>
        <!-- Columna2 -->
        <div>
            <h5>
                Nuestras lineas
            </h5>
                <h5>
                    <p class = "text-info">
                        Bizcochos
                    </p>
                    <p class = "text-info">
                        Bizcochos con relleno
                    </p>
                    <p class = "text-info">
                        bizcocho con relleno y cobertura
                    </p>
                    <p class = "text-info">
                        Postres 
                    </p>
                    <p class = "text-info">
                        Cupcakes
                    </p>
                    <p class = "text-info">
                        Brownies
                    </p>
              </h5>

        </div>
        <!-- Columna3 -->
        <div>
            <h5>
                D'cake es una familia
            </h5>
                <h5>
                    <p class = "text-info">
                       Campañas
                    </p>
                    <p class = "text-info">
                        Fundación
                    </p>
                    <p class = "text-info">
                        Unidos por Cake?
                    </p>
                    <p class = "text-info">
                        Comunidad
                    </p>
                    <p class = "text-info">
                        Seguidores
                    </p>
                </h5>
        </div>
        <!-- Columan4 -->
        <div>
            <h5>
                Nuestras sedes
            </h5>
                <h5>
                    <p class = "text-info">
                        Apartadó
                    </p>
                    <p class = "text-info">
                        Chigorodó
                    </p>
                    <p class = "text-info">
                        Carepa
                    </p>
                    <p class = "text-info">
                        Turbo
                    </p>
                    <p class = "text-info">
                        Necoclí
                    </p>
                </h5>
        </div>
    </section>

    <hr>

    <!-- SECCIÓN 4 -->
    <section class =  "general_section flex justify_icon_s4">
       
         <a  href="https://www.facebook.com/profile.php?id=61551866494804" target="blank"><img class= "sizeicon-section4"src="images2/facebook.png" alt="facebookdirection"></a>

         <a  href="https://www.instagram.com/dcake99/" target="blank"><img class= "sizeicon-section4"src="images2/instagram.png" alt="instagramdirection"></a>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>
</html>