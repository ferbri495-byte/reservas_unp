<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = trim($_POST['correo'] ?? '');
            $password = $_POST['password'] ?? '';

            if (!empty($correo) && !empty($password)) {
                                $usuarioModel = new Usuario();
                $usuario = $usuarioModel->login($correo, $password);

                if ($usuario) {
                    // Regla especial para el SuperAdmin fijo
                    if (strtolower($usuario['correo']) === 'admin@unp.edu.pe') {
                        $_SESSION['id_usuario'] = $usuario['id_usuario'];
                        $_SESSION['nombre_completo'] = $usuario['nombre_completo'];
                        $_SESSION['rol'] = 'SuperAdmin';
                        $_SESSION['id_dependencia'] = null;
                        header("Location: index.php?route=superadmin_dashboard");
                        exit();
                    }

                    // Guardado estándar de la sesión
                    $_SESSION['id_usuario'] = $usuario['id_usuario'];
                    $_SESSION['nombre_completo'] = $usuario['nombre_completo'];
                    $_SESSION['rol'] = $usuario['rol'];
                    $_SESSION['id_dependencia'] = $usuario['id_dependencia'];

                    // Redirección según rol
                    if ($_SESSION['rol'] === 'SuperAdmin') {
                        header("Location: index.php?route=superadmin_dashboard");
                    } elseif ($_SESSION['rol'] === 'Admin_Facultad') {
                        header("Location: index.php?route=admin_dashboard");
                    } else {
                        header("Location: index.php?route=catalogo_auditorios");
                    }
                    exit();
                } else {
                    $error = "Correo o contraseña incorrectos.";
                    require_once __DIR__ . '/../views/auth/login.php';
                }
            } else {
                $error = "Por favor, complete todos los campos.";
                require_once __DIR__ . '/../views/auth/login.php';
            }
        } else {
            // Mostrar la vista de login si es GET
            require_once __DIR__ . '/../views/auth/login.php';
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        header("Location: index.php?route=login");
        exit();
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre_completo = trim($_POST['nombre_completo'] ?? '');
            $correo = trim($_POST['correo'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (!empty($nombre_completo) && !empty($correo) && !empty($password)) {
                
                // Auto-asignar rol basado en el dominio del correo
                $correo_lower = strtolower($correo);
                $rol = 'Externo'; // Por defecto
                
                if (str_ends_with($correo_lower, '@alumnos.unp.edu.pe')) {
                    $rol = 'Alumno';
                } elseif (str_ends_with($correo_lower, '@unp.edu.pe')) {
                    $rol = 'Docente';
                }

                $usuarioModel = new Usuario();
                $registrado = $usuarioModel->registrar($nombre_completo, $correo, $password, $rol);
                
                if ($registrado) {
                    $rol_display = ($rol === 'Docente') ? 'Docente / Administrativo' : $rol;
                    // Pasar un mensaje de éxito a la vista de login
                    $exito = "Cuenta creada exitosamente (Detectado como: $rol_display). Ya puedes iniciar sesión.";
                    require_once __DIR__ . '/../views/auth/login.php';
                    return;
                } else {
                    $error = "Error al registrar la cuenta. Es posible que el correo ya esté en uso.";
                    require_once __DIR__ . '/../views/auth/register.php';
                    return;
                }
            } else {
                $error = "Por favor, complete todos los campos obligatorios.";
                require_once __DIR__ . '/../views/auth/register.php';
                return;
            }
        } else {
            // Mostrar formulario de registro (GET)
            require_once __DIR__ . '/../views/auth/register.php';
        }
    }
}
?>
