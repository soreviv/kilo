<?php

namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

class AppointmentModel {
    private $db;

    public function __construct() {
        // Obtenemos la instancia de la conexión a la base de datos.
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Guarda una nueva cita en la base de datos.
     *
     * @param array $data Los datos de la cita.
     * @return bool True si se guardó correctamente, false en caso contrario.
     */
    public function create(array $data) {
        // Campos esperados en el array $data.
        $fields = [
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'telefono' => $data['telefono'],
            'fecha_cita' => $data['fecha_cita'],
            'hora_cita' => $data['hora_cita'],
            'tipo_consulta' => $data['tipo_consulta'],
            'motivo' => $data['motivo']
        ];

        // Construimos la consulta SQL.
        $columns = implode(', ', array_keys($fields));
        $placeholders = ':' . implode(', :', array_keys($fields));
        $query = "INSERT INTO appointments ($columns) VALUES ($placeholders)";

        try {
            $statement = $this->db->prepare($query);

            // Hacemos el bind de los valores.
            foreach ($fields as $key => $value) {
                $statement->bindValue(":$key", $value);
            }

            // Ejecutamos la consulta.
            return $statement->execute();
        } catch (PDOException $e) {
            // En una aplicación real, se registraría el error detallado.
            error_log("Error al crear la cita: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica si un horario de cita está disponible.
     *
     * @param string $fecha
     * @param string $hora
     * @return bool
     */
    public function isTimeSlotAvailable(string $fecha, string $hora): bool {
        $query = "SELECT COUNT(*) FROM appointments
                  WHERE fecha_cita = :fecha_cita
                  AND hora_cita = :hora_cita
                  AND status != 'cancelada'";

        try {
            $statement = $this->db->prepare($query);
            $statement->execute([
                ':fecha_cita' => $fecha,
                ':hora_cita' => $hora
            ]);

            return $statement->fetchColumn() == 0;
        } catch (PDOException $e) {
            error_log("Error al verificar disponibilidad: " . $e->getMessage());
            // Si hay un error, es más seguro asumir que el horario no está disponible.
            return false;
        }
    }

    /**
     * Obtiene todas las citas, ordenadas por fecha y hora.
     *
     * @return array
     */
    public function getAllAppointments() {
        $query = "SELECT * FROM appointments ORDER BY fecha_cita DESC, hora_cita ASC";
        try {
            $statement = $this->db->prepare($query);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener todas las citas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Update the status of an appointment identified by its ID.
     *
     * @param int $id The appointment ID to update.
     * @param string $status The new status to assign to the appointment.
     * @return bool `true` if the update succeeded, `false` otherwise.
     */
    public function updateStatus(int $id, string $status): bool {
        $query = "UPDATE appointments SET status = :status WHERE id = :id";
        try {
            $statement = $this->db->prepare($query);
            return $statement->execute([':status' => $status, ':id' => $id]);
        } catch (PDOException $e) {
            error_log("Error al actualizar el estado de la cita: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Retrieve all appointments scheduled for the current date, ordered by time.
     *
     * @return array An array of associative arrays representing today's appointments; empty array if there are none or an error occurs.
     */
    public function getAppointmentsForToday() {
        $query = "SELECT * FROM appointments WHERE fecha_cita = CURRENT_DATE ORDER BY hora_cita ASC";
        try {
            $statement = $this->db->prepare($query);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener las citas de hoy: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Retrieve the count of appointments whose status is 'pendiente'.
     *
     * @return int The number of appointments with status 'pendiente'; returns 0 if a database error occurs.
     */
    public function getPendingAppointmentsCount() {
        $query = "SELECT COUNT(*) FROM appointments WHERE status = 'pendiente'";
        try {
            $statement = $this->db->prepare($query);
            $statement->execute();
            return $statement->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error al contar las citas pendientes: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Compute available 30-minute appointment time slots for a given date based on active schedule configuration and existing bookings.
     *
     * @param string $date The date to check in `Y-m-d` format.
     * @return string[] List of available time strings in `H:i` format (e.g., "14:30"); returns an empty array if no slots are available or on error.
     */
    public function getAvailableSlotsForDate(string $date) {
        try {
            // 1. Obtener el día de la semana (0=Domingo, 6=Sábado).
            $dayOfWeek = date('w', strtotime($date));

            // 2. Obtener los horarios de atención para ese día de la semana.
            $scheduleQuery = "SELECT hora_inicio, hora_fin FROM schedule_config WHERE dia_semana = :dayOfWeek AND activo = TRUE";
            $scheduleStmt = $this->db->prepare($scheduleQuery);
            $scheduleStmt->execute([':dayOfWeek' => $dayOfWeek]);
            $schedules = $scheduleStmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($schedules)) {
                return []; // No hay horarios configurados para este día.
            }

            // 3. Obtener las citas ya agendadas para esa fecha.
            $appointmentsQuery = "SELECT hora_cita FROM appointments WHERE fecha_cita = :date AND status != 'cancelada'";
            $appointmentsStmt = $this->db->prepare($appointmentsQuery);
            $appointmentsStmt->execute([':date' => $date]);
            $bookedSlots = $appointmentsStmt->fetchAll(PDO::FETCH_COLUMN, 0);

            // 4. Generar todos los posibles horarios y filtrar los ocupados.
            $availableSlots = [];
            $slotDuration = 30; // Duración de cada cita en minutos

            foreach ($schedules as $schedule) {
                $start = new \DateTime($schedule['hora_inicio']);
                $end = new \DateTime($schedule['hora_fin']);

                while ($start < $end) {
                    $slot = $start->format('H:i:s');
                    if (!in_array($slot, $bookedSlots)) {
                        $availableSlots[] = $start->format('H:i');
                    }
                    $start->add(new \DateInterval('PT' . $slotDuration . 'M'));
                }
            }

            return $availableSlots;
        } catch (\Exception $e) {
            error_log("Error al calcular horarios disponibles: " . $e->getMessage());
            return [];
        }
    }
}