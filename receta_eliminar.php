<?php
// receta_eliminar.php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/funciones_recetas.php';
requerirLogin();

$id = (int) ($_GET['id'] ?? 0);
$r = obtener_receta($id);

if ($r && $r['id_usuario'] == $_SESSION['id_usuario']) {
    eliminar_receta($id);
}
header('Location: index.php');
exit;