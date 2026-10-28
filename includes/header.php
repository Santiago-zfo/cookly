<?php
// includes/header.php
require_once __DIR__ . '/auth.php';

$titulo        = $titulo        ?? 'Cookly';
$usuarioHeader = usuarioActual();
$paginaActiva  = basename($_SERVER['PHP_SELF'] ?? '');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo) ?> · Cookly</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/CSS/main.css">
        <link rel="icon" type="image/png" href="public/IMG/logo.png">
</head>
<body>
<div class="page-shell">

    <header class="topbar">
        <div class="brand-wrap">
            <img class="brand-logo" src="public/IMG/logo.png" alt="Logo de Cookly">
            <div>
                <p class="brand-kicker">Recetas para cada momento</p>
                <h1>Cookly</h1>
            </div>
        </div>

        <?php if ($usuarioHeader): ?>
            <nav class="top-actions" aria-label="Navegación principal">
                <a class="nav-btn <?= $paginaActiva === 'index.php' ? 'active' : '' ?>"
                   href="index.php">Mis recetas</a>
                <a class="nav-btn <?= $paginaActiva === 'recetas.php' ? 'active' : '' ?>"
                   href="recetas.php">Explorar</a>
                <a class="nav-btn <?= $paginaActiva === 'receta_crear.php' ? 'active' : '' ?>"
                   href="receta_crear.php">+ Nueva receta</a>
                
                <!-- BOTÓN DE ADMIN (Usa la función esAdmin que acabamos de restaurar) -->
                <?php if (esAdmin()): ?>
                    <a class="nav-btn <?= $paginaActiva === 'admin.php' ? 'active' : '' ?>" 
                       href="admin.php" style="background: var(--primary); color: white;">🛡️ Admin</a>
                <?php endif; ?>

                <a class="nav-btn <?= $paginaActiva === 'manual.php' ? 'active' : '' ?>"
                   href="manual.php">📖 Manual</a>
                <a class="nav-btn <?= $paginaActiva === 'video.php' ? 'active' : '' ?>"
                   href="video.php">🎥 Video</a>
            </nav>

            <div class="user-zone">
                <a href="perfil.php" style="text-decoration: none; color: var(--text-main); font-weight: 700;">
                    👋 <?= htmlspecialchars($usuarioHeader['nombre']) ?>
                </a>
                <a class="logout-btn" href="logout.php">Cerrar sesión</a>
            </div>
        <?php endif; ?>
    </header>

    <main class="dashboard">