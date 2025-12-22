<!-- sidebar.php -->
<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link" href="<?php echo base_url('dashboard'); ?>">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                <a class="nav-link" href="<?php echo base_url('tickets'); ?>">
                    <div class="sb-nav-link-icon"><i class="fas fa-ticket-alt"></i></div>
                    Tickets
                </a>
                <a class="nav-link" href="<?php echo base_url('clientes'); ?>">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    Clientes
                </a>
                <a class="nav-link" href="<?php echo base_url('recurrentes'); ?>">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-bar"></i></div>
                    Recurrentes
                </a>
                <div class="sb-sidenav-menu-heading">Escenarios</div>
                <?php if (isset($escenarios) && !empty($escenarios)): ?>
                    <?php foreach ($escenarios as $escenario): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="<?= $escenario['id']; ?>" id="escenario_<?= $escenario['id']; ?>" name="escenarios[]" <?= $escenario['activo'] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="escenario_<?= $escenario['id']; ?>">
                                <?= $escenario['nombre']; ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            <?= session()->get('nombre'); ?>
        </div>
    </nav>
</div>
