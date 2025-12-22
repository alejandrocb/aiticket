<h2>Editar Cliente</h2>
<form action="/clientes/actualizar/<?= $cliente['id']; ?>" method="post">
    <div class="form-group">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" value="<?= $cliente['nombre']; ?>" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?= $cliente['email']; ?>" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="telefono">Teléfono:</label>
        <input type="text" name="telefono" id="telefono" value="<?= $cliente['telefono']; ?>" class="form-control">
    </div>
    <div class="form-group">
        <label for="direccion">Dirección:</label>
        <input type="text" name="direccion" id="direccion" value="<?= $cliente['direccion']; ?>" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Actualizar</button>
</form>
