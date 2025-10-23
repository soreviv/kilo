<?php

namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

class UserModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Busca un usuario por su nombre de usuario.
     *
     * @param string $username
     * @return array|false Los datos del usuario si se encuentra, o false si no.
     */
    public function findByUsername(string $username) {
        $query = "SELECT id, username, password_hash, rol FROM users WHERE username = :username AND activo = TRUE";

        try {
            $statement = $this->db->prepare($query);
            $statement->execute([':username' => $username]);
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al buscar usuario: " . $e->getMessage());
            return false;
        }
    }
}