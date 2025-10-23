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
        $appointmentModel = new \App\Models\AppointmentModel();
        $contactMessageModel = new \App\Models\ContactMessageModel();

        $data = [
            'pageTitle' => 'Dashboard - Administración',
            'appointmentsToday' => $appointmentModel->getAppointmentsForToday(),
            'pendingAppointmentsCount' => $appointmentModel->getPendingAppointmentsCount(),
            'unreadMessagesCount' => $contactMessageModel->getUnreadMessagesCount(),
        ];

        echo $this->renderView('admin/dashboard', $data);
    }

    /**
     * Muestra la lista de citas.
     */
    public function listAppointments() {
        $appointmentModel = new \App\Models\AppointmentModel();
        $appointments = $appointmentModel->getAllAppointments();

        $data = [
            'pageTitle' => 'Citas Agendadas - Administración',
            'appointments' => $appointments
        ];

        echo $this->renderView('admin/appointments', $data);
    }

    /**
     * Muestra la lista de mensajes de contacto.
     */
    public function listMessages() {
        $contactMessageModel = new \App\Models\ContactMessageModel();
        $messages = $contactMessageModel->getAllMessages();

        $data = [
            'pageTitle' => 'Mensajes de Contacto - Administración',
            'messages' => $messages
        ];

        echo $this->renderView('admin/messages', $data);
    }

    /**
     * Actualiza el estado de una cita.
     */
    public function updateAppointmentStatus() {
        $id = $_POST['id'] ?? null;
        $status = $_POST['status'] ?? null;

        if ($id && $status) {
            $appointmentModel = new \App\Models\AppointmentModel();
            $appointmentModel->updateStatus((int)$id, $status);
        }

        // Redirigir de vuelta a la lista de citas.
        header('Location: /admin/appointments');
        exit;
    }

    /**
     * Actualiza el estado de un mensaje de contacto.
     */
    public function updateMessageStatus() {
        $id = $_POST['id'] ?? null;
        $status = $_POST['status'] ?? null;

        if ($id && $status) {
            $contactMessageModel = new \App\Models\ContactMessageModel();
            $contactMessageModel->updateStatus((int)$id, $status);
        }

        // Redirigir de vuelta a la lista de mensajes.
        header('Location: /admin/messages');
        exit;
    }
}