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
}