<?php
// contacto_eliminar.php: elimina un contacto del usuario (solo por POST + token CSRF).
// La confirmación ("¿Seguro?") se pide en panel.php antes de enviar el formulario.
require_once 'includes/seguridad.php';
require_once 'config/conexion.php';

requerir_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: panel.php');
    exit;
}
validar_csrf();

$id_usuario = $_SESSION['usuario_id'];
$id_contacto = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);

if ($id_contacto === false || $id_contacto < 1) {
    poner_mensaje('error', 'Contacto no encontrado.');
    header('Location: panel.php');
    exit;
}

// AND id_usuario = ?  -> imposible borrar contactos de otra persona
$stmt = $conexion->prepare('DELETE FROM contactos WHERE id = ? AND id_usuario = ?');
$stmt->bind_param('ii', $id_contacto, $id_usuario);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    poner_mensaje('exito', 'Contacto eliminado.');
} else {
    poner_mensaje('error', 'Contacto no encontrado.');
}
$stmt->close();

header('Location: panel.php');
exit;