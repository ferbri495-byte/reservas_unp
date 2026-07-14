<?php

class Database {
    private $host = "localhost";
    private $db_name = "sistema_reservas_unp";
    private $username = "root"; // Ajustar si es necesario
    private $password = ""; // Ajustar si es necesario
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            // Se utiliza utf8mb4 para coincidir con la codificación de la base de datos y evitar problemas con caracteres especiales
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4", $this->username, $this->password);
            
            // Configurar PDO para que lance excepciones en caso de error
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Opcional: configurar PDO para devolver resultados como arrays asociativos por defecto
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
