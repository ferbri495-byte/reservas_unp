<?php
// controllers/AdminController.php

require_once 'config/Database.php'; 

class AdminController {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // ==========================================
    // 1. DASHBOARD DEL SUPERADMIN
    // ==========================================
    public function superadminDashboard() {
        $esSuperAdmin = (isset($_SESSION['correo']) && $_SESSION['correo'] === 'admin@unp.edu.pe') || 
                        (isset($_SESSION['rol']) && $_SESSION['rol'] === 'SuperAdmin');

        if (!$esSuperAdmin) {
            header("Location: index.php?route=logout");
            exit();
        }

        $database = new Database();
        $db = $database->getConnection();

        if (!$db) {
            die("Error: No se pudo establecer la conexión.");
        }

        try {
            // A. Cantidades de indicadores
            $queryPendientes = $db->query("SELECT COUNT(*) as total FROM solicitudes_reserva WHERE estado = 'Pendiente'");
            $totalPendientes = $queryPendientes->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

            $queryAprobados = $db->query("SELECT COUNT(*) as total FROM solicitudes_reserva WHERE estado = 'Confirmada'");
            $totalAprobados = $queryAprobados->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

            $queryIngresos = $db->query("SELECT SUM(monto_total) as total_ingresos FROM solicitudes_reserva WHERE estado = 'Confirmada' AND monto_total > 0");
            $totalIngresos = $queryIngresos->fetch(PDO::FETCH_ASSOC)['total_ingresos'] ?? 0.00;

            // B. Tabla: Reservas Recientes
            $queryLista = $db->query("
                SELECT r.id_reserva, d.nombre_dependencia, a.nombre_ambiente AS nombre_auditorio, u.nombre_completo AS solicitante, r.fecha_evento, r.monto_total, r.estado 
                FROM solicitudes_reserva r
                INNER JOIN auditorios a ON r.id_auditorio = a.id_auditorio
                INNER JOIN dependencias d ON a.id_dependencia = d.id_dependencia
                INNER JOIN usuarios u ON r.id_usuario = u.id_usuario
                ORDER BY r.id_reserva DESC LIMIT 10
            ");
            $reservasRecientes = $queryLista->fetchAll(PDO::FETCH_ASSOC);

            // C. Tabla: Usuarios del sistema
            $queryUsuarios = $db->query("
                SELECT u.id_usuario, u.nombre_completo, u.correo, u.rol, 1 as estado, d.nombre_dependencia 
                FROM usuarios u
                LEFT JOIN dependencias d ON u.id_dependencia = d.id_dependencia
                ORDER BY u.id_usuario DESC
            ");
            $usuarios = $queryUsuarios->fetchAll(PDO::FETCH_ASSOC);

            // D. Tabla: Auditorios 
            $queryAuditorios = $db->query("
                SELECT a.id_auditorio, a.nombre_ambiente AS nombre_auditorio, d.nombre_dependencia, a.estado 
                FROM auditorios a
                INNER JOIN dependencias d ON a.id_dependencia = d.id_dependencia
                ORDER BY d.nombre_dependencia ASC, a.nombre_ambiente ASC
            ");
            $auditoriosList = $queryAuditorios->fetchAll(PDO::FETCH_ASSOC);

            // E. Para el modal: Listado de dependencias (para asignar facultad al crear usuario)
            $queryDeps = $db->query("SELECT id_dependencia, nombre_dependencia FROM dependencias ORDER BY nombre_dependencia ASC");
            $dependencias = $queryDeps->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            $totalPendientes = 0; $totalAprobados = 0; $totalIngresos = 0.00;
            $reservasRecientes = []; $usuarios = []; $auditoriosList = []; $dependencias = [];
        }

        if (file_exists('views/admin/superadmin_dashboard.php')) {
            require_once 'views/admin/superadmin_dashboard.php';
        } else {
            echo "Error: El archivo views/admin/superadmin_dashboard.php no existe.";
        }
    }

    // ==========================================
    // 2. PROCESAR ACCIONES DE USUARIO
    // ==========================================
    public function guardarUsuario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $database = new Database();
            $db = $database->getConnection();

            $nombre = $_POST['nombre_completo'] ?? '';
            $correo = $_POST['correo'] ?? '';
            $password = $_POST['password'] ?? '';
            $rol = $_POST['rol'] ?? 'Coordinador';
            $id_dependencia = !empty($_POST['id_dependencia']) ? $_POST['id_dependencia'] : null;

            if (!empty($nombre) && !empty($correo) && !empty($password)) {
                // Encriptar la contraseña de forma segura
                $passwordHash = password_hash($password, PASSWORD_BCRYPT);
                
                $sql = "INSERT INTO usuarios (nombre_completo, correo, password, rol, id_dependencia, estado) VALUES (?, ?, ?, ?, ?, 1)";
                $stmt = $db->prepare($sql);
                $stmt->execute([$nombre, $correo, $passwordHash, $rol, $id_dependencia]);
            }
        }
        header("Location: index.php?route=superadmin_dashboard#tab-usuarios");
        exit();
    }

    public function cambiarPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $database = new Database();
            $db = $database->getConnection();

            $id_usuario = $_POST['id_usuario'] ?? null;
            $new_password = $_POST['new_password'] ?? '';

            if ($id_usuario && !empty($new_password)) {
                $passwordHash = password_hash($new_password, PASSWORD_BCRYPT);
                $sql = "UPDATE usuarios SET password = ? WHERE id_usuario = ?";
                $stmt = $db->prepare($sql);
                $stmt->execute([$passwordHash, $id_usuario]);
            }
        }
        header("Location: index.php?route=superadmin_dashboard#tab-usuarios");
        exit();
    }

    public function toggleEstadoUsuario() {
        // 1. Capturamos los datos enviados por la URL (GET)
        $id_usuario = $_GET['id_usuario'] ?? ($_GET['id'] ?? null);
        $estadoActual = isset($_GET['estado']) ? (int)$_GET['estado'] : null;
    
        if ($id_usuario !== null && $estadoActual !== null) {
            // 2. Si el estado es 1 (activo), lo cambiamos a 0 (inactivo). Si es 0, lo cambiamos a 1.
            $nuevoEstado = ($estadoActual === 1) ? 0 : 1;
    
            $database = new Database();
            $db = $database->getConnection();
    
            // 3. Ejecutamos la consulta usando sentencias preparadas de forma segura
            $stmt = $db->prepare("UPDATE usuarios SET estado = ? WHERE id_usuario = ?");
            $stmt->execute([$nuevoEstado, $id_usuario]);
        }
    
        // 4. Redirigimos de vuelta a la pestaña de usuarios
        header("Location: index.php?route=superadmin_dashboard#tab-usuarios");
        exit();
    }

    // ==========================================
    // 3. PROCESAR ACCIONES DE ESPACIOS
    // ==========================================
    public function toggleEstadoAuditorio() {
        $id = $_GET['id'] ?? null;
        $estadoActual = $_GET['estado'] ?? null;
        if ($id !== null && $estadoActual !== null) {
            $nuevoEstado = ($estadoActual == 1) ? 0 : 1;
            $database = new Database();
            $db = $database->getConnection();
            $stmt = $db->prepare("UPDATE auditorios SET estado = ? WHERE id_auditorio = ?");
            $stmt->execute([$nuevoEstado, $id]);
        }
        header("Location: index.php?route=superadmin_dashboard#tab-espacios");
        exit();
    }
    // ==========================================
    // 4. GUARDAR NUEVO AUDITORIO
    // ==========================================
    public function guardarAuditorio() {
        $esSuperAdmin = (isset($_SESSION['rol']) && $_SESSION['rol'] === 'SuperAdmin');
        if (!$esSuperAdmin) {
            header("Location: index.php?route=logout");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre_auditorio'] ?? '');
            $id_dependencia = $_POST['id_dependencia'] ?? null;
            $estado = $_POST['estado'] ?? 1;

            if (!empty($nombre) && $id_dependencia !== null) {
                $database = new Database();
                $db = $database->getConnection();

                $sql = "INSERT INTO auditorios (nombre_auditorio, id_dependencia, estado) VALUES (?, ?, ?)";
                $stmt = $db->prepare($sql);
                $stmt->execute([$nombre, $id_dependencia, $estado]);
            }
        }
        header("Location: index.php?route=superadmin_dashboard#tab-espacios");
        exit();
    }

    // ==========================================
    // 4. OTROS METODOS
    // ==========================================
    public function index() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] === 'Docente') {
            header("Location: index.php?route=logout");
            exit();
        }

        $id_dependencia = $_SESSION['id_dependencia'] ?? null;

        if (file_exists('views/admin/dashboard.php')) {
            require_once 'views/admin/dashboard.php';
        } else {
            echo "<h1>Dashboard de Facultad (ID: $id_dependencia)</h1>";
            echo "<a href='index.php?route=logout'>Cerrar Sesión</a>";
        }
    }

    public function verDetalle() {
        if (!isset($_SESSION['rol'])) {
            header("Location: index.php?route=login");
            exit();
        }
        echo "<h1>Detalle de la Reserva</h1>";
    }
}