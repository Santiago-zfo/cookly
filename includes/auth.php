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

// Función que verifica si el usuario es administrador
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

// --- SISTEMA DE LOGS (REGISTRO) ---
/**
 * Registra un error o evento en el archivo de logs.
 * 
 * @param string $mensaje El mensaje a guardar.
 * @param string $tipo    El tipo de log (ERROR, INFO, WARNING, CRITICAL).
 */
function registrar_log($mensaje, $tipo = 'ERROR') {
    $directorio = __DIR__ . '/../logs';
    $archivo = $directorio . '/sistema.log';

    if (!is_dir($directorio)) {
        mkdir($directorio, 0755, true);
    }

    $fecha = date('Y-m-d H:i:s');
    $usuario = isset($_SESSION['correo_electronico']) ? $_SESSION['correo_electronico'] : 'Invitado/Anónimo';
    
    $linea = "[$fecha] [$tipo] [$usuario] $mensaje" . PHP_EOL;
    file_put_contents($archivo, $linea, FILE_APPEND | LOCK_EX);
}