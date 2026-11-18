<?php
/**
 * app/Controllers/SystemController.php
 * Registro del sistema (logs).
 */
final class SystemController extends Controller
{
    public function logs(): void
    {
        requerirAdmin();

        $rutaLog = cookly_app_root() . '/logs/sistema.log';
        $logs = [];

        if (file_exists($rutaLog)) {
            $lineas = file($rutaLog, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $logs = array_reverse($lineas); // Los más nuevos primero
        }

        $this->render('pages/logs', [
            'logs' => $logs,
        ], ['titulo' => 'Registro de Logs']);
    }
}
