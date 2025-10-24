<?php

namespace App\Controllers;

use App\Models\AppointmentModel;

class ApiController extends BaseController {
    /**
     * Devuelve los horarios disponibles para una fecha dada en formato JSON.
     */
    public function getAvailableTimes() {
        // Obtener la fecha de la query string (ej. /api/available-times?date=2025-10-23)
        $date = $_GET['date'] ?? null;

        if (!$this->isValidDate($date)) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Fecha no válida o no proporcionada.']);
            return;
        }

        $appointmentModel = new AppointmentModel();
        $availableSlots = $appointmentModel->getAvailableSlotsForDate($date);

        // Devolver los datos como JSON.
        header('Content-Type: application/json');
        echo json_encode($availableSlots);
    }

    /**
     * Valida si una cadena es una fecha válida en formato Y-m-d.
     *
     * @param string|null $date
     * @return bool
     */
    private function isValidDate($date) {
        if (!$date) return false;
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
}