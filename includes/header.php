<?php
// includes/header.php
require_once __DIR__ . '/auth.php';

$titulo         = $titulo         ?? 'Cookly';
$usuarioHeader = usuarioActual();
$paginaActiva  = basename($_SERVER['PHP_SELF'] ?? '');

// Detecta la carpeta raíz del proyecto (ej. /cookly) para que las rutas funcionen siempre
$base_url = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo) ?> · Cookly</title>

    <!-- FAVICON CORREGIDO -->
    <link rel="icon" type="image/png" href="<?= $base_url ?>/public/IMG/logo.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $base_url ?>/public/CSS/main.css">
</head>
<body>
<div class="page-shell">

    <header class="topbar">
        <!-- Logo y enlace al inicio -->
        <a href="<?= $base_url ?>/index.php" class="brand-wrap" style="text-decoration: none;">
            <img class="brand-logo" src="<?= $base_url ?>/public/IMG/logo.png" alt="Logo de Cookly">
            <div>
                <p class="brand-kicker">Recetas para cada momento</p>
                <h1>Cookly</h1>
            </div>
        </a>

        <?php if ($usuarioHeader): ?>
            <nav class="top-actions" aria-label="Navegación principal">
                <a class="nav-btn <?= $paginaActiva === 'index.php' ? 'active' : '' ?>" href="<?= $base_url ?>/index.php">Mis recetas</a>
                <a class="nav-btn <?= $paginaActiva === 'recetas.php' ? 'active' : '' ?>" href="<?= $base_url ?>/recetas.php">Explorar</a>
                <a class="nav-btn <?= $paginaActiva === 'receta_crear.php' ? 'active' : '' ?>" href="<?= $base_url ?>/receta_crear.php">+ Nueva receta</a>
                
                <?php if (esAdmin()): ?>
                    <a class="nav-btn <?= $paginaActiva === 'admin.php' ? 'active' : '' ?>" 
                       href="<?= $base_url ?>/admin.php" style="background: var(--primary); color: white;">🛡️ Admin</a>
                <?php endif; ?>

                <a class="nav-btn <?= $paginaActiva === 'manual.php' ? 'active' : '' ?>" href="<?= $base_url ?>/manual.php">📖 Manual</a>
                <a class="nav-btn <?= $paginaActiva === 'video.php' ? 'active' : '' ?>" href="<?= $base_url ?>/video.php">🎥 Video</a>
            </nav>

            <div class="user-zone">
                <a href="<?= $base_url ?>/perfil.php" style="text-decoration: none; color: var(--text-main); font-weight: 700;">
                    👋 <?= htmlspecialchars($usuarioHeader['nombre']) ?>
                </a>
                <a class="logout-btn" href="<?= $base_url ?>/logout.php">Cerrar sesión</a>
            </div>
        <?php endif; ?>
    </header>

    <main class="dashboard">
        <?php mostrar_flash_message(); ?>