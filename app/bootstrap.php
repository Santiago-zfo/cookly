<?php
/**
 * app/bootstrap.php
 * ------------------------------------------------------------
 * Punto único de arranque de la aplicación.
 * Carga infraestructura, modelos y controladores.
 * ------------------------------------------------------------
 */
require_once __DIR__ . '/Core/paths.php';
require_once __DIR__ . '/Core/auth.php';
require_once __DIR__ . '/Core/View.php';
require_once __DIR__ . '/Core/Controller.php';

// Modelos (acceso a datos y lógica de negocio; funciones originales intactas).
require_once __DIR__ . '/Models/RecipeModel.php';
require_once __DIR__ . '/Models/UserModel.php';
require_once __DIR__ . '/Models/AdminModel.php';
require_once __DIR__ . '/Models/AdminGestionModel.php';

// Controladores.
require_once __DIR__ . '/Controllers/AuthController.php';
require_once __DIR__ . '/Controllers/RecipeController.php';
require_once __DIR__ . '/Controllers/AdminController.php';
require_once __DIR__ . '/Controllers/ReportController.php';
require_once __DIR__ . '/Controllers/ProfileController.php';
require_once __DIR__ . '/Controllers/SystemController.php';
require_once __DIR__ . '/Controllers/PageController.php';
