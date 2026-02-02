<?php include(APPPATH . 'Views/templates/header.php'); ?>
<?php include(APPPATH . 'Views/templates/navbar.php'); ?>

<div id="layoutSidenav">
    <?php include(APPPATH . 'Views/templates/sidebar.php'); ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Clientes</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Clientes</li>
                </ol>
                <div class="row">
                    <div class="col-md-12">
                        <a href="<?php echo base_url('clientes/crear'); ?>" class="btn btn-primary mb-3">Crear Cliente</a>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Lista de Clientes
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Email</th>
                                            <th>Teléfono</th>
                                            <th>Dirección</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Email</th>
                                            <th>Teléfono</th>
                                            <th>Dirección</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php foreach ($clientes as $cliente): ?>
                                        <tr>
                                            <td><?= $cliente['nombre']; ?></td>
                                            <td><?= $cliente['email']; ?></td>
                                            <td><?= $cliente['telefono']; ?></td>
                                            <td><?= $cliente['direccion']; ?></td>
                                            <td>
                                                <a href="<?php echo base_url('clientes/editar/' . $cliente['id']); ?>" class="btn btn-warning">Editar</a>
                                                <a href="<?php echo base_url('clientes/eliminar/' . $cliente['id']); ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include(APPPATH . 'Views/templates/footer.php'); ?>
