<?php
// recetas.php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/funciones_recetas.php';
requerirLogin();

$titulo = 'Explorar';
$todas = listar_todas_las_recetas(); // Esta función ya filtra por Aceptado
$usuarioId = (int) $_SESSION['id_usuario'];

require_once __DIR__ . '/includes/header.php';
?>
<section class="panel">
    <div class="section-heading"><h2>Explorar recetas</h2></div>
    <div class="recipe-grid">
        <?php if (empty($todas)): ?>
            <p style="grid-column: 1/-1; color: var(--text-muted);">Aún no hay recetas aceptadas en la comunidad.</p>
        <?php else: ?>
            <?php foreach ($todas as $r): ?>
                <?php $esMia = ((int)$r['id_usuario'] === $usuarioId); ?>
                <article class="recipe-card">
                    <div class="recipe-image-wrap">
                        <?php if (!empty($r['imagen_url'])): ?>
                            <img src="<?= htmlspecialchars($r['imagen_url']) ?>" alt="Imagen">
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
                            <a href="receta_detalle.php?id=<?= $r['id_receta'] ?>">Ver</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>