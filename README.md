# Agenda personal de contactos

Aplicación web en **PHP y MySQL** que permite registrarse, iniciar sesión y administrar una agenda personal de contactos. Cada usuario ve y gestiona únicamente sus propios contactos.

Proyecto de la materia Desarrollo Web (Unidad 3: PHP con MySQL).

## Funcionalidades

- Registro de usuarios con validación de correo único.
- Inicio y cierre de sesión con manejo de sesiones PHP.
- Panel privado con nombre, correo y lista de contactos del usuario.
- Crear, editar y eliminar contactos (con confirmación antes de eliminar).
- Restricción de acceso: un usuario no puede ver, editar ni eliminar contactos de otro, ni siquiera cambiando la URL.

## Tecnologías utilizadas

- PHP 8 (mysqli con consultas preparadas)
- MySQL / MariaDB
- HTML5 y CSS3
- Apache (XAMPP en Windows) y phpMyAdmin
- Visual Studio Code

## Video de Presentacion proyecto

- https://youtu.be/4LhVGYubHRk

## Seguridad implementada

- Contraseñas guardadas con `password_hash()` y comprobadas con `password_verify()`.
- Consultas preparadas (`prepare` + `bind_param`) contra inyección SQL.
- Validación de formularios en el servidor.
- Escape de salida con `htmlspecialchars()` contra XSS.
- Verificación de sesión activa en todas las páginas protegidas y `session_regenerate_id()` al iniciar sesión.
- Token CSRF en los formularios que modifican datos.
- Todas las consultas de contactos filtran por `id_usuario` tomado de la sesión.
- Mensajes de error genéricos, sin revelar información sensible.

## Requisitos

- XAMPP (Apache + MySQL) con PHP 8.0 o superior.
- Navegador web actualizado.

## Instalación

1. Clona o descarga este repositorio dentro de la carpeta `htdocs` de XAMPP:
   ```
   cd C:\xampp\htdocs
   git clone https://github.com/HansFarias/agenda-contactos.git
   ```
2. Abre el Panel de control de XAMPP e inicia **Apache** y **MySQL**.
3. Entra a `http://localhost/phpmyadmin`, ve a la pestaña **Importar**, selecciona `sql/agenda.sql` y pulsa **Importar**. Esto crea la base `agenda_db` con las tablas `usuarios` y `contactos`.
4. Revisa los datos de conexión en `config/conexion.php`. Por defecto en XAMPP: servidor `localhost`, usuario `root`, contraseña vacía y puerto `3306`.
5. Abre en el navegador: `http://localhost/agenda-contactos/`

## Estructura del proyecto

```
agenda-contactos/
├── config/conexion.php
├── css/estilos.css
├── includes/
├── sql/agenda.sql
├── index.php
├── registro.php
├── login.php
├── panel.php
├── contacto_nuevo.php
├── contacto_editar.php
├── contacto_eliminar.php
└── logout.php
```

## Autor

Hans Vladimir Farias Zavala