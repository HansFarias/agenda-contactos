<?php
// panel.php: página privada con datos del usuario y su lista de contactos.
require_once 'includes/seguridad.php';
require_once 'config/conexion.php';

requerir_login();
$id_usuario = $_SESSION['usuario_id'];

// Datos del usuario autenticado
$stmt = $conexion->prepare('SELECT nombre, correo FROM usuarios WHERE id = ?');
$stmt->bind_param('i', $id_usuario);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$usuario) {            // la cuenta ya no existe: cerrar sesión
    header('Location: logout.php');
    exit;
}

// SOLO los contactos de este usuario
$stmt = $conexion->prepare(
    'SELECT id, nombre, telefono, correo, ciudad FROM contactos WHERE id_usuario = ? ORDER BY nombre'
);
$stmt->bind_param('i', $id_usuario);
$stmt->execute();
$contactos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$titulo = 'Mi agenda';
require_once 'includes/encabezado.php';
?>
<h1>Bienvenido, <?= e($usuario['nombre']) ?></h1>
<p>Correo: <?= e($usuario['correo']) ?></p>

<h2>Mis contactos</h2>
<p><a class="boton" href="contacto_nuevo.php">+ Nuevo contacto</a></p>

<?php if (!$contactos): ?>
  <p>Aún no tienes contactos registrados.</p>
<?php else: ?>
<table>
  <thead>
    <tr><th>Nombre</th><th>Teléfono</th><th>Correo</th><th>Ciudad</th><th>Acciones</th></tr>
  </thead>
  <tbody>
  <?php foreach ($contactos as $c): ?>
    <tr>
      <td><?= e($c['nombre']) ?></td>
      <td><?= e($c['telefono']) ?></td>
      <td><?= e($c['correo']) ?></td>
      <td><?= e($c['ciudad']) ?></td>
      <td class="acciones">
        <a class="boton azul" href="contacto_editar.php?id=<?= (int) $c['id'] ?>">Editar</a>
        <form method="POST" action="contacto_eliminar.php"
              onsubmit="return confirm('¿Seguro que deseas eliminar este contacto?');">
          <?= campo_csrf() ?>
          <input type="hidden" name="id" value="<?= (int) $c['id'] ?>">
          <button type="submit" class="rojo">Eliminar</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>
<?php require_once 'includes/pie.php'; ?>