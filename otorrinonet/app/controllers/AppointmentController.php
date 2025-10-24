<?php

namespace App\Controllers;

use App\Models\AppointmentModel;

/**
 * Handles the creation and storage of appointments.
 */
class AppointmentController extends BaseController {
    /**
     * Displays the form for scheduling an appointment.
     * Corresponds to the GET /agendar-cita route.
     * @return void
     */
    public function create() {
        $status = $_SESSION['status'] ?? null;
        $errors = $_SESSION['errors'] ?? [];
        $old_data = $_SESSION['old_data'] ?? [];

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
     * Processes the appointment scheduling form.
     * Corresponds to the POST /agendar-cita route.
     * @return void
     */
    public function store() {
        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'fecha_cita' => trim($_POST['fecha_cita'] ?? ''),
            'hora_cita' => trim($_POST['hora_cita'] ?? ''),
            'tipo_consulta' => trim($_POST['tipo_consulta'] ?? ''),
            'motivo' => trim($_POST['motivo'] ?? '')
        ];

        $errors = $this->validateAppointmentData($data);

        if (!$this->validateHCaptcha($_POST['h-captcha-response'] ?? '')) {
            $errors['hcaptcha'] = 'Por favor, completa la verificación de seguridad.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_data'] = $data;
            $_SESSION['status'] = ['type' => 'error', 'message' => 'Por favor, corrige los errores en el formulario.'];

            header('Location: /agendar-cita');
            exit;
        }

        $appointmentModel = new AppointmentModel();
        if (!$appointmentModel->isTimeSlotAvailable($data['fecha_cita'], $data['hora_cita'])) {
            $errors['hora_cita'] = 'Este horario ya no está disponible. Por favor, selecciona otro.';
            $_SESSION['errors'] = $errors;
            $_SESSION['old_data'] = $data;
            $_SESSION['status'] = ['type' => 'error', 'message' => 'El horario seleccionado ya no está disponible.'];

            header('Location: /agendar-cita');
            exit;
        }

        if ($appointmentModel->create($data)) {
            $_SESSION['status'] = ['type' => 'success', 'message' => '¡Tu cita ha sido agendada con éxito! Nos pondremos en contacto contigo para confirmar.'];
        } else {
            $_SESSION['status'] = ['type' => 'error', 'message' => 'Hubo un error al procesar tu solicitud. Por favor, inténtalo de nuevo.'];
            $_SESSION['old_data'] = $data;
        }

        header('Location: /agendar-cita');
        exit;
    }

    /**
     * Validates the appointment form data.
     *
     * @param array $data The data from the appointment form.
     * @return array An array of validation errors.
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
     * Validates the hCaptcha response.
     *
     * @param string $response The hCaptcha response from the form.
     * @return bool True if the response is valid, false otherwise.
     */
    private function validateHCaptcha(string $response) {
        if (empty($response)) {
            return false;
        }

        $secret = $_ENV['HCAPTCHA_SECRET_KEY'] ?? '';
        if (empty($secret)) {
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