<h2>Crear Cliente</h2>
<form action="/clientes/insertar" method="post">
    <div class="form-group">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="telefono">Teléfono:</label>
        <input type="text" name="telefono" id="telefono" class="form-control">
    </div>
    <div class="form-group">
        <label for="direccion">Dirección:</label>
        <input type="text" name="direccion" id="direccion" class="form-control">
    </div>
    
    <?php if (count($escenarios) > 1): ?>
        <div class="form-group">
            <label for="escenario_id">Escenario:</label>
            <select name="escenario_id" id="escenario_id" class="form-control">
                <?php foreach ($escenarios as $escenario): ?>
                    <option value="<?= $escenario['id']; ?>"><?= $escenario['nombre']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php else: ?>
        <input type="hidden" name="escenario_id" value="<?= $escenarios[0]['id']; ?>">
    <?php endif; ?>
    
    <button type="submit" class="btn btn-primary">Crear</button>
</form>
