<?php
// contacto_editar.php: editar un contacto, solo si pertenece al usuario.
require_once 'includes/seguridad.php';
require_once 'config/conexion.php';
require_once 'includes/validaciones.php';

requerir_login();
$id_usuario = $_SESSION['usuario_id'];

// El id llega por GET (al abrir) o por POST (al guardar).
$id_contacto = filter_var($_POST['id'] ?? $_GET['id'] ?? '', FILTER_VALIDATE_INT);
if ($id_contacto === false || $id_contacto < 1) {
    poner_mensaje('error', 'Contacto no encontrado.');
    header('Location: panel.php');
    exit;
}

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validar_csrf();
    [$errores, $datos] = validar_contacto($_POST);

    if (!$errores) {
        // AND id_usuario = ?  -> solo modifica contactos del dueño
        $stmt = $conexion->prepare(
            'UPDATE contactos SET nombre = ?, telefono = ?, correo = ?, ciudad = ? WHERE id = ? AND id_usuario = ?'
        );
        $stmt->bind_param('ssssii', $datos['nombre'], $datos['telefono'], $datos['correo'], $datos['ciudad'], $id_contacto, $id_usuario);
        $stmt->execute();
        $stmt->close();

        // Comprobar que el contacto es del usuario (affected_rows puede ser 0 si no hubo cambios)
        $stmt = $conexion->prepare('SELECT id FROM contactos WHERE id = ? AND id_usuario = ?');
        $stmt->bind_param('ii', $id_contacto, $id_usuario);
        $stmt->execute();
        $existe = $stmt->get_result()->num_rows > 0;
        $stmt->close();

        if ($existe) {
            poner_mensaje('exito', 'Contacto actualizado correctamente.');
        } else {
            poner_mensaje('error', 'Contacto no encontrado.');
        }
        header('Location: panel.php');
        exit;
    }
} else {
    // Cargar el contacto SOLO si es del usuario autenticado
    $stmt = $conexion->prepare(
        'SELECT nombre, telefono, correo, ciudad FROM contactos WHERE id = ? AND id_usuario = ?'
    );
    $stmt->bind_param('ii', $id_contacto, $id_usuario);
    $stmt->execute();
    $datos = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$datos) {   // no existe o es de otro usuario: misma respuesta
        poner_mensaje('error', 'Contacto no encontrado.');
        header('Location: panel.php');
        exit;
    }
}

$titulo = 'Editar contacto';
$accion = 'contacto_editar.php';
$boton = 'Guardar cambios';
require_once 'includes/encabezado.php';
?>
<h1>Editar contacto</h1>
<?php mostrar_errores($errores); ?>
<?php require 'includes/formulario_contacto.php'; ?>
<?php require_once 'includes/pie.php'; ?>