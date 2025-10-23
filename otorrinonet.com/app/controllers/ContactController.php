<?php

namespace App\Controllers;

use App\Models\ContactMessageModel;

class ContactController extends BaseController {
    /**
     * Muestra el formulario de contacto.
     */
    public function create() {
        $status = $_SESSION['status'] ?? null;
        $errors = $_SESSION['errors'] ?? [];
        $old_data = $_SESSION['old_data'] ?? [];

        unset($_SESSION['status'], $_SESSION['errors'], $_SESSION['old_data']);

        $data = [
            'pageTitle' => 'Contacto',
            'status' => $status,
            'errors' => $errors,
            'old_data' => $old_data
        ];

        echo $this->renderView('contacto', $data);
    }

    /**
     * Procesa el formulario de contacto.
     */
    public function store() {
        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'asunto' => trim($_POST['asunto'] ?? ''),
            'mensaje' => trim($_POST['mensaje'] ?? '')
        ];

        $errors = $this->validateContactData($data);

        if (!$this->validateHCaptcha($_POST['h-captcha-response'] ?? '')) {
            $errors['hcaptcha'] = 'Por favor, completa la verificación de seguridad.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_data'] = $data;
            $_SESSION['status'] = ['type' => 'error', 'message' => 'Por favor, corrige los errores en el formulario.'];

            header('Location: /contacto');
            exit;
        }

        $contactMessageModel = new ContactMessageModel();
        if ($contactMessageModel->create($data)) {
            $_SESSION['status'] = ['type' => 'success', 'message' => '¡Gracias por tu mensaje! Nos pondremos en contacto contigo pronto.'];
        } else {
            $_SESSION['status'] = ['type' => 'error', 'message' => 'Hubo un error al enviar tu mensaje. Por favor, inténtalo de nuevo.'];
            $_SESSION['old_data'] = $data;
        }

        header('Location: /contacto');
        exit;
    }

    private function validateContactData(array $data) {
        $errors = [];
        if (empty($data['nombre'])) $errors['nombre'] = 'El nombre es obligatorio.';
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'El correo electrónico no es válido.';
        if (empty($data['asunto'])) $errors['asunto'] = 'El asunto es obligatorio.';
        if (empty($data['mensaje'])) $errors['mensaje'] = 'El mensaje es obligatorio.';
        return $errors;
    }

    private function validateHCaptcha(string $response) {
        if (empty($response)) return false;
        $secret = $_ENV['HCAPTCHA_SECRET_KEY'] ?? '';
        if (empty($secret)) {
            error_log("hCaptcha secret key is not set.");
            return false;
        }
        $data = ['secret' => $secret, 'response' => $response];
        $options = ['http' => ['header' => "Content-type: application/x-www-form-urlencoded\r\n", 'method' => 'POST', 'content' => http_build_query($data)]];
        $context = stream_context_create($options);
        $verify = file_get_contents('https://hcaptcha.com/siteverify', false, $context);
        $captchaSuccess = json_decode($verify);
        return $captchaSuccess->success ?? false;
    }
}