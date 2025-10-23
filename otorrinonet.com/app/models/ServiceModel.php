<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class ServiceModel {
    private $db;

    public function __construct() {
        // Obtenemos la instancia de la conexión a la base de datos.
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtiene todos los servicios activos de la base de datos.
     *
     * @return array
     */
    public function getAllActiveServices() {
        try {
            // Preparamos la consulta para obtener solo los servicios activos.
            $query = "SELECT nombre, descripcion, categoria, precio FROM services WHERE activo = TRUE ORDER BY categoria, nombre";
            $statement = $this->db->prepare($query);
            $statement->execute();

            // Devolvemos todos los servicios encontrados.
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // En una aplicación real, aquí se registraría el error.
            // Por ahora, devolvemos un array vacío para evitar que la aplicación falle.
            error_log("Error al obtener servicios: " . $e->getMessage());
            return [];
        }
    }
}