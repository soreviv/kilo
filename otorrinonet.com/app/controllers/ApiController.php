<?php

namespace App\Controllers;

use App\Models\AppointmentModel;
use App\Controllers\BaseController;

class ApiController extends BaseController {
    /**
     * Devuelve los horarios disponibles para una fecha dada en formato JSON.
     */
    public function getAvailableTimes() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['error' => 'Método no permitido.']);
            return;
        }

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
     * Valida si una cadena es una fecha válida en formato Y-m-d.
     *
     * @param string|null $date
     * @return bool
     */
    private function isValidDate($date) {
        if (!$date) return false;
        $dateObj = \DateTimeImmutable::createFromFormat('Y-m-d', $date);
        return $dateObj && $dateObj->format('Y-m-d') === $date;
    }
}