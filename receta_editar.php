<?php
// receta_editar.php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/funciones_recetas.php';
requerirLogin();

$id = (int) ($_GET['id'] ?? 0);
$receta = obtener_receta($id);

// Validar que la receta exista y pertenezca al usuario
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
            
            // Validar cantidad para evitar error de MySQL
            $cantidad = trim($_POST['ing_cantidad'][$i]);
            if ($cantidad === '' || (float)$cantidad <= 0) {
                $cantidad = 1; 
            }
            
            $ings[] = [
                'nombre' => $nombre, 
                'cantidad' => (float)$cantidad, 
                'unidad_medida' => $_POST['ing_unidad'][$i]
            ];
        }
    }

    if (guardar_receta($_POST, $ings, $id)) {
        set_flash_message('exito', '¡Receta actualizada! Ha vuelto a estado Pendiente para revisión.');
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
                <input type="number" name="tiempo_preparacion" min="1" value="<?= $receta['tiempo_preparacion'] ?>" required>
            </label>
            
            <label>Dificultad
                <select name="dificultad">
                    <option value="Fácil" <?= $receta['dificultad'] == 'Fácil' ? 'selected' : '' ?>>Fácil</option>
                    <option value="Media" <?= $receta['dificultad'] == 'Media' ? 'selected' : '' ?>>Media</option>
                    <option value="Difícil" <?= $receta['dificultad'] == 'Difícil' ? 'selected' : '' ?>>Difícil</option>
                </select>
            </label>
            
            <label>Porciones
                <input type="number" name="porciones" min="1" value="<?= $receta['porciones'] ?>" required>
            </label>
            
            <!-- CAMPO DE IMAGEN CORREGIDO (type="text" en lugar de "url") -->
            <label class="full-width">
                Imagen (URL o Archivo)
                <div style="background: var(--bg-color); padding: 1rem; border-radius: var(--radius-md); display: flex; gap: 1rem; flex-direction: column;">
                    <input type="text" name="imagen_url" value="<?= htmlspecialchars($receta['imagen_url'] ?? '') ?>" placeholder="https://... (Opcional)">
                    <small style="color: var(--text-muted);">Deja este campo como está si no deseas cambiar la imagen actual, o selecciona un archivo nuevo abajo.</small>
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
                    <input type="number" name="ing_cantidad[]" placeholder="Cant." step="0.01" min="0.1" value="1">
                    <select name="ing_unidad[]">
                        <option value="gramos">gramos</option>
                        <option value="unidad">unidad</option>
                        <option value="mililitros">mililitros</option>
                        <option value="tazas">tazas</option>
                        <option value="cucharadas">cucharadas</option>
                    </select>
                </div>
            <?php else: ?>
                <?php foreach ($ings_db as $ing): ?>
                    <div class="ing-row">
                        <input type="text" name="ing_nombre[]" value="<?= htmlspecialchars($ing['nombre']) ?>">
                        <input type="number" name="ing_cantidad[]" value="<?= $ing['cantidad'] ?>" step="0.01" min="0.1">
                        <select name="ing_unidad[]">
                            <option value="gramos" <?= $ing['unidad_medida'] == 'gramos' ? 'selected' : '' ?>>gramos</option>
                            <option value="kilogramos" <?= $ing['unidad_medida'] == 'kilogramos' ? 'selected' : '' ?>>kilogramos</option>
                            <option value="libras" <?= $ing['unidad_medida'] == 'libras' ? 'selected' : '' ?>>libras</option>
                            <option value="onzas" <?= $ing['unidad_medida'] == 'onzas' ? 'selected' : '' ?>>onzas</option>
                            <option value="mililitros" <?= $ing['unidad_medida'] == 'mililitros' ? 'selected' : '' ?>>mililitros</option>
                            <option value="litros" <?= $ing['unidad_medida'] == 'litros' ? 'selected' : '' ?>>litros</option>
                            <option value="tazas" <?= $ing['unidad_medida'] == 'tazas' ? 'selected' : '' ?>>tazas</option>
                            <option value="cucharadas" <?= $ing['unidad_medida'] == 'cucharadas' ? 'selected' : '' ?>>cucharadas</option>
                            <option value="cucharaditas" <?= $ing['unidad_medida'] == 'cucharaditas' ? 'selected' : '' ?>>cucharaditas</option>
                            <option value="pizcas" <?= $ing['unidad_medida'] == 'pizcas' ? 'selected' : '' ?>>pizcas</option>
                            <option value="unidad" <?= $ing['unidad_medida'] == 'unidad' ? 'selected' : '' ?>>unidad</option>
                            <option value="unidades" <?= $ing['unidad_medida'] == 'unidades' ? 'selected' : '' ?>>unidades</option>
                            <option value="dientes" <?= $ing['unidad_medida'] == 'dientes' ? 'selected' : '' ?>>dientes</option>
                            <option value="atados" <?= $ing['unidad_medida'] == 'atados' ? 'selected' : '' ?>>atados</option>
                            <option value="rodajas" <?= $ing['unidad_medida'] == 'rodajas' ? 'selected' : '' ?>>rodajas</option>
                            <option value="hojas" <?= $ing['unidad_medida'] == 'hojas' ? 'selected' : '' ?>>hojas</option>
                            <option value="latas" <?= $ing['unidad_medida'] == 'latas' ? 'selected' : '' ?>>latas</option>
                            <option value="sobres" <?= $ing['unidad_medida'] == 'sobres' ? 'selected' : '' ?>>sobres</option>
                            <option value="ramas" <?= $ing['unidad_medida'] == 'ramas' ? 'selected' : '' ?>>ramas</option>
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