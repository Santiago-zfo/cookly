<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cookly · Registro</title>
    <link rel="stylesheet" href="<?= cookly_asset('css/auth.css') ?>">
</head>
<body>
<main class="auth">
    <img class="logo" src="<?= cookly_asset('IMG/logo.png') ?>" alt="Cookly">
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
        <p class="enlaces"><a href="<?= cookly_page('Login.php') ?>">¿Ya tienes cuenta? Inicia sesión</a></p>
    </section>
</main>
</body>
</html>
