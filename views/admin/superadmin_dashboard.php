<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Global - SuperAdmin UNP</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <style>
        .card-clickable { cursor: pointer; }
    </style>
</head>
<body class="bg-light">

    <!-- Barra de Navegación Superior -->
    <nav class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container-fluid px-4">
            <span class="navbar-brand mb-0 h1">
                <i class="bi bi-shield-lock-fill text-warning me-2"></i> UNP - Sistema de Reservas Central
            </span>
            <div class="d-flex align-items-center">
                <span class="text-light me-3 small">
                    <i class="bi bi-person-circle text-info me-1"></i>
                    <?php echo htmlspecialchars($_SESSION['correo'] ?? $_SESSION['email'] ?? 'admin@unp.edu.pe'); ?> (SuperAdmin)
                </span>
                <a href="index.php?route=logout" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 mt-4">
        <h2 class="fw-bold mb-0 text-secondary">Panel de Control Global</h2>
        <p class="text-muted mb-4">Vista unificada de todas las facultades, dependencias y auditorios.</p>

        <!-- Nav Tabs -->
        <ul class="nav nav-tabs mb-3" id="adminTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="nav-resumen-tab" data-bs-toggle="tab" data-bs-target="#tab-resumen" type="button" role="tab" aria-controls="tab-resumen" aria-selected="true">Panel Resumen</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="nav-usuarios-tab" data-bs-toggle="tab" data-bs-target="#tab-usuarios" type="button" role="tab" aria-controls="tab-usuarios" aria-selected="false">Gestión de Usuarios</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="nav-espacios-tab" data-bs-toggle="tab" data-bs-target="#tab-espacios" type="button" role="tab" aria-controls="tab-espacios" aria-selected="false">Gestión de auditorios</button>
            </li>
        </ul>

        <div class="tab-content" id="adminTabsContent">
            <!-- ==================== RESUMEN TAB ==================== -->
            <div class="tab-pane fade show active" id="tab-resumen" role="tabpanel" aria-labelledby="nav-resumen-tab">
                <!-- Tarjetas de Métricas -->
                <div class="row g-4 mb-4">
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="card border-0 shadow-sm bg-warning text-dark h-100 card-clickable" data-bs-toggle="tab" data-bs-target="#tab-usuarios" role="button">
                            <div class="card-body d-flex align-items-center justify-content-between p-4">
                                <div>
                                    <h6 class="text-uppercase fw-semibold mb-1 opacity-75">Alertas Pendientes</h6>
                                    <h2 class="mb-0 fw-bold"><?php echo $totalPendientes; ?></h2>
                                </div>
                                <i class="bi bi-clock-history fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="card border-0 shadow-sm bg-success text-white h-100 card-clickable" data-bs-toggle="tab" data-bs-target="#tab-espacios" role="button">
                            <div class="card-body d-flex align-items-center justify-content-between p-4">
                                <div>
                                    <h6 class="text-uppercase fw-semibold mb-1 opacity-75">Eventos Programados</h6>
                                    <h2 class="mb-0 fw-bold"><?php echo $totalAprobados; ?></h2>
                                </div>
                                <i class="bi bi-calendar-check fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="card border-0 shadow-sm bg-primary text-white h-100">
                            <div class="card-body d-flex align-items-center justify-content-between p-4">
                                <div>
                                    <h6 class="text-uppercase fw-semibold mb-1 opacity-75">Ingresos Proyectados</h6>
                                    <h2 class="mb-0 fw-bold">S/ <?php echo number_format($totalIngresos, 2); ?></h2>
                                </div>
                                <i class="bi bi-cash-coin fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="card border-0 shadow-sm bg-dark text-white h-100">
                            <div class="card-body d-flex align-items-center justify-content-between p-4">
                                <div>
                                    <h6 class="text-uppercase fw-semibold mb-1 opacity-75">Administradores UNP</h6>
                                    <h2 class="mb-0 fw-bold">8</h2>
                                </div>
                                <i class="bi bi-people fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Reservas Recientes -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold text-secondary"><i class="bi bi-activity me-2"></i> Monitoreo General de Reservas Recientes</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">ID</th>
                                        <th>Dependencia / Facultad</th>
                                        <th>Espacio / Auditorio</th>
                                        <th>Solicitante</th>
                                        <th>Fecha del Evento</th>
                                        <th>Monto</th>
                                        <th>Estado</th>
                                        <th class="pe-4 text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($reservasRecientes)): ?>
                                    <?php foreach ($reservasRecientes as $res): ?>
                                        <tr>
                                            <td class="ps-4"><?php echo str_pad($res['id_reserva'], 4, "0", STR_PAD_LEFT); ?></td>
                                            <td><span class="badge bg-secondary"><?php echo htmlspecialchars($res['nombre_dependencia']); ?></span></td>
                                            <td class="fw-semibold"><?php echo htmlspecialchars($res['nombre_auditorio']); ?></td>
                                            <td><?php echo htmlspecialchars($res['solicitante']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($res['fecha_evento'])); ?></td>
                                            <td><?php echo ($res['monto'] == 0) ? '<span class="text-success fw-bold">Exonerado</span>' : 'S/ ' . number_format($res['monto'], 2); ?></td>
                                            <td>
                                                <?php
                                                    if ($res['estado'] == 0) {
                                                        echo '<span class="badge bg-warning text-dark">Pendiente</span>';
                                                    } elseif ($res['estado'] == 1) {
                                                        echo '<span class="badge bg-success">Aprobado</span>';
                                                    } else {
                                                        echo '<span class="badge bg-danger">Rechazado</span>';
                                                    }
                                                ?>
                                            </td>
                                            <td class="pe-4 text-end">
                                                <a href="index.php?route=reserva_detalle&id=<?php echo $res['id_reserva']; ?>" class="btn btn-outline-primary btn-sm">Ver</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">No hay reservas registradas en el sistema.</td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==================== USUARIOS TAB ==================== -->
            <div class="tab-pane fade" id="tab-usuarios" role="tabpanel" aria-labelledby="nav-usuarios-tab">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold text-secondary"><i class="bi bi-people me-2"></i> Gestión de Usuarios</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre Completo</th>
                                        <th>Correo</th>
                                        <th>Rol</th>
                                        <th>Estado</th>
                                        <th>Dependencia</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($listaUsuarios)): ?>
                                    <?php foreach ($listaUsuarios as $usr): ?>
                                        <tr>
                                            <td><?php echo $usr['id_usuario']; ?></td>
                                            <td><?php echo htmlspecialchars($usr['nombre_completo']); ?></td>
                                            <td><?php echo htmlspecialchars($usr['correo']); ?></td>
                                            <td><?php echo htmlspecialchars($usr['rol']); ?></td>
                                            <td><?php echo $usr['estado'] ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>'; ?></td>
                                            <td><?php echo htmlspecialchars($usr['nombre_dependencia'] ?? 'N/A'); ?></td>
                                            <td class="text-end">
                                                <form method="post" action="index.php?route=cambiar_password" class="d-inline" style="margin:0;">
                                                    <input type="hidden" name="id_usuario" value="<?php echo $usr['id_usuario']; ?>">
                                                    <input type="password" name="nueva_password" placeholder="Nueva clave" class="form-control form-control-sm d-inline w-auto" required>
                                                    <button type="submit" class="btn btn-sm btn-warning">Cambiar</button>
                                                </form>
                                                <a href="index.php?route=toggle_estado_usuario&id_usuario=<?php echo $usr['id_usuario']; ?>" class="btn btn-sm btn-<?php echo $usr['estado'] ? 'secondary' : 'success'; ?> ms-1">
                                                    <?php echo $usr['estado'] ? 'Desactivar' : 'Activar'; ?>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">No hay usuarios registrados.</td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold text-secondary"><i class="bi bi-person-plus me-2"></i> Crear Nuevo Usuario</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" action="index.php?route=guardar_usuario">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <input type="text" name="nombre_completo" class="form-control" placeholder="Nombre completo" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="email" name="correo" class="form-control" placeholder="Correo" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="password" name="password" class="form-control" placeholder="Clave" required>
                                </div>
                                <div class="col-md-2">
                                    <select name="rol" class="form-select" required>
                                        <option value="Admin_Facultad">Coordinador</option>
                                        <option value="Externo">Externo</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Guardar Usuario</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ==================== ESPACIOS TAB ==================== -->
            <div class="tab-pane fade" id="tab-espacios" role="tabpanel" aria-labelledby="nav-espacios-tab">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold text-secondary"><i class="bi bi-building me-2"></i> Auditorios y Salas de conferencias</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Dependencia</th>
                                        <th>Estado</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($listaAuditorios)): ?>
                                    <?php foreach ($listaAuditorios as $aud): ?>
                                        <tr>
                                            <td><?php echo $aud['id_auditorio']; ?></td>
                                            <td><?php echo htmlspecialchars($aud['nombre_auditorio']); ?></td>
                                            <td><?php echo htmlspecialchars($aud['nombre_dependencia']); ?></td>
                                            <td>
                                                <?php if ($aud['estado_auditorio'] == 1): ?>
                                                    <span class="badge bg-success">Activo</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">En Mantenimiento</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <a href="index.php?route=toggle_estado_auditorio&id_auditorio=<?php echo $aud['id_auditorio']; ?>" class="btn btn-sm btn-<?php echo $aud['estado_auditorio'] == 1 ? 'secondary' : 'success'; ?>">
                                                    <?php echo $aud['estado_auditorio'] == 1 ? 'Desactivar' : 'Activar'; ?>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">No hay auditorios registrados.</td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>