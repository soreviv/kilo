<?php

namespace App\Controllers;

use App\Models\ServiceModel;

class ServicesController extends BaseController {
    /**
     * Muestra la página de servicios.
     */
    public function index() {
        // Creamos una instancia del modelo de servicios.
        $serviceModel = new ServiceModel();

        // Obtenemos todos los servicios activos desde el modelo.
        $services = $serviceModel->getAllActiveServices();

        // Pasamos los datos a la vista.
        $data = [
            'pageTitle' => 'Nuestros Servicios',
            'services' => $services
        ];

        echo $this->renderView('servicios', $data);
    }
}