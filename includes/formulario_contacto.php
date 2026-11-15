<?php
// includes/formulario_contacto.php
// Formulario compartido por "nuevo" y "editar".
// Variables esperadas: $accion, $boton, $datos y (al editar) $id_contacto.
?>
<form method="POST" action="<?= e($accion) ?>">
  <?= campo_csrf() ?>
  <?php if (isset($id_contacto)): ?>
    <input type="hidden" name="id" value="<?= (int) $id_contacto ?>">
  <?php endif; ?>
  <label>Nombre
    <input type="text" name="nombre" maxlength="100" value="<?= e($datos['nombre']) ?>" required>
  </label>
  <label>Teléfono
    <input type="text" name="telefono" maxlength="20" value="<?= e($datos['telefono']) ?>" required>
  </label>
  <label>Correo electrónico
    <input type="email" name="correo" maxlength="150" value="<?= e($datos['correo']) ?>" required>
  </label>
  <label>Ciudad
    <input type="text" name="ciudad" maxlength="80" value="<?= e($datos['ciudad']) ?>" required>
  </label>
  <button type="submit"><?= e($boton) ?></button>
  <a class="boton gris" href="panel.php">Cancelar</a>
</form>