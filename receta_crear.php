<?php
// receta_crear.php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/funciones_recetas.php';
requerirLogin();

$titulo = 'Nueva receta';
$categorias = listar_categorias();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ings = [];
    if (!empty($_POST['ing_nombre'])) {
        foreach ($_POST['ing_nombre'] as $i => $nombre) {
            if (trim($nombre) === '') continue;
            $ings[] = [
                'nombre' => $nombre, 
                'cantidad' => $_POST['ing_cantidad'][$i], 
                'unidad_medida' => $_POST['ing_unidad'][$i]
            ];
        }
    }

    $datos = $_POST;
    if (!isset($datos['estado'])) {
        $datos['estado'] = 'Pendiente';
    }

    if (guardar_receta($datos, $ings)) {
        header('Location: index.php'); 
        exit;
    } else {
        $error = 'Error al guardar la receta.';
    }
}
require_once __DIR__ . '/includes/header.php';
?>
<section class="panel">
    <div class="section-heading"><h2>Nueva receta</h2><a href="index.php" class="logout-btn">×</a></div>
    
    <?php if ($error): ?>
        <div class="estado error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data">
        <div class="form-grid">
            <label class="full-width">Título
                <input type="text" name="titulo" required>
            </label>
            <label>Categoría
                <select name="id_categoria" required>
                    <option value="">Selecciona…</option>
                    <?php foreach ($categorias as $c): ?>
                        <option value="<?= $c['id_categoria'] ?>"><?= $c['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Tiempo (min)
                <input type="number" name="tiempo_preparacion" required>
            </label>
            <label>Dificultad
                <select name="dificultad">
                    <option value="Fácil">Fácil</option>
                    <option value="Media" selected>Media</option>
                    <option value="Difícil">Difícil</option>
                </select>
            </label>
            <label>Porciones
                <input type="number" name="porciones" value="1" required>
            </label>
            <label class="full-width">
                Imagen (URL o Archivo)
                <div style="background: var(--bg-color); padding: 1rem; border-radius: var(--radius-md); display: flex; gap: 1rem; flex-direction: column;">
                    <input type="url" name="imagen_url" placeholder="https://...">
                    <input type="file" name="imagen_file" accept="image/*">
                </div>
            </label>
            <label class="full-width">Descripción
                <textarea name="descripcion" rows="3"></textarea>
            </label>
            <label class="full-width">Instrucciones
                <textarea name="instrucciones" rows="5" required></textarea>
            </label>
        </div>
        
        <h3>Ingredientes</h3>
        <div id="ings">
            <div class="ing-row">
                <input type="text" name="ing_nombre[]" placeholder="Ingrediente">
                <input type="number" name="ing_cantidad[]" placeholder="Cant." step="0.01">
                <select name="ing_unidad[]">
                    <option value="gramos">gramos</option>
                    <option value="unidad">unidad</option>
                    <option value="mililitros">mililitros</option>
                    <option value="tazas">tazas</option>
                    <option value="cucharadas">cucharadas</option>
                </select>
            </div>
        </div>
        
        <button type="button" class="secondary" id="add-ing" style="margin-top: 1rem;">+ Ingrediente</button>
        <button type="submit" class="submit-btn" style="margin-top: 1rem;">Guardar</button>
    </form>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>