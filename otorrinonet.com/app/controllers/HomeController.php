<?php

namespace App\Controllers;

class HomeController extends BaseController {
    /**
     * Muestra la página de inicio.
     */
    public function index() {
        // Lógica para obtener datos de la base de datos (si es necesario)
        $data = [
            'pageTitle' => 'Bienvenido a OtorrinoNet',
            'welcomeMessage' => 'La mejor atención para tu salud auditiva, nasal y de garganta.'
        ];

        // Renderiza la vista y le pasa los datos.
        echo $this->renderView('home', $data);
    }
}