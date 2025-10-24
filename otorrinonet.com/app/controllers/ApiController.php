<?php

namespace App\Controllers;

use App\Models\AppointmentModel;
use App\Controllers\BaseController;

class ApiController extends BaseController {
    /**
     * Outputs available time slots for the date specified by the "date" query parameter as JSON.
     *
     * Reads the "date" query parameter (expected format YYYY-MM-DD). If the date is missing or invalid,
     * sets HTTP status 400 and outputs a JSON error object: {"error":"Fecha no válida o no proporcionada."}.
     * If valid, retrieves available slots from the AppointmentModel and outputs them as JSON with
     * the Content-Type header set to application/json.
     */
    public function getAvailableTimes() {
        header('Content-Type: application/json');
        $date = $_GET['date'] ?? null;

        if (!$this->isValidDate($date)) {
            http_response_code(400); // Bad Request
            echo json_encode(['date' => $date, 'error' => 'Fecha no válida o no proporcionada.']);
            return;
        }

        try {
            $appointmentModel = new AppointmentModel();
            $availableSlots = $appointmentModel->getAvailableSlotsForDate($date);

            echo json_encode(['date' => $date, 'slots' => $availableSlots]);
        } catch (\Exception $e) {
            error_log("Error en ApiController::getAvailableTimes: " . $e->getMessage());
            http_response_code(500); // Internal Server Error
            echo json_encode(['date' => $date, 'error' => 'Ocurrió un error al procesar la solicitud.']);
        }
    }

    /**
     * Determines whether a string represents a valid date in YYYY-MM-DD format.
     *
     * @param string|null $date The date string to validate (expected format: "Y-m-d").
     * @return bool `true` if $date is a valid calendar date formatted as "Y-m-d", `false` otherwise.
     */
    private function isValidDate($date) {
        if (!$date) return false;
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
}