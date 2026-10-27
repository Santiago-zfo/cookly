<?php
// receta_editar.php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/funciones_recetas.php';
requerirLogin();

$id = (int) ($_GET['id'] ?? 0);
$receta = obtener_receta($id);

// Verificar que la receta exista y pertenezca al usuario
if (!$receta || $receta['id_usuario'] != $_SESSION['id_usuario']) {
    header('Location: index.php'); 
    exit;
}

$titulo = 'Editar receta';
$categorias = listar_categorias();
$ings_db = obtener_ingredientes_receta($id);
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

    if (guardar_receta($_POST, $ings, $id)) {
        header('Location: index.php'); 
        exit;
    } else {
        $error = 'Error al actualizar la receta.';
    }
}
require_once __DIR__ . '/includes/header.php';
?>
<section class="panel">
    <div class="section-heading"><h2>Editar receta</h2><a href="index.php" class="logout-btn">×</a></div>
    
    <?php if ($error): ?>
        <div class="estado error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data">
        <div class="form-grid">
            <label class="full-width">Título
                <input type="text" name="titulo" value="<?= htmlspecialchars($receta['titulo']) ?>" required>
            </label>
            <label>Categoría
                <select name="id_categoria" required>
                    <?php foreach ($categorias as $c): ?>
                        <option value="<?= $c['id_categoria'] ?>" <?= $c['id_categoria'] == $receta['id_categoria'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Tiempo (min)
                <input type="number" name="tiempo_preparacion" value="<?= $receta['tiempo_preparacion'] ?>" required>
            </label>
            <label>Dificultad
                <select name="dificultad">
                    <option value="Fácil" <?= $receta['dificultad'] == 'Fácil' ? 'selected' : '' ?>>Fácil</option>
                    <option value="Media" <?= $receta['dificultad'] == 'Media' ? 'selected' : '' ?>>Media</option>
                    <option value="Difícil" <?= $receta['dificultad'] == 'Difícil' ? 'selected' : '' ?>>Difícil</option>
                </select>
            </label>
            <label>Porciones
                <input type="number" name="porciones" value="<?= $receta['porciones'] ?>" required>
            </label>
            
            <label class="full-width">
                Imagen (URL o Archivo)
                <div style="background: var(--bg-color); padding: 1rem; border-radius: var(--radius-md); display: flex; gap: 1rem; flex-direction: column;">
                    <!-- El ?? '' evita el error si la imagen no existe -->
                    <input type="url" name="imagen_url" value="<?= htmlspecialchars($receta['imagen_url'] ?? '') ?>" placeholder="https://...">
                    <small style="color: var(--text-muted);">Sube un archivo si quieres reemplazar la imagen actual:</small>
                    <input type="file" name="imagen_file" accept="image/*">
                </div>
            </label>
            
            <label class="full-width">Descripción
                <textarea name="descripcion" rows="3"><?= htmlspecialchars($receta['descripcion'] ?? '') ?></textarea>
            </label>
            <label class="full-width">Instrucciones
                <textarea name="instrucciones" rows="5" required><?= htmlspecialchars($receta['instrucciones']) ?></textarea>
            </label>
        </div>
        
        <h3>Ingredientes</h3>
        <div id="ings">
            <?php if (empty($ings_db)): ?>
                <div class="ing-row">
                    <input type="text" name="ing_nombre[]" placeholder="Ingrediente">
                    <input type="number" name="ing_cantidad[]" placeholder="Cant." step="0.01">
                    <select name="ing_unidad[]">
                        <option value="gramos">gramos</option>
                        <option value="unidad">unidad</option>
                    </select>
                </div>
            <?php else: ?>
                <?php foreach ($ings_db as $ing): ?>
                    <div class="ing-row">
                        <input type="text" name="ing_nombre[]" value="<?= htmlspecialchars($ing['nombre']) ?>">
                        <input type="number" name="ing_cantidad[]" value="<?= $ing['cantidad'] ?>" step="0.01">
                        <select name="ing_unidad[]">
                            <option value="gramos" <?= $ing['unidad_medida'] == 'gramos' ? 'selected' : '' ?>>gramos</option>
                            <option value="unidad" <?= $ing['unidad_medida'] == 'unidad' ? 'selected' : '' ?>>unidad</option>
                            <option value="mililitros" <?= $ing['unidad_medida'] == 'mililitros' ? 'selected' : '' ?>>mililitros</option>
                            <option value="tazas" <?= $ing['unidad_medida'] == 'tazas' ? 'selected' : '' ?>>tazas</option>
                            <option value="cucharadas" <?= $ing['unidad_medida'] == 'cucharadas' ? 'selected' : '' ?>>cucharadas</option>
                        </select>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <button type="button" class="secondary" id="add-ing" style="margin-top: 1rem;">+ Ingrediente</button>
        <button type="submit" class="submit-btn" style="margin-top: 1rem;">Actualizar receta</button>
    </form>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>