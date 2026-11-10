<?php
// reportes.php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/funciones_recetas.php';
requerirAdmin();

$titulo = 'Reportes y Estadísticas';

$filtros = [
    'categoria' => $_GET['categoria'] ?? '',
    'fecha_inicio' => $_GET['fecha_inicio'] ?? '',
    'fecha_fin' => $_GET['fecha_fin'] ?? '',
    'autor' => $_GET['autor'] ?? ''
];

$categorias = listar_categorias();
$recetas_filtradas = obtener_recetas_filtradas($filtros);

require_once __DIR__ . '/includes/header.php';
?>

<!-- ESTILOS PARA LA IMPRESIÓN EN PDF -->
<style>
    @media print {
        .topbar, .site-footer, .no-print, form, .logout-btn, h2 button { 
            display: none !important; 
        }
        body, .page-shell, .panel { 
            background: white !important; 
            box-shadow: none !important; 
            padding: 0 !important; 
            margin: 0 !important; 
        }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; font-size: 12px; }
        th { background-color: #f2f2f2 !important; -webkit-print-color-adjust: exact; }
        h2 { text-align: center; margin-bottom: 10px; }
        .print-header { display: block !important; text-align: center; margin-bottom: 20px; }
    }
    .print-header { display: none; }
</style>

<section class="panel">
    <div class="section-heading no-print">
        <h2>📊 Reportes del Sistema</h2>
        <a href="index.php" class="logout-btn">← Volver al Inicio</a>
    </div>

    <!-- ENCABEZADO QUE SOLO APARECE AL IMPRIMIR -->
    <div class="print-header">
        <h2>Reporte de Recetas - Cookly</h2>
        <p>Generado el: <?= date('d/m/Y H:i') ?></p>
        <p>
            Filtros: Categoría (<?= $filtros['categoria'] ?: 'Todas' ?>) | 
            Desde (<?= $filtros['fecha_inicio'] ?: 'N/A' ?>) | 
            Hasta (<?= $filtros['fecha_fin'] ?: 'N/A' ?>) | 
            Autor (<?= $filtros['autor'] ?: 'Todos' ?>)
        </p>
        <hr>
    </div>

    <!-- FORMULARIO DE FILTROS -->
    <form method="GET" class="no-print" style="background: var(--bg-color); padding: 1.5rem; border-radius: var(--radius-md); margin-bottom: 2rem; display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
        <div style="flex: 1; min-width: 200px;">
            <label style="font-weight: bold; font-size: 0.9rem;">Categoría</label>
            <select name="categoria" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-color);">
                <option value="">Todas las categorías</option>
                <?php foreach ($categorias as $c): ?>
                    <option value="<?= $c['id_categoria'] ?>" <?= $filtros['categoria'] == $c['id_categoria'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="flex: 1; min-width: 150px;">
            <label style="font-weight: bold; font-size: 0.9rem;">Desde Fecha</label>
            <input type="date" name="fecha_inicio" value="<?= htmlspecialchars($filtros['fecha_inicio']) ?>" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-color);">
        </div>

        <div style="flex: 1; min-width: 150px;">
            <label style="font-weight: bold; font-size: 0.9rem;">Hasta Fecha</label>
            <input type="date" name="fecha_fin" value="<?= htmlspecialchars($filtros['fecha_fin']) ?>" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-color);">
        </div>

        <div style="flex: 1; min-width: 200px;">
            <label style="font-weight: bold; font-size: 0.9rem;">Autor</label>
            <input type="text" name="autor" placeholder="Ej. Santiago" value="<?= htmlspecialchars($filtros['autor']) ?>" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-color);">
        </div>

        <div style="display: flex; gap: 0.5rem;">
            <button type="submit" class="submit-btn">Filtrar</button>
            <a href="reportes.php" class="secondary" style="padding: 0.8rem; text-decoration: none; border-radius: 8px; background: white; border: 1px solid var(--border-color); color: var(--text-main); font-weight: bold;">Limpiar</a>
        </div>
    </form>

    <!-- RESULTADOS Y EXPORTACIÓN -->
    <div class="section-heading no-print" style="margin-top: 2rem;">
        <h3>Resultados (<?= count($recetas_filtradas) ?> recetas encontradas)</h3>
        <div style="display: flex; gap: 1rem;">
            <?php 
            $queryString = http_build_query($filtros);
            $url_excel = "exportar_excel.php?" . $queryString; 
            ?>
            <a href="<?= $url_excel ?>" class="submit-btn" style="background: #10B981;">📥 Descargar Excel (CSV)</a>
            <button onclick="window.print()" class="submit-btn" style="background: #EF4444;">📄 Descargar PDF</button>
        </div>
    </div>

    <!-- TABLA DE RESULTADOS AMPLIADA -->
    <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem; margin-top: 1rem;">
        <thead>
            <tr style="background: var(--primary); color: white; text-align: left;">
                <th style="padding: 10px;">Fecha</th>
                <th style="padding: 10px;">Título</th>
                <th style="padding: 10px;">Categoría</th>
                <th style="padding: 10px;">Autor</th>
                <th style="padding: 10px;">Dificultad</th>
                <th style="padding: 10px; text-align: center;">Porciones</th>
                <th style="padding: 10px;">Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($recetas_filtradas)): ?>
                <tr><td colspan="7" style="text-align: center; padding: 2rem;">No hay recetas que coincidan con los filtros.</td></tr>
            <?php else: ?>
                <?php foreach ($recetas_filtradas as $r): ?>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 10px;"><?= date('d/m/Y', strtotime($r['fecha_creacion'])) ?></td>
                        <td style="padding: 10px; font-weight: bold;"><?= htmlspecialchars($r['titulo']) ?></td>
                        <td style="padding: 10px;"><?= htmlspecialchars($r['categoria']) ?></td>
                        <td style="padding: 10px;"><?= htmlspecialchars($r['autor']) ?></td>
                        <td style="padding: 10px;"><?= htmlspecialchars($r['dificultad']) ?></td>
                        <td style="padding: 10px; text-align: center;"><?= $r['porciones'] ?></td>
                        <td style="padding: 10px;">
                            <span class="pill" style="background: <?= $r['estado'] === 'Aceptado' ? '#D1FAE5' : ($r['estado'] === 'Rechazado' ? '#FEE2E2' : '#FEF3C7') ?>; color: <?= $r['estado'] === 'Aceptado' ? '#065F46' : ($r['estado'] === 'Rechazado' ? '#991B1B' : '#92400E') ?>;">
                                <?= $r['estado'] ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>