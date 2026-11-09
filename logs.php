<?php
// logs.php
require_once __DIR__ . '/includes/auth.php';
requerirAdmin(); // Bloquea el acceso a usuarios normales

$titulo = 'Registro de Logs';
require_once __DIR__ . '/includes/header.php';

$rutaLog = __DIR__ . '/logs/sistema.log';
$logs = [];

if (file_exists($rutaLog)) {
    $lineas = file($rutaLog, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $logs = array_reverse($lineas); // Los más nuevos primero
}
?>

<section class="panel">
    <div class="section-heading">
        <h2>📋 Registro del Sistema (Logs)</h2>
        <a href="<?= $base_url ?>/admin.php" class="logout-btn">← Volver al Admin</a>
    </div>

    <?php if (empty($logs)): ?>
        <div class="empty-state"><p>No hay registros de errores o movimientos todavía.</p></div>
    <?php else: ?>
        <div style="background: #1e1e1e; color: #d4d4d4; padding: 1.5rem; border-radius: var(--radius-md); font-family: monospace; font-size: 0.85rem; overflow-x: auto; max-height: 600px; overflow-y: auto; line-height: 1.5;">
            <?php foreach ($logs as $linea): ?>
                <?php 
                    $color = '#d4d4d4'; 
                    if (str_contains($linea, '[ERROR]')) $color = '#F87171'; 
                    if (str_contains($linea, '[CRITICAL]')) $color = '#EF4444'; 
                    if (str_contains($linea, '[WARNING]')) $color = '#FBBF24'; 
                    if (str_contains($linea, '[INFO]')) $color = '#34D399'; 
                ?>
                <div style="color: <?= $color ?>; margin-bottom: 4px;">
                    <?= htmlspecialchars($linea) ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>