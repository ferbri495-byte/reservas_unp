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
            $duracion_alquiler = $_POST['duracion_alquiler'] ?? '';
            
            // Validación estricta de tiempo para Medio Día (máx 4 hrs)
            if ($duracion_alquiler === 'medio_dia') {
                $diff_segundos = strtotime($hora_fin) - strtotime($hora_inicio);
                if ($diff_segundos > 14400 || $diff_segundos <= 0) { // 4 horas * 60 * 60
                    header("Location: index.php?route=reservar_espacio&id=$id_auditorio&error=5");
                    exit();
                }
            }
            
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

         // Lógica de Negocio: Monto Dinámico
            $monto_total = 0.00;
            $estado = 'Pendiente';
            $tipo_evento = 'Pago_Ordinario';
            $documento_path = null;

            // Captura del rol interno
            $es_interno = ($rol === 'Docente' || $rol === 'Admin_Facultad' || $rol === 'SuperAdmin');

            // Cálculo del monto según auditorio y parámetros
            if ($id_auditorio == 1) { // Auditorio Central
                if ($es_interno) {
                    $monto_total = ($duracion_alquiler === 'medio_dia') ? 400.00 : 800.00;
                } else {
                    $tipo_actividad = $_POST['tipo_actividad'] ?? '';
                    if ($tipo_actividad === 'espectaculo') {
                        $monto_total = 2000.00;
                    } else {
                        // Académico u otro tipo de actividad externa
                        $precio_medio = $auditorio['precio_externo_medio_dia'] ?? 0;
                        $precio_completo = $auditorio['precio_externo_dia_completo'] ?? 0;
                        $monto_total = ($duracion_alquiler === 'medio_dia') ? $precio_medio : $precio_completo;
                    }
                }
            } elseif ($id_auditorio == 7) { // Ingeniería Pesquera
                $equip = $_POST['equipamiento'] ?? '';
                switch ($equip) {
                    case 'solo_ambiente':
                        $monto_total = 120.00;
                        break;
                    case 'multimedia':
                        $monto_total = 170.00;
                        break;
                    case 'multimedia_sonido':
                        $monto_total = 200.00;
                        break;
                    default:
                        $monto_total = 0.00;
                }
            } elseif ($id_auditorio == 8) { // Sala de Conferencias FIM
                $monto_total = 1000.00;
            } elseif ($id_auditorio == 9) { // Auditorio FIM
                $monto_total = 2200.00;
            } else { // Otros auditorios
                $precio_medio = $es_interno ? ($auditorio['precio_interno_medio_dia'] ?? 0) : ($auditorio['precio_externo_medio_dia'] ?? 0);
                $precio_completo = $es_interno ? ($auditorio['precio_interno_dia_completo'] ?? 0) : ($auditorio['precio_externo_dia_completo'] ?? 0);
                if ($precio_medio == $precio_completo && $precio_medio > 0) {
                    $monto_total = $precio_medio;
                } else {
                    $monto_total = ($duracion_alquiler === 'medio_dia') ? $precio_medio : $precio_completo;
                }
            }

            // Verificación de exoneración (Solo Docente/Admin)
            if ($es_interno && $rol !== 'Alumno') {
                $solicita_exoneracion = isset($_POST['solicita_exoneracion']) ? true : false;
                if ($solicita_exoneracion && isset($_FILES['documento_resolucion']) && $_FILES['documento_resolucion']['error'] === UPLOAD_ERR_OK) {
                    $tipo_evento = 'Academico_Gratuito';
                    $monto_total = 0.00;
                    $estado = 'Por_Verificar';
                    
                    $upload_dir = __DIR__ . '/../public/uploads/resoluciones/';
                    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                    $filename = time() . '_' . basename($_FILES['documento_resolucion']['name']);
                    
                    if (move_uploaded_file($_FILES['documento_resolucion']['tmp_name'], $upload_dir . $filename)) {
                        $documento_path = 'public/uploads/resoluciones/' . $filename;
                    }
                }
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
