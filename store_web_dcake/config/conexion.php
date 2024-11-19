<?php
$conexion = new mysqli("localhost", "root", "", "store_web_dcake");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}