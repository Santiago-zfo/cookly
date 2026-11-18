<?php
/**
 * app/Views/layouts/footer.php
 * ------------------------------------------------------------
 * Pie de página HTML reutilizable.
 * Cierra el <main>, el .page-shell y el <body>.
 * ------------------------------------------------------------
 */
require_once __DIR__ . '/../../Core/paths.php';

$footerE = static fn($valor) => htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
$footerAutenticado = function_exists('estaAutenticado') && estaAutenticado();
$footerAdmin = function_exists('esAdmin') && esAdmin();
?>
    </main><!-- /dashboard -->
</div><!-- /page-shell -->

<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-grid">
            <section class="footer-brand">
                <a class="footer-logo" href="<?= $footerE(cookly_page('index.php')) ?>">
                    <img src="<?= $footerE(cookly_asset('IMG/logo.png')) ?>" alt="" width="40" height="40">
                    <span>Cookly</span>
                </a>
                <p>Guarda, descubre y comparte recetas caseras. Tu sazón tiene un lugar aquí.</p>
            </section>

            <nav class="footer-col" aria-label="Enlaces para explorar">
                <h2>Explorar</h2>
                <a href="<?= $footerE(cookly_page('index.php')) ?>">Mis recetas</a>
                <a href="<?= $footerE(cookly_page('recetas.php')) ?>">Explorar recetas</a>
                <a href="<?= $footerE(cookly_page('receta_crear.php')) ?>">Nueva receta</a>
            </nav>

            <nav class="footer-col" aria-label="Ayuda y recursos">
                <h2>Ayuda</h2>
                <a href="<?= $footerE(cookly_page('manual.php')) ?>">Manual de usuario</a>
                <a href="<?= $footerE(cookly_page('anexos.php')) ?>">Anexos y documentos</a>
                <a href="<?= $footerE(cookly_page('video.php')) ?>">Video de presentación</a>
            </nav>

            <nav class="footer-col" aria-label="Enlaces de cuenta">
                <h2>Tu cuenta</h2>
                <?php if ($footerAutenticado): ?>
                    <a href="<?= $footerE(cookly_page('perfil.php')) ?>">Mi perfil</a>
                    <?php if ($footerAdmin): ?>
                        <a href="<?= $footerE(cookly_page('admin.php')) ?>">Administrar</a>
                    <?php endif; ?>
                    <a href="<?= $footerE(cookly_page('Logout.php')) ?>">Cerrar sesión</a>
                <?php else: ?>
                    <a href="<?= $footerE(cookly_page('Login.php')) ?>">Iniciar sesión</a>
                    <a href="<?= $footerE(cookly_page('registro.php')) ?>">Crear cuenta</a>
                <?php endif; ?>
            </nav>
        </div>

        <div class="footer-bottom">
            <p>© <?= date('Y') ?> Cookly · Comparte tu sazón 🍳</p>
            <a class="footer-top" href="#top">Volver arriba <span aria-hidden="true">↑</span></a>
        </div>
    </div>
</footer>

<script>window.COOKLY = {
    baseUrl: <?= json_encode(cookly_base_url(), JSON_UNESCAPED_SLASHES) ?>,
    pagesUrl: <?= json_encode(cookly_page(), JSON_UNESCAPED_SLASHES) ?>,
    assetsUrl: <?= json_encode(cookly_url('public'), JSON_UNESCAPED_SLASHES) ?>
};</script>
<script src="<?= cookly_asset('JS/main.js') ?>"></script>
</body>
</html>
