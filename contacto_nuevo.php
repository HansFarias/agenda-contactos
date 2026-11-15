<?php
// contacto_nuevo.php: registrar un contacto del usuario autenticado.
require_once 'includes/seguridad.php';
require_once 'config/conexion.php';
require_once 'includes/validaciones.php';

requerir_login();
$id_usuario = $_SESSION['usuario_id'];

$errores = [];
$datos = ['nombre' => '', 'telefono' => '', 'correo' => '', 'ciudad' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validar_csrf();
    [$errores, $datos] = validar_contacto($_POST);

    if (!$errores) {
        // id_usuario sale de la SESIÓN, nunca del formulario
        $stmt = $conexion->prepare(
            'INSERT INTO contactos (id_usuario, nombre, telefono, correo, ciudad) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->bind_param('issss', $id_usuario, $datos['nombre'], $datos['telefono'], $datos['correo'], $datos['ciudad']);
        if ($stmt->execute()) {
            poner_mensaje('exito', 'Contacto registrado correctamente.');
            header('Location: panel.php');
            exit;
        }
        error_log('Error al crear contacto: ' . $stmt->error);
        $errores[] = 'No se pudo guardar el contacto. Intenta nuevamente.';
    }
}

$titulo = 'Nuevo contacto';
$accion = 'contacto_nuevo.php';
$boton = 'Guardar contacto';
require_once 'includes/encabezado.php';
?>
<h1>Nuevo contacto</h1>
<?php mostrar_errores($errores); ?>
<?php require 'includes/formulario_contacto.php'; ?>
<?php require_once 'includes/pie.php'; ?>