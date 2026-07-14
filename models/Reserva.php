<?php
require_once __DIR__ . '/../config/Database.php';

class Reserva {
    private $conn;
    private $table_name = "solicitudes_reserva";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function crearReserva($id_usuario, $id_auditorio, $titulo, $descripcion, $fecha, $hora_inicio, $hora_fin, $tipo_evento, $monto, $estado, $documento) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (id_usuario, id_auditorio, titulo_evento, descripcion, fecha_evento, hora_inicio, hora_fin, tipo_evento, monto_total, estado, documento_resolucion) 
                  VALUES (:id_usuario, :id_auditorio, :titulo, :descripcion, :fecha, :hora_inicio, :hora_fin, :tipo_evento, :monto, :estado, :documento)";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':id_auditorio', $id_auditorio);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':hora_inicio', $hora_inicio);
        $stmt->bindParam(':hora_fin', $hora_fin);
        $stmt->bindParam(':tipo_evento', $tipo_evento);
        $stmt->bindParam(':monto', $monto);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':documento', $documento);

        return $stmt->execute();
    }

    public function obtenerPorUsuario($id_usuario) {
        $query = "SELECT r.*, a.nombre_ambiente 
                  FROM " . $this->table_name . " r
                  JOIN auditorios a ON r.id_auditorio = a.id_auditorio
                  WHERE r.id_usuario = :id_usuario
                  ORDER BY r.fecha_solicitud DESC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function verificarDisponibilidad($id_auditorio, $fecha, $hora_inicio, $hora_fin) {
        $query = "SELECT hora_fin FROM " . $this->table_name . " 
                  WHERE id_auditorio = :id_auditorio 
                  AND fecha_evento = :fecha 
                  AND estado IN ('Confirmada', 'Por_Verificar', 'Esperando_Pago')
                  AND (hora_inicio < :hora_fin AND ADDTIME(hora_fin, '00:30:00') > :hora_inicio)
                  ORDER BY hora_fin DESC LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_auditorio', $id_auditorio);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':hora_inicio', $hora_inicio);
        $stmt->bindParam(':hora_fin', $hora_fin);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }
}
?>
