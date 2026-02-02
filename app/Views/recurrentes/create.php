<div class="container-fluid px-4">
    <h2 class="mt-4">Crear Ticket Recurrente</h2>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Inicio</a></li>
        <li class="breadcrumb-item active">Crear Ticket Recurrente</li>
    </ol>

    <div class="card mb-4">
        <div class="card-body">
            <form action="<?php echo base_url('/recurrentes/insertar'); ?>" method="post">
                
                <!-- Datos del Ticket -->
                <div class="card mb-3">
                    <div class="card-header">
                        Datos del Ticket
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="cliente_id" class="form-label">Cliente:</label>
                            <select name="cliente_id" id="cliente_id" class="form-select" required>
                                <?php foreach ($clientes as $cliente): ?>
                                    <option value="<?= $cliente['id']; ?>"><?= $cliente['nombre']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="responsable_id" class="form-label">Asignar a:</label>
                            <select class="form-select" name="responsable_id" id="responsable_id" required>
                                <option value="" selected disabled>Seleccione un usuario</option>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <option value="<?= $usuario['id']; ?>"><?= $usuario['nombre']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="tipo_ticket_id" class="form-label">Tipo:</label>
                            <select name="tipo_ticket_id" id="tipo_ticket_id" class="form-select" required>
                                <?php foreach ($tipos as $tipo): ?>
                                    <option value="<?= $tipo['id']; ?>"><?= $tipo['nombre']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="prioridad_ticket_id" class="form-label">Prioridad:</label>
                            <input type="range" class="form-range" min="1" max="4" step="1" id="prioridad_ticket_id" name="prioridad_ticket_id" value="2" oninput="updatePriorityLabel(this.value)">
                            <div class="d-flex justify-content-between">
                                <span class="badge bg-success">Baja</span>
                                <span id="priorityLabel" class="badge bg-primary">Media</span>
                                <span class="badge bg-warning text-dark">Alta</span>
                                <span class="badge bg-danger">Urgente</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="estado_ticket_id" class="form-label">Estado:</label>
                            <select name="estado_ticket_id" id="estado_ticket_id" class="form-select" required>
                                <?php foreach ($estados as $estado): ?>
                                    <option value="<?= $estado['id']; ?>"><?= $estado['nombre']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción:</label>
                            <textarea name="descripcion" id="descripcion" class="form-control" rows="4" required></textarea>
                        </div>
                    </div>
                </div>

                <!-- Datos de la Periodicidad -->
                <div class="card">
                    <div class="card-header">
                        Datos de la Periodicidad
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="frecuencia" class="form-label">Frecuencia:</label>
                            <select name="frecuencia" id="frecuencia" class="form-select" required onchange="updateFrequencyControls()">
                                <option value="diaria">Diaria</option>
                                <option value="semanal">Semanal</option>
                                <option value="mensual">Mensual</option>
                                <option value="anual">Anual</option>
                            </select>
                        </div>

                        <div class="mb-3" id="dia_mes_container" style="display:none;">
                            <label for="dia_mes" class="form-label">Día del Mes (1-31):</label>
                            <select name="dia_mes" id="dia_mes" class="form-select">
                                <?php for ($i = 1; $i <= 31; $i++): ?>
                                    <option value="<?= $i; ?>"><?= $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="mb-3" id="dia_semana_container" style="display:none;">
                            <label for="dia_semana" class="form-label">Día de la Semana:</label><br>
                            <?php 
                            $dias_semana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                            foreach ($dias_semana as $index => $dia): ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="dia_semana_<?= $index; ?>" name="dia_semana[]" value="<?= $index; ?>">
                                    <label class="form-check-label" for="dia_semana_<?= $index; ?>"><?= $dia; ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="mb-3" id="mes_container" style="display:none;">
                            <label for="mes" class="form-label">Mes:</label>
                            <select name="mes" id="mes" class="form-select">
                                <?php 
                                $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                                foreach ($meses as $index => $mes): ?>
                                    <option value="<?= $index + 1; ?>"><?= $mes; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">
                    <i class="fas fa-save"></i> Crear Ticket Recurrente
                </button>
            </form>
        </div>
    </div>
</div>

<script>

    //function updatePriorityLabel(value) {
    //    const label = document.getElementById('priorityLabel');
    //    const labels = ['Baja', 'Media', 'Alta', 'Urgente'];
    //    const colors = ['bg-success', 'bg-primary', 'bg-warning', 'bg-danger'];
    //    label.textContent = labels[value - 1];
    //    label.className = `badge ${colors[value - 1]}`;
    //    if (value == 3) label.classList.add('text-dark');
    //}

    function updateFrequencyControls() {
        const frecuencia = document.getElementById('frecuencia').value;
        document.getElementById('dia_mes_container').style.display = (frecuencia == 'mensual' || frecuencia == 'anual') ? 'block' : 'none';
        document.getElementById('dia_semana_container').style.display = (frecuencia == 'semanal') ? 'block' : 'none';
        document.getElementById('mes_container').style.display = (frecuencia == 'anual') ? 'block' : 'none';
    }

    // Inicializar el label de prioridad
    updatePriorityLabel(document.getElementById('prioridad_ticket_id').value);

    // Actualizar los controles de frecuencia al cargar la página
    updateFrequencyControls();
</script>
