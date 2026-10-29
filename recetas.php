<?php
// recetas.php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/funciones_recetas.php';
requerirLogin();

$titulo = 'Explorar';
$usuarioId = (int) $_SESSION['id_usuario'];

// Configuración de paginación
$por_pagina = 6;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_actual < 1) $pagina_actual = 1;

$total_recetas = contar_todas_las_recetas();
$total_paginas = ceil($total_recetas / $por_pagina);
$offset = ($pagina_actual - 1) * $por_pagina;

$todas = listar_todas_las_recetas($por_pagina, $offset);

require_once __DIR__ . '/includes/header.php';
?>
<section class="panel">
    <div class="section-heading"><h2>Explorar recetas</h2></div>
    
    <?php if (empty($todas)): ?>
        <div class="empty-state"><p>No hay recetas disponibles en este momento.</p></div>
    <?php else: ?>
        <div class="recipe-grid">
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
        </div>

        <!-- CONTROLES DE PAGINACIÓN -->
        <?php if ($total_paginas > 1): ?>
            <div style="display: flex; justify-content: center; align-items: center; gap: 1rem; margin-top: 2.5rem;">
                <?php if ($pagina_actual > 1): ?>
                    <a href="?pagina=<?= $pagina_actual - 1 ?>" class="secondary" style="padding: 0.5rem 1rem; border-radius: 8px;">&laquo; Anterior</a>
                <?php endif; ?>

                <span style="font-weight: 700; color: var(--text-muted);">
                    Página <?= $pagina_actual ?> de <?= $total_paginas ?>
                </span>

                <?php if ($pagina_actual < $total_paginas): ?>
                    <a href="?pagina=<?= $pagina_actual + 1 ?>" class="secondary" style="padding: 0.5rem 1rem; border-radius: 8px;">Siguiente &raquo;</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>