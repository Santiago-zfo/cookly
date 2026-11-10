<?php
// Zona horaria utilizada por el registro de eventos.
date_default_timezone_set('America/Bogota');

/**
 * includes/auth.php
 * ------------------------------------------------------------
 * Helpers de sesión y control de acceso.
 *
 * Se incluye en controladores y vistas. Cada función es
 * "idempotente": puede llamarse varias veces sin efectos raros.
 *
 * Funciones:
 *   - iniciarSesion()      → arranca la sesión si no estaba activa.
 *   - estaAutenticado()    → true si hay un usuario logueado.
 *   - usuarioActual()      → datos básicos del usuario o null.
 *   - requerirLogin()      → redirige a login.php si no hay sesión.
 *   - redirigirSiLogueado()→ redirige a index.php si YA hay sesión.
 *   - csrfToken()          → genera/devuelve token CSRF de la sesión.
 *   - verificarCsrf()      → valida el token recibido en POST.
 *   - cerrarSesion()       → limpia y destruye la sesión.
 * ------------------------------------------------------------
 */

/**
 * Inicia la sesión si aún no está activa.
 * Se llama automáticamente en todas las funciones que la necesitan.
 */
function iniciarSesion(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/* ------------------------------------------------------------
 * Arranca la sesión al incluir este archivo.
 * ------------------------------------------------------------ */
iniciarSesion();

/**
 * ¿Hay un usuario autenticado?
 */
function estaAutenticado(): bool
{
    iniciarSesion();
    return isset($_SESSION['id_usuario']);
}

/**
 * Devuelve los datos básicos del usuario actual, o null.
 *
 * @return array{id_usuario:int, nombre:string, correo:string}|null
 */
function usuarioActual(): ?array
{
    iniciarSesion();
    if (!estaAutenticado()) {
        return null;
    }
    return [
        'id_usuario' => (int) $_SESSION['id_usuario'],
        'nombre'     => (string) ($_SESSION['nombre'] ?? ''),
        'correo'     => (string) ($_SESSION['correo_electronico'] ?? ''),
        'rol'        => (string) ($_SESSION['rol'] ?? 'usuario'),
    ];
}

/**
 * Redirige a login.php si el usuario no está autenticado.
 * Úsalo al inicio de cada página protegida.
 */
function requerirLogin(): void
{
    iniciarSesion();
    if (!estaAutenticado()) {
        header('Location: Login.php');
        exit;
    }
}

/**
 * Redirige a index.php si el usuario YA está autenticado.
 * Úsalo en login.php y registro.php.
 */
function redirigirSiLogueado(): void
{
    iniciarSesion();
    if (estaAutenticado()) {
        header('Location: index.php');
        exit;
    }
}

/* ============================================================
 * CSRF (Cross-Site Request Forgery)
 * ============================================================ */

/**
 * Devuelve el token CSRF de la sesión, creándolo si no existe.
 */
function csrfToken(): string
{
    iniciarSesion();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verifica que el token recibido por POST coincida con el de la sesión.
 */
function verificarCsrf(string $token): bool
{
    iniciarSesion();
    return isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Cierra y destruye la sesión actual.
 * (El AuthController tiene su propia versión con redirección;
 *  esta es una alternativa reutilizable desde vistas.)
 */
function cerrarSesion(): void
{
    iniciarSesion();
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $p['path'],
            $p['domain'],
            $p['secure'],
            $p['httponly']
        );
    }

    session_destroy();
}

// Permisos de administración compatibles con la sesión existente.
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

    // La función date() usará la zona horaria que definimos al principio
    $fecha = date('Y-m-d H:i:s');
    $usuario = isset($_SESSION['correo_electronico']) ? $_SESSION['correo_electronico'] : 'Invitado/Anónimo';
    
    $linea = "[$fecha] [$tipo] [$usuario] $mensaje" . PHP_EOL;
    file_put_contents($archivo, $linea, FILE_APPEND | LOCK_EX);
}
