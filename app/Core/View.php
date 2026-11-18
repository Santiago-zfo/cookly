<?php
/**
 * app/Core/View.php
 * ------------------------------------------------------------
 * Renderiza vistas PHP dentro del layout común (header + footer).
 * Las variables de $datos se exponen a la vista con extract().
 * ------------------------------------------------------------
 */
final class View
{
    private string $viewsPath;

    public function __construct(?string $viewsPath = null)
    {
        $this->viewsPath = $viewsPath ?? dirname(__DIR__) . '/Views';
    }

    /**
     * Vista completa dentro del layout de la aplicación.
     *
     * @param array $datos  Variables disponibles en la vista.
     * @param array $layout titulo, estilos, scripts y cuerpo (clase CSS del body).
     */
    public function render(string $vista, array $datos = [], array $layout = []): void
    {
        $titulo        = $layout['titulo'] ?? 'Cookly';
        $estilosPagina = $layout['estilos'] ?? [];
        $scriptsPagina = $layout['scripts'] ?? [];
        $claseBody     = $layout['cuerpo'] ?? '';

        // IMPORTANTE: los require van directamente aquí para que heredan este
        // ámbito y las variables extraídas estén disponibles en header/vista/footer.
        extract($datos, EXTR_SKIP);

        $archivo = $this->viewsPath . '/' . ltrim($vista, '/') . '.php';
        if (!is_file($archivo)) {
            throw new RuntimeException('Vista no encontrada: ' . $archivo);
        }

        require $this->viewsPath . '/layouts/header.php';
        require $archivo;
        require $this->viewsPath . '/layouts/footer.php';
    }

    /** Vista sin layout (páginas de login/registro y documentos Excel). */
    public function partial(string $vista, array $datos = []): void
    {
        extract($datos, EXTR_SKIP);

        $archivo = $this->viewsPath . '/' . ltrim($vista, '/') . '.php';
        if (!is_file($archivo)) {
            throw new RuntimeException('Vista no encontrada: ' . $archivo);
        }

        require $archivo;
    }

    /** Devuelve una vista como string (útil para respuestas AJAX). */
    public function capture(string $vista, array $datos = []): string
    {
        ob_start();
        $this->partial($vista, $datos);
        return (string) ob_get_clean();
    }
}
