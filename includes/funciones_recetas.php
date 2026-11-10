<?php
// includes/funciones_recetas.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/auth.php';

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

function listar_todas_las_recetas($limite = 6, $offset = 0) {
    global $pdo;
    $sql = "SELECT r.*, c.nombre AS categoria, u.nombre AS autor 
            FROM Recetas r 
            INNER JOIN Categorias c ON r.id_categoria = c.id_categoria 
            INNER JOIN Usuarios u ON r.id_usuario = u.id_usuario 
            WHERE r.estado = 'Aceptado'
            ORDER BY r.id_receta DESC
            LIMIT ? OFFSET ?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $limite, PDO::PARAM_INT);
    $stmt->bindValue(2, $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function contar_todas_las_recetas() {
    global $pdo;
    return $pdo->query("SELECT COUNT(*) FROM Recetas WHERE estado = 'Aceptado'")->fetchColumn();
}

// --- NUEVA FUNCIÓN DE BÚSQUEDA ---
function buscar_recetas_ajax($termino) {
    global $pdo;
    $busqueda = "%" . $termino . "%";
    
    $sql = "SELECT r.*, c.nombre AS categoria, u.nombre AS autor 
            FROM Recetas r 
            INNER JOIN Categorias c ON r.id_categoria = c.id_categoria 
            INNER JOIN Usuarios u ON r.id_usuario = u.id_usuario 
            WHERE r.estado = 'Aceptado' 
            AND (r.titulo LIKE ? OR c.nombre LIKE ? OR u.nombre LIKE ?)
            ORDER BY r.id_receta DESC 
            LIMIT 20";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$busqueda, $busqueda, $busqueda]);
    return $stmt->fetchAll();
}
// ----------------------------------

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
    
    $tiempo = (int)($datos['tiempo_preparacion'] ?? 0);
    $porciones = (int)($datos['porciones'] ?? 0);
    
    if ($tiempo <= 0 || $porciones <= 0) {
        die("<div style='background: #FEE2E2; color: #B91C1C; padding: 2rem; margin: 2rem auto; max-width: 800px; border-radius: 12px; font-family: sans-serif; text-align: center;'>
                <h3 style='margin-top:0;'>Error de Validación</h3>
                <p>El tiempo y las porciones deben ser mayores a 0.</p>
                <a href='javascript:history.back()' style='display:inline-block; margin-top:1rem; padding: 0.5rem 1rem; background: #B91C1C; color: white; text-decoration: none; border-radius: 8px;'>Volver al formulario</a>
             </div>");
    }

    $imagen_url = trim($datos['imagen_url'] ?? '');
    if (isset($_FILES['imagen_file']) && $_FILES['imagen_file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../public/IMG/recetas/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        
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
            $sql = "INSERT INTO Recetas (id_categoria, id_usuario, titulo, descripcion, instrucciones, tiempo_preparacion, dificultad, porciones, estado, imagen_url) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pendiente', ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $datos['id_categoria'], $_SESSION['id_usuario'], $datos['titulo'], 
                $datos['descripcion'] ?? '', $datos['instrucciones'], $tiempo, 
                $datos['dificultad'], $porciones, $imagen_url
            ]);
            $id_receta = $pdo->lastInsertId();
        } else {
            $sql = "UPDATE Recetas SET id_categoria=?, titulo=?, descripcion=?, instrucciones=?, tiempo_preparacion=?, dificultad=?, porciones=?, estado='Pendiente', imagen_url=? WHERE id_receta=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $datos['id_categoria'], $datos['titulo'], $datos['descripcion'] ?? '', 
                $datos['instrucciones'], $tiempo, $datos['dificultad'], 
                $porciones, $imagen_url, $id
            ]);
            $id_receta = $id;
            $pdo->prepare("DELETE FROM Receta_Ingredientes WHERE id_receta = ?")->execute([$id]);
        }

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

function obtener_estadisticas_generales() {
    global $pdo;
    $stats = [];
    $stats['total_usuarios'] = $pdo->query("SELECT COUNT(*) FROM Usuarios")->fetchColumn();
    $stats['total_recetas'] = $pdo->query("SELECT COUNT(*) FROM Recetas")->fetchColumn();
    $stats['total_categorias'] = $pdo->query("SELECT COUNT(*) FROM Categorias")->fetchColumn();
    
    $sql_cat = "SELECT c.nombre, COUNT(r.id_receta) as cantidad FROM Categorias c LEFT JOIN Recetas r ON c.id_categoria = r.id_categoria GROUP BY c.id_categoria ORDER BY cantidad DESC";
    $stats['recetas_por_categoria'] = $pdo->query($sql_cat)->fetchAll();
    
    $sql_dif = "SELECT dificultad, COUNT(*) as cantidad FROM Recetas GROUP BY dificultad";
    $stats['recetas_por_dificultad'] = $pdo->query($sql_dif)->fetchAll();
    
    $sql_top = "SELECT u.nombre, COUNT(r.id_receta) as cantidad FROM Usuarios u LEFT JOIN Recetas r ON u.id_usuario = r.id_usuario GROUP BY u.id_usuario ORDER BY cantidad DESC LIMIT 3";
    $stats['top_usuarios'] = $pdo->query($sql_top)->fetchAll();

    return $stats;
}

function listar_recetas_pendientes() {
    global $pdo;
    $sql = "SELECT r.*, c.nombre AS categoria, u.nombre AS autor 
            FROM Recetas r 
            INNER JOIN Categorias c ON r.id_categoria = c.id_categoria 
            INNER JOIN Usuarios u ON r.id_usuario = u.id_usuario 
            WHERE r.estado = 'Pendiente'
            ORDER BY r.id_receta DESC";
    return $pdo->query($sql)->fetchAll();
}

function cambiar_estado_receta($id_receta, $nuevo_estado) {
    global $pdo;
    $estados_validos = ['Pendiente', 'Aceptado', 'Rechazado'];
    if (!in_array($nuevo_estado, $estados_validos)) return false;

    $stmt = $pdo->prepare("UPDATE Recetas SET estado = ? WHERE id_receta = ?");
    return $stmt->execute([$nuevo_estado, $id_receta]);
}
// --- NUEVA FUNCIÓN PARA REPORTES FILTRADOS ---
// --- FUNCIÓN PARA REPORTES FILTRADOS (ACTUALIZADA) ---
function obtener_recetas_filtradas($filtros = []) {
    global $pdo;
    
    // Agregamos dificultad y porciones a la consulta
    $sql = "SELECT r.id_receta, r.titulo, r.estado, r.fecha_creacion, 
                   r.dificultad, r.porciones,
                   c.nombre AS categoria, u.nombre AS autor 
            FROM Recetas r 
            INNER JOIN Categorias c ON r.id_categoria = c.id_categoria 
            INNER JOIN Usuarios u ON r.id_usuario = u.id_usuario 
            WHERE 1=1";
            
    $params = [];

    // Filtro por Categoría
    if (!empty($filtros['categoria'])) {
        $sql .= " AND r.id_categoria = ?";
        $params[] = $filtros['categoria'];
    }
    
    // Filtro por Fecha Inicio
    if (!empty($filtros['fecha_inicio'])) {
        $sql .= " AND DATE(r.fecha_creacion) >= ?";
        $params[] = $filtros['fecha_inicio'];
    }
    
    // Filtro por Fecha Fin
    if (!empty($filtros['fecha_fin'])) {
        $sql .= " AND DATE(r.fecha_creacion) <= ?";
        $params[] = $filtros['fecha_fin'];
    }

    // Filtro por Autor (Búsqueda parcial)
    if (!empty($filtros['autor'])) {
        $sql .= " AND u.nombre LIKE ?";
        $params[] = "%" . $filtros['autor'] . "%";
    }

    $sql .= " ORDER BY r.fecha_creacion DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}