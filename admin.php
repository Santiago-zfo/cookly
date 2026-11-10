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

<!-- ESTILOS ESPECIALES PARA IMPRIMIR PDF -->
<style>
    @media print {
        .topbar, .no-print, .acciones, .site-footer { display: none !important; }
        body, .page-shell, .panel { background: white !important; padding: 0 !important; margin: 0 !important; box-shadow: none !important; border: none !important; }
        .panel { padding: 20px !important; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2, h3 { color: black !important; }
        .chart-box { page-break-inside: avoid; }
    }
</style>

<!-- Cargar Google Charts -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawCharts);

    function drawCharts() {
        // Gráfico 1: Pastel (Recetas por Categoría)
        var dataCat = google.visualization.arrayToDataTable([
            ['Categoría', 'Cantidad'],
            <?php foreach($stats['recetas_por_categoria'] as $c): ?>
                ['<?= addslashes($c['nombre']) ?>', <?= (int)$c['cantidad'] ?>],
            <?php endforeach; ?>
        ]);
        var optionsCat = {
            title: 'Distribución de Recetas por Categoría',
            pieHole: 0.4,
            colors: ['#0F766E', '#14B8A6', '#5EEAD4', '#CCFBF1', '#F59E0B', '#EF4444', '#3B82F6'],
            chartArea: {width: '90%', height: '80%'},
            legend: {position: 'bottom'}
        };
        var chartCat = new google.visualization.PieChart(document.getElementById('chart_categoria'));
        chartCat.draw(dataCat, optionsCat);

        // Gráfico 2: Barras (Recetas por Dificultad)
        var dataDif = google.visualization.arrayToDataTable([
            ['Dificultad', 'Cantidad'],
            <?php foreach($stats['recetas_por_dificultad'] as $d): ?>
                ['<?= addslashes($d['dificultad']) ?>', <?= (int)$d['cantidad'] ?>],
            <?php endforeach; ?>
        ]);
        var optionsDif = {
            title: 'Recetas según su Dificultad',
            colors: ['#F59E0B'],
            chartArea: {width: '60%'},
            hAxis: {title: 'Cantidad', minValue: 0},
            vAxis: {title: 'Dificultad'},
            legend: {position: 'none'}
        };
        var chartDif = new google.visualization.BarChart(document.getElementById('chart_dificultad'));
        chartDif.draw(dataDif, optionsDif);
    }
</script>

<section class="panel">
    <div class="section-heading">
        <h2>🛡️ Panel de Administración</h2>
        <div class="no-print" style="display: flex; gap: 1rem;">
            <!-- Este botón nos lleva a la nueva vista -->
            <a href="reportes.php" class="submit-btn" style="background: #3B82F6;">📊 Ir a Reportes Detallados</a>
            <a href="exportar_excel.php" class="submit-btn" style="background: #10B981;">Descargar Excel Global</a>
        </div>
    </div>

    <?php if ($mensaje): ?>
        <div class="estado exito no-print" style="margin-bottom: 2rem;"><?= $mensaje ?></div>
    <?php endif; ?>

    <!-- SECCIÓN 1: MODERACIÓN -->
    <div class="no-print">
        <h3 style="margin-bottom: 1rem; border-bottom: 2px solid var(--border-color); padding-bottom: 0.5rem; color: var(--text-main);">Recetas Pendientes de Aprobación</h3>
        
        <?php if (empty($pendientes)): ?>
            <div class="empty-state" style="padding: 2rem; margin-bottom: 3rem;"><p>No hay recetas pendientes por revisar. ¡Todo está al día! 🎉</p></div>
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
                            <div class="acciones" style="display: flex; gap: 0.5rem; margin-top: auto; padding-top: 1rem;">
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
    </div>

    <!-- SECCIÓN 2: REPORTES -->
    <h3 style="margin-bottom: 1.5rem; border-bottom: 2px solid var(--border-color); padding-bottom: 0.5rem; color: var(--text-main);">📊 Reportes y Gráficas</h3>

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

    <!-- GRÁFICAS VISUALES -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 3rem;">
        <div class="chart-box" style="background: var(--surface); padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
            <div id="chart_categoria" style="width: 100%; height: 300px;"></div>
        </div>
        <div class="chart-box" style="background: var(--surface); padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
            <div id="chart_dificultad" style="width: 100%; height: 300px;"></div>
        </div>
    </div>

    <!-- TABLAS DETALLADAS -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
        <!-- Tabla Categorías -->
        <div style="background: var(--bg-color); padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
            <h4 style="margin-top: 0; color: var(--text-main); font-size: 1.1rem; margin-bottom: 1rem;">Recetas por Categoría</h4>
            <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                <thead><tr style="border-bottom: 2px solid var(--border-color); text-align: left;"><th style="padding: 0.5rem 0;">Categoría</th><th style="padding: 0.5rem 0; text-align: right;">Cantidad</th></tr></thead>
                <tbody>
                    <?php foreach ($stats['recetas_por_categoria'] as $c): ?>
                        <tr style="border-bottom: 1px solid var(--border-color);"><td style="padding: 0.75rem 0;"><?= htmlspecialchars($c['nombre']) ?></td><td style="padding: 0.75rem 0; text-align: right; font-weight: 700; color: var(--primary);"><?= $c['cantidad'] ?></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- Tabla Dificultad -->
        <div style="background: var(--bg-color); padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
            <h4 style="margin-top: 0; color: var(--text-main); font-size: 1.1rem; margin-bottom: 1rem;">Recetas por Dificultad</h4>
            <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                <thead><tr style="border-bottom: 2px solid var(--border-color); text-align: left;"><th style="padding: 0.5rem 0;">Dificultad</th><th style="padding: 0.5rem 0; text-align: right;">Cantidad</th></tr></thead>
                <tbody>
                    <?php foreach ($stats['recetas_por_dificultad'] as $d): ?>
                        <tr style="border-bottom: 1px solid var(--border-color);"><td style="padding: 0.75rem 0;"><?= htmlspecialchars($d['dificultad']) ?></td><td style="padding: 0.75rem 0; text-align: right; font-weight: 700; color: var(--primary);"><?= $d['cantidad'] ?></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- Tabla Top Usuarios -->
        <div style="background: var(--bg-color); padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
            <h4 style="margin-top: 0; color: var(--text-main); font-size: 1.1rem; margin-bottom: 1rem;">Top Usuarios</h4>
            <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                <thead><tr style="border-bottom: 2px solid var(--border-color); text-align: left;"><th style="padding: 0.5rem 0;">Usuario</th><th style="padding: 0.5rem 0; text-align: right;">Recetas</th></tr></thead>
                <tbody>
                    <?php foreach ($stats['top_usuarios'] as $index => $u): ?>
                        <tr style="border-bottom: 1px solid var(--border-color);"><td style="padding: 0.75rem 0;"><strong><?= $index + 1 ?>.</strong> <?= htmlspecialchars($u['nombre']) ?></td><td style="padding: 0.75rem 0; text-align: right; font-weight: 700; color: var(--primary);"><?= $u['cantidad'] ?></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>