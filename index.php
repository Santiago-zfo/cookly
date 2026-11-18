<?php
/**
 * index.php — punto de entrada público que permanece en la raíz.
 * Las pantallas y endpoints viven en public/pages/.
 */
require_once __DIR__ . '/includes/paths.php';

header('Location: ' . cookly_page('index.php'), true, 302);
exit;
