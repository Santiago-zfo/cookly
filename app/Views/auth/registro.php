<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cookly · Registro</title>
    <link rel="stylesheet" href="<?= cookly_asset('css/auth.css') ?>">
</head>
<body>
<main class="auth">
    <div class="logo-panel">
        <img class="logo" src="<?= cookly_asset('IMG/logo.png') ?>" alt="Cookly">
    </div>
    <section class="vista">
        <h1>Crea tu cuenta</h1>
        <?php if ($error): ?><p class="estado error" role="alert"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <form method="POST" id="form-registro">
            <div class="campo">
                <label for="reg-usuario">Nombre</label>
                <input id="reg-usuario" type="text" name="usuario" required
                       value="<?= htmlspecialchars($valores['usuario'] ?? '') ?>"
                       class="<?= !empty($errores['usuario']) ? 'invalido' : '' ?>"
                       aria-invalid="<?= !empty($errores['usuario']) ? 'true' : 'false' ?>">
                <?php if (!empty($errores['usuario'])): ?><p class="error" role="alert"><?= htmlspecialchars($errores['usuario']) ?></p><?php endif; ?>
            </div>
            <div class="campo">
                <label for="reg-correo">Correo</label>
                <input id="reg-correo" type="email" name="correo" required
                       value="<?= htmlspecialchars($valores['correo'] ?? '') ?>"
                       class="<?= !empty($errores['correo']) ? 'invalido' : '' ?>"
                       aria-invalid="<?= !empty($errores['correo']) ? 'true' : 'false' ?>">
                <?php if (!empty($errores['correo'])): ?><p class="error" role="alert"><?= htmlspecialchars($errores['correo']) ?></p><?php endif; ?>
            </div>
            <div class="campo">
                <label for="reg-clave">Contraseña</label>
                <div class="con-boton">
                    <input id="reg-clave" type="password" name="clave" required autocomplete="new-password"
                           class="<?= !empty($errores['clave']) ? 'invalido' : '' ?>"
                           aria-invalid="<?= !empty($errores['clave']) ? 'true' : 'false' ?>">
                    <button type="button" class="ver" aria-controls="reg-clave" aria-pressed="false">Mostrar</button>
                </div>
                <?php if (!empty($errores['clave'])): ?><p class="error" role="alert"><?= htmlspecialchars($errores['clave']) ?></p><?php endif; ?>
                <div class="medidor" id="medidor-clave" data-nivel="0" aria-live="polite">
                    <div class="medidor-barra" role="progressbar" aria-label="Seguridad de la contraseña"
                         aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                        <div class="medidor-barra-relleno"></div>
                    </div>
                    <div class="medidor-barras" aria-hidden="true">
                        <span></span><span></span><span></span><span></span>
                    </div>
                    <p class="medidor-texto">Escribe una contraseña para ver su seguridad</p>
                </div>
                <details class="requisitos-panel" open>
                    <summary>Requisitos de la contraseña</summary>
                    <ul class="requisitos" id="requisitos-clave">
                        <li data-regla="longitud">Mínimo 8 caracteres</li>
                        <li data-regla="mayuscula">Una mayúscula</li>
                        <li data-regla="minuscula">Una minúscula</li>
                        <li data-regla="numero">Un número</li>
                        <li data-regla="simbolo">Un símbolo</li>
                    </ul>
                </details>
            </div>
            <div class="campo">
                <label for="reg-confirmar">Confirmar contraseña</label>
                <div class="con-boton">
                    <input id="reg-confirmar" type="password" name="confirmar" required autocomplete="new-password"
                           class="<?= !empty($errores['confirmar']) ? 'invalido' : '' ?>"
                           aria-invalid="<?= !empty($errores['confirmar']) ? 'true' : 'false' ?>">
                    <button type="button" class="ver" aria-controls="reg-confirmar" aria-pressed="false">Mostrar</button>
                </div>
                <?php if (!empty($errores['confirmar'])): ?><p class="error" role="alert"><?= htmlspecialchars($errores['confirmar']) ?></p><?php endif; ?>
            </div>
            <button class="principal" type="submit">Crear cuenta</button>
        </form>
        <p class="enlaces"><a href="<?= cookly_page('Login.php') ?>">¿Ya tienes cuenta? Inicia sesión</a></p>
    </section>
</main>
<script src="<?= cookly_asset('JS/auth.js') ?>" defer></script>
</body>
</html>
