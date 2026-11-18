<?php
/**
 * app/Controllers/AuthController.php
 * Login, registro y cierre de sesión.
 */
final class AuthController extends Controller
{
    public function login(): void
    {
        if (estaAutenticado()) {
            $this->redirect($this->pagina('index.php'));
        }

        $error = '';
        $errorCorreo = '';
        $errorClave = '';
        $correo = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = trim($_POST['correo'] ?? '');
            $password = $_POST['clave'] ?? '';

            $usuario = $correo !== '' ? obtener_usuario_por_correo($correo) : false;

            if ($usuario && password_verify($password, $usuario['contrasena_hash'])) {
                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                $_SESSION['nombre'] = $usuario['nombre'];
                $_SESSION['correo_electronico'] = $usuario['correo_electronico'];
                // El permiso procede de la cuenta validada en la base de datos.
                $_SESSION['rol'] = $usuario['rol'] ?? 'usuario';
                $this->redirect($this->pagina('index.php'));
            }

            // El mensaje se asocia al campo que falló para que se vea dónde está el error.
            if ($correo === '') {
                $errorCorreo = 'Ingresa tu correo electrónico.';
            } elseif (!$usuario) {
                $errorCorreo = 'No encontramos una cuenta con ese correo.';
            } elseif ($password === '') {
                $errorClave = 'Ingresa tu contraseña.';
            } else {
                $errorClave = 'La contraseña es incorrecta.';
            }

            $error = 'Revisa el campo marcado e inténtalo de nuevo.';
        }

        $this->view->partial('auth/login', [
            'error'        => $error,
            'error_correo' => $errorCorreo,
            'error_clave'  => $errorClave,
            'correo'       => $correo,
        ]);
    }

    public function logout(): void
    {
        cerrarSesion();
        $this->redirect($this->pagina('Login.php'));
    }

    public function registro(): void
    {
        if (estaAutenticado()) {
            $this->redirect($this->pagina('index.php'));
        }

        $error = '';
        $errores = ['usuario' => '', 'correo' => '', 'clave' => '', 'confirmar' => ''];
        $valores = ['usuario' => '', 'correo' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['usuario'] ?? '');
            $correo = trim($_POST['correo'] ?? '');
            $password = $_POST['clave'] ?? '';
            $confirmar = $_POST['confirmar'] ?? '';
            $valores = ['usuario' => $nombre, 'correo' => $correo];

            if ($nombre === '') {
                $errores['usuario'] = 'Ingresa tu nombre.';
            } elseif (strlen($nombre) < 3) {
                $errores['usuario'] = 'El nombre debe tener al menos 3 caracteres.';
            }

            if ($correo === '') {
                $errores['correo'] = 'Ingresa tu correo electrónico.';
            } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $errores['correo'] = 'El correo no tiene un formato válido.';
            } elseif (obtener_usuario_por_correo($correo)) {
                $errores['correo'] = 'Ese correo ya está registrado.';
            }

            if (strlen($password) < 8) {
                $errores['clave'] = 'La contraseña debe tener al menos 8 caracteres.';
            }

            if ($password !== $confirmar) {
                $errores['confirmar'] = 'Las contraseñas no coinciden.';
            }

            if (!$errores['usuario'] && !$errores['correo'] && !$errores['clave'] && !$errores['confirmar']) {
                if (crear_usuario($nombre, $correo, $password)) {
                    set_flash_message('exito', 'Cuenta creada con éxito. Ya puedes iniciar sesión.');
                    $this->redirect($this->pagina('Login.php'));
                }
                $error = 'No se pudo crear la cuenta. Inténtalo de nuevo.';
            } else {
                $error = 'Revisa los campos marcados e inténtalo de nuevo.';
            }
        }

        $this->view->partial('auth/registro', [
            'error'   => $error,
            'errores' => $errores,
            'valores' => $valores,
        ]);
    }
}
