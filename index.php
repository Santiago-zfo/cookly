<?php
// index.php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/funciones_recetas.php';
requerirLogin();

$titulo = 'Mis recetas';
$mis_recetas = listar_mis_recetas($_SESSION['id_usuario']);

require_once __DIR__ . '/includes/header.php';
?>
<section class="panel">
    <div class="section-heading">
        <h2>Mis recetas</h2>
        <a class="submit-btn" href="receta_crear.php">+ Nueva receta</a>
    </div>

    <?php if (empty($mis_recetas)): ?>
        <div class="empty-state"><p>🍽️ Aún no has publicado recetas.</p></div>
    <?php else: ?>
        <div class="recipe-grid">
            <?php foreach ($mis_recetas as $r): ?>
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
                            <span class="rating"><?= htmlspecialchars($r['estado']) ?></span>
                        </div>
                        <h4><?= htmlspecialchars($r['titulo']) ?></h4>
                        <p><?= htmlspecialchars(mb_strimwidth($r['descripcion'] ?? '', 0, 80, '…')) ?></p>
                        <div class="acciones">
                            <a href="receta_detalle.php?id=<?= $r['id_receta'] ?>">Ver</a>
                            <a href="receta_editar.php?id=<?= $r['id_receta'] ?>">Editar</a>
                            <a href="receta_eliminar.php?id=<?= $r['id_receta'] ?>" data-confirm="¿Eliminar?">Eliminar</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>