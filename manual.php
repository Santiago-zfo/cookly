<?php
/** Manual público, sin consultas ni cambios en la base de datos. */
require_once __DIR__ . '/includes/auth.php';
$manual = json_decode(file_get_contents(__DIR__ . '/includes/manual_contenido.json'), true, 512, JSON_THROW_ON_ERROR);
$titulo = 'Manual de Usuario';
$estilosPagina = ['public/css/manual.css'];
$scriptsPagina = ['public/JS/manual.js'];
$claseBody = 'manual-body';
function manualTexto(string $texto): string {
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}
function manualFigura(array $imagen): void {
    static $numero = 0;
    $numero++;
    $ruta = 'public/IMG/manual/' . $imagen['archivo'];
    ?>
    <figure class="manual-figure">
        <a class="manual-image-link" href="<?= manualTexto($ruta) ?>" data-manual-image
           data-caption="<?= manualTexto($imagen['descripcion']) ?>" aria-label="Ampliar: <?= manualTexto($imagen['alt']) ?>">
            <img src="<?= manualTexto($ruta) ?>" alt="<?= manualTexto($imagen['alt']) ?>" loading="lazy" decoding="async"
                 width="<?= (int)$imagen['ancho'] ?>" height="<?= (int)$imagen['alto'] ?>">
            <span class="manual-image-zoom" aria-hidden="true">↗ Ampliar captura</span>
        </a>
        <figcaption>Figura <?= $numero ?>. <?= manualTexto($imagen['descripcion']) ?></figcaption>
    </figure>
    <?php
}
require __DIR__ . '/includes/header.php';
?>
<a class="manual-skip" href="#manual-contenido">Saltar al contenido del manual</a>
<div class="manual-root" id="manual-portada">
    <header class="manual-hero">
        <div>
            <span class="manual-eyebrow">COOKLY / CENTRO DE AYUDA</span>
            <h1>Manual de Usuario</h1>
            <p class="manual-hero-lead">Tu recetario, paso a paso.</p>
            <p>Aprende a crear, organizar y compartir tus recetas. Todo lo que necesitas, en un solo lugar.</p>
            <div class="manual-cover-meta">
                <span>Edición <?= manualTexto($manual['version']) ?></span>
                <span><?= manualTexto($manual['fecha_visible']) ?></span>
                <span>Por <?= manualTexto($manual['autor']) ?></span>
            </div>
            <a class="manual-start" href="#registro">Empieza con tu cuenta <span aria-hidden="true">→</span></a>
        </div>
        <img class="manual-cover-logo" src="public/IMG/logo.png" alt="Cookly, plataforma de recetas" width="220" height="220">
    </header>
    <div class="manual-tools" role="search" aria-label="Buscar capítulos del manual">
        <div class="manual-search-wrap">
            <label for="manual-busqueda">Buscar en el manual</label>
            <div class="manual-search-field"><span aria-hidden="true">⌕</span><input id="manual-busqueda" type="search" placeholder="Prueba: ingredientes, contraseña, editar…" autocomplete="off" aria-controls="manual-contenido manual-indice"><button type="button" id="manual-limpiar" hidden aria-label="Borrar la búsqueda">×</button></div>
        </div>
        <a class="manual-button manual-button-primary" href="manual_descargar.php" download="Manual_de_Usuario_Cookly.pdf">↓ Descargar PDF completo</a>
        <button class="manual-button" type="button" id="manual-imprimir" hidden>Imprimir</button>
    </div>
    <p class="manual-search-status" id="manual-estado" role="status" aria-live="polite"><?= count($manual['capitulos']) ?> capítulos · Capturas reales con datos de prueba · Haz clic en una imagen para ampliarla.</p>
    <noscript><p class="manual-note">Puedes leer el manual completo y abrir sus imágenes. Para usar el buscador y el visor de imágenes, activa JavaScript. La descarga del PDF no necesita JavaScript.</p></noscript>
    <div class="manual-layout">
        <aside class="manual-sidebar" aria-label="Índice del manual">
            <div class="manual-sidebar-heading"><strong>En esta guía</strong><button type="button" id="manual-toggle-indice" aria-controls="manual-indice" aria-expanded="true" hidden>Ocultar índice</button></div>
            <nav id="manual-indice" aria-label="Capítulos"><ol>
                <?php foreach ($manual['capitulos'] as $i => $capitulo): ?>
                <li data-index-item="<?= manualTexto($capitulo['id']) ?>"><a href="#<?= manualTexto($capitulo['id']) ?>"><span class="manual-index-number" aria-hidden="true"><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></span><span><?= manualTexto($capitulo['titulo']) ?></span></a></li>
                <?php endforeach; ?>
            </ol></nav>
            <a class="manual-home" href="index.php">← Volver al inicio</a>
        </aside>
        <div class="manual-content" id="manual-contenido" tabindex="-1">
            <div class="manual-empty" id="manual-sin-resultados" hidden><h2>No encontramos ese tema</h2><p>Prueba con otra palabra, como «receta», «perfil» o «categoría».</p><button type="button" class="manual-button" data-manual-reset>Mostrar todo el manual</button></div>
            <?php foreach ($manual['capitulos'] as $i => $capitulo): ?>
            <section class="manual-chapter" id="<?= manualTexto($capitulo['id']) ?>" data-keywords="<?= manualTexto(implode(' ', $capitulo['claves'] ?? [])) ?>" aria-labelledby="titulo-<?= manualTexto($capitulo['id']) ?>">
                <div class="manual-chapter-heading"><span class="manual-chapter-number" aria-hidden="true"><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></span><div><p class="manual-eyebrow"><?= manualTexto($capitulo['grupo']) ?></p><h2 id="titulo-<?= manualTexto($capitulo['id']) ?>"><?= manualTexto($capitulo['titulo']) ?></h2></div></div>
                <p class="manual-lead"><?= manualTexto($capitulo['intro']) ?></p>
                <?php foreach ($capitulo['bloques'] as $bloque): ?>
                    <?php if ($bloque['tipo'] === 'paso'): ?>
                        <div class="manual-step"><h3><?= manualTexto($bloque['titulo']) ?></h3><p><?= nl2br(manualTexto($bloque['texto'])) ?></p></div>
                    <?php elseif ($bloque['tipo'] === 'nota'): ?>
                        <aside class="manual-note <?= ($bloque['nivel'] ?? '') === 'aviso' ? 'manual-note-warning' : '' ?>"><strong><?= manualTexto($bloque['titulo']) ?></strong><p><?= manualTexto($bloque['texto']) ?></p></aside>
                    <?php elseif ($bloque['tipo'] === 'texto'): ?>
                        <?php if (!empty($bloque['titulo'])): ?><h3><?= manualTexto($bloque['titulo']) ?></h3><?php endif; ?>
                        <p><?= nl2br(manualTexto($bloque['texto'])) ?></p>
                    <?php elseif ($bloque['tipo'] === 'tabla'): ?>
                        <div class="manual-table-wrap" role="region" aria-label="<?= manualTexto($bloque['titulo']) ?>" tabindex="0"><table><caption><?= manualTexto($bloque['titulo']) ?></caption><thead><tr><?php foreach ($bloque['columnas'] as $columna): ?><th scope="col"><?= manualTexto($columna) ?></th><?php endforeach; ?></tr></thead><tbody><?php foreach ($bloque['filas'] as $fila): ?><tr><?php foreach ($fila as $celda): ?><td><?= manualTexto($celda) ?></td><?php endforeach; ?></tr><?php endforeach; ?></tbody></table></div>
                    <?php elseif ($bloque['tipo'] === 'pregunta'): ?>
                        <details class="manual-faq"><summary><?= manualTexto($bloque['titulo']) ?></summary><p><?= manualTexto($bloque['texto']) ?></p></details>
                    <?php endif; ?>
                    <?php if (!empty($bloque['imagen'])) manualFigura($manual['imagenes'][$bloque['imagen']]); ?>
                <?php endforeach; ?>
                <nav class="manual-chapter-nav" aria-label="Navegación de <?= manualTexto($capitulo['titulo']) ?>">
                    <?php if ($i > 0): ?><a href="#<?= manualTexto($manual['capitulos'][$i-1]['id']) ?>" data-chapter-direction="-1">← Anterior</a><?php else: ?><a href="#manual-portada">↑ Portada</a><?php endif; ?>
                    <span><?= $i + 1 ?> / <?= count($manual['capitulos']) ?></span>
                    <?php if (isset($manual['capitulos'][$i+1])): ?><a href="#<?= manualTexto($manual['capitulos'][$i+1]['id']) ?>" data-chapter-direction="1">Siguiente →</a><?php else: ?><a href="#manual-portada">Volver a la portada ↑</a><?php endif; ?>
                </nav>
            </section>
            <?php endforeach; ?>
        </div>
    </div>
    <footer class="manual-end"><p>Tu próxima gran receta empieza aquí.</p><a class="manual-button manual-button-primary" href="index.php">Volver al inicio de Cookly →</a><a href="#manual-portada">Volver arriba ↑</a></footer>
</div>
<dialog class="manual-lightbox" id="manual-visor" aria-labelledby="manual-visor-caption">
    <div class="manual-lightbox-bar"><span>Captura de Cookly</span><button type="button" class="manual-button" id="manual-cerrar-visor" autofocus>Cerrar ×</button></div>
    <div class="manual-lightbox-scroll"><img id="manual-visor-img" alt=""></div>
    <p id="manual-visor-caption"></p><a id="manual-visor-original" target="_blank" rel="noopener">Abrir imagen original ↗</a>
</dialog>
<?php require __DIR__ . '/includes/footer.php'; ?>
