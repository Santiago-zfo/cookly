<?php
/**
 * app/Controllers/ProfileController.php
 * Perfil del usuario: datos, avatar, contraseña y baja de cuenta.
 */
final class ProfileController extends Controller
{
    public function index(): void
    {
        requerirLogin();

        $id_usuario = $_SESSION['id_usuario'];
        $perfil = obtener_usuario_por_id($id_usuario);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accion = $_POST['accion'] ?? '';

            if ($accion === 'perfil') {
                $nombre = trim($_POST['nombre'] ?? '');
                $correo = trim($_POST['correo'] ?? '');
                $bio    = trim($_POST['biografia'] ?? '');
                $avatar = trim($_POST['avatar_url'] ?? '');

                if (isset($_FILES['avatar_file']) && $_FILES['avatar_file']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = cookly_app_root() . '/public/IMG/avatares/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                    $ext = strtolower(pathinfo($_FILES['avatar_file']['name'], PATHINFO_EXTENSION));
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                        $nombreArchivo = uniqid('avatar_') . '.' . $ext;
                        if (move_uploaded_file($_FILES['avatar_file']['tmp_name'], $uploadDir . $nombreArchivo)) {
                            $avatar = 'public/IMG/avatares/' . $nombreArchivo;
                        }
                    }
                }

                if (actualizar_perfil($id_usuario, $nombre, $correo, $avatar, $bio)) {
                    $_SESSION['nombre'] = $nombre;
                    $_SESSION['correo_electronico'] = $correo;
                    set_flash_message('exito', 'Perfil actualizado correctamente.');
                    $this->redirect($this->pagina('perfil.php'));
                } else {
                    set_flash_message('error', 'Error al guardar los cambios.');
                    $this->redirect($this->pagina('perfil.php'));
                }

            } elseif ($accion === 'password') {
                $actual = $_POST['pass_actual'] ?? '';
                $nueva  = $_POST['pass_nueva'] ?? '';
                $confir = $_POST['pass_confirmar'] ?? '';

                if (password_verify($actual, $perfil['contrasena_hash'])) {
                    if (strlen($nueva) >= 8 && $nueva === $confir) {
                        actualizar_password($id_usuario, $nueva);
                        set_flash_message('exito', 'Contraseña actualizada correctamente.');
                    } else {
                        set_flash_message('error', 'La nueva contraseña debe tener al menos 8 caracteres y coincidir.');
                    }
                } else {
                    set_flash_message('error', 'La contraseña actual es incorrecta.');
                }
                $this->redirect($this->pagina('perfil.php'));

            } elseif ($accion === 'eliminar_cuenta') {
                eliminar_usuario($id_usuario);
                cerrarSesion();
                $this->redirect($this->pagina('Login.php'));
            }
        }

        $this->render('profile/index', [
            'perfil' => $perfil,
        ], ['titulo' => 'Mi Perfil']);
    }
}
