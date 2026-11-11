<?php
// video.php
require_once __DIR__ . '/includes/auth.php';
requerirLogin(); // Solo los usuarios logueados pueden verlo

$titulo = 'Video de Presentación';
require_once __DIR__ . '/includes/header.php';
?>

<section class="panel" style="max-width: 900px; margin: 0 auto;">
    <div class="section-heading" style="text-align: center; display: block; margin-bottom: 2rem;">
        <h2 style="font-size: 2rem; color: var(--text-main); margin-bottom: 0.5rem;">🎥 Video de Presentación</h2>
        <p style="color: var(--text-muted); font-size: 1.1rem;">
            Conoce Cookly en menos de 2 minutos: descubre cómo organizar tus recetas y compartirlas con el mundo.
        </p>
    </div>

    <!-- Contenedor del video -->
    <div style="background: #000; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-lg); border: 4px solid var(--surface);">
        
        <!-- 
        INSTRUCCIONES PARA EL VIDEO:
        Reemplaza el <video> de abajo por tu etiqueta de video.
        Puedes subir tu video a la carpeta public/ y llamarlo 'presentacion.mp4'.
        -->
        <video 
            src="public/video.mp4" 
            controls 
            poster="public/IMG/logo.png" 
            style="width: 100%; display: block; max-height: 500px; object-fit: cover;">
            Tu navegador no soporta la etiqueta de video.
        </video>

        <!-- Si usas YouTube, borra la etiqueta <video> de arriba y descomenta esto:
        <iframe width="100%" height="500" 
            src="https://www.youtube.com/embed/TU_CODIGO_DE_VIDEO?autoplay=0" 
            title="Presentación Cookly" frameborder="0" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
            allowfullscreen>
        </iframe>
        -->
    </div>

    <div style="background: var(--bg-color); padding: 1.5rem; border-radius: var(--radius-md); margin-top: 2rem; text-align: center; border: 1px solid var(--border-color);">
        <h3 style="margin-top: 0; color: var(--primary); font-size: 1.2rem;">¿Qué estás viendo en este video?</h3>
        <p style="color: var(--text-muted); margin-bottom: 0;">
            Un recorrido rápido por las funcionalidades clave de Cookly: el registro de usuario, la creación de recetas con imágenes, la exploración de la comunidad y la gestión del perfil.
        </p>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>