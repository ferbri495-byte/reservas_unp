<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar Espacio - UNP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 mb-5" style="max-width: 800px;">
    <h3 class="mb-4 text-primary">Solicitud de Reserva: <?= htmlspecialchars($auditorio['nombre_ambiente']) ?></h3>
    
    <?php if (isset($_GET['error'])): ?>
        <?php 
            $error_msg = "Error al procesar la solicitud.";
            if ($_GET['error'] == 1) $error_msg = "Datos inválidos o incompletos.";
            if ($_GET['error'] == 2) $error_msg = "Error al guardar en la base de datos.";
            if ($_GET['error'] == 3) $error_msg = "El auditorio ya se encuentra reservado o en proceso de verificación en ese rango de horario. Por favor, elige otra hora u otro día.";
            if ($_GET['error'] == 4) {
                $hora_sug = isset($_GET['sug']) ? htmlspecialchars($_GET['sug']) : '';
                $error_msg = "El auditorio requiere un periodo de 30 minutos para limpieza y preparación entre eventos. Por favor, programa tu actividad a partir de las $hora_sug.";
            }
        ?>
        <div class="alert alert-danger shadow-sm">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($error_msg) ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="index.php?route=guardar_reserva" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_auditorio" value="<?= $auditorio['id_auditorio'] ?>">
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Título del Evento</label>
                    <input type="text" class="form-control bg-light border-0 shadow-sm" name="titulo_evento" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Descripción (Opcional)</label>
                    <textarea class="form-control bg-light border-0 shadow-sm" name="descripcion" rows="3"></textarea>
                </div>
                
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Fecha</label>
                        <input type="date" class="form-control bg-light border-0 shadow-sm" name="fecha_evento" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Hora Inicio</label>
                        <input type="time" class="form-control bg-light border-0 shadow-sm" name="hora_inicio" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Hora Fin</label>
                        <input type="time" class="form-control bg-light border-0 shadow-sm" name="hora_fin" required>
                    </div>
                </div>

                <?php if (in_array($_SESSION['rol'], ['Docente', 'Admin_Facultad', 'SuperAdmin'])): ?>
                    <div class="border border-success rounded p-4 mb-4 bg-white shadow-sm">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="chkExoneracion" name="solicita_exoneracion">
                            <label class="form-check-label fw-bold text-success ms-2" for="chkExoneracion">
                                ¿Solicitar Exoneración de Pago (Evento Académico Oficial)?
                            </label>
                        </div>
                        <div id="divResolucion" class="mt-3 pt-3 border-top" style="display: none;">
                            <label class="form-label fw-bold">Documento de Resolución/Memorando (PDF, JPG, PNG)</label>
                            <input type="file" class="form-control" name="documento_resolucion" id="fileResolucion" accept=".pdf,.png,.jpg,.jpeg">
                            <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Adjunte el documento oficial para que la administración verifique la exoneración al 100%.</small>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="d-flex justify-content-between mt-4">
                    <a href="index.php?route=catalogo_auditorios" class="btn btn-outline-secondary px-4">Cancelar</a>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Confirmar Solicitud</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const chkExoneracion = document.getElementById('chkExoneracion');
    const divResolucion = document.getElementById('divResolucion');
    const fileResolucion = document.getElementById('fileResolucion');

    if (chkExoneracion) {
        chkExoneracion.addEventListener('change', function() {
            if (this.checked) {
                divResolucion.style.display = 'block';
                fileResolucion.setAttribute('required', 'required');
            } else {
                divResolucion.style.display = 'none';
                fileResolucion.removeAttribute('required');
            }
        });
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
