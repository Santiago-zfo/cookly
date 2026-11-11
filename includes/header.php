<?php
require_once __DIR__ . '/auth.php';
$titulo = $titulo ?? 'Cookly';
$usuarioHeader = usuarioActual();
$paginaActiva = basename($_SERVER['PHP_SELF'] ?? '');
$base_url = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
$esAdminHeader = function_exists('esAdmin') && esAdmin();
$navE = static function($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); };
if (!function_exists('cookly_nav_icon')) {
    function cookly_nav_icon(string $name): string {
        $paths = [
            'home'=>'<path d="m3 10 9-7 9 7v10H3z"/><path d="M9 20v-7h6v7"/>',
            'search'=>'<circle cx="10.5" cy="10.5" r="6.5"/><path d="m16 16 5 5"/>',
            'plus'=>'<path d="M12 5v14M5 12h14"/>',
            'shield'=>'<path d="m12 3 8 3v6c0 5-8 9-8 9s-8-4-8-9V6z"/><path d="m8 12 3 3 5-6"/>',
            'book'=>'<path d="M12 5v16M12 5C8 2 3 4 3 4v15s5-2 9 2c4-4 9-2 9-2V4s-5-2-9 1Z"/>',
            'chevron'=>'<path d="m6 9 6 6 6-6"/>',
            'menu'=>'<path d="M4 6h16M4 12h16M4 18h16"/>',
            'user'=>'<circle cx="12" cy="8" r="4"/><path d="M4 21v-2a8 8 0 0 1 16 0v2"/>',
            'logout'=>'<path d="M9 3H4v18h5M13 7l5 5-5 5M8 12h12"/>',
            'chart'=>'<path d="M4 3v18h17M8 16v-5M13 16V7M18 16V4"/>',
            'logs'=>'<path d="M6 3h9l4 4v14H6zM14 3v5h5M9 12h7M9 16h7"/>',
            'video'=>'<rect x="3" y="4" width="18" height="16" rx="3"/><path d="m10 8 6 4-6 4z"/>'
        ];
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.($paths[$name]??$paths['home']).'</svg>';
    }
}
$navItems = [ ['index.php','Mis recetas','home'], ['recetas.php','Explorar','search'], ['receta_crear.php','Nueva receta','plus'] ];
if ($esAdminHeader && is_file(__DIR__.'/../admin.php')) $navItems[] = ['admin.php','Administrar','shield'];
$navItems[] = ['manual.php','Manual de Usuario','book'];
$nombreHeader = $usuarioHeader['nombre'] ?? '';
$partesNombre = preg_split('/\s+/u', trim($nombreHeader), -1, PREG_SPLIT_NO_EMPTY);
$iniciales = '';
foreach (array_slice($partesNombre ?: ['C'], 0, 2) as $parte) $iniciales .= function_exists('mb_substr') ? mb_substr($parte,0,1,'UTF-8') : substr($parte,0,1);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $navE($titulo) ?> · Cookly</title>
<link rel="icon" type="image/png" href="<?= $navE($base_url) ?>/public/IMG/logo.png">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= $navE($base_url) ?>/public/css/main.css">
<link rel="stylesheet" href="<?= $navE($base_url) ?>/public/css/navegacion.css?v=20261110">
<?php foreach (($estilosPagina ?? []) as $estilo): ?><link rel="stylesheet" href="<?= $navE($base_url.'/'.$estilo) ?>"><?php endforeach; ?>
<script src="<?= $navE($base_url) ?>/public/JS/navegacion.js?v=20261110" defer></script>
</head>
<body class="<?= $navE($claseBody ?? '') ?>">
<div class="page-shell">
<header class="topbar cookly-nav <?= $esAdminHeader ? 'cookly-nav-admin' : '' ?>">
    <a href="<?= $navE($base_url) ?>/index.php" class="brand-wrap cookly-brand" aria-label="Cookly, volver al inicio">
        <img class="brand-logo" src="<?= $navE($base_url) ?>/public/IMG/logo.png" alt="Logo de Cookly" width="44" height="44">
        <div><h1>Cookly</h1><p class="brand-kicker"><?= $esAdminHeader ? 'Espacio de administración' : 'Recetas para compartir' ?></p></div>
    </a>
    <?php if ($usuarioHeader): ?>
    <details class="cookly-nav-menu" id="cookly-menu" open>
        <summary aria-label="Menú de navegación"><?= cookly_nav_icon('menu') ?><span>Menú</span></summary>
        <nav class="top-actions" aria-label="Navegación principal">
        <?php foreach ($navItems as [$pagina,$label,$icono]): $activa = $paginaActiva === $pagina || ($pagina === 'admin.php' && $paginaActiva === 'admin_receta_editar.php'); ?>
            <a class="nav-btn <?= $activa ? 'active' : '' ?> <?= $pagina==='admin.php' ? 'cookly-nav-control' : '' ?>" href="<?= $navE($base_url.'/'.$pagina) ?>" <?= $activa ? 'aria-current="page"' : '' ?>><?= cookly_nav_icon($icono) ?><span><?= $navE($label) ?></span></a>
        <?php endforeach; ?>
        </nav>
    </details>
    <details class="cookly-account" id="cookly-account">
        <summary aria-label="Opciones de cuenta: <?= $navE($nombreHeader) ?>">
            <span class="cookly-avatar" aria-hidden="true"><?= $navE($iniciales) ?></span>
            <span class="cookly-account-label"><strong title="<?= $navE($nombreHeader) ?>"><?= $navE($nombreHeader) ?></strong><small><?= $esAdminHeader ? 'Administrador' : 'Mi cuenta' ?></small></span>
            <?= cookly_nav_icon('chevron') ?>
        </summary>
        <div class="cookly-account-dropdown">
            <p><?= $navE($nombreHeader) ?><small><?= $esAdminHeader ? 'Cuenta administradora' : 'Cuenta de usuario' ?></small></p>
            <a href="<?= $navE($base_url) ?>/perfil.php"><?= cookly_nav_icon('user') ?>Mi perfil</a>
            <?php if ($esAdminHeader && is_file(__DIR__.'/../reportes.php')): ?><a href="<?= $navE($base_url) ?>/reportes.php"><?= cookly_nav_icon('chart') ?>Reportes</a><?php endif; ?>
            <?php if ($esAdminHeader && is_file(__DIR__.'/../logs.php')): ?><a href="<?= $navE($base_url) ?>/logs.php"><?= cookly_nav_icon('logs') ?>Registro del sistema</a><?php endif; ?>
            <?php if (is_file(__DIR__.'/../video.php')): ?><a href="<?= $navE($base_url) ?>/video.php"><?= cookly_nav_icon('video') ?>Video de presentación</a><?php endif; ?>
            <a class="cookly-account-logout" href="<?= $navE($base_url) ?>/Logout.php"><?= cookly_nav_icon('logout') ?>Cerrar sesión</a>
        </div>
    </details>
    <?php elseif ($paginaActiva === 'manual.php'): ?>
    <nav class="cookly-guest-nav" aria-label="Navegación principal"><a href="<?= $navE($base_url) ?>/Login.php">Iniciar sesión</a><a href="<?= $navE($base_url) ?>/registro.php">Crear cuenta</a><a class="active" href="<?= $navE($base_url) ?>/manual.php" aria-current="page">Manual de Usuario</a></nav>
    <?php endif; ?>
</header>
<main class="dashboard">
<?php if (function_exists('mostrar_flash_message')) mostrar_flash_message(); ?>
