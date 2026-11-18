<?php
/**
 * app/Views/layouts/footer.php
 * ------------------------------------------------------------
 * Pie de página HTML reutilizable.
 * Cierra el <main>, el .page-shell y el <body>.
 * ------------------------------------------------------------
 */
require_once __DIR__ . '/../../Core/paths.php';
?>
    </main><!-- /dashboard -->
</div><!-- /page-shell -->

<footer class="site-footer">
    <p>© <?= date('Y') ?> Cookly · Comparte tu sazón 🍳</p>
</footer>

<script>window.COOKLY = {
    baseUrl: <?= json_encode(cookly_base_url(), JSON_UNESCAPED_SLASHES) ?>,
    pagesUrl: <?= json_encode(cookly_page(), JSON_UNESCAPED_SLASHES) ?>,
    assetsUrl: <?= json_encode(cookly_url('public'), JSON_UNESCAPED_SLASHES) ?>
};</script>
<script src="<?= cookly_asset('JS/main.js') ?>"></script>
</body>
</html>