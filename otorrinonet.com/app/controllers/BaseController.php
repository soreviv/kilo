<?php

namespace App\Controllers;

class BaseController {
    /**
     * Carga una vista y le pasa datos.
     *
     * @param string $view El nombre del archivo de la vista (sin la extensión .php).
     * @param array $data Los datos que se pasarán a la vista.
     */
    protected function renderView($view, $data = []) {
        // Extrae los datos para que estén disponibles como variables en la vista.
        extract($data);

        $viewPath = __DIR__ . "/../views/{$view}.php";

        if (file_exists($viewPath)) {
            // Inicia el buffer de salida para capturar el contenido de la vista.
            ob_start();
            require $viewPath;
            // Limpia y devuelve el contenido del buffer.
            return ob_get_clean();
        }

        // Si la vista no existe, lanza una excepción.
        throw new \Exception("Vista no encontrada: {$viewPath}");
    }
}