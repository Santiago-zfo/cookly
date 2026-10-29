<?php
// ajax_buscar.php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/funciones_recetas.php';
requerirLogin();

header('Content-Type: application/json');

$q = trim($_GET['q'] ?? '');

if ($q === '') {
    echo json_encode(['html' => '']);
    exit;
}

$resultados = buscar_recetas_ajax($q);

if (empty($resultados)) {
    echo json_encode(['html' => '<div class="empty-state" style="grid-column: 1/-1;"><p>🔍 No se encontraron recetas para "' . htmlspecialchars($q) . '".</p></div>']);
    exit;
}

$html = '';
foreach ($resultados as $r) {
    $imagen = !empty($r['imagen_url']) 
        ? '<img src="' . htmlspecialchars($r['imagen_url']) . '" alt="Imagen">' 
        : '🍲';
        
    $html .= '
    <article class="recipe-card">
        <div class="recipe-image-wrap">
            ' . $imagen . '
        </div>
        <div class="recipe-body">
            <div class="recipe-topline">
                <span class="pill">' . htmlspecialchars($r['categoria']) . '</span>
            </div>
            <h4>' . htmlspecialchars($r['titulo']) . '</h4>
            <p>Por <strong>' . htmlspecialchars($r['autor']) . '</strong></p>
            <div class="acciones">
                <a href="receta_detalle.php?id=' . $r['id_receta'] . '">Ver</a>
            </div>
        </div>
    </article>';
}

echo json_encode(['html' => $html]);