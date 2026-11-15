<?php
// index.php: punto de entrada. Redirige según haya sesión o no.
require_once 'includes/seguridad.php';

header('Location: ' . (usuario_autenticado() ? 'panel.php' : 'login.php'));
exit;