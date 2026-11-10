<?php
/** Descarga real del PDF incluido; no requiere librerías ni conexión a MySQL. */
require_once __DIR__ . '/includes/auth.php';
$archivo = __DIR__ . '/public/docs/Manual_de_Usuario_Cookly.pdf';
if (!is_file($archivo) || !is_readable($archivo)) {
    http_response_code(503);
    header('Content-Type: text/plain; charset=utf-8');
    exit('El PDF no está disponible. Puedes abrir manual.php e imprimir el manual. Contacta al administrador.');
}
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="Manual_de_Usuario_Cookly.pdf"');
header('Content-Length: ' . filesize($archivo));
header('X-Content-Type-Options: nosniff');
readfile($archivo);
exit;
