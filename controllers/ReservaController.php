<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/Auditorio.php';

class ReservaController {
    
    // Muestra el formulario de reserva
    public function crear() {
        if (!isset($_GET['id'])) {
            header("Location: index.php?route=catalogo_auditorios");
            exit();
        }
        
        $id_auditorio = $_GET['id'];
        
        $auditorioModel = new Auditorio();
        $auditorios = $auditorioModel->obtenerTodos();
        $auditorio = null;
        foreach($auditorios as $aud) {
            if ($aud['id_auditorio'] == $id_auditorio) {
                $auditorio = $aud;
                break;
            }
        }
        
        if (!$auditorio) {
            header("Location: index.php?route=catalogo_auditorios");
            exit();
        }

        require_once __DIR__ . '/../views/reservas/crear.php';
    }

    // Procesa el guardado
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_usuario = $_SESSION['id_usuario'];
            $rol = $_SESSION['rol'];
            $id_auditorio = $_POST['id_auditorio'];
            
            $titulo = $_POST['titulo_evento'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $fecha = $_POST['fecha_evento'] ?? '';
            $hora_inicio = $_POST['hora_inicio'] ?? '';
            $hora_fin = $_POST['hora_fin'] ?? '';
            
            $auditorioModel = new Auditorio();
            $auditorios = $auditorioModel->obtenerTodos();
            $auditorio = null;
            foreach($auditorios as $aud) {
                if ($aud['id_auditorio'] == $id_auditorio) {
                    $auditorio = $aud;
                    break;
                }
            }

            if (!$auditorio || empty($titulo) || empty($fecha) || empty($hora_inicio) || empty($hora_fin)) {
                header("Location: index.php?route=reservar_espacio&id=$id_auditorio&error=1");
                exit();
            }

            // Validar solapamiento de horarios con holgura de 30 min
            $reservaModel = new Reserva();
            $conflicto = $reservaModel->verificarDisponibilidad($id_auditorio, $fecha, $hora_inicio, $hora_fin);
            
            if ($conflicto) {
                // Calcular hora sugerida
                $hora_sugerida = date('H:i', strtotime($conflicto['hora_fin'] . ' + 30 minutes'));
                header("Location: index.php?route=reservar_espacio&id=$id_auditorio&error=4&sug=" . urlencode($hora_sugerida));
                exit();
            }

            // Lógica de Negocio estricta según el rol
            $monto_total = 0.00;
            $estado = 'Pendiente';
            $tipo_evento = 'Pago_Ordinario';
            $documento_path = null;

            if ($rol === 'Externo') {
                $monto_total = $auditorio['precio_externo'];
                $estado = 'Pendiente';
            } elseif ($rol === 'Docente' || $rol === 'Admin_Facultad' || $rol === 'SuperAdmin') {
                $solicita_exoneracion = isset($_POST['solicita_exoneracion']) ? true : false;
                
                if ($solicita_exoneracion && isset($_FILES['documento_resolucion']) && $_FILES['documento_resolucion']['error'] === UPLOAD_ERR_OK) {
                    $tipo_evento = 'Academico_Gratuito';
                    $monto_total = 0.00;
                    $estado = 'Por_Verificar'; // La administración debe verificar la resolución
                    
                    // Subir archivo
                    $upload_dir = __DIR__ . '/../public/uploads/resoluciones/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }
                    $filename = time() . '_' . basename($_FILES['documento_resolucion']['name']);
                    $target_file = $upload_dir . $filename;
                    
                    if (move_uploaded_file($_FILES['documento_resolucion']['tmp_name'], $target_file)) {
                        $documento_path = 'public/uploads/resoluciones/' . $filename;
                    }
                } else {
                    $monto_total = $auditorio['precio_interno'];
                    $estado = 'Pendiente';
                }
            } else { 
                // Alumno (Asumimos precio interno estándar)
                $monto_total = $auditorio['precio_interno'];
                $estado = 'Pendiente';
            }

            $reservaModel = new Reserva();
            $exito = $reservaModel->crearReserva(
                $id_usuario, $id_auditorio, $titulo, $descripcion, $fecha, 
                $hora_inicio, $hora_fin, $tipo_evento, $monto_total, $estado, $documento_path
            );

            if ($exito) {
                header("Location: index.php?route=mis_reservas&exito=1");
                exit();
            } else {
                header("Location: index.php?route=reservar_espacio&id=$id_auditorio&error=2");
                exit();
            }
        }
    }

    public function misReservas() {
        $id_usuario = $_SESSION['id_usuario'];
        $reservaModel = new Reserva();
        $reservas = $reservaModel->obtenerPorUsuario($id_usuario);
        
        require_once __DIR__ . '/../views/reservas/mis_reservas.php';
    }
}
?>
