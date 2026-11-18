<?php
// logout.php
require_once __DIR__ . '/../../includes/auth.php';
cerrarSesion();
header('Location: ' . cookly_page('Login.php'));
exit;