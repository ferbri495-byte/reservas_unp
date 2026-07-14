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
        // Vista temporal del dashboard para probar que la autenticación funciona
        echo "<!DOCTYPE html><html lang='es'><head><meta charset='UTF-8'><title>Dashboard - Reservas UNP</title>";
        echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'></head>";
        echo "<body class='bg-light'><div class='container mt-5'>";
        echo "<div class='card shadow-sm'><div class='card-body'>";
        echo "<h1 class='text-primary'>Bienvenido al Dashboard</h1>";
        echo "<hr>";
        echo "<h4>Datos de Sesión Actual:</h4>";
        echo "<ul>";
        echo "<li><strong>ID Usuario:</strong> " . htmlspecialchars($_SESSION['id_usuario']) . "</li>";
        echo "<li><strong>Nombre Completo:</strong> " . htmlspecialchars($_SESSION['nombre_completo']) . "</li>";
        echo "<li><strong>Rol:</strong> <span class='badge bg-success'>" . htmlspecialchars($_SESSION['rol']) . "</span></li>";
        if (!empty($_SESSION['id_dependencia'])) {
            echo "<li><strong>ID Dependencia:</strong> " . htmlspecialchars($_SESSION['id_dependencia']) . "</li>";
        }
        echo "</ul>";
        echo "<a href='index.php?route=logout' class='btn btn-danger mt-3'>Cerrar Sesión</a>";
        echo "</div></div></div></body></html>";
        break;

    default:
        http_response_code(404);
        echo "<h1>404 - Página no encontrada</h1>";
        echo "<a href='index.php'>Volver al inicio</a>";
        break;
}
?>
