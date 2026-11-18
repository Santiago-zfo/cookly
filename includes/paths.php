<?php
/**
 * includes/paths.php
 * ------------------------------------------------------------
 * Rutas de la aplicación centralizadas. Permite que las páginas vivan en
 * public/pages/ sin depender de la profundidad de la URL ni de la carpeta
 * desde la que se sirva el proyecto (raíz del servidor o subcarpeta).
 * ------------------------------------------------------------
 */

if (!defined('COOKLY_PAGES_DIR')) {
    define('COOKLY_PAGES_DIR', '/public/pages/');
}

/** Raíz física del proyecto (para is_file/include). */
if (!function_exists('cookly_app_root')) {
    function cookly_app_root(): string
    {
        return dirname(__DIR__);
    }
}

/** Prefijo URL de la aplicación: '' si es document root, '/cookly' si es subcarpeta. */
if (!function_exists('cookly_base_url')) {
    function cookly_base_url(): string
    {
        $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
        $pos = strrpos($script, COOKLY_PAGES_DIR);
        if ($pos !== false) {
            return rtrim(substr($script, 0, $pos), '/\\');
        }
        // dirname() puede devolver '\' en Windows: se normaliza de nuevo.
        return rtrim(str_replace('\\', '/', dirname($script)), '/\\');
    }
}

/** URL absoluta dentro de la aplicación. */
if (!function_exists('cookly_url')) {
    function cookly_url(string $path = ''): string
    {
        $base = cookly_base_url();
        $path = ltrim(str_replace('\\', '/', $path), '/');
        return $path === '' ? $base : $base . '/' . $path;
    }
}

/** URL de una página agrupada en public/pages/. */
if (!function_exists('cookly_page')) {
    function cookly_page(string $page = ''): string
    {
        return cookly_url('public/pages/' . ltrim($page, '/'));
    }
}

/** URL de un recurso estático dentro de public/. */
if (!function_exists('cookly_asset')) {
    function cookly_asset(string $path): string
    {
        return cookly_url('public/' . ltrim($path, '/'));
    }
}

/**
 * Las imágenes/avatares guardados en BD usan rutas históricas "public/IMG/...".
 * Las resolvemos al renderizar para no migrar datos ni cambiar la lógica.
 */
if (!function_exists('cookly_media_url')) {
    function cookly_media_url($url): string
    {
        $url = trim((string) $url);
        if ($url === '') {
            return '';
        }
        // http(s), //host, /ruta, #ancla, data:, javascript:, etc.: se respetan.
        if (preg_match('~^(?:[a-z][a-z0-9+.-]*:|//|/|#)~i', $url)) {
            return $url;
        }
        return cookly_url($url);
    }
}

/** Escapado corto para atributos HTML. */
if (!function_exists('cookly_e')) {
    function cookly_e($value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}
