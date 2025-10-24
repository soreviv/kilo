<?php

// Iniciar la sesión para poder usar variables $_SESSION.
session_start();

// Cargar el autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Usar las clases con su namespace.
use App\Core\Router;

// No es necesario requerir los archivos directamente gracias al autoloader.
// require_once __DIR__ . '/../app/core/Database.php';
// require_once __DIR__ . '/../app/core/Router.php';

// Cargar las rutas y obtener el router.
$router = Router::load(__DIR__ . '/../app/routes.php');

// --- Dispatch the request ---
// Obtener la URI y el método de la petición.
$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_SERVER['REQUEST_METHOD'];

// Dirigir la petición al controlador correspondiente.
try {
    // El método direct ahora se encarga de llamar al controlador y renderizar la vista.
    $router->direct($uri, $method);
} catch (Exception $e) {
    // En un entorno de producción, aquí se mostraría una página de error amigable.
    // Por ahora, mostramos el mensaje de la excepción para depuración.
    http_response_code(500);
    echo 'Error: ' . $e->getMessage();
}
