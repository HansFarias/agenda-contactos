<?php
// config/conexion.php
// Conexión a MySQL con mysqli. Ajusta estos valores a tu entorno.
// XAMPP (Windows): usuario root, clave vacía, puerto 3306.

$servidor   = 'localhost';
$usuario_bd = 'root';
$clave_bd   = '';
$base_datos = 'agenda_db';
$puerto     = 3306;

mysqli_report(MYSQLI_REPORT_OFF);
$conexion = @new mysqli($servidor, $usuario_bd, $clave_bd, $base_datos, $puerto);

if ($conexion->connect_errno) {
    // El detalle técnico va al log del servidor, no a la pantalla del usuario.
    error_log('Error de conexión MySQL: ' . $conexion->connect_error);
    http_response_code(500);
    exit('No se pudo conectar a la base de datos.');
}

$conexion->set_charset('utf8mb4');