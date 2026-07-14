<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Router frontal básico
$route = $_GET['route'] ?? 'dashboard';

// Control de acceso: Redirigir al login si no hay sesión iniciada
if (!isset($_SESSION['id_usuario']) && $route !== 'login' && $route !== 'register') {
    header("Location: index.php?route=login");
    exit();
}

// Despachador de rutas
switch ($route) {
    case 'login':
        require_once 'controllers/AuthController.php';
        $authController = new AuthController();
        $authController->login();
        break;
        
    case 'logout':
        require_once 'controllers/AuthController.php';
        $authController = new AuthController();
        $authController->logout();
        break;
        
    case 'register':
        require_once 'controllers/AuthController.php';
        $authController = new AuthController();
        $authController->register();
        break;

    case 'catalogo_auditorios':
        require_once 'controllers/CatalogoController.php';
        $catalogoController = new CatalogoController();
        $catalogoController->index();
        break;

    case 'reservar_espacio':
        require_once 'controllers/ReservaController.php';
        $reservaController = new ReservaController();
        $reservaController->crear();
        break;

    case 'guardar_reserva':
        require_once 'controllers/ReservaController.php';
        $reservaController = new ReservaController();
        $reservaController->guardar();
        break;

    case 'mis_reservas':
        require_once 'controllers/ReservaController.php';
        $reservaController = new ReservaController();
        $reservaController->misReservas();
        break;

        case 'dashboard':
            // Redirección inteligente según el rol de la sesión
            if (!isset($_SESSION['rol'])) {
                header("Location: index.php?route=login");
                exit();
            }
            
            if ($_SESSION['rol'] === 'SuperAdmin' || (isset($_SESSION['correo']) && $_SESSION['correo'] === 'admin@unp.edu.pe')) {
                header("Location: index.php?route=superadmin_dashboard");
            } else {
                header("Location: index.php?route=admin_dashboard");
            }
            exit();
            break;
            case 'admin_dashboard':
                require_once 'controllers/AdminController.php';
                $controller = new AdminController();
                $controller->index(); // Carga el panel por facultad
                break;
        
            case 'superadmin_dashboard':
                require_once 'controllers/AdminController.php';
                $controller = new AdminController();
                $controller->superadminDashboard(); // Carga el panel global para ti (admin@unp.edu.pe)
                break;
        
            case 'reserva_detalle':
                require_once 'controllers/AdminController.php';
                $controller = new AdminController();
                $controller->verDetalle(); // Para que revises a fondo los datos y los PDF
                break;
    // Nuevas rutas de administración
    case 'guardar_usuario':
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->guardarUsuario();
        break;
    case 'cambiar_password':
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->cambiarPassword();
        break;
    case 'toggle_estado_usuario':
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->toggleEstadoUsuario();
        break;
    case 'toggle_estado_auditorio':
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->toggleEstadoAuditorio();
        break;

    case 'guardar_auditorio':
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->guardarAuditorio();
        break;
    default:
        http_response_code(404);
        echo "<h1>404 - Página no encontrada</h1>";
        echo "<a href='index.php'>Volver al inicio</a>";
        break;
}
?>
