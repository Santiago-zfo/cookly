<?php
// reportes.php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/funciones_recetas.php';
requerirLogin();

$titulo = 'Reportes y Estadísticas';
$stats = obtener_estadisticas_generales();

require_once __DIR__ . '/includes/header.php';
?>

<section class="panel">
    <div class="section-heading">
        <h2>📊 Reportes del Sistema</h2>
        <a href="index.php" class="logout-btn">← Volver</a>
    </div>

    <!-- TARJETAS DE RESUMEN (KPIs) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div style="background: var(--primary-light); padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--primary); text-align: center;">
            <h3 style="margin: 0; color: var(--primary-hover); font-size: 2.5rem;"><?= $stats['total_usuarios'] ?></h3>
            <p style="margin: 0.5rem 0 0; color: var(--primary-hover); font-weight: 700; text-transform: uppercase; font-size: 0.85rem;">Usuarios Registrados</p>
        </div>

        <div style="background: #F0FDF4; padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid #BBF7D0; text-align: center;">
            <h3 style="margin: 0; color: #166534; font-size: 2.5rem;"><?= $stats['total_recetas'] ?></h3>
            <p style="margin: 0.5rem 0 0; color: #166534; font-weight: 700; text-transform: uppercase; font-size: 0.85rem;">Recetas Publicadas</p>
        </div>

        <div style="background: #EFF6FF; padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid #BFDBFE; text-align: center;">
            <h3 style="margin: 0; color: #1E3A8A; font-size: 2.5rem;"><?= $stats['total_categorias'] ?></h3>
            <p style="margin: 0.5rem 0 0; color: #1E3A8A; font-weight: 700; text-transform: uppercase; font-size: 0.85rem;">Categorías Activas</p>
        </div>
    </div>

    <!-- TABLAS DE DETALLE -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
        
        <!-- Recetas por Categoría -->
        <div style="background: var(--bg-color); padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
            <h3 style="margin-top: 0; color: var(--text-main); font-size: 1.2rem;">Recetas por Categoría</h3>
            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1rem;">
                <?php 
                $max_cat = 0;
                foreach($stats['recetas_por_categoria'] as $c) { if ($c['cantidad'] > $max_cat) $max_cat = $c['cantidad']; }
                
                foreach ($stats['recetas_por_categoria'] as $c): 
                    $porcentaje = $max_cat > 0 ? ($c['cantidad'] / $max_cat) * 100 : 0;
                ?>
                    <li>
                        <div style="display: flex; justify-content: space-between; font-size: 0.9rem; font-weight: 700; margin-bottom: 0.3rem;">
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
            <h3 style="margin-top: 0; color: var(--text-main); font-size: 1.2rem;">Recetas por Dificultad</h3>
            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1rem;">
                <?php foreach ($stats['recetas_por_dificultad'] as $d): ?>
                    <li style="display: flex; justify-content: space-between; align-items: center; padding: 0.8rem; background: var(--surface); border-radius: 8px; border: 1px solid var(--border-color);">
                        <span style="font-weight: 700; color: var(--text-main);"><?= htmlspecialchars($d['dificultad']) ?></span>
                        <span class="pill" style="background: var(--surface); border: 1px solid var(--border-color);"><?= $d['cantidad'] ?> recetas</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Top Usuarios -->
        <div style="background: var(--bg-color); padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
            <h3 style="margin-top: 0; color: var(--text-main); font-size: 1.2rem;">🏆 Top Usuarios (Más recetas)</h3>
            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1rem;">
                <?php foreach ($stats['top_usuarios'] as $index => $u): ?>
                    <li style="display: flex; align-items: center; gap: 1rem; padding: 0.8rem; background: var(--surface); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="width: 30px; height: 30px; background: var(--primary-light); color: var(--primary-hover); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800;">
                            <?= $index + 1 ?>
                        </div>
                        <div style="flex: 1; font-weight: 700; color: var(--text-main);">
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