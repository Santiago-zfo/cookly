<?php
// receta_detalle.php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/funciones_recetas.php';
requerirLogin();

$id = (int) ($_GET['id'] ?? 0);
$r = obtener_receta($id);
if (!$r) { header('Location: recetas.php'); exit; }

// Seguridad: Si la receta NO está aceptada, solo el dueño o el admin pueden verla
$esMia = ($r['id_usuario'] == $_SESSION['id_usuario']);
if ($r['estado'] !== 'Aceptado' && !$esMia && !esAdmin()) {
    header('Location: recetas.php'); 
    exit;
}

$ings = obtener_ingredientes_receta($id);
$titulo = $r['titulo'];

require_once __DIR__ . '/includes/header.php';
?>
<section class="panel">
    <div class="section-heading">
        <div>
            <div class="detail-meta">
                <span class="pill"><?= htmlspecialchars($r['categoria']) ?></span>
                <span class="difficulty-tag"><?= htmlspecialchars($r['dificultad']) ?></span>
                <?php if ($r['estado'] !== 'Aceptado'): ?>
                    <span class="difficulty-tag" style="background: #FEF3C7; color: #92400E;"><?= $r['estado'] ?></span>
                <?php endif; ?>
            </div>
            <h2><?= htmlspecialchars($r['titulo']) ?></h2>
        </div>
        <a href="index.php" class="logout-btn">← Volver</a>
    </div>
    
    <?php if (!empty($r['imagen_url'])): ?>
        <img src="<?= htmlspecialchars($r['imagen_url']) ?>" style="width: 100%; max-height: 400px; object-fit: cover; border-radius: 16px; margin-bottom: 2rem;">
    <?php endif; ?>

    <p style="color: var(--text-muted);">Por <strong><?= htmlspecialchars($r['autor']) ?></strong> | <?= $r['tiempo_preparacion'] ?> min | <?= $r['porciones'] ?> porciones</p>
    <p><?= nl2br(htmlspecialchars($r['descripcion'])) ?></p>
    
    <div class="detail-column">
        <div>
            <h5>Ingredientes</h5>
            <ul>
                <?php foreach ($ings as $i): ?>
                    <li><?= htmlspecialchars($i['nombre']) ?> - <?= $i['cantidad'] ?> <?= $i['unidad_medida'] ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div>
            <h5>Preparación</h5>
            <div class="step-text"><?= nl2br(htmlspecialchars($r['instrucciones'])) ?></div>
        </div>
    </div>
    
    <?php if ($esMia || esAdmin()): ?>
        <div class="acciones" style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid var(--border-color);">
            <?php if ($esMia): ?>
                <a href="receta_editar.php?id=<?= $id ?>" class="submit-btn">Editar</a>
                <a href="receta_eliminar.php?id=<?= $id ?>" class="logout-btn" style="color: var(--error); border-color: var(--error);" data-confirm="¿Seguro que quieres eliminar esta receta?">Eliminar</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>