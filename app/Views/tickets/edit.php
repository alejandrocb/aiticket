<!-- tickets/edit.php -->
<h2 class="mt-4">Editar Ticket</h2>
<form action="/tickets/actualizar/<?= $ticket['id']; ?>" method="post">
    <input type="hidden" name="ticket_id" value="<?= $ticket['id']; ?>">

    <div class="form-group">
        <label for="cliente_id">Cliente:</label>
        <div class="d-flex justify-content-between align-items-center">
            <select name="cliente_id" id="cliente_id" class="form-control" required>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= $cliente['id']; ?>" <?= $cliente['id'] == $ticket['cliente_id'] ? 'selected' : ''; ?>><?= $cliente['nombre']; ?></option>
                <?php endforeach; ?>
            </select>
            <a href="/clientes/editar/<?= $ticket['cliente_id']; ?>" class="text-decoration-none ms-2">
                <i class="fas fa-pencil-alt"></i>
            </a>
        </div>
    </div>

    <div class="form-group">
        <label for="responsable_id">Asignar a:</label>
        <select class="form-select" name="responsable_id" size="3" aria-label="size 3 select example" required>
            <option value="" selected>Seleccione un usuario</option>
            <?php foreach ($usuarios as $usuario): ?>
                <option value="<?= $usuario['id']; ?>" <?= $usuario['id'] == $ticket['responsable_id'] ? 'selected' : ''; ?>>
                    <?= $usuario['nombre']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="tipo_ticket_id">Tipo:</label>
        <select name="tipo_ticket_id" id="tipo_ticket_id" class="form-control" required>
            <?php foreach ($tipos as $tipo): ?>
                <option value="<?= $tipo['id']; ?>" <?= $tipo['id'] == $ticket['tipo_ticket_id'] ? 'selected' : ''; ?>><?= $tipo['nombre']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="prioridad_ticket_id">Prioridad:</label>
        <input type="range" class="form-range" min="1" max="4" step="1" id="prioridad_ticket_id" name="prioridad_ticket_id" value="<?= $ticket['prioridad_ticket_id']; ?>" oninput="updatePriorityLabel(this.value)">
        <div class="d-flex justify-content-between">
            <span>Baja</span>
            <span id="priorityLabel" class="text-center">Media</span>
            <span>Alta</span>
            <span>Urgente</span>
        </div>
    </div>

    <div class="form-group">
        <label for="estado_ticket_id">Estado:</label>
        <select name="estado_ticket_id" id="estado_ticket_id" class="form-control" required>
            <?php foreach ($estados as $estado): ?>
                <option value="<?= $estado['id']; ?>" <?= $estado['id'] == $ticket['estado_ticket_id'] ? 'selected' : ''; ?>><?= $estado['nombre']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="fecha_ticket">Fecha:</label>
        <input type="datetime-local" name="fecha_ticket" id="fecha_ticket" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($ticket['fecha_creacion'])); ?>" required>
    </div>

    <div class="form-group">
        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" id="descripcion" class="form-control" required><?= $ticket['descripcion']; ?></textarea>
    </div>
    
    <button type="submit" class="btn btn-primary mt-3">Actualizar Ticket</button>
</form>

