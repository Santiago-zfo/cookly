<?php
/**
 * app/Controllers/PageController.php
 * Páginas informativas y de contenido: manual, anexos, video y 404.
 */
final class PageController extends Controller
{
    public function manual(): void
    {
        requerirLogin();
        $this->render('pages/manual', [], ['titulo' => 'Manual de Usuario']);
    }

    public function anexos(): void
    {
        requerirLogin();
        $this->render('pages/anexos', [], ['titulo' => 'Anexos y Documentación']);
    }

    public function video(): void
    {
        requerirLogin();
        $this->render('pages/video', [], ['titulo' => 'Video de Presentación']);
    }

    public function notFound(): void
    {
        // No se exige login estricto por si la sesión expiró.
        $this->render('pages/404', [], ['titulo' => 'Página no encontrada']);
    }
}
