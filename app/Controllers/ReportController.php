<?php
/**
 * app/Controllers/ReportController.php
 * Reportes estadísticos y exportación a Excel.
 */
final class ReportController extends Controller
{
    /** reportes.php */
    public function index(): void
    {
        requerirLogin();

        $this->render('reports/index', [
            'stats' => obtener_estadisticas_generales(),
        ], ['titulo' => 'Reportes y Estadísticas']);
    }

    /** exportar_excel.php — descarga directa, sin layout HTML. */
    public function excel(): void
    {
        requerirAdmin();

        $stats = obtener_estadisticas_generales();

        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=Reporte_Cookly_" . date('Y-m-d') . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $this->view->partial('reports/excel', ['stats' => $stats]);
    }
}
