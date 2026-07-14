<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reservas - UNP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background: linear-gradient(135deg, #0d6efd, #0b5ed7);">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php?route=catalogo_auditorios">
            <i class="bi bi-building me-2"></i>Reservas UNP
        </a>
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item">
                    <a class="nav-link text-white fw-bold me-3" href="index.php?route=mis_reservas">
                        <i class="bi bi-list-check me-1"></i> Mis Reservas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-light btn-sm" href="index.php?route=logout">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5 mb-5">
    <h3 class="fw-bold mb-4 text-dark"><i class="bi bi-clock-history me-2"></i>Mi Historial de Reservas</h3>

    <?php if (isset($_GET['exito'])): ?>
        <div class="alert alert-success shadow-sm rounded">
            <i class="bi bi-check-circle-fill me-2"></i> Tu solicitud de reserva fue registrada exitosamente.
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Evento</th>
                            <th>Ambiente</th>
                            <th>Fecha y Hora</th>
                            <th>Monto (S/)</th>
                            <th class="pe-4">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reservas)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-calendar-x fs-1 d-block mb-2 text-black-50"></i>
                                    No tienes reservas registradas.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($reservas as $res): ?>
                                <?php
                                    $badgeClass = 'bg-secondary';
                                    switch($res['estado']) {
                                        case 'Pendiente': $badgeClass = 'bg-warning text-dark'; break;
                                        case 'Por_Verificar': $badgeClass = 'bg-info text-dark'; break;
                                        case 'Confirmada': $badgeClass = 'bg-success'; break;
                                        case 'Esperando_Pago': $badgeClass = 'bg-primary'; break;
                                        case 'Rechazada': $badgeClass = 'bg-danger'; break;
                                    }
                                ?>
                                <tr>
                                    <td class="ps-4 fw-medium">#<?= $res['id_reserva'] ?></td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($res['titulo_evento']) ?></div>
                                        <span class="badge bg-light text-dark border border-secondary-subtle">
                                            <?= htmlspecialchars(str_replace('_', ' ', $res['tipo_evento'])) ?>
                                        </span>
                                    </td>
                                    <td class="text-secondary"><?= htmlspecialchars($res['nombre_ambiente']) ?></td>
                                    <td>
                                        <div class="fw-medium text-dark"><?= date('d/m/Y', strtotime($res['fecha_evento'])) ?></div>
                                        <small class="text-muted"><i class="bi bi-clock me-1"></i><?= date('H:i', strtotime($res['hora_inicio'])) ?> - <?= date('H:i', strtotime($res['hora_fin'])) ?></small>
                                    </td>
                                    <td class="fw-bold text-primary fs-5">
                                        <?= $res['monto_total'] == 0 ? 'Gratis' : number_format($res['monto_total'], 2) ?>
                                    </td>
                                    <td class="pe-4">
                                        <span class="badge <?= $badgeClass ?> px-3 py-2 rounded-pill">
                                            <?= str_replace('_', ' ', $res['estado']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
