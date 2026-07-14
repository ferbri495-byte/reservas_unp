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
            if ($_GET['error'] == 5) $error_msg = "El alquiler por Medio Día no puede exceder las 4 horas. Por favor, selecciona Día Completo.";
        ?>
        <div class="alert alert-danger shadow-sm">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($error_msg) ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="index.php?route=guardar_reserva" method="POST" enctype="multipart/form-data">
                <!-- Entrada oculta con atributos de precios -->
                <input type="hidden" name="id_auditorio" id="auditorio_id" value="<?= $auditorio['id_auditorio'] ?>" data-precio-medio-interno="<?= $auditorio['precio_interno_medio_dia'] ?? 0 ?>" data-precio-completo-interno="<?= $auditorio['precio_interno_dia_completo'] ?? 0 ?>" data-precio-medio-externo="<?= $auditorio['precio_externo_medio_dia'] ?? 0 ?>" data-precio-completo-externo="<?= $auditorio['precio_externo_dia_completo'] ?? 0 ?>" data-precio-especial="<?= $auditorio['precio_evento_especial'] ?? 0 ?>" data-rol="<?= $_SESSION['rol'] ?? '' ?>">

            <!-- Resumen de Costo y Detalles -->
           
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Título del Evento</label>
                    <input type="text" class="form-control bg-light border-0 shadow-sm" name="titulo_evento" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Descripción (Opcional)</label>
                    <textarea class="form-control bg-light border-0 shadow-sm" name="descripcion" rows="3"></textarea>
                </div>
                
                <?php
$isInternal = in_array($_SESSION['rol'], ['Docente','Admin_Facultad','SuperAdmin','Alumno']);
$showDuracion = false;
if ($isInternal) {
    $showDuracion = $auditorio['precio_interno_medio_dia'] != $auditorio['precio_interno_dia_completo'];
} else {
    $showDuracion = $auditorio['precio_externo_medio_dia'] != $auditorio['precio_externo_dia_completo'];
}
?>
<?php if ($showDuracion): ?>
<div class="mb-4">
    <label class="form-label fw-bold">Duración del Alquiler</label>
    <select class="form-select bg-light border-0 shadow-sm" name="duracion_alquiler" required>
        <option value="" disabled selected>Seleccione...</option>
        <option value="medio_dia">Medio Día (Hasta 4 horas)</option>
        <option value="dia_completo">Día Completo (Más de 4 horas)</option>
    </select>
</div>
<?php else: ?>
<input type="hidden" name="duracion_alquiler" value="dia_completo">
<?php endif; ?>
                
<?php if ($auditorio['id_auditorio'] == 1): ?>
    <!-- Recorremos el rol para saber si es interno -->
    <?php 
    $esInterno = in_array($_SESSION['rol'], ['Docente', 'Admin_Facultad', 'SuperAdmin']); 
    ?>

    <?php if (!$esInterno): ?>
        <!-- Este selector SOLO lo verán los usuarios EXTERNOS -->
        <div class="mb-4">
            <label class="form-label fw-bold text-primary">Tipo de Actividad (Auditorio Central)</label>
            <select class="form-select border-primary shadow-sm" name="tipo_actividad" required>
                <option value="academico">Congreso / Académico</option>
                <option value="espectaculo">Espectáculo Artístico</option>
            </select>
        </div>
    <?php else: ?>
        <!-- Si es Docente o interno, el sistema asume automáticamente que es académico y envía el valor oculto -->
        <input type="hidden" name="tipo_actividad" value="academico">
    <?php endif; ?>
<?php endif; ?>

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

                <div id="contenedor-equipamiento" style="display:none;">
    <label for="equipamiento" class="form-label fw-bold">Equipamiento</label>
    <select name="equipamiento" id="equipamiento" class="form-select bg-light border-0 shadow-sm" required>
        <option value="solo_ambiente" selected>Solo Ambiente - Base</option>
        <option value="multimedia">Con Equipo Multimedia</option>
        <option value="multimedia_sonido">Con Equipo Multimedia y Sonido</option>
    </select>
    <p id="precio_display" class="mt-2 fw-bold">Precio: S/ 120.00</p>
</div>
<div id="resumen-pago" class="alert alert-info mt-3" style="display:none;">
                <p><strong>Detalles de la Reserva:</strong></p>
                <div id="detalle-equipos"></div>
                <div class="h5">Total a Pagar: <span id="monto_proyectado_texto">S/ 0.00</span></div>
                <input type="hidden" name="monto_proyectado" id="monto_proyectado" value="0.00">
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    const auditorioInput = document.getElementById('auditorio_id');
    const equipSelect = document.getElementById('equipamiento');
    const contEquip = document.getElementById('contenedor-equipamiento');
    const precioDisplay = document.getElementById('precio_display');
    const montoInput = document.getElementById('monto_proyectado');
    const montoTexto = document.getElementById('monto_proyectado_texto');
    const detalleEquip = document.getElementById('detalle-equipos');
    const resumenPago = document.getElementById('resumen-pago');

    function actualizarPrecio() {
        if (!auditorioInput) return;
        const idAud = parseInt(auditorioInput.value, 10);
        let monto = 0;
        let detalle = '';

        // Ocultar equipamiento por defecto y limpiar detalles
        if (contEquip) contEquip.style.display = 'none';
        if (detalleEquip) detalleEquip.innerHTML = '';

        // CAPTURA DE ROL (Interno vs Externo)
        const rol = auditorioInput.dataset.rol || '';
        const esInterno = (rol === 'Docente' || rol === 'Admin_Facultad' || rol === 'SuperAdmin');
        const durSelect = document.querySelector('select[name="duracion_alquiler"]');
        const dur = durSelect ? durSelect.value : 'dia_completo';

        // 1. CASO AUDITORIO CENTRAL (ID 1)
       // 1. CASO AUDITORIO CENTRAL (ID 1)
       if (idAud === 1) {
            if (esInterno) {
                monto = (dur === 'medio_dia') ? 400.00 : 800.00;
                detalle = 'Tarifa Interna (Docente/Dependencia) - Auditorio Central';
            } else {
                // CAPTURAMOS EL SELECTOR DE TIPO DE ACTIVIDAD
                const tipoActividadSelect = document.querySelector('[name="tipo_actividad"]');
                const tipoActividad = tipoActividadSelect ? tipoActividadSelect.value : 'academico';

                if (tipoActividad === 'espectaculo') {
                    monto = 2000.00;
                    detalle = 'Tarifa Externa: Espectáculo Artístico (Por Evento).';
                } else {
                    // Si es académico normal, usa los precios por defecto del TUPA externo
                    const medExt = parseFloat(auditorioInput.dataset.precioMedioExterno) || 900.00;
                    const compExt = parseFloat(auditorioInput.dataset.precioCompletoExterno) || 1800.00;
                    monto = (dur === 'medio_dia') ? medExt : compExt;
                    detalle = 'Tarifa Externa: Congreso / Académico.';
                }
            }
        }
        // 2. CASO INGENIERÍA PESQUERA (ID 7)
        else if (idAud === 7) {
            if (contEquip) contEquip.style.display = 'block';
            const sel = equipSelect ? equipSelect.value : 'solo_ambiente';
            if (sel === 'solo_ambiente') {
                monto = 120.00;
                detalle = 'Equipamiento seleccionado: Solo Ambiente - Base.';
            } else if (sel === 'multimedia') {
                monto = 170.00;
                detalle = 'Equipamiento seleccionado: Con Equipo Multimedia.';
            } else if (sel === 'multimedia_sonido') {
                monto = 200.00;
                detalle = 'Equipamiento seleccionado: Con Equipo Multimedia y Sonido.';
            }
        }
        // 3. CASO SALA DE CONFERENCIAS FIM (ID 8)
        else if (idAud === 8) {
            monto = 1000.00;
            detalle = 'Equipos incluidos: 01 pantalla interactiva 85" y 02 equipos de aire acondicionado.';
        }
        // 4. CASO AUDITORIO FIM (ID 9)
        else if (idAud === 9) {
            monto = 2200.00;
            detalle = 'Equipos incluidos: Micrófonos, 01 laptop, 01 equipo de sonido con 05 parlantes y 04 equipos de aire acondicionado.';
        }
        // 5. OTROS AUDITORIOS (Lógica General TUPA)
        else {
            const medioAttr = esInterno ? 'precioMedioInterno' : 'precioMedioExterno';
            const completoAttr = esInterno ? 'precioCompletoInterno' : 'precioCompletoExterno';
            const medio = parseFloat(auditorioInput.dataset[medioAttr]) || 0;
            const completo = parseFloat(auditorioInput.dataset[completoAttr]) || 0;
            if (medio === completo && medio > 0) {
                monto = medio;
            } else {
                monto = (dur === 'medio_dia') ? medio : completo;
            }
        }

        // MOSTRAR PRECIO EN TODOS LOS FORMULARIOS SIEMPRE
        if (monto > 0) {
            if (precioDisplay) precioDisplay.textContent = 'Precio: S/ ' + monto.toFixed(2);
            if (montoInput) montoInput.value = monto.toFixed(2);
            if (montoTexto) montoTexto.textContent = 'S/ ' + monto.toFixed(2);
            if (detalle && detalleEquip) {
                detalleEquip.innerHTML = '<div class="alert alert-warning mt-2">' + detalle + '</div>';
            }
            if (resumenPago) resumenPago.style.display = 'block';
        } else {
            if (resumenPago) resumenPago.style.display = 'none';
        }
    }

// Escuchar si cambia el tipo de actividad (Congreso vs Espectáculo)
const tipoActividadSelect = document.querySelector('[name="tipo_actividad"]');
    if (tipoActividadSelect) {
        tipoActividadSelect.addEventListener('change', actualizarPrecio);
    }

    if (equipSelect) equipSelect.addEventListener('change', actualizarPrecio);
    if (auditorioInput) auditorioInput.addEventListener('change', actualizarPrecio);
    const durSelect = document.querySelector('select[name="duracion_alquiler"]');
    if (durSelect) durSelect.addEventListener('change', actualizarPrecio);
    
    // Ejecución inicial
    actualizarPrecio();
});
</script>

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
