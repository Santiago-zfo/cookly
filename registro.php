<?php
// registro.php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/funciones_usuarios.php';

if (estaAutenticado()) { header('Location: index.php'); exit; }

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
        // ÉXITO: Redirigir al login automáticamente
        set_flash_message('exito', 'Cuenta creada con éxito. Ya puedes iniciar sesión.');
        header('Location: login.php'); 
        exit;
    } else {
        $error = 'Error al crear la cuenta.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cookly · Registro</title>
    <link rel="stylesheet" href="public/CSS/auth.css">
</head>
<body>
<main class="auth">
    <img class="logo" src="public/IMG/logo.png" alt="Cookly">
    <section class="vista">
        <h1>Crea tu cuenta</h1>
        <?php if ($error): ?><p class="estado error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <form method="POST">
            <div class="campo"><label>Nombre</label><input type="text" name="usuario" required></div>
            <div class="campo"><label>Correo</label><input type="email" name="correo" required></div>
            <div class="campo"><label>Contraseña</label><input type="password" name="clave" required></div>
            <div class="campo"><label>Confirmar Contraseña</label><input type="password" name="confirmar" required></div>
            <button class="principal" type="submit">Crear cuenta</button>
        </form>
        <p class="enlaces"><a href="login.php">¿Ya tienes cuenta? Inicia sesión</a></p>
    </section>
</main>
</body>
</html>