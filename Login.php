<?php
// login.php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/funciones_usuarios.php';

if (estaAutenticado()) { header('Location: index.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    $password = $_POST['clave'] ?? '';

    $usuario = verificar_credenciales($correo, $password);
    if ($usuario) {
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['correo_electronico'] = $usuario['correo_electronico'];
        $_SESSION['rol'] = $usuario['rol'] ?? 'usuario'; 
        
        registrar_log("Inicio de sesión exitoso.", 'INFO');
        header('Location: index.php');
        exit;
    } else {
        registrar_log("Intento de inicio de sesión fallido con el correo: $correo", 'WARNING');
        $error = 'Correo o contraseña incorrectos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cookly · Iniciar sesión</title>
    <link rel="stylesheet" href="public/CSS/auth.css">
</head>
<body>
<main class="auth">
    <img class="logo" src="public/IMG/logo.png" alt="Cookly">
    <section class="vista">
        <h1>Inicia sesión</h1>
        <?php if ($error): ?><p class="estado error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <form method="POST">
            <div class="campo">
                <label>Correo electrónico</label>
                <input type="email" name="correo" required>
            </div>
            <div class="campo">
                <label>Contraseña</label>
                <input type="password" name="clave" required>
            </div>
            <button class="principal" type="submit">Iniciar sesión</button>
        </form>
        <p class="enlaces"><a href="registro.php">¿No tienes cuenta? Regístrate</a></p>
    </section>
</main>
</body>
</html>