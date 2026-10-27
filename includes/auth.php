<?php
// includes/auth.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function estaAutenticado(): bool {
    return isset($_SESSION['id_usuario']);
}

function requerirLogin(): void {
    if (!estaAutenticado()) {
        header('Location: login.php');
        exit;
    }
}

// Función que faltaba y causaba el error
function esAdmin(): bool {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
}

function requerirAdmin(): void {
    requerirLogin();
    if (!esAdmin()) {
        header('Location: index.php');
        exit;
    }
}

function usuarioActual(): ?array {
    if (!estaAutenticado()) return null;
    return [
        'id_usuario' => (int) $_SESSION['id_usuario'],
        'nombre'     => $_SESSION['nombre'] ?? '',
        'correo'     => $_SESSION['correo_electronico'] ?? '',
        'rol'        => $_SESSION['rol'] ?? 'usuario'
    ];
}

function cerrarSesion(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}