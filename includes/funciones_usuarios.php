<?php
// includes/funciones_usuarios.php
require_once __DIR__ . '/../config/database.php';

function crear_usuario($nombre, $correo, $password) {
    global $pdo;
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $sql = "INSERT INTO Usuarios (nombre, correo_electronico, contrasena_hash) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$nombre, $correo, $hash]);
}

function obtener_usuario_por_correo($correo) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM Usuarios WHERE correo_electronico = ? LIMIT 1");
    $stmt->execute([$correo]);
    return $stmt->fetch();
}

function obtener_usuario_por_id($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM Usuarios WHERE id_usuario = ? LIMIT 1");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function verificar_credenciales($correo, $password) {
    $user = obtener_usuario_por_correo($correo);
    if ($user && password_verify($password, $user['contrasena_hash'])) {
        return $user;
    }
    return false;
}

function actualizar_perfil($id, $nombre, $correo, $avatar, $bio) {
    global $pdo;
    $sql = "UPDATE Usuarios SET nombre = ?, correo_electronico = ?, avatar_url = ?, biografia = ? WHERE id_usuario = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$nombre, $correo, $avatar, $bio, $id]);
}

function actualizar_password($id, $nueva_password) {
    global $pdo;
    $hash = password_hash($nueva_password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("UPDATE Usuarios SET contrasena_hash = ? WHERE id_usuario = ?");
    return $stmt->execute([$hash, $id]);
}

function eliminar_usuario($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM Usuarios WHERE id_usuario = ?");
    return $stmt->execute([$id]);
}