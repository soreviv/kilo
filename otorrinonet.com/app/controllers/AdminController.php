<?php

namespace App\Controllers;

class AdminController extends BaseController {
    /**
     * El constructor verifica si el usuario está autenticado
     * antes de permitir el acceso a cualquier método de este controlador.
     */
    public function __construct() {
        // Si no hay un usuario en la sesión, redirigir a la página de login.
        if (!isset($_SESSION['user'])) {
            header('Location: /admin/login');
            exit;
        }
    }

    /**
     * Muestra el dashboard principal del panel de administración.
     */
    public function dashboard() {
        $data = [
            'pageTitle' => 'Dashboard - Administración'
        ];

        // Usaremos una vista específica para el dashboard.
        echo $this->renderView('admin/dashboard', $data);
    }
}