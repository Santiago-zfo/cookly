<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cookly · Iniciar sesión</title>
    <link rel="stylesheet" href="<?= cookly_asset('css/auth.css') ?>">
</head>
<body>
<main class="auth">
    <img class="logo" src="<?= cookly_asset('IMG/logo.png') ?>" alt="Cookly">
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
        <p class="enlaces"><a href="<?= cookly_page('registro.php') ?>">¿No tienes cuenta? Regístrate</a></p>
    </section>
</main>
</body>
</html>
