<?php
// login.php: inicio de sesión.
require_once 'includes/seguridad.php';
require_once 'config/conexion.php';

solo_invitados();

$error = '';
$correo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validar_csrf();

    $correo = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = 'Ingresa un correo válido y tu contraseña.';
    } else {
        $stmt = $conexion->prepare('SELECT id, nombre, password FROM usuarios WHERE correo = ?');
        $stmt->bind_param('s', $correo);
        $stmt->execute();
        $usuario = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        // Mismo mensaje si el correo no existe o la clave es incorrecta
        // (no revelamos cuál de los dos falló).
        if ($usuario && password_verify($password, $usuario['password'])) {
            session_regenerate_id(true);          // evita fijación de sesión
            $_SESSION['usuario_id'] = (int) $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            header('Location: panel.php');
            exit;
        }
        $error = 'Correo o contraseña incorrectos.';
    }
}

$titulo = 'Iniciar sesión';
require_once 'includes/encabezado.php';
?>
<h1>Iniciar sesión</h1>
<?php if ($error): ?><p class="mensaje error"><?= e($error) ?></p><?php endif; ?>
<form method="POST" action="login.php">
  <?= campo_csrf() ?>
  <label>Correo electrónico
    <input type="email" name="correo" value="<?= e($correo) ?>" required>
  </label>
  <label>Contraseña
    <input type="password" name="password" required>
  </label>
  <button type="submit">Entrar</button>
</form>
<p>¿No tienes cuenta? <a href="registro.php">Regístrate</a></p>
<?php require_once 'includes/pie.php'; ?>