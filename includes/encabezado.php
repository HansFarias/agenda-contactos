<?php
// includes/encabezado.php  (uso: $titulo = '...'; require_once 'includes/encabezado.php';)
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($titulo ?? 'Agenda de contactos') ?></title>
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<header class="barra">
  <strong>Agenda de contactos</strong>
  <?php if (usuario_autenticado()): ?>
    <nav><a href="panel.php">Mi agenda</a> <a href="logout.php">Cerrar sesión</a></nav>
  <?php endif; ?>
</header>
<main class="contenedor">
<?php mostrar_mensaje(); ?>