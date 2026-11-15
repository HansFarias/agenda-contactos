<?php
// includes/seguridad.php
// Sesiones, protección de páginas, token CSRF, escape de salida y mensajes.

// Cookie de sesión más segura (HttpOnly + SameSite). Secure solo si hay HTTPS.
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'httponly' => true,
    'samesite' => 'Lax',
    'secure'   => !empty($_SERVER['HTTPS']),
]);
session_start();

// Escapa texto antes de mostrarlo en HTML (previene XSS).
function e($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function usuario_autenticado(): bool
{
    return isset($_SESSION['usuario_id']);
}

// Páginas privadas: si no hay sesión activa, se envía al login.
function requerir_login(): void
{
    if (!usuario_autenticado()) {
        header('Location: login.php');
        exit;
    }
}

// Páginas públicas (login/registro): si ya hay sesión, se envía al panel.
function solo_invitados(): void
{
    if (usuario_autenticado()) {
        header('Location: panel.php');
        exit;
    }
}

// ---------- CSRF ----------
function token_csrf(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function campo_csrf(): string
{
    return '<input type="hidden" name="csrf" value="' . e(token_csrf()) . '">';
}

function validar_csrf(): void
{
    $enviado = $_POST['csrf'] ?? '';
    if (!hash_equals($_SESSION['csrf'] ?? '', $enviado)) {
        http_response_code(403);
        exit('Solicitud no válida.');
    }
}

// ---------- Mensajes de una sola vez (flash) ----------
function poner_mensaje(string $tipo, string $texto): void
{
    $_SESSION['mensaje'] = ['tipo' => $tipo, 'texto' => $texto];
}

function mostrar_mensaje(): void
{
    if (!empty($_SESSION['mensaje'])) {
        $m = $_SESSION['mensaje'];
        unset($_SESSION['mensaje']);
        echo '<p class="mensaje ' . e($m['tipo']) . '">' . e($m['texto']) . '</p>';
    }
}