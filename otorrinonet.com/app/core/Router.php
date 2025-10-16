<?php

class Router {
    protected $routes = [];

    /**
     * Add a new route to the routing table.
     *
     * @param string $method The request method (e.g., 'GET', 'POST').
     * @param string $uri The URI pattern.
     * @param string $view The path to the view file.
     */
    public function addRoute($method, $uri, $view) {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'view' => $view
        ];
    }

    /**
     * Add a GET route.
     *
     * @param string $uri
     * @param string $view
     */
    public function get($uri, $view) {
        $this->addRoute('GET', $uri, $view);
    }

    /**
     * Direct the request to the appropriate view.
     *
     * @param string $uri The request URI.
     * @param string $method The request method.
     */
    public function direct($uri, $method) {
        foreach ($this->routes as $route) {
            if ($route['uri'] === $uri && $route['method'] === strtoupper($method)) {
                return $this->loadView($route['view']);
            }
        }

        // If no route is found, show a 404 page.
        $this->abort(404);
    }

    /**
     * Load a view file.
     *
     * @param string $view
     */
    protected function loadView($view) {
        $viewPath = __DIR__ . "/../views/{$view}";
        if (file_exists($viewPath)) {
            return $viewPath;
        }

        throw new Exception("View file not found: {$viewPath}");
    }

    /**
     * Abort the request and show an error page.
     *
     * @param int $code The HTTP status code.
     */
    protected function abort($code = 404) {
        http_response_code($code);
        // You can create a custom error view later
        echo "Error {$code}: Page Not Found";
        die();
    }
}