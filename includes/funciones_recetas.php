<?php
// includes/funciones_recetas.php
require_once __DIR__ . '/../config/database.php';

function listar_categorias() {
    global $pdo;
    return $pdo->query("SELECT * FROM Categorias ORDER BY nombre")->fetchAll();
}

function listar_mis_recetas($id_usuario) {
    global $pdo;
    $sql = "SELECT r.*, c.nombre AS categoria 
            FROM Recetas r 
            INNER JOIN Categorias c ON r.id_categoria = c.id_categoria 
            WHERE r.id_usuario = ? ORDER BY r.id_receta DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_usuario]);
    return $stmt->fetchAll();
}

function listar_todas_las_recetas() {
    global $pdo;
    $sql = "SELECT r.*, c.nombre AS categoria, u.nombre AS autor 
            FROM Recetas r 
            INNER JOIN Categorias c ON r.id_categoria = c.id_categoria 
            INNER JOIN Usuarios u ON r.id_usuario = u.id_usuario 
            ORDER BY r.id_receta DESC";
    return $pdo->query($sql)->fetchAll();
}

function obtener_receta($id) {
    global $pdo;
    $sql = "SELECT r.*, c.nombre AS categoria, u.nombre AS autor 
            FROM Recetas r 
            INNER JOIN Categorias c ON r.id_categoria = c.id_categoria 
            INNER JOIN Usuarios u ON r.id_usuario = u.id_usuario 
            WHERE r.id_receta = ? LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function obtener_ingredientes_receta($id_receta) {
    global $pdo;
    $sql = "SELECT i.nombre, ri.cantidad, ri.unidad_medida 
            FROM Receta_Ingredientes ri 
            INNER JOIN Ingredientes i ON ri.id_ingrediente = i.id_ingrediente 
            WHERE ri.id_receta = ? ORDER BY i.nombre";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_receta]);
    return $stmt->fetchAll();
}

function buscar_o_crear_ingrediente($nombre) {
    global $pdo;
    $nombre = trim($nombre);
    $stmt = $pdo->prepare("SELECT id_ingrediente FROM Ingredientes WHERE nombre = ? LIMIT 1");
    $stmt->execute([$nombre]);
    $row = $stmt->fetch();
    
    if ($row) return $row['id_ingrediente'];
    
    $stmt = $pdo->prepare("INSERT INTO Ingredientes (nombre) VALUES (?)");
    $stmt->execute([$nombre]);
    return $pdo->lastInsertId();
}

function receta_pertenece_a_usuario($id_receta, $id_usuario) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT 1 FROM Recetas WHERE id_receta = ? AND id_usuario = ?");
    $stmt->execute([$id_receta, $id_usuario]);
    return $stmt->fetchColumn() ? true : false;
}

function guardar_receta($datos, $ingredientes, $id = null) {
    global $pdo;
    
    $imagen_url = trim($datos['imagen_url'] ?? '');
    if (isset($_FILES['imagen_file']) && $_FILES['imagen_file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../public/IMG/recetas/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        
        $ext = strtolower(pathinfo($_FILES['imagen_file']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
            $nombreArchivo = uniqid('receta_') . '.' . $ext;
            if (move_uploaded_file($_FILES['imagen_file']['tmp_name'], $uploadDir . $nombreArchivo)) {
                $imagen_url = 'public/IMG/recetas/' . $nombreArchivo;
            }
        }
    }

    $ings_convertidos = [];
    foreach ($ingredientes as $ing) {
        if (empty(trim($ing['nombre']))) continue;
        $id_ing = buscar_o_crear_ingrediente($ing['nombre']);
        $ings_convertidos[] = [
            'id_ingrediente' => $id_ing,
            'cantidad' => $ing['cantidad'],
            'unidad_medida' => $ing['unidad_medida']
        ];
    }

    try {
        $pdo->beginTransaction();

        if ($id === null) {
            // INSERT
            $sql = "INSERT INTO Recetas (id_categoria, id_usuario, titulo, descripcion, instrucciones, tiempo_preparacion, dificultad, porciones, estado, imagen_url) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $datos['id_categoria'], $_SESSION['id_usuario'], $datos['titulo'], 
                $datos['descripcion'] ?? '', $datos['instrucciones'], $datos['tiempo_preparacion'], 
                $datos['dificultad'], $datos['porciones'], $datos['estado'] ?? 'Pendiente', $imagen_url
            ]);
            $id_receta = $pdo->lastInsertId();
        } else {
            // UPDATE
            $sql = "UPDATE Recetas SET id_categoria=?, titulo=?, descripcion=?, instrucciones=?, tiempo_preparacion=?, dificultad=?, porciones=?, estado=?, imagen_url=? WHERE id_receta=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $datos['id_categoria'], $datos['titulo'], $datos['descripcion'] ?? '', 
                $datos['instrucciones'], $datos['tiempo_preparacion'], $datos['dificultad'], 
                $datos['porciones'], $datos['estado'] ?? 'Pendiente', $imagen_url, $id
            ]);
            $id_receta = $id;
            
            // Borrar ingredientes viejos
            $pdo->prepare("DELETE FROM Receta_Ingredientes WHERE id_receta = ?")->execute([$id]);
        }

        // Insertar ingredientes
        if (!empty($ings_convertidos)) {
            $stmt_ing = $pdo->prepare("INSERT INTO Receta_Ingredientes (id_receta, id_ingrediente, cantidad, unidad_medida) VALUES (?, ?, ?, ?)");
            foreach ($ings_convertidos as $ing) {
                $stmt_ing->execute([$id_receta, $ing['id_ingrediente'], $ing['cantidad'], $ing['unidad_medida']]);
            }
        }

        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        // ESTO NOS DIRÁ EL ERROR REAL
        die("<div style='background: #FEE2E2; color: #B91C1C; padding: 2rem; margin: 2rem auto; max-width: 800px; border-radius: 12px; font-family: sans-serif; border: 1px solid #F87171;'>
                <h3 style='margin-top:0;'>Error al guardar en la Base de Datos</h3>
                <p>Detalle del error de MySQL:</p>
                <code style='display: block; background: white; padding: 1rem; border-radius: 8px;'>" . $e->getMessage() . "</code>
             </div>");
    }
}

function eliminar_receta($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM Recetas WHERE id_receta = ?");
    return $stmt->execute([$id]);
}