<article class="recipe-card">
    <div class="recipe-image-wrap">
        <?php if (!empty($r['imagen_url'])): ?>
            <img src="<?= htmlspecialchars(cookly_media_url($r['imagen_url'])) ?>" alt="Imagen">
        <?php else: ?>
            🍲
        <?php endif; ?>
    </div>
    <div class="recipe-body">
        <div class="recipe-topline">
            <span class="pill"><?= htmlspecialchars($r['categoria']) ?></span>
        </div>
        <h4><?= htmlspecialchars($r['titulo']) ?></h4>
        <p>Por <strong><?= htmlspecialchars($r['autor']) ?></strong></p>
        <div class="acciones">
            <a href="<?= cookly_page('receta_detalle.php') ?>?id=<?= $r['id_receta'] ?>">Ver</a>
        </div>
    </div>
</article>
