<?php

namespace App\Controllers;

/**
 * Base controller that other controllers can extend from.
 */
class BaseController {
    /**
     * Renders a view and passes data to it.
     *
     * @param string $view The name of the view file (without the .php extension).
     * @param array $data The data to be passed to the view.
     * @return string The rendered view content.
     * @throws \Exception If the view file is not found.
     */
    protected function renderView($view, $data = []) {
        // Generate a random nonce for CSP
        $nonce = base64_encode(random_bytes(16));
        $data['csp_nonce'] = $nonce;

        // Set the Content-Security-Policy header
        $cspHeader = "script-src 'self' 'nonce-{$nonce}' https://js.hcaptcha.com https://*.hcaptcha.com https://cdn.jsdelivr.net; style-src 'self' 'nonce-{$nonce}' https://cdn.jsdelivr.net; frame-src 'self' https://*.hcaptcha.com; connect-src 'self' https://*.hcaptcha.com; font-src 'self' data:; object-src 'none';";
        header("Content-Security-Policy: {$cspHeader}");

        extract($data);

        $viewPath = __DIR__ . "/../views/{$view}.php";

        if (file_exists($viewPath)) {
            ob_start();
            require $viewPath;
            return ob_get_clean();
        }

        throw new \Exception("Vista no encontrada: {$viewPath}");
    }
}