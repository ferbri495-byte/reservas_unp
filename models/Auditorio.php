<?php
require_once __DIR__ . '/../config/Database.php';

class Auditorio {
    private $conn;
    private $table_name = "auditorios";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function obtenerTodos() {
        $query = "SELECT a.*, d.nombre_dependencia, d.abreviatura 
                  FROM " . $this->table_name . " a
                  LEFT JOIN dependencias d ON a.id_dependencia = d.id_dependencia
                  ORDER BY d.nombre_dependencia, a.nombre_ambiente";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
