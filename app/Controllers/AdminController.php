<?php
/**
 * app/Controllers/AdminController.php
 * Panel de administración y edición administrativa de recetas.
 * La lógica de ca_* permanece intacta en AdminGestionModel.
 */
final class AdminController extends Controller
{
    /** admin.php */
    public function index(): void
    {
        requerirAdmin();

        if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'], true)) {
            http_response_code(405);
            header('Allow: GET, POST');
            exit('Método no permitido.');
        }

        $filtros = ca_filters($_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET);
        $error = '';
        $confirmacion = null;
        $flash = ca_take_flash();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                ca_check_token($_POST['_csrf'] ?? null);
                $id = ca_id($_POST['id_receta'] ?? 0);
                $accion = ca_string($_POST['accion'] ?? '');
                if ($accion === 'estado') {
                    ca_change_state($id, ca_string($_POST['nuevo_estado'] ?? ''), ca_string($_POST['version'] ?? ''));
                    ca_flash('El estado de la receta se actualizó correctamente.');
                    $this->redirect(ca_url($filtros), 303);
                } elseif ($accion === 'solicitar_eliminacion') {
                    $confirmacion = ca_prepare_delete($id);
                } elseif ($accion === 'eliminar') {
                    ca_delete($id, ca_string($_POST['confirmacion'] ?? ''));
                    ca_flash('La receta se eliminó correctamente.');
                    $this->redirect(ca_url($filtros), 303);
                } else {
                    throw new InvalidArgumentException('La acción no es válida.');
                }
            } catch (DomainException $e) {
                http_response_code(403);
                $error = $e->getMessage();
            } catch (InvalidArgumentException $e) {
                http_response_code(400);
                $error = $e->getMessage();
            } catch (Throwable $e) {
                http_response_code(500);
                error_log('[Cookly Admin] ' . $e->getMessage());
                $error = 'No se pudo completar la operación. El cambio no se ha confirmado; vuelve a intentarlo o consulta al responsable.';
            }
        }

        $stats = ca_statistics();
        $catalogos = ca_catalogs();
        $listado = ca_listing($filtros);
        $filtros = $listado['filtros'];

        $this->render('admin/index', [
            'filtros'      => $filtros,
            'error'        => $error,
            'confirmacion' => $confirmacion,
            'flash'        => $flash,
            'stats'        => $stats,
            'catalogos'    => $catalogos,
            'listado'      => $listado,
        ], [
            'titulo'  => 'Administrar recetas',
            'estilos' => ['public/css/admin.css'],
            'scripts' => ['public/JS/admin.js'],
            'cuerpo'  => 'cookly-admin-body',
        ]);
    }

    /** admin_receta_editar.php */
    public function editarReceta(): void
    {
        requerirAdmin();

        if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'], true)) {
            http_response_code(405);
            header('Allow: GET, POST');
            exit('Método no permitido.');
        }

        $id = ca_id($_GET['id'] ?? 0);
        $receta = ca_recipe($id);
        if (!$receta) {
            http_response_code(404);
            $this->render('admin/no_disponible', [], [
                'titulo'  => 'Receta no encontrada',
                'estilos' => ['public/css/admin.css'],
                'cuerpo'  => 'cookly-admin-body',
            ]);
            return;
        }

        $ingredientes = ca_ingredients($id);
        $form = $receta;
        $formIngs = $ingredientes;
        $version = ca_signature($receta, $ingredientes);
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach (['titulo', 'descripcion', 'instrucciones', 'dificultad', 'estado', 'imagen_url', 'id_categoria', 'tiempo_preparacion', 'porciones'] as $k) {
                $form[$k] = ca_string($_POST[$k] ?? '');
            }
            $version = ca_string($_POST['version'] ?? '');
            if (is_array($_POST['ing_nombre'] ?? null) && count($_POST['ing_nombre']) <= 100) {
                $formIngs = [];
                foreach ($_POST['ing_nombre'] as $k => $name) {
                    $formIngs[] = [
                        'nombre'        => ca_string($name),
                        'cantidad'      => ca_string($_POST['ing_cantidad'][$k] ?? ''),
                        'unidad_medida' => ca_string($_POST['ing_unidad'][$k] ?? 'gramos')
                    ];
                }
            }
            try {
                ca_check_token($_POST['_csrf'] ?? null);
                $data = ca_validate_recipe($_POST);
                ca_save_recipe($id, $data, $version, $_FILES);
                ca_flash('La receta se actualizó correctamente. Se conservó su autor y se aplicó el estado seleccionado.');
                $this->redirect($this->pagina('admin.php'), 303);
            } catch (DomainException $e) {
                http_response_code(403);
                $error = $e->getMessage();
            } catch (InvalidArgumentException $e) {
                http_response_code(400);
                $error = $e->getMessage();
            } catch (Throwable $e) {
                http_response_code(500);
                error_log('[Cookly Admin Edit] ' . $e->getMessage());
                $error = 'No se pudo guardar la receta. Revisa los datos o vuelve a intentarlo. Los cambios no se han confirmado.';
            }
        }

        $this->render('admin/editar', [
            'id'          => $id,
            'receta'      => $receta,
            'form'        => $form,
            'formIngs'    => $formIngs,
            'version'     => $version,
            'error'       => $error,
            'catalogos'   => ca_catalogs(),
        ], [
            'titulo'  => 'Editar receta como administrador',
            'estilos' => ['public/css/admin.css'],
            'cuerpo'  => 'cookly-admin-body',
        ]);
    }
}
