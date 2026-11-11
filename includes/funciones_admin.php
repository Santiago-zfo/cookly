<?php
/** Herramientas de administración que faltaban en pabon. */
require_once __DIR__ . '/funciones_recetas.php';

if (!function_exists('obtener_estadisticas_generales')) {
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
}

if (!function_exists('listar_recetas_pendientes')) {
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
}

if (!function_exists('cambiar_estado_receta')) {
function cambiar_estado_receta($id_receta, $nuevo_estado) {
    global $pdo;
    $estados_validos = ['Pendiente', 'Aceptado', 'Rechazado'];
    if (!in_array($nuevo_estado, $estados_validos)) return false;

    $stmt = $pdo->prepare("UPDATE Recetas SET estado = ? WHERE id_receta = ?");
    $res = $stmt->execute([$nuevo_estado, $id_receta]);
    
    if ($res) registrar_log("El admin ha cambiado la receta ID $id_receta a estado: $nuevo_estado", 'INFO');
    return $res;
}
}
