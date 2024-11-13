
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 
</head>

<body>

<body>
    <!-- Modal de bienvenida -->
    <div class="modal" id="modalBienvenida" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bienvenido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p>Aquí podrás tener control sobre los productos y comentarios que hacen los clientes en la página.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">D'cake</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="vista_comentarios.php">Comentarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="productos.php">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="usuarios.php">Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pedidos.php">Pedidos</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            Configuración
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Perfil</a></li>
                            <li><a class="dropdown-item" href="#">Configuración</a></li>
                            <li><a class="dropdown-item" href="../config/close_section.php">Salir</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

   <!-- Sección de Descripción con Estilos Personalizados -->
    <section class="container mt-4 text-center">
    <h2 class="display-4 text-primary">BIENVENIDO AL CONTROL DE D'CAKE</h2>
    <p class="lead text-primary">¡Bienvenido señor Daniel, es un gusto tenerlo de vuelta, asegurate de que tu pagina se vea bonita.</p>
    </section>

    <!-- Listado de Opciones -->
    <section class="container mt-4">
    <h2 class=" text-primary">Opciones en la Página</h2>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">Ver comentarios</li>
        <li class="list-group-item">Explorar productos</li>
        <li class="list-group-item">Ver usuarios</li>
        <li class="list-group-item">Ver pedidos</li>
        <li class="list-group-item">Configurar tu perfil</li>
    </ul>
    </section>
    <!-- Inicializa el modal de bienvenida -->
    <script>
        var myModal = new bootstrap.Modal(document.getElementById('modalBienvenida'));
        myModal.show();
    </script>
</body>
</html>