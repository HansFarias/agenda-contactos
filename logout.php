<?php
// logout.php: cierra la sesión por completo.
require_once 'includes/seguridad.php';

$_SESSION = [];                                   // vaciar datos
if (ini_get('session.use_cookies')) {             // borrar la cookie de sesión
    $p = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
}
session_destroy();                                // destruir la sesión en el servidor

header('Location: login.php');
exit;