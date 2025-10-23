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
}