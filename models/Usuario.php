<?php
require_once __DIR__ . '/../config/Database.php';

class Usuario {
    private $conn;
    private $table_name = "usuarios";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Registrar un nuevo usuario con contraseña encriptada
    public function registrar($nombre_completo, $correo, $password, $rol, $id_dependencia = null) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (nombre_completo, correo, password, rol, id_dependencia) 
                  VALUES (:nombre_completo, :correo, :password, :rol, :id_dependencia)";
        
        $stmt = $this->conn->prepare($query);

        // Encriptar la contraseña
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt->bindParam(':nombre_completo', $nombre_completo);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':rol', $rol);
        $stmt->bindParam(':id_dependencia', $id_dependencia);

        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch (PDOException $e) {
            // Manejo de errores (ej. el correo ya existe por el UNIQUE)
            error_log("Error al registrar usuario: " . $e->getMessage());
            return false;
        }
        return false;
    }

    // Validar login verificando el hash
    public function login($correo, $password) {
        $query = "SELECT id_usuario, nombre_completo, correo, password, rol, id_dependencia 
                  FROM " . $this->table_name . " 
                  WHERE correo = :correo LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // Verificar si la contraseña coincide con el hash almacenado
            if (password_verify($password, $row['password'])) {
                return $row; // Retornamos los datos del usuario si es exitoso
            }
        }
        return false; // Credenciales incorrectas o usuario no encontrado
    }
}
?>
