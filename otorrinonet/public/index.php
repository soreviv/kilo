<?php

try {
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
    $router->direct($uri, $method);
} catch (Throwable $e) { // Usamos Throwable para capturar tanto Errores como Excepciones (PHP 7+)
    // En un entorno de producción, aquí se mostraría una página de error amigable.
    // Por ahora, mostramos el mensaje de la excepción para depuración.
    http_response_code(500);
    echo '<h1>Error 500 - Internal Server Error</h1>';
    echo '<p>Se ha producido un error crítico en la aplicación.</p>';
    echo '<pre><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</pre>';
    echo '<pre><strong>Archivo:</strong> ' . htmlspecialchars($e->getFile()) . ' en la línea ' . $e->getLine() . '</pre>';
    // echo '<pre><strong>Stack Trace:</strong><br>' . htmlspecialchars($e->getTraceAsString()) . '</pre>'; // Descomentar para más detalles
}
