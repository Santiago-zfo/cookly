<?php
// admin.php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/funciones_recetas.php';

// 1. Seguridad: Solo los administradores pueden entrar aquí
requerirAdmin();

$titulo = 'Panel de Administración';

// 2. Lógica para aprobar o rechazar recetas
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion_receta'])) {
    $id_receta = (int)$_POST['id_receta'];
    $accion = $_POST['accion_receta']; // 'Aceptado' o 'Rechazado'

    if (cambiar_estado_receta($id_receta, $accion)) {
        $mensaje = "La receta ha sido marcada como: <strong>" . htmlspecialchars($accion) . "</strong>.";
    }
}

// 3. Obtener datos para la vista
$stats = obtener_estadisticas_generales();
$pendientes = listar_recetas_pendientes();

require_once __DIR__ . '/includes/header.php';
?>

<section class="panel">
    <div class="section-heading">
        <h2>🛡️ Panel de Administración</h2>
    </div>

    <?php if ($mensaje): ?>
        <div class="estado exito" style="margin-bottom: 2rem;"><?= $mensaje ?></div>
    <?php endif; ?>

    <!-- ==========================================
         SECCIÓN 1: MODERACIÓN DE RECETAS
         ========================================== -->
    <h3 style="margin-bottom: 1rem; border-bottom: 2px solid var(--border-color); padding-bottom: 0.5rem; color: var(--text-main);">
        Recetas Pendientes de Aprobación
    </h3>
    
    <?php if (empty($pendientes)): ?>
        <div class="empty-state" style="padding: 2rem; margin-bottom: 3rem;">
            <p>No hay recetas pendientes por revisar. ¡Todo está al día! 🎉</p>
        </div>
    <?php else: ?>
        <div class="recipe-grid" style="margin-bottom: 3rem;">
            <?php foreach ($pendientes as $r): ?>
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
                            <span class="rating" style="color: #F59E0B;">Pendiente</span>
                        </div>
                        <h4><?= htmlspecialchars($r['titulo']) ?></h4>
                        <p style="font-size: 0.85rem;">Por: <strong><?= htmlspecialchars($r['autor']) ?></strong></p>
                        
                        <!-- Botones de Acción -->
                        <div style="display: flex; gap: 0.5rem; margin-top: auto; padding-top: 1rem;">
                            <a href="receta_detalle.php?id=<?= $r['id_receta'] ?>" target="_blank" class="secondary" style="flex: 1; text-align: center; padding: 0.5rem; font-size: 0.8rem;">Leer</a>
                            
                            <form method="POST" style="margin: 0; flex: 1;">
                                <input type="hidden" name="id_receta" value="<?= $r['id_receta'] ?>">
                                <input type="hidden" name="accion_receta" value="Aceptado">
                                <button type="submit" class="submit-btn" style="width: 100%; padding: 0.5rem; font-size: 0.8rem; background: var(--success); border-radius: 8px;">Aprobar</button>
                            </form>

                            <form method="POST" style="margin: 0; flex: 1;">
                                <input type="hidden" name="id_receta" value="<?= $r['id_receta'] ?>">
                                <input type="hidden" name="accion_receta" value="Rechazado">
                                <button type="submit" class="submit-btn" style="width: 100%; padding: 0.5rem; font-size: 0.8rem; background: var(--error); border-radius: 8px;">Rechazar</button>
                            </form>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- ==========================================
         SECCIÓN 2: REPORTES DE LA PÁGINA
         ========================================== -->
    <h3 style="margin-bottom: 1.5rem; border-bottom: 2px solid var(--border-color); padding-bottom: 0.5rem; color: var(--text-main);">
        📊 Reportes de la Página
    </h3>

    <!-- Tarjetas resumen -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div style="background: var(--primary-light); padding: 1.5rem; border-radius: var(--radius-md); text-align: center; border: 1px solid var(--primary);">
            <h3 style="margin: 0; color: var(--primary-hover); font-size: 2.5rem;"><?= $stats['total_usuarios'] ?></h3>
            <p style="margin: 0; font-weight: 700; font-size: 0.85rem; color: var(--primary-hover);">Usuarios Registrados</p>
        </div>
        <div style="background: #F0FDF4; padding: 1.5rem; border-radius: var(--radius-md); text-align: center; border: 1px solid #BBF7D0;">
            <h3 style="margin: 0; color: #166534; font-size: 2.5rem;"><?= $stats['total_recetas'] ?></h3>
            <p style="margin: 0; font-weight: 700; font-size: 0.85rem; color: #166534;">Recetas Creadas</p>
        </div>
        <div style="background: #EFF6FF; padding: 1.5rem; border-radius: var(--radius-md); text-align: center; border: 1px solid #BFDBFE;">
            <h3 style="margin: 0; color: #1E3A8A; font-size: 2.5rem;"><?= $stats['total_categorias'] ?></h3>
            <p style="margin: 0; font-weight: 700; font-size: 0.85rem; color: #1E3A8A;">Categorías Activas</p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
        
        <!-- Recetas por Categoría -->
        <div style="background: var(--bg-color); padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
            <h4 style="margin-top: 0; color: var(--text-main); font-size: 1.1rem;">Recetas por Categoría</h4>
            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1rem;">
                <?php 
                $max_cat = 0;
                foreach($stats['recetas_por_categoria'] as $c) { if ($c['cantidad'] > $max_cat) $max_cat = $c['cantidad']; }
                
                foreach ($stats['recetas_por_categoria'] as $c): 
                    $porcentaje = $max_cat > 0 ? ($c['cantidad'] / $max_cat) * 100 : 0;
                ?>
                    <li>
                        <div style="display: flex; justify-content: space-between; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">
                            <span><?= htmlspecialchars($c['nombre']) ?></span>
                            <span><?= $c['cantidad'] ?></span>
                        </div>
                        <div style="width: 100%; background: var(--border-color); height: 8px; border-radius: 4px; overflow: hidden;">
                            <div style="width: <?= $porcentaje ?>%; background: var(--primary); height: 100%; border-radius: 4px;"></div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Recetas por Dificultad -->
        <div style="background: var(--bg-color); padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
            <h4 style="margin-top: 0; color: var(--text-main); font-size: 1.1rem;">Recetas por Dificultad</h4>
            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.8rem;">
                <?php foreach ($stats['recetas_por_dificultad'] as $d): ?>
                    <li style="display: flex; justify-content: space-between; align-items: center; padding: 0.8rem; background: var(--surface); border-radius: 8px; border: 1px solid var(--border-color);">
                        <span style="font-weight: 700; color: var(--text-main);"><?= htmlspecialchars($d['dificultad']) ?></span>
                        <span class="pill" style="background: var(--surface); border: 1px solid var(--border-color); margin: 0;"><?= $d['cantidad'] ?> recetas</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Top Usuarios -->
        <div style="background: var(--bg-color); padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
            <h4 style="margin-top: 0; color: var(--text-main); font-size: 1.1rem;">🏆 Top Usuarios</h4>
            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.8rem;">
                <?php foreach ($stats['top_usuarios'] as $index => $u): ?>
                    <li style="display: flex; align-items: center; gap: 1rem; padding: 0.8rem; background: var(--surface); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="width: 30px; height: 30px; background: var(--primary-light); color: var(--primary-hover); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800;">
                            <?= $index + 1 ?>
                        </div>
                        <div style="flex: 1; font-weight: 700; color: var(--text-main); font-size: 0.9rem;">
                            <?= htmlspecialchars($u['nombre']) ?>
                        </div>
                        <div style="font-weight: 800; color: var(--primary);">
                            <?= $u['cantidad'] ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>