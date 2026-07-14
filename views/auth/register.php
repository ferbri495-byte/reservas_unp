<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Reservas UNP</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }
        .login-card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
            background: #ffffff;
            border: none;
        }
        .login-header {
            background: linear-gradient(135deg, #198754, #157347); /* Verde UNP/Registro */
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .login-header h4 {
            font-weight: 600;
            margin-bottom: 5px;
        }
        .login-body {
            padding: 40px 30px;
        }
        .form-control, .form-select {
            border-radius: 8px;
            padding: 10px 15px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.15);
            border-color: #198754;
            background-color: #ffffff;
        }
        .btn-success {
            width: 100%;
            border-radius: 8px;
            padding: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <h4>Crear Nueva Cuenta</h4>
        <small>Registro de Usuarios - UNP</small>
    </div>
    <div class="login-body">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <form action="index.php?route=register" method="POST">
            <div class="mb-3">
                <label for="nombre_completo" class="form-label text-muted fw-bold">Nombre Completo</label>
                <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" required placeholder="Juan Pérez">
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label text-muted fw-bold">Correo Electrónico</label>
                <input type="email" class="form-control" id="correo" name="correo" required placeholder="ejemplo@unp.edu.pe">
                <div class="form-text">Si eres alumno, docente o administrativo, usa tu correo institucional.</div>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label text-muted fw-bold">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn btn-success">Registrarse</button>
            
            <div class="mt-3 text-center">
                <a href="index.php?route=login" class="text-decoration-none text-success">¿Ya tienes cuenta? Inicia sesión</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
