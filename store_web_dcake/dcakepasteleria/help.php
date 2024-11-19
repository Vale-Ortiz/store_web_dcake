<!DOCTYPE html>  
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayuda de D'cake</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <body>
    <section class="help-section bg-light py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2 class="mb-4">¿Necesitas Ayuda o Quieres Reportar un Problema?</h2>
                    <p>Estamos aquí para ayudarte. Si tienes alguna pregunta, necesitas asistencia o deseas reportar un problema en nuestra página, por favor, utiliza el siguiente formulario para ponerte en contacto con nosotros.</p>

                    <!-- Formulario de Reporte de Problemas -->
                    <form action="procesar_reporte.php" method="post">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre:</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo Electrónico:</label>
                            <input type="email" id="correo" name="correo" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="mensaje" class="form-label">Mensaje:</label>
                            <textarea id="mensaje" name="mensaje" class="form-control" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar Reporte</button>
                    </form>
                </div>
                <div class="col-lg-6">
                    <img src="../images2/ayuda.png" alt="Ayuda" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- Posibles Ayudas a Problemas Comunes -->
<section class="common-issues-section py-5">
    <div class="container">
        <h2 class="mb-4">Posibles Ayudas a Problemas Comunes</h2>
        <ul>
            <li><strong>No puedes añadir productos al carrito:</strong> Asegúrate de que estes conectado a internet.</li>
            <li><strong>No recibo sugerencias de tortas:</strong> Quiero saber por qué.</li>
            <li><strong>No puedo realizar un pedido:</strong> Asegúrate de que todos los campos requeridos estén completos y de que los detalles de pago sean correctos. Si sigues teniendo problemas, contáctanos utilizando el formulario de reporte de problemas.</li>
            <li><strong>Problemas de visualización en la página:</strong> Intenta borrar la caché de tu navegador o utilizar otro navegador. Si el problema persiste, avísanos para que podamos solucionarlo.</li>
            <li><strong>Otro problema no listado:</strong> Si experimentas un problema que no se menciona aquí, por favor, utiliza el formulario de reporte de problemas para informarnos. Estamos aquí para ayudarte.</li>
        </ul>
    </div>
</section>
</body>
</html>