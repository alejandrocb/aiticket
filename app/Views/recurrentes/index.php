<h1 class="mt-4">Tickets Recurrentes</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active">Tickets Recurrentes</li>
</ol>
<div class="row">
    <div class="col-md-12">
        <a href="<?php echo base_url('recurrentes/crear'); ?>" class="btn btn-primary mb-3">Crear Ticket Recurrente</a>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>
                    <i class="fas fa-table me-1"></i>
                    Lista de Tickets Recurrentes
                </span>
                <div>
                    <a href="<?php echo base_url('recurrentes'); ?>" class="btn btn-outline-primary btn-sm position-relative" title="Tickets Recurrentes Activos">
                        <i class="fas fa-sync-alt"></i>
                        <span class="visually-hidden">Tickets Recurrentes Activos</span>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="list-group" id="ticketsList">
                    <?php foreach ($tickets as $ticket): ?>
                        <div class="list-group-item prioridad<?php echo $ticket['prioridad_ticket_nombre']; ?>" data-id="<?php echo $ticket['id']; ?>">
                            <div class="ticket-content" onclick="window.location.href='<?php echo base_url('tickets_recurrentes/detalle/' . $ticket['id']); ?>'">
                                <div class="d-flex w-100 justify-content-between">
                                    <div class="d-flex">
                                        <?php if (!empty($ticket['responsable_imagen'])): ?>
                                            <img src="<?php echo base_url('upload/' . $ticket['responsable_imagen']); ?>" alt="Imagen de <?= $ticket['responsable_nombre']; ?>" class="rounded-circle border-0 me-3" style="width: 50px; height: 50px;">
                                        <?php endif; ?>
                                        <div>
                                            <h5 class="mb-1">
                                                <?= $ticket['cliente_nombre']; ?> 
                                                <span class="badge prioridad<?php echo $ticket['prioridad_ticket_nombre']; ?>">
                                                    <i class="<?php echo $ticket['prioridad_ticket_nombre']; ?> text-light"></i> 
                                                    <?= $ticket['prioridad_ticket_nombre']; ?>
                                                </span>
                                                <span class="badge <?php echo $ticket['estado_estilo'] ?>">
                                                    <i class="<?php echo $ticket['estado_icono']; ?> text-light"></i> 
                                                    <?= $ticket['estado_nombre']; ?>
                                                </span>
                                                <small>
                                                    <span class="badge <?php echo $ticket['estado_estilo'] ?>">
                                                        <i class="<?php echo $ticket['tipo_ticket_icono']; ?> text-light"></i> 
                                                        <?= $ticket['tipo_ticket_nombre']; ?>
                                                    </span>
                                                </small>
                                            </h5>
                                        </div>
                                    </div>
                                    <small>
                                        <?php
                                            $fecha_ultima_ejecucion = new DateTime($ticket['ultima_ejecucion']);
                                            $hoy = new DateTime();
                                            $ayer = new DateTime('yesterday');

                                            if ($fecha_ultima_ejecucion->format('Y-m-d') == $hoy->format('Y-m-d')) {
                                                echo 'Última ejecución: Hoy ' . $fecha_ultima_ejecucion->format('H:i');
                                            } elseif ($fecha_ultima_ejecucion->format('Y-m-d') == $ayer->format('Y-m-d')) {
                                                echo 'Última ejecución: Ayer ' . $fecha_ultima_ejecucion->format('H:i');
                                            } else {
                                                echo 'Última ejecución: ' . $fecha_ultima_ejecucion->format('d/m/Y H:i');
                                            }
                                        ?>
                                    </small>
                                </div>
                                <p class="mb-1"><?= $ticket['descripcion']; ?></p>
                                <small class="text-muted">Frecuencia: <?= $ticket['frecuencia']; ?></small>
                                <?php if ($ticket['frecuencia'] == 'mensual' || $ticket['frecuencia'] == 'anual'): ?>
                                    <small class="text-muted"> - Día del mes: <?= $ticket['dia_mes']; ?></small>
                                <?php endif; ?>
                                <?php if ($ticket['frecuencia'] == 'semanal'): ?>
                                    <small class="text-muted"> - Día de la semana: <?= $ticket['dia_semana']; ?></small>
                                <?php endif; ?>
                                <?php if ($ticket['frecuencia'] == 'anual'): ?>
                                    <small class="text-muted"> - Mes: <?= $ticket['mes']; ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
