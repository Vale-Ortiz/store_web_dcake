
<?php
    if(!empty($_POST["envio"]))
    {
        if(empty ($_POST['texto']))
        {
         echo '<p class ="alerta">¡El campo comentario no puede ir vacio!</p>';
        }else 
        {
            $correo= $_POST['correo_electronico'];
            $texto = $_POST['texto'];

            $sql = $conexion2 ->query ("INSERT INTO store_web_dcake.comentario (correo_electronico,texto)values('$correo','$texto') ");
    
            if($sql == 1){
                echo '<p class ="exito">Comentario registrado con éxito</p>';
            } else
            {
                echo '<p class ="alerta">Ocurrio un error al guardar el comentario</p>';
            }
        }
    }
?>