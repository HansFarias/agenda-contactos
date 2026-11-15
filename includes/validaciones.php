<?php
// includes/validaciones.php
// Validación en el SERVIDOR (el atributo required del HTML no basta).

// Devuelve [errores, datos_limpios] para un contacto.
function validar_contacto(array $entrada): array
{
    $errores = [];
    $datos = [
        'nombre'   => trim($entrada['nombre'] ?? ''),
        'telefono' => trim($entrada['telefono'] ?? ''),
        'correo'   => trim($entrada['correo'] ?? ''),
        'ciudad'   => trim($entrada['ciudad'] ?? ''),
    ];

    if ($datos['nombre'] === '' || mb_strlen($datos['nombre']) > 100) {
        $errores[] = 'El nombre es obligatorio (máximo 100 caracteres).';
    }
    if (!preg_match('/^[0-9+\-\s()]{7,20}$/', $datos['telefono'])) {
        $errores[] = 'El teléfono no es válido (7 a 20 dígitos; se permiten + - ( ) y espacios).';
    }
    if (!filter_var($datos['correo'], FILTER_VALIDATE_EMAIL) || mb_strlen($datos['correo']) > 150) {
        $errores[] = 'El correo del contacto no es válido.';
    }
    if ($datos['ciudad'] === '' || mb_strlen($datos['ciudad']) > 80) {
        $errores[] = 'La ciudad es obligatoria (máximo 80 caracteres).';
    }

    return [$errores, $datos];
}

function mostrar_errores(array $errores): void
{
    if ($errores) {
        echo '<ul class="mensaje error">';
        foreach ($errores as $error) {
            echo '<li>' . e($error) . '</li>';
        }
        echo '</ul>';
    }
}