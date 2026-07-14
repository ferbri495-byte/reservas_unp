<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Reservas UNP</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 420px;
            width: 100%;
            background: #ffffff;
            border: none;
        }
        .login-header {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .login-header h4 {
            font-weight: 600;
            margin-bottom: 5px;
        }
        .login-header small {
            font-size: 0.9em;
            opacity: 0.9;
        }
        .login-body {
            padding: 40px 30px;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
            background-color: #ffffff;
        }
        .btn-primary {
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
        <h4>Sistema de Reservas</h4>
        <small>Universidad Nacional de Piura</small>
    </div>
    <div class="login-body">
        <?php if (isset($exito)): ?>
            <div class="alert alert-success" role="alert">
                <?= htmlspecialchars($exito) ?>
            </div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <form action="index.php?route=login" method="POST">
            <div class="mb-3">
                <label for="correo" class="form-label text-muted fw-bold">Correo Electrónico</label>
                <input type="email" class="form-control" id="correo" name="correo" required placeholder="usuario@unp.edu.pe" autocomplete="email">
            </div>
            <div class="mb-4">
                <label for="password" class="form-label text-muted fw-bold">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required placeholder="••••••••" autocomplete="current-password">
            </div>
            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
            <div class="mt-3 text-center">
                <a href="index.php?route=register" class="text-decoration-none">¿No tienes cuenta? Regístrate aquí</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
