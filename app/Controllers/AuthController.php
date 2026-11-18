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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = trim($_POST['correo'] ?? '');
            $password = $_POST['clave'] ?? '';

            $usuario = verificar_credenciales($correo, $password);
            if ($usuario) {
                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                $_SESSION['nombre'] = $usuario['nombre'];
                $_SESSION['correo_electronico'] = $usuario['correo_electronico'];
                // El permiso procede de la cuenta validada en la base de datos.
                $_SESSION['rol'] = $usuario['rol'] ?? 'usuario';
                $this->redirect($this->pagina('index.php'));
            } else {
                $error = 'Correo o contraseña incorrectos.';
            }
        }

        $this->view->partial('auth/login', ['error' => $error]);
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['usuario'] ?? '');
            $correo = trim($_POST['correo'] ?? '');
            $password = $_POST['clave'] ?? '';
            $confirmar = $_POST['confirmar'] ?? '';

            if (strlen($nombre) < 3 || !filter_var($correo, FILTER_VALIDATE_EMAIL) || strlen($password) < 8 || $password !== $confirmar) {
                $error = 'Verifica los datos. Mínimo 8 caracteres en la contraseña.';
            } elseif (obtener_usuario_por_correo($correo)) {
                $error = 'Ese correo ya está registrado.';
            } elseif (crear_usuario($nombre, $correo, $password)) {
                set_flash_message('exito', 'Cuenta creada con éxito. Ya puedes iniciar sesión.');
                $this->redirect($this->pagina('Login.php'));
            } else {
                $error = 'Error al crear la cuenta.';
            }
        }

        $this->view->partial('auth/registro', ['error' => $error]);
    }
}
