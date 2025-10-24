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
     * Actualiza el estado de una cita.
     *
     * @param int $id
     * @param string $status
     * @return bool
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
     * Obtiene las citas para una fecha específica.
     *
     * @param string $date La fecha en formato Y-m-d.
     * @return array
     */
    public function getAppointmentsForDate(string $date) {
        $query = "SELECT hora_cita, nombre, tipo_consulta, status
                  FROM appointments
                  WHERE fecha_cita = :date
                  ORDER BY hora_cita ASC";
        try {
            $statement = $this->db->prepare($query);
            $statement->execute([':date' => $date]);
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener las citas para la fecha {$date}: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene el número de citas pendientes.
     *
     * @return int
     */
    public function getPendingAppointmentsCount() {
        $query = "SELECT COUNT(*) FROM appointments WHERE status = 'pendiente'";
        try {
            $statement = $this->db->prepare($query);
            $statement->execute();
            $count = $statement->fetchColumn();
            return $count !== false ? (int)$count : 0;
        } catch (PDOException $e) {
            error_log("Error al contar las citas pendientes: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Calcula los horarios disponibles para una fecha específica.
     *
     * @param string $date La fecha en formato Y-m-d.
     * @return array
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