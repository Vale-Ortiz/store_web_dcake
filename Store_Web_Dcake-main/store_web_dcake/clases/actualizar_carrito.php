<?php

require '../config/config.php';
require '../config/conexion_producto.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if(isset($_POST['action'])){

    $action = $_POST['action'];
    $id = isset($_POST['Codigo']) ? $_POST['Codigo'] : 0;

    if($action == 'agregar') {
        $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : 0;
        $respuesta = agregar($id, $cantidad);
        if($respuesta > 0) {
            $datos['ok'] = true;
        } else {
            $datos['ok'] = false;
        }
        $datos['sub'] = MONEDA . number_format($respuesta, 2, ',', '.');
    } else if($action == 'eliminar'){
          $datos['ok'] =   eliminar($id);
    }    else {
        $datos['ok'] = false;
    }
} else {
    $datos['ok'] = false;
}

echo json_encode($datos);

function agregar($id, $cantidad)
{

    $res = 0;
    if($id > 0  && $cantidad > 0 && is_numeric(($cantidad))){
        if(isset($_SESSION['carrito']['productos'][$id])){
            $_SESSION['carrito']['productos'][$id] = $cantidad;

            $db = new Database();
            $con = $db->conectar();
            $sql = $con-> prepare("SELECT Precio, Descuento FROM store_web_dcake.producto WHERE Codigo =? AND Activo = 1 AND id_categoria = 1 LIMIT 1");
            $sql -> execute([$id]);
            $row = $sql-> fetch(PDO::FETCH_ASSOC);
            $precio = $row['Precio'];
            $descuento = $row['Descuento'];
            $precio_desc= $precio - (($precio * $descuento) / 100);
            $res = $cantidad *  $precio_desc;

            return $res;
        }
    } else {
        return $res;
    }
}

function eliminar($id){
    if($id>0){
        if (isset($_SESSION['carrito']['productos'][$id])){
            unset($_SESSION['carrito']['productos'][$id]);
            return true;
        }
    } else {
        return false;
    }
}
?>