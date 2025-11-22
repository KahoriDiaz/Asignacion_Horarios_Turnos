<?php
$servidor  = "localhost";
$usuario   = "root";
$clave     = "hola";
$baseDatos = "turnos_db";

$conexion = new mysqli($servidor, $usuario, $clave, $baseDatos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8mb4");
