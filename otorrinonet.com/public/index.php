<?php

// Cargar el autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Router.php';

// Create a new Router instance
$router = new Router();

// --- Define Routes ---
// Load the routes from the routes file
require_once __DIR__ . '/../app/routes.php';


// --- Dispatch the request ---
// Get the current URI and request method
$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_SERVER['REQUEST_METHOD'];

// Direct the request to the correct view
try {
    $viewToInclude = $router->direct($uri, $method);
    require $viewToInclude;
} catch (Exception $e) {
    // Log the error or show a generic error message
    // For now, we'll just echo the exception message for debugging
    echo 'Error: ' . $e->getMessage();
}
