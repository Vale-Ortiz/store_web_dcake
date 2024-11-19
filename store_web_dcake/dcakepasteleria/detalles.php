
<?php

require '../config/config.php';
require '../config/conexion_producto.php';
$db = new Database();
$con = $db->conectar();

$id = isset($_GET['Codigo']) ? $_GET['Codigo'] :  '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

if($id == '' || $token == '') {
    echo 'Error en la petición'; 
     exit;
} else{

    $token_tmp = hash_hmac("sha1", $id, KEY_TOKEN);

    if($token == $token_tmp){

        $sql = $con-> prepare("SELECT count(Codigo) FROM store_web_dcake.producto WHERE Codigo =? AND Activo = 1 AND id_categoria = 1");
        $sql -> execute([$id]);
        if($sql -> fetchColumn() >0){

            $sql = $con-> prepare("SELECT Nombre, Descripción, Precio, Descuento FROM store_web_dcake.producto WHERE Codigo =? AND Activo = 1 AND id_categoria = 1 
            LIMIT 1");
            $sql -> execute([$id]);
            $row = $sql-> fetch(PDO::FETCH_ASSOC);
            $nombre = $row['Nombre'];
            $descripcion = $row['Descripción'];
            $precio = $row['Precio'];
            $descuento = $row['Descuento'];
            // Sacar descuento
            $precio_desc = $precio - (($precio * $descuento) / 100);

            //Imagenes dinamicas
            $dir_images = '../images/producto/bizcochos/'. $id .'/';

            $rutaImg = $dir_images . '/principalimg.jpg';

            if(!file_exists($rutaImg)){
                $rutaImg = '/images/nofoto.jpg';
            }

            $imagenes = array();
            if(file_exists($dir_images))
            {
                $dir = dir($dir_images);

             while(($archivo = $dir ->read()) != false) {
                    if($archivo != 'principalimg.jpg' && (strpos($archivo, 'jpg') || strpos($archivo, 'jpeg') )){
                        $imagenes[] = $dir_images . $archivo; 
                    }
             }
             $dir-> close();
            }
        }
    } else{
        echo 'Error en el procesamiento';
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D'cake pasteleria</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="/css/Style.css">
    <link rel="shortcut icon" href="/images2/icologo.ico">
</head>

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
                        <span class="text-white ml-2"><a href="#">Fidelizate</a></span>
                     
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
                    <a class="nav-link" href="bizcochos+con+relleno.php">Bizcochos con relleno</a>
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
    <main class = "container-sm">
        <div class = "d-flex  d-grid gap-3">
            <div>
                <div>
                    <h1><?php echo $nombre;?></h1>
                </div>
                <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img class = "bizcocho"src="<?php echo $rutaImg;?>" class="d-block w-100">
                        </div>

                        <?php  foreach( $imagenes as $img){?>
                    <div class="carousel-item">
                        <img class = "bizcocho"src="<?php echo $img;?>" class="d-block w-100">
                    </div>
                    <?php }?>
                    </div>
                        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>

                        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                         </a>
                    </div>
                </div>
            </div>

            <?php if($descuento > 0 ) { ?>
            <div>
                 <p><del><?php echo MONEDA . number_format($precio, 2, ',','.'); ?></del></p>
            </div>   

            <div> 
                <h2>
                <?php echo MONEDA . number_format($precio_desc, 2, ',','.'); ?>
                <small class = "text-success"><?php echo $descuento; ?>% de descuento</small>
                </h2>
            </div>   

            <?php } else {?> 
            <div class = "d-grid gap-3 col-9 mx.10">       
                <h2><?php echo MONEDA . number_format($precio, 2, ',','.'); ?></h2>
            </div> 

            <?php } ?>

            <div class = "d-grid gap-3 col-9 descrip">
                <h4>Descripción</h4>
                <p class ="tt-desc-posi"> <?php echo $descripcion;?> </p>
            </div>
        
            <div class = "d-grid gap-3 col-5 mx.auto">
                <button class ="btn btn-primary btn-lg" type="button">Comprar ahora</button>
                <button class ="btn btn-primary btn-lg" type="button" onclick = "addProducto(<?php echo 
                $id; ?>, '<?php echo $token_tmp;?>')">Añadir al carrito</button>

                 </div>  
            </div>
        </div>
    </main>


    <script>
        function addProducto(id, token){
            let url = '../clases/carrito.php'
            let formData = new FormData()
            formData.append('Codigo', id)
            formData.append('token', token)

            fetch(url,{
                method: 'POST',
                body: formData,
                mode: 'cors'
             }).then(response => response.json())
             .then(data => {
                if(data.ok){
                    let elemento = document.getElementById("num_cart")
                    elemento.innerHTML = data.numero
                }else {
      console.error("Error en la respuesta:", data.error);
    }
  })
  .catch(error => {
    console.error("Error en la solicitud:", error);
  });
            }
    </script>
    
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>