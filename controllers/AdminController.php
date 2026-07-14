<?php
// controllers/AdminController.php

// 1. Importamos la conexión de manera global para evitar errores de sintaxis
require_once 'config/Database.php'; 

class AdminController {

    public function __construct() {
        // Aseguramos que la sesión esté activa en cualquier método
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // ==========================================
    // 1. DASHBOARD DEL SUPERADMIN
    // ==========================================
    public function superadminDashboard() {
        // Validación de seguridad estricta
        $esSuperAdmin = (isset($_SESSION['correo']) && $_SESSION['correo'] === 'admin@unp.edu.pe') || 
                        (isset($_SESSION['rol']) && $_SESSION['rol'] === 'SuperAdmin');

        if (!$esSuperAdmin) {
            header("Location: index.php?route=logout");
            exit();
        }

        // Conexión usando tu clase Database
        $database = new Database();
        $db = $database->getConnection();

        if (!$db) {
            die("Error: No se pudo establecer la conexión a la base de datos.");
        }

        // --- CONSULTAS SQL REALES PARA INDICADORES Y PESTAÑAS ---
        try {
            // A. Contar solicitudes pendientes (Estado 0)
            $queryPendientes = $db->query("SELECT COUNT(*) as total FROM reservas WHERE estado = 0");
            $totalPendientes = $queryPendientes->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

            // B. Contar eventos aprobados (Estado 1)
            $queryAprobados = $db->query("SELECT COUNT(*) as total FROM reservas WHERE estado = 1");
            $totalAprobados = $queryAprobados->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

            // C. Sumar ingresos reales (Reservas aprobadas cuyo monto sea mayor a 0)
            $queryIngresos = $db->query("SELECT SUM(monto) as total_ingresos FROM reservas WHERE estado = 1 AND monto > 0");
            $totalIngresos = $queryIngresos->fetch(PDO::FETCH_ASSOC)['total_ingresos'] ?? 0.00;

            // D. Obtener las últimas reservas registradas (Monitoreo)
            $queryLista = $db->query("
                SELECT r.id_reserva, d.nombre_dependencia, a.nombre_auditorio, r.solicitante, r.fecha_evento, r.monto, r.estado 
                FROM reservas r
                INNER JOIN auditorios a ON r.id_auditorio = a.id_auditorio
                INNER JOIN dependencias d ON a.id_dependencia = d.id_dependencia
                ORDER BY r.id_reserva DESC LIMIT 10
            ");
            $reservasRecientes = $queryLista->fetchAll(PDO::FETCH_ASSOC);

            // E. Obtener todos los usuarios (para la pestaña de Usuarios)
            $queryUsuarios = $db->query("
                SELECT u.id_usuario, u.nombre_completo, u.correo, u.rol, u.estado, d.nombre_dependencia 
                FROM usuarios u
                LEFT JOIN dependencias d ON u.id_dependencia = d.id_dependencia
                ORDER BY u.id_usuario DESC
            ");
            $usuarios = $queryUsuarios->fetchAll(PDO::FETCH_ASSOC);

            // F. Obtener auditorios (para la pestaña de Espacios)
            $queryAuditorios = $db->query("
                SELECT a.id_auditorio, a.nombre_auditorio, d.nombre_dependencia, a.estado 
                FROM auditorios a
                INNER JOIN dependencias d ON a.id_dependencia = d.id_dependencia
                ORDER BY d.nombre_dependencia ASC, a.nombre_auditorio ASC
            ");
            $auditoriosList = $queryAuditorios->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            // En caso de que falte alguna tabla o columna, inicializamos todo vacío de forma segura
            $totalPendientes = 0;
            $totalAprobados = 0;
            $totalIngresos = 0.00;
            $reservasRecientes = [];
            $usuarios = [];
            $auditoriosList = [];
        }

        // Cargar la vista unificada del Dashboard
        if (file_exists('views/admin/superadmin_dashboard.php')) {
            require_once 'views/admin/superadmin_dashboard.php';
        } else {
            echo "Error: El archivo views/admin/superadmin_dashboard.php no existe.";
        }
    }

    // ==========================================
    // 2. MÉTODOS DE GESTIÓN DE USUARIOS
    // ==========================================
    public function guardarUsuario() {
        // Método que llamará Antigravity para registrar coordinadores/externos
    }

    public function cambiarPassword() {
        // Método que llamará Antigravity para actualizar contraseñas
    }

    public function toggleEstadoUsuario() {
        // Método que llamará Antigravity para activar/desactivar cuentas
    }

    // ==========================================
    // 3. MÉTODOS DE GESTIÓN DE ESPACIOS
    // ==========================================
    public function toggleEstadoAuditorio() {
        
    }

    // ==========================================
    // 4. DASHBOARD DE FACULTAD / AUDITORIAS
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