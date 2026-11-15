<?php
// registro.php: alta de usuarios.
require_once 'includes/seguridad.php';
require_once 'config/conexion.php';
require_once 'includes/validaciones.php';

solo_invitados();

$errores = [];
$nombre = '';
$correo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validar_csrf();

    // 1. Sanear / validar entradas
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmar = $_POST['confirmar'] ?? '';

    if ($nombre === '' || mb_strlen($nombre) > 100) {
        $errores[] = 'El nombre es obligatorio (máximo 100 caracteres).';
    }
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL) || mb_strlen($correo) > 150) {
        $errores[] = 'El correo no es válido.';
    }
    if (strlen($password) < 8) {
        $errores[] = 'La contraseña debe tener al menos 8 caracteres.';
    }
    if ($password !== $confirmar) {
        $errores[] = 'Las contraseñas no coinciden.';
    }

    // 2. Verificar que el correo no exista (consulta preparada)
    if (!$errores) {
        $stmt = $conexion->prepare('SELECT id FROM usuarios WHERE correo = ?');
        $stmt->bind_param('s', $correo);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errores[] = 'Ese correo ya está registrado.';
        }
        $stmt->close();
    }

    // 3. Guardar con la contraseña hasheada
    if (!$errores) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conexion->prepare('INSERT INTO usuarios (nombre, correo, password) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $nombre, $correo, $hash);
        if ($stmt->execute()) {
            poner_mensaje('exito', 'Registro exitoso. Ya puedes iniciar sesión.');
            header('Location: login.php');
            exit;
        }
        error_log('Error al registrar usuario: ' . $stmt->error);
        $errores[] = 'No se pudo completar el registro. Intenta nuevamente.';
    }
}

$titulo = 'Registro de usuario';
require_once 'includes/encabezado.php';
?>
<h1>Registro de usuario</h1>
<?php mostrar_errores($errores); ?>
<form method="POST" action="registro.php" autocomplete="off">
  <?= campo_csrf() ?>
  <label>Nombre completo
    <input type="text" name="nombre" maxlength="100" value="<?= e($nombre) ?>" required>
  </label>
  <label>Correo electrónico
    <input type="email" name="correo" maxlength="150" value="<?= e($correo) ?>" required>
  </label>
  <label>Contraseña (mínimo 8 caracteres)
    <input type="password" name="password" required>
  </label>
  <label>Confirmar contraseña
    <input type="password" name="confirmar" required>
  </label>
  <button type="submit">Registrarme</button>
</form>
<p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
<?php require_once 'includes/pie.php'; ?>