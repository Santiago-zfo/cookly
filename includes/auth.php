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

function esAdmin(): bool {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
}

function requerirAdmin(): void {
    requerirLogin();
    if (!esAdmin()) {
        require_once __DIR__ . '/logger.php'; // Incluir logger
        registrar_log('ACCESO DENEGADO', 'Intento de acceder al panel de administración sin permisos.', 'WARNING');
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

// --- SISTEMA DE MENSAJES FLASH ---
function set_flash_message($tipo, $mensaje) {
    $_SESSION['flash'] = ['tipo' => $tipo, 'mensaje' => $mensaje];
}

function mostrar_flash_message() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']); 
        
        $clase = $flash['tipo'] === 'exito' ? 'exito' : 'error';
        echo "<div class='estado {$clase}' style='margin-bottom: 1.5rem; animation: slideUp 0.3s;'>{$flash['mensaje']}</div>";
    }
}   