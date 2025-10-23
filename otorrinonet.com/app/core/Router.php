<?php

namespace App\Core;

use Exception;

class Router {
    protected $routes = [
        'GET' => [],
        'POST' => []
    ];

    /**
     * Carga las rutas definidas en un archivo.
     *
     * @param string $file
     * @return static
     */
    public static function load($file)
    {
        $router = new static;
        require $file;
        return $router;
    }

    /**
     * Registra una ruta GET.
     *
     * @param string $uri
     * @param string $handler Puede ser 'Controlador@metodo' o un archivo de vista.
     */
    public function get($uri, $handler)
    {
        $this->routes['GET'][$uri] = $handler;
    }

    /**
     * Registra una ruta POST.
     *
     * @param string $uri
     * @param string $handler 'Controlador@metodo'.
     */
    public function post($uri, $handler)
    {
        $this->routes['POST'][$uri] = $handler;
    }

    /**
     * Dirige la solicitud a la ruta y controlador correspondientes.
     *
     * @param string $uri
     * @param string $requestType
     */
    public function direct($uri, $requestType)
    {
        if (array_key_exists($uri, $this->routes[$requestType])) {
            $handler = $this->routes[$requestType][$uri];

            // Si el handler contiene '@', es una llamada a un controlador.
            if (strpos($handler, '@') !== false) {
                return $this->callAction(
                    ...explode('@', $handler)
                );
            }

            // Mantenemos la lógica anterior para vistas directas (temporalmente).
            return $this->loadView($handler);
        }

        $this->abort();
    }

    /**
     * Llama a un método de un controlador.
     *
     * @param string $controller
     * @param string $action
     */
    protected function callAction($controller, $action)
    {
        // Añade el namespace completo al controlador.
        $controller = "App\\Controllers\\{$controller}";

        if (!class_exists($controller)) {
            throw new Exception("Controlador no encontrado: {$controller}");
        }

        $controllerInstance = new $controller;

        if (!method_exists($controllerInstance, $action)) {
            throw new Exception(
                "El método {$action} no está definido en el controlador {$controller}."
            );
        }

        return $controllerInstance->$action();
    }

    /**
     * Carga un archivo de vista (lógica heredada, se recomienda no usar).
     *
     * @param string $view
     */
    protected function loadView($view)
    {
        $viewPath = __DIR__ . "/../views/{$view}";
        if (file_exists($viewPath)) {
            // Esta es una forma simplificada. En el futuro, el controlador se encargará de esto.
            require $viewPath;
            return;
        }

        throw new Exception("Vista no encontrada: {$viewPath}");
    }

    /**
     * Aborta la solicitud y muestra una página de error.
     *
     * @param int $code
     */
    protected function abort($code = 404)
    {
        http_response_code($code);

        // Aquí podrías cargar una vista de error.
        echo "Error {$code}: Página no encontrada";

        die();
    }
}