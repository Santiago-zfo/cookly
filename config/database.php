<?php
// config/database.php
require_once __DIR__ . '/../includes/auth.php';

$host = 'localhost';
$dbname = 'cookly';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    registrar_log("Fallo de conexión a la BD: " . $e->getMessage(), 'CRITICAL');
    die('Error de conexión a la base de datos. Por favor, contacta al administrador.');
}