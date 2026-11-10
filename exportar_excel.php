<?php
// exportar_excel.php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/funciones_recetas.php';
requerirAdmin();

$filtros = [
    'categoria' => $_GET['categoria'] ?? '',
    'fecha_inicio' => $_GET['fecha_inicio'] ?? '',
    'fecha_fin' => $_GET['fecha_fin'] ?? '',
    'autor' => $_GET['autor'] ?? ''
];

$recetas = obtener_recetas_filtradas($filtros);

// Cabeceras para forzar la descarga de un archivo CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=Reporte_Recetas_' . date('Y-m-d') . '.csv');
header('Pragma: no-cache');
header('Expires: 0');

// Abrir la salida de PHP
$output = fopen('php://output', 'w');

// Escribir el BOM UTF-8 para que Excel detecte tildes y eñes
fwrite($output, "\xEF\xBB\xBF");

// Cabeceras (Usamos ; porque es el separador por defecto en Excel en español)
fputcsv($output, [
    'ID', 'Fecha de Creación', 'Título', 'Categoría', 'Autor', 'Dificultad', 'Porciones', 'Estado'
], ';');

// Filas de datos
foreach ($recetas as $r) {
    fputcsv($output, [
        $r['id_receta'],
        date('d/m/Y H:i', strtotime($r['fecha_creacion'])),
        $r['titulo'],
        $r['categoria'],
        $r['autor'],
        $r['dificultad'],
        $r['porciones'],
        $r['estado']
    ], ';');
}

fclose($output);
exit;
?>