<?php
// exportar_excel.php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/funciones_recetas.php';
requerirAdmin();

$stats = obtener_estadisticas_generales();

// Cabeceras para descargar Excel
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=Reporte_Cookly_" . date('Y-m-d') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

// Iniciar HTML con estilos para Excel
echo "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:x='urn:schemas-microsoft-com:office:excel' xmlns='http://www.w3.org/TR/REC-html40'>";
echo "<head>";
echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
echo "<style>";
echo "  table { border-collapse: collapse; font-family: Arial, sans-serif; font-size: 11pt; margin-bottom: 20px; }";
echo "  th { background-color: #0F766E; color: #FFFFFF; font-weight: bold; border: 1px solid #CCCCCC; padding: 8px; text-align: left; }";
echo "  td { border: 1px solid #CCCCCC; padding: 8px; }";
echo "  .titulo { font-size: 18pt; font-weight: bold; color: #1F2937; margin-bottom: 10px; }";
echo "  .subtitulo { font-size: 14pt; font-weight: bold; color: #0F766E; margin-top: 20px; margin-bottom: 10px; }";
echo "  .num { text-align: center; font-weight: bold; }";
echo "  .chart-container { text-align: center; margin: 30px 0; }";
echo "</style>";
echo "</head><body>";

echo "<div class='titulo'>Reporte General - Cookly (" . date('d/m/Y') . ")</div>";

// 1. Tabla de Resumen
echo "<div class='subtitulo'>Resumen del Sistema</div>";
echo "<table>";
echo "<tr><th style='width: 200px;'>Métrica</th><th style='width: 100px;'>Total</th></tr>";
echo "<tr><td>Usuarios Registrados</td><td class='num'>" . $stats['total_usuarios'] . "</td></tr>";
echo "<tr><td>Recetas Creadas</td><td class='num'>" . $stats['total_recetas'] . "</td></tr>";
echo "<tr><td>Categorías Activas</td><td class='num'>" . $stats['total_categorias'] . "</td></tr>";
echo "</table>";

// 2. Tabla de Categorías
echo "<div class='subtitulo'>Recetas por Categoría</div>";
echo "<table>";
echo "<tr><th style='width: 250px;'>Categoría</th><th style='width: 150px;'>Cantidad de Recetas</th></tr>";
foreach ($stats['recetas_por_categoria'] as $c) {
    echo "<tr><td>" . htmlspecialchars($c['nombre']) . "</td><td class='num'>" . $c['cantidad'] . "</td></tr>";
}
echo "</table>";

// 3. Tabla de Dificultad
echo "<div class='subtitulo'>Recetas por Dificultad</div>";
echo "<table>";
echo "<tr><th style='width: 250px;'>Dificultad</th><th style='width: 150px;'>Cantidad de Recetas</th></tr>";
foreach ($stats['recetas_por_dificultad'] as $d) {
    echo "<tr><td>" . htmlspecialchars($d['dificultad']) . "</td><td class='num'>" . $d['cantidad'] . "</td></tr>";
}
echo "</table>";

// 4. Tabla de Top Usuarios
echo "<div class='subtitulo'>Top Usuarios (Más recetas)</div>";
echo "<table>";
echo "<tr><th style='width: 250px;'>Usuario</th><th style='width: 150px;'>Recetas Publicadas</th></tr>";
foreach ($stats['top_usuarios'] as $index => $u) {
    echo "<tr><td>" . ($index + 1) . ". " . htmlspecialchars($u['nombre']) . "</td><td class='num'>" . $u['cantidad'] . "</td></tr>";
}
echo "</table>";

echo "</body></html>";
exit;
?>