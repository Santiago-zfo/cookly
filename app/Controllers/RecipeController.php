<?php
/**
 * app/Controllers/RecipeController.php
 * Mis recetas, exploración, alta/edición/baja y búsqueda AJAX.
 */
final class RecipeController extends Controller
{
    /** index.php — recetas del usuario autenticado. */
    public function index(): void
    {
        requerirLogin();

        $this->render('recipes/index', [
            'mis_recetas' => listar_mis_recetas($_SESSION['id_usuario']),
        ], ['titulo' => 'Mis recetas']);
    }

    /** recetas.php — exploración paginada. */
    public function explorar(): void
    {
        requerirLogin();

        $por_pagina = 6;
        $pagina_actual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
        if ($pagina_actual < 1) $pagina_actual = 1;

        $total_recetas = contar_todas_las_recetas();
        $total_paginas = ceil($total_recetas / $por_pagina);
        $offset = ($pagina_actual - 1) * $por_pagina;

        $this->render('recipes/explorar', [
            'usuarioId'     => (int) $_SESSION['id_usuario'],
            'todas'         => listar_todas_las_recetas($por_pagina, $offset),
            'pagina_actual' => $pagina_actual,
            'total_paginas' => $total_paginas,
        ], ['titulo' => 'Explorar']);
    }

    /** receta_crear.php */
    public function crear(): void
    {
        requerirLogin();

        $categorias = listar_categorias();
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ings = [];
            if (!empty($_POST['ing_nombre'])) {
                foreach ($_POST['ing_nombre'] as $i => $nombre) {
                    if (trim($nombre) === '') continue;

                    $cantidad = trim($_POST['ing_cantidad'][$i]);
                    if ($cantidad === '' || (float) $cantidad <= 0) {
                        $cantidad = 1;
                    }

                    $ings[] = [
                        'nombre'        => $nombre,
                        'cantidad'      => (float) $cantidad,
                        'unidad_medida' => $_POST['ing_unidad'][$i]
                    ];
                }
            }

            $datos = $_POST;
            if (!isset($datos['estado'])) {
                $datos['estado'] = 'Pendiente';
            }

            if (guardar_receta($datos, $ings)) {
                set_flash_message('exito', '¡Receta creada con éxito! Está pendiente de aprobación.');
                $this->redirect($this->pagina('index.php'));
            } else {
                $error = 'Error al guardar la receta.';
            }
        }

        $this->render('recipes/crear', [
            'categorias' => $categorias,
            'error'      => $error,
        ], ['titulo' => 'Nueva receta']);
    }

    /** receta_detalle.php */
    public function detalle(): void
    {
        requerirLogin();

        $id = (int) ($_GET['id'] ?? 0);
        $r = obtener_receta($id);
        if (!$r) {
            $this->redirect($this->pagina('recetas.php'));
        }

        // Si la receta NO está aceptada, solo el dueño o el admin pueden verla.
        $esMia = ($r['id_usuario'] == $_SESSION['id_usuario']);
        if ($r['estado'] !== 'Aceptado' && !$esMia && !esAdmin()) {
            $this->redirect($this->pagina('recetas.php'));
        }

        $this->render('recipes/detalle', [
            'r'      => $r,
            'ings'   => obtener_ingredientes_receta($id),
            'esMia'  => $esMia,
            'id'     => $id,
        ], ['titulo' => $r['titulo']]);
    }

    /** receta_editar.php */
    public function editar(): void
    {
        requerirLogin();

        $id = (int) ($_GET['id'] ?? 0);
        $receta = obtener_receta($id);
        if (!$receta || $receta['id_usuario'] != $_SESSION['id_usuario']) {
            $this->redirect($this->pagina('index.php'));
        }

        $categorias = listar_categorias();
        $ings_db = obtener_ingredientes_receta($id);
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ings = [];
            if (!empty($_POST['ing_nombre'])) {
                foreach ($_POST['ing_nombre'] as $i => $nombre) {
                    if (trim($nombre) === '') continue;

                    $cantidad = trim($_POST['ing_cantidad'][$i]);
                    if ($cantidad === '' || (float) $cantidad <= 0) {
                        $cantidad = 1;
                    }

                    $ings[] = [
                        'nombre'        => $nombre,
                        'cantidad'      => (float) $cantidad,
                        'unidad_medida' => $_POST['ing_unidad'][$i]
                    ];
                }
            }

            if (guardar_receta($_POST, $ings, $id)) {
                set_flash_message('exito', '¡Receta actualizada! Ha vuelto a estado Pendiente para revisión.');
                $this->redirect($this->pagina('index.php'));
            } else {
                $error = 'Error al actualizar la receta.';
            }
        }

        $this->render('recipes/editar', [
            'receta'     => $receta,
            'categorias' => $categorias,
            'ings_db'    => $ings_db,
            'error'      => $error,
        ], ['titulo' => 'Editar receta']);
    }

    /** receta_eliminar.php */
    public function eliminar(): void
    {
        requerirLogin();

        $id = (int) ($_GET['id'] ?? 0);
        $r = obtener_receta($id);

        if ($r && $r['id_usuario'] == $_SESSION['id_usuario']) {
            eliminar_receta($id);
        }
        $this->redirect($this->pagina('index.php'));
    }

    /** ajax_buscar.php — respuesta JSON para el buscador. */
    public function buscarAjax(): void
    {
        requerirLogin();

        header('Content-Type: application/json');

        $q = trim($_GET['q'] ?? '');
        if ($q === '') {
            echo json_encode(['html' => '']);
            return;
        }

        $resultados = buscar_recetas_ajax($q);
        if (empty($resultados)) {
            echo json_encode(['html' => '<div class="empty-state" style="grid-column: 1/-1;"><p>🔍 No se encontraron recetas para "' . htmlspecialchars($q) . '".</p></div>']);
            return;
        }

        $html = '';
        foreach ($resultados as $r) {
            $html .= $this->view->capture('recipes/_tarjeta_ajax', ['r' => $r]);
        }

        echo json_encode(['html' => $html]);
    }
}
