<?php
/**
 * includes/footer.php
 * ------------------------------------------------------------
 * Pie de página HTML reutilizable.
 * Cierra el <main>, el .page-shell y el <body>.
 * ------------------------------------------------------------
 */
?>
    </main><!-- /dashboard -->
</div><!-- /page-shell -->

<footer class="site-footer">
    <p>© <?= date('Y') ?> Cookly · Comparte tu sazón 🍳</p>
</footer>

<script src="public/JS/main.js"></script>
<?php foreach (($scriptsPagina ?? []) as $script): ?>
    <script src="<?= htmlspecialchars($script) ?>" defer></script>
<?php endforeach; ?>
</body>
</html>
