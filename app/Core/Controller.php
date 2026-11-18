<?php
/**
 * app/Core/Controller.php
 * ------------------------------------------------------------
 * Clase base para todos los controladores. Centraliza la respuesta
 * HTML, las redirecciones y la generación de URLs internas.
 * ------------------------------------------------------------
 */
abstract class Controller
{
    protected View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    /** Renderiza una vista con el layout por defecto. */
    protected function render(string $vista, array $datos = [], array $layout = []): void
    {
        $this->view->render($vista, $datos, $layout);
    }

    /** Redirección y fin de la ejecución. */
    protected function redirect(string $url, int $codigo = 302): void
    {
        header('Location: ' . $url, true, $codigo);
        exit;
    }

    /** URL de una página pública (mantiene las URLs actuales). */
    protected function pagina(string $archivo): string
    {
        return cookly_page($archivo);
    }
}
