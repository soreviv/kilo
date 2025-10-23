<?php

namespace App\Controllers;

class LegalController extends BaseController {
    /**
     * Muestra la página de Aviso de Privacidad.
     */
    public function privacyPolicy() {
        $data = [
            'pageTitle' => 'Aviso de Privacidad'
        ];

        echo $this->renderView('aviso-privacidad', $data);
    }

    /**
     * Muestra la página de Política de Cookies.
     */
    public function cookiePolicy() {
        $data = [
            'pageTitle' => 'Política de Cookies'
        ];

        echo $this->renderView('politica-cookies', $data);
    }

    /**
     * Muestra la página de Términos y Condiciones.
     */
    public function termsAndConditions() {
        $data = [
            'pageTitle' => 'Términos y Condiciones'
        ];

        echo $this->renderView('terminos-condiciones', $data);
    }
}