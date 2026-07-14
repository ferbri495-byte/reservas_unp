<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Auditorios - UNP</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Iconos de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar-unp {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7); /* Azul institucional */
        }
        .card-auditorio {
            transition: transform 0.2s, box-shadow 0.2s;
            border-radius: 12px;
            overflow: hidden;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            height: 100%;
        }
        .card-auditorio:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .badge-dependencia {
            background-color: #e9ecef;
            color: #495057;
            font-weight: 500;
            padding: 5px 10px;
            font-size: 0.85em;
        }
        .icono-capacidad {
            color: #198754;
        }
    </style>
</head>
<body>

<!-- Navegación -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-unp shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php?route=catalogo_auditorios">
            <i class="bi bi-building me-2"></i>Reservas UNP
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item me-3">
                    <span class="text-white-50">Hola, <?= htmlspecialchars($_SESSION['nombre_completo'] ?? 'Usuario') ?></span>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-light btn-sm" href="index.php?route=logout">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Catálogo de Espacios</h2>
            <span class="text-muted">Explora y reserva nuestros ambientes disponibles</span>
        </div>
    </div>

    <div class="row g-4">
        <?php if (!empty($auditorios)): ?>
            <?php foreach ($auditorios as $auditorio): ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card card-auditorio d-flex flex-column">
                        <div class="card-body">
                            <span class="badge badge-dependencia mb-2"><?= htmlspecialchars($auditorio['abreviatura']) ?></span>
                            <h5 class="card-title fw-bold text-dark mb-1"><?= htmlspecialchars($auditorio['nombre_ambiente']) ?></h5>
                            <p class="card-text text-muted small mb-3">
                                Gestionado por: <strong><?= htmlspecialchars($auditorio['nombre_dependencia']) ?></strong>
                            </p>
                            
                            <div class="d-flex align-items-center mb-3 bg-light p-2 rounded">
                                <i class="bi bi-people-fill icono-capacidad me-2 fs-5"></i>
                                <span class="fw-medium text-dark">Aforo: <?= htmlspecialchars($auditorio['capacidad']) ?> personas</span>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0 pt-0 mt-auto pb-3">
                            <div class="d-grid gap-2">
                                <a href="index.php?route=reservar_espacio&id=<?= $auditorio['id_auditorio'] ?>" class="btn btn-primary fw-medium">
                                    <i class="bi bi-calendar-plus me-1"></i> Reservar Espacio
                                </a>
                                <a href="index.php?route=ver_eventos&id=<?= $auditorio['id_auditorio'] ?>" class="btn btn-outline-secondary fw-medium">
                                    <i class="bi bi-ticket-perforated me-1"></i> Ver Eventos / Cupos
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info shadow-sm" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i> No hay auditorios disponibles en este momento.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
