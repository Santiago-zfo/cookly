<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cookly · Iniciar sesión</title>
    <link rel="stylesheet" href="<?= cookly_asset('css/auth.css') ?>">
</head>
<body>
<main class="auth">
    <img class="logo" src="<?= cookly_asset('IMG/logo.png') ?>" alt="Cookly">
    <section class="vista">
        <h1>Inicia sesión</h1>
        <?php if ($error): ?><p class="estado error" role="alert"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <form method="POST" id="form-login">
            <div class="campo">
                <label for="login-correo">Correo electrónico</label>
                <input id="login-correo" type="email" name="correo" required
                       value="<?= htmlspecialchars($correo ?? '') ?>"
                       class="<?= $error_correo ? 'invalido' : '' ?>"
                       aria-invalid="<?= $error_correo ? 'true' : 'false' ?>">
                <?php if ($error_correo): ?><p class="error" role="alert"><?= htmlspecialchars($error_correo) ?></p><?php endif; ?>
            </div>
            <div class="campo">
                <label for="login-clave">Contraseña</label>
                <div class="con-boton">
                    <input id="login-clave" type="password" name="clave" required autocomplete="current-password"
                           class="<?= $error_clave ? 'invalido' : '' ?>"
                           aria-invalid="<?= $error_clave ? 'true' : 'false' ?>">
                    <button type="button" class="ver" aria-controls="login-clave" aria-pressed="false">Mostrar</button>
                </div>
                <?php if ($error_clave): ?><p class="error" role="alert"><?= htmlspecialchars($error_clave) ?></p><?php endif; ?>
            </div>
            <button class="principal" type="submit">Iniciar sesión</button>
        </form>
        <p class="enlaces"><a href="<?= cookly_page('registro.php') ?>">¿No tienes cuenta? Regístrate</a></p>
    </section>
</main>
<script src="<?= cookly_asset('JS/auth.js') ?>" defer></script>
</body>
</html>
