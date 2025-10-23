<?php

namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

class ContactMessageModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Guarda un nuevo mensaje de contacto en la base de datos.
     *
     * @param array $data Los datos del mensaje.
     * @return bool True si se guardó correctamente, false en caso contrario.
     */
    public function create(array $data) {
        $fields = [
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'telefono' => $data['telefono'],
            'asunto' => $data['asunto'],
            'mensaje' => $data['mensaje']
        ];

        $columns = implode(', ', array_keys($fields));
        $placeholders = ':' . implode(', :', array_keys($fields));
        $query = "INSERT INTO contact_messages ($columns) VALUES ($placeholders)";

        try {
            $statement = $this->db->prepare($query);
            foreach ($fields as $key => $value) {
                $statement->bindValue(":$key", $value);
            }
            return $statement->execute();
        } catch (PDOException $e) {
            error_log("Error al guardar mensaje de contacto: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene todos los mensajes de contacto, ordenados por fecha de envío.
     *
     * @return array
     */
    public function getAllMessages() {
        $query = "SELECT * FROM contact_messages ORDER BY fecha_envio DESC";
        try {
            $statement = $this->db->prepare($query);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener todos los mensajes: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Actualiza el estado de un mensaje.
     *
     * @param int $id
     * @param string $status
     * @return bool
     */
    public function updateStatus(int $id, string $status): bool {
        $query = "UPDATE contact_messages SET status = :status WHERE id = :id";
        try {
            $statement = $this->db->prepare($query);
            return $statement->execute([':status' => $status, ':id' => $id]);
        } catch (PDOException $e) {
            error_log("Error al actualizar el estado del mensaje: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene el número de mensajes no leídos.
     *
     * @return int
     */
    public function getUnreadMessagesCount() {
        $query = "SELECT COUNT(*) FROM contact_messages WHERE status = 'nuevo'";
        try {
            $statement = $this->db->prepare($query);
            $statement->execute();
            return $statement->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error al contar los mensajes no leídos: " . $e->getMessage());
            return 0;
        }
    }
}