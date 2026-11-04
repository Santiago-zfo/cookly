<?php
// 404.php
require_once __DIR__ . '/includes/auth.php';
// No requerimos login estricto aquí por si la sesión expiró
$titulo = 'Página no encontrada';
require_once __DIR__ . '/includes/header.php';
?>

<section class="panel" style="text-align: center; padding: 5rem 2rem; max-width: 800px; margin: 4rem auto;">
    <div style="font-size: 8rem; line-height: 1; margin-bottom: 1rem; animation: floatLogo 3s ease-in-out infinite;">🍳</div>
    <h1 style="font-size: 3rem; margin: 0 0 1rem; color: var(--text-main);">404</h1>
    <h2 style="font-size: 1.5rem; margin: 0 0 1rem; color: var(--primary);">¡Ups! No encontramos esta receta</h2>
    <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 2rem; max-width: 500px; margin-left: auto; margin-right: auto;">
        Parece que la página que estás buscando se quemó en el horno o nunca existió. 
        Regresa al inicio para seguir explorando la comunidad.
    </p>
    
    <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
        <a href="index.php" class="submit-btn">Volver al inicio</a>
        <a href="recetas.php" class="secondary" style="text-decoration: none;">Explorar recetas</a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>