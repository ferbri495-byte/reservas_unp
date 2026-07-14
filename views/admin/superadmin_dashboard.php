<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control Central - UNP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container-fluid px-4">
            <span class="navbar-brand mb-0 h1">
                <i class="bi bi-shield-lock-fill text-warning me-2"></i> UNP - Sistema de Reservas Central
            </span>
            <div class="d-flex align-items-center">
                <span class="text-light me-3 small">
                    <i class="bi bi-person-circle text-info me-1"></i> <?php echo htmlspecialchars($_SESSION['correo'] ?? 'admin@unp.edu.pe'); ?> (SuperAdmin)
                </span>
                <a href="index.php?route=logout" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 mt-4">
        
        <ul class="nav nav-pills mb-4 gap-2 bg-white p-2 rounded shadow-sm" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold" id="tab-resumen-btn" data-bs-toggle="pill" data-bs-target="#tab-resumen" type="button" role="tab"><i class="bi bi-speedometer2 me-2"></i>Panel Resumen</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold" id="tab-usuarios-btn" data-bs-toggle="pill" data-bs-target="#tab-usuarios" type="button" role="tab"><i class="bi bi-people-fill me-2"></i>Gestión de Usuarios</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold" id="tab-espacios-btn" data-bs-toggle="pill" data-bs-target="#tab-espacios" type="button" role="tab"><i class="bi bi-building-gear me-2"></i>Gestión de Auditorios</button>
            </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">
            
            <div class="tab-pane fade show active" id="tab-resumen" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold mb-0 text-secondary">Panel de Control Global</h2>
                        <p class="text-muted mb-0">Vista unificada de todas las facultades, dependencias y auditorios.</p>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="card border-0 shadow-sm bg-warning text-dark h-100" style="cursor: pointer;" onclick="document.getElementById('tab-resumen-btn').click();">
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
                        <div class="card border-0 shadow-sm bg-success text-white h-100" style="cursor: pointer;" onclick="document.getElementById('tab-resumen-btn').click();">
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
                        <div class="card border-0 shadow-sm bg-primary text-white h-100" style="cursor: pointer;">
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
                        <div class="card border-0 shadow-sm bg-dark text-white h-100" style="cursor: pointer;" onclick="document.getElementById('tab-usuarios-btn').click();">
                            <div class="card-body d-flex align-items-center justify-content-between p-4">
                                <div>
                                    <h6 class="text-uppercase fw-semibold mb-1 opacity-75">Usuarios Registrados</h6>
                                    <h2 class="mb-0 fw-bold"><?php echo count($usuarios); ?></h2>
                                </div>
                                <i class="bi bi-people fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

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
                                                <td><?php echo ($res['monto_total'] == 0) ? '<span class="text-success fw-bold">Exonerado</span>' : 'S/ ' . number_format($res['monto_total'], 2); ?></td>
                                                <td>
                                                    <?php 
                                                        if ($res['estado'] == 0) echo '<span class="badge bg-warning text-dark">Pendiente</span>';
                                                        elseif ($res['estado'] == 1) echo '<span class="badge bg-success">Aprobado</span>';
                                                        else echo '<span class="badge bg-danger">Rechazado</span>';
                                                    ?>
                                                </td>
                                                <td class="pe-4 text-end">
                                                    <a href="index.php?route=reserva_detalle&id=<?php echo $res['id_reserva']; ?>" class="btn btn-outline-primary btn-sm">Audit</a>
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

            <div class="tab-pane fade" id="tab-usuarios" role="tabpanel">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-secondary"><i class="bi bi-people-fill me-2"></i> Cuentas de Coordinadores y Externos</h5>
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalCrearUsuario">
                            <i class="bi bi-person-plus-fill me-1"></i> Registrar Usuario
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">ID</th>
                                        <th>Nombre Completo</th>
                                        <th>Correo Electrónico</th>
                                        <th>Rol</th>
                                        <th>Facultad / Dependencia</th>
                                        <th>Estado</th>
                                        <th class="pe-4 text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($usuarios)): ?>
                                        <?php foreach ($usuarios as $usu): ?>
                                            <tr>
                                                <td class="ps-4"><?php echo $usu['id_usuario']; ?></td>
                                                <td class="fw-bold"><?php echo htmlspecialchars($usu['nombre_completo']); ?></td>
                                                <td><?php echo htmlspecialchars($usu['correo']); ?></td>
                                                <td>
                                                    <?php $rolDisplay = $usu['rol'] ?? '';
                                                          if (empty($rolDisplay)) {
                                                              $rolDisplay = 'Usuario';
                                                          }
                                                    ?>
                                                    <span class="badge <?php echo ($rolDisplay === 'SuperAdmin') ? 'bg-dark' : (($rolDisplay === 'Coordinador') ? 'bg-info text-dark' : 'bg-secondary'); ?>">
                                                        <?php echo $rolDisplay; ?>
                                                    </span>
                                                </td>
                                                <td><span class="text-muted"><?php echo htmlspecialchars($usu['nombre_dependencia'] ?? 'Externo / Público'); ?></span></td>
                                                <td>
                                                    <span class="badge <?php echo ($usu['estado'] == 1) ? 'bg-success' : 'bg-danger'; ?>">
                                                        <?php echo ($usu['estado'] == 1) ? 'Activo' : 'Inactivo'; ?>
                                                    </span>
                                                </td>
                                                <td class="pe-4 text-end">
                                                    <button class="btn btn-outline-warning btn-sm me-1" onclick="abrirModalPassword(<?php echo $usu['id_usuario']; ?>, '<?php echo htmlspecialchars($usu['nombre_completo']); ?>')">
                                                        <i class="bi bi-key-fill"></i> Clave
                                                    </button>
                                                    <?php if ($usu['rol'] !== 'SuperAdmin'): ?>
                                                        <a href="index.php?route=toggle_estado_usuario&id=<?php echo $usu['id_usuario']; ?>&estado=<?php echo $usu['estado']; ?>" 
                                                           class="btn <?php echo ($usu['estado'] == 1) ? 'btn-outline-danger' : 'btn-outline-success'; ?> btn-sm">
                                                            <?php echo ($usu['estado'] == 1) ? 'Desactivar' : 'Activar'; ?>
                                                        </a>
                                                    <?php endif; ?>
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
            </div>

            <div class="tab-pane fade" id="tab-espacios" role="tabpanel">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-secondary"><i class="bi bi-building-gear me-2"></i> Gestión de Auditorios</h5>
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalCrearAuditorio"><i class="bi bi-plus-lg me-1"></i>Añadir Auditorio/Sala</button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">ID</th>
                                        <th>Auditorio / Sala de conferencias</th>
                                        <th>Ubicación (Dependencia)</th>
                                        <th>Estado Operativo</th>
                                        <th class="pe-4 text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($auditoriosList)): ?>
                                        <?php foreach ($auditoriosList as $aud): ?>
                                            <tr>
                                                <td class="ps-4"><?php echo $aud['id_auditorio']; ?></td>
                                                <td class="fw-semibold text-primary"><?php echo htmlspecialchars($aud['nombre_auditorio']); ?></td>
                                                <td><?php echo htmlspecialchars($aud['nombre_dependencia']); ?></td>
                                                <td>
                                                    <span class="badge <?php echo ($aud['estado'] == 1) ? 'bg-success' : 'bg-danger'; ?>">
                                                        <?php echo ($aud['estado'] == 1) ? 'Operativo / Libre' : 'En Mantenimiento'; ?>
                                                    </span>
                                                </td>
                                                <td class="pe-4 text-end">
                                                    <a href="index.php?route=toggle_estado_auditorio&id=<?php echo $aud['id_auditorio']; ?>&estado=<?php echo $aud['estado']; ?>" class="btn <?php echo ($aud['estado'] == 1) ? 'btn-danger' : 'btn-success'; ?> btn-sm">
                                                        <i class="bi <?php echo ($aud['estado'] == 1) ? 'bi-tools' : 'bi-check-circle'; ?>"></i>
                                                        <?php echo ($aud['estado'] == 1) ? 'Mantenimiento' : 'Habilitar'; ?>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">No hay auditorios o sala de conferencias registrados.</td>
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

    <div class="modal fade" id="modalCrearUsuario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="index.php?route=guardar_usuario" method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Registrar Nuevo Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre Completo</label>
                        <input type="text" name="nombre_completo" class="form-control" required placeholder="Ej. Ing. Carlos Torres">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" name="correo" class="form-control" required placeholder="ejemplo@unp.edu.pe">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña Inicial</label>
                        <input type="password" name="password" class="form-control" required placeholder="Mínimo 6 caracteres">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rol del Usuario</label>
                        <select name="rol" class="form-select" id="selectRol" onchange="verificarDependencia()">
                            <option value="Coordinador">Coordinador (Administrador de Facultad)</option>
                            <option value="Docente">Docente (Externo / Público)</option>
                            <option value="Externo">Externo</option>
                            <option value="Alumno">Alumno</option>
                        </select>
                    </div>
                    <div class="mb-3" id="divDependencia">
                        <label class="form-label">Asignar Facultad / Dependencia</label>
                        <select name="id_dependencia" class="form-select">
                            <option value="">-- Seleccione una Facultad --</option>
                            <?php foreach ($dependencias as $dep): ?>
                                <option value="<?php echo $dep['id_dependencia']; ?>"><?php echo htmlspecialchars($dep['nombre_dependencia']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-header bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm">Guardar Usuario</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalPassword" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <form action="index.php?route=cambiar_password" method="POST" class="modal-content">
                <input type="hidden" name="id_usuario" id="pass_id_usuario">
                <div class="modal-header">
                    <h6 class="modal-title fw-bold" id="pass_nombre">Cambiar Clave</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small">Nueva Contraseña</label>
                        <input type="password" name="new_password" class="form-control form-control-sm" required placeholder="Nueva contraseña">
                    </div>
                </div>
                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-light btn-xs" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-warning btn-xs">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
<div class="modal fade" id="modalCrearAuditorio" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="index.php?route=guardar_auditorio" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Crear Nuevo Auditorio / Sala</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nombre del Auditorio / Sala</label>
                    <input type="text" name="nombre_auditorio" class="form-control" required placeholder="Ej. Auditorio Central">
                </div>
                <div class="mb-3">
                    <label class="form-label">Facultad / Dependencia</label>
                    <select name="id_dependencia" class="form-select" required>
                        <option value="">-- Seleccione una Facultad --</option>
                        <?php foreach ($dependencias as $dep): ?>
                            <option value="<?php echo $dep['id_dependencia']; ?>"><?php echo htmlspecialchars($dep['nombre_dependencia']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Estado Inicial</label>
                    <select name="estado" class="form-select">
                        <option value="1">Operativo</option>
                        <option value="0">En Mantenimiento</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary btn-sm">Guardar Auditorio</button>
            </div>
        </form>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mantener la pestaña activa tras recargar la página (usando Hash en la URL)
        document.addEventListener("DOMContentLoaded", function() {
            var hash = window.location.hash;
            if (hash) {
                var triggerEl = document.querySelector('button[data-bs-target="' + hash + '"]');
                if (triggerEl) {
                    var tab = new bootstrap.Tab(triggerEl);
                    tab.show();
                }
            }
            
            // Actualizar el hash en la barra de direcciones al cambiar de pestaña
            var tabElList = [].slice.call(document.querySelectorAll('button[data-bs-toggle="pill"]'))
            tabElList.forEach(function(tabEl) {
                tabEl.addEventListener('shown.bs.tab', function(event) {
                    window.location.hash = event.target.getAttribute('data-bs-target');
                });
            });
        });

        // Ocultar campo dependencia si el rol es Docente/Externo
        function verificarDependencia() {
            var rol = document.getElementById("selectRol").value;
            var divDep = document.getElementById("divDependencia");
            if (rol === "Docente") {
                divDep.style.display = "none";
            } else {
                divDep.style.display = "block";
            }
        }

        // Llenar datos en el modal de contraseña
        function abrirModalPassword(id, nombre) {
            document.getElementById("pass_id_usuario").value = id;
            document.getElementById("pass_nombre").innerText = "Clave para: " + nombre;
            var modal = new bootstrap.Modal(document.getElementById('modalPassword'));
            modal.show();
        }
    </script>
</body>
</html>