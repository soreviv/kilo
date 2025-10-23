<?php

namespace App\Controllers;

use App\Models\AppointmentModel;

class AppointmentController extends BaseController {
    /**
     * Muestra el formulario para agendar una cita.
     * Corresponde a la ruta GET /agendar-cita.
     */
    public function create() {
        // Obtenemos los mensajes de la sesión para mostrarlos en la vista.
        $status = $_SESSION['status'] ?? null;
        $errors = $_SESSION['errors'] ?? [];
        $old_data = $_SESSION['old_data'] ?? [];

        // Limpiamos los mensajes de la sesión después de obtenerlos.
        unset($_SESSION['status'], $_SESSION['errors'], $_SESSION['old_data']);

        $data = [
            'pageTitle' => 'Agendar Cita',
            'status' => $status,
            'errors' => $errors,
            'old_data' => $old_data
        ];

        echo $this->renderView('agendar-cita', $data);
    }

    /**
     * Procesa el formulario de agendamiento de cita.
     * Corresponde a la ruta POST /agendar-cita.
     */
    public function store() {
        // 1. Recoger los datos del formulario.
        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'fecha_cita' => trim($_POST['fecha_cita'] ?? ''),
            'hora_cita' => trim($_POST['hora_cita'] ?? ''),
            'tipo_consulta' => trim($_POST['tipo_consulta'] ?? ''),
            'motivo' => trim($_POST['motivo'] ?? '')
        ];

        // 2. Validar los datos.
        $errors = $this->validateAppointmentData($data);

        // 2.1. Validar hCaptcha.
        if (!$this->validateHCaptcha($_POST['h-captcha-response'] ?? '')) {
            $errors['hcaptcha'] = 'Por favor, completa la verificación de seguridad.';
        }

        // Si hay errores, guardamos los errores y los datos antiguos en la sesión y redirigimos.
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_data'] = $data;
            $_SESSION['status'] = ['type' => 'error', 'message' => 'Por favor, corrige los errores en el formulario.'];

            header('Location: /agendar-cita');
            exit;
        }

        // 3. Si la validación es exitosa, verificamos la disponibilidad.
        $appointmentModel = new AppointmentModel();
        if (!$appointmentModel->isTimeSlotAvailable($data['fecha_cita'], $data['hora_cita'])) {
            $errors['hora_cita'] = 'Este horario ya no está disponible. Por favor, selecciona otro.';
            $_SESSION['errors'] = $errors;
            $_SESSION['old_data'] = $data;
            $_SESSION['status'] = ['type' => 'error', 'message' => 'El horario seleccionado ya no está disponible.'];

            header('Location: /agendar-cita');
            exit;
        }

        // 4. Si el horario está disponible, intentamos guardar la cita.
        if ($appointmentModel->create($data)) {
            // Si se guarda correctamente, establecemos un mensaje de éxito.
            $_SESSION['status'] = ['type' => 'success', 'message' => '¡Tu cita ha sido agendada con éxito! Nos pondremos en contacto contigo para confirmar.'];
        } else {
            // Si hay un error al guardar, establecemos un mensaje de error.
            $_SESSION['status'] = ['type' => 'error', 'message' => 'Hubo un error al procesar tu solicitud. Por favor, inténtalo de nuevo.'];
            $_SESSION['old_data'] = $data;
        }

        // 4. Redirigimos de vuelta al formulario.
        header('Location: /agendar-cita');
        exit;
    }

    /**
     * Valida los datos del formulario de cita.
     *
     * @param array $data
     * @return array
     */
    private function validateAppointmentData(array $data) {
        $errors = [];

        if (empty($data['nombre'])) {
            $errors['nombre'] = 'El nombre es obligatorio.';
        }
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'El correo electrónico no es válido.';
        }
        if (empty($data['telefono'])) {
            $errors['telefono'] = 'El teléfono es obligatorio.';
        }
        if (empty($data['fecha_cita'])) {
            $errors['fecha_cita'] = 'La fecha de la cita es obligatoria.';
        }
        if (empty($data['hora_cita'])) {
            $errors['hora_cita'] = 'La hora de la cita es obligatoria.';
        }
        if (empty($data['tipo_consulta'])) {
            $errors['tipo_consulta'] = 'El tipo de consulta es obligatorio.';
        }

        return $errors;
    }

    /**
     * Valida la respuesta de hCaptcha.
     *
     * @param string $response
     * @return bool
     */
    private function validateHCaptcha(string $response) {
        if (empty($response)) {
            return false;
        }

        $secret = $_ENV['HCAPTCHA_SECRET_KEY'] ?? '';
        if (empty($secret)) {
            // Si no hay clave secreta, no se puede validar.
            // Es mejor fallar de forma segura.
            error_log("hCaptcha secret key is not set.");
            return false;
        }

        $data = [
            'secret' => $secret,
            'response' => $response
        ];

        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];

        $context = stream_context_create($options);
        $verify = file_get_contents('https://hcaptcha.com/siteverify', false, $context);
        $captchaSuccess = json_decode($verify);

        return $captchaSuccess->success ?? false;
    }
}