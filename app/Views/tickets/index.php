<h1 class="mt-4">Tickets</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active">Tickets</li>
</ol>

<div class="row">
    <div class="col-md-12">
        <a href="<?php echo base_url('tickets/crear'); ?>" class="btn btn-primary mb-3">Crear Ticket</a>
        
        <!-- Filtro por Cliente -->
        <div class="client-filter-container" style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <label for="clientFilter" class="form-label mb-0">
                        <i class="fas fa-filter me-1"></i>
                        Filtrar por Cliente:
                    </label>
                </div>
                <div class="col-md-6">
                    <select class="form-select" id="clientFilter">
                        <option value="">Todos los clientes</option>
                        <!-- Las opciones se llenarán con JavaScript -->
                    </select>
                </div>
                <div class="col-md-3">
                    <small class="text-muted">
                        <span id="ticketCount"><?= count($tickets); ?></span> ticket(s) mostrado(s)
                    </small>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>
                    <i class="fas fa-table me-1"></i>
                    Lista de Tickets
                </span>
                <div>
                    <a href="<?php echo base_url('tickets/'); ?>" class="btn btn-outline-primary btn-sm position-relative" title="Tickets abiertos">
                        <i class="fas fa-folder-open"></i>
                        <span class="visually-hidden">Tickets abiertos</span>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-custom-gray">
                            <?php echo $numero_tickets_abiertos; ?>
                        </span>
                    </a>
                    <a href="<?php echo base_url('tickets/cerrados'); ?>" class="btn btn-outline-secondary btn-sm position-relative" title="Tickets cerrados">
                        <i class="fas fa-folder"></i>
                        <span class="visually-hidden">Tickets cerrados</span>
                    </a>
                    <a href="<?php echo base_url('tickets/programados'); ?>" class="btn btn-outline-info btn-sm position-relative" title="Tickets programados">
                        <i class="fas fa-calendar-alt"></i>
                        <span class="visually-hidden">Tickets programados</span>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-custom-gray">
                            <?php echo $numero_tickets_programados; ?>
                        </span>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="list-group" id="ticketsList">
                    <?php foreach ($tickets as $ticket): ?>
                        <div class="list-group-item prioridad<?php echo $ticket['prioridad_ticket_nombre']; ?>" 
                             data-id="<?php echo $ticket['id']; ?>"
                             data-client="<?= htmlspecialchars($ticket['cliente_nombre']); ?>">
                            <div class="ticket-content" onclick="window.location.href='<?php echo base_url('tickets/detalle/' . $ticket['id']); ?>'">
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
                                            $fecha_mostrar = !empty($ticket['fecha_inicio_publicacion']) ? new DateTime($ticket['fecha_inicio_publicacion']) : new DateTime($ticket['fecha_creacion']);
                                            $hoy = new DateTime();
                                            $ayer = new DateTime('yesterday');
                                            
                                            if ($fecha_mostrar->format('Y-m-d') == $hoy->format('Y-m-d')) {
                                                echo 'Hoy ' . $fecha_mostrar->format('H:i');
                                            } elseif ($fecha_mostrar->format('Y-m-d') == $ayer->format('Y-m-d')) {
                                                echo 'Ayer ' . $fecha_mostrar->format('H:i');
                                            } elseif ($fecha_mostrar > $hoy) {
                                                echo 'Programado: ' . $fecha_mostrar->format('d/m/Y H:i');
                                            } else {
                                                echo $fecha_mostrar->format('d/m/Y');
                                            }
                                        ?>
                                    </small>
                                </div>
                                <p class="mb-1"><?= $ticket['descripcion']; ?></p>
                            </div>

                            <?php if (!empty($ticket['ultimo_movimiento'])): ?>
                                <div class="ticket-movement">
                                    <small>
                                        <?php
                                            $fecha_ultimo_movimiento = new DateTime($ticket['fecha_ultimo_movimiento']);
                                            $hoy = new DateTime();
                                            echo $fecha_ultimo_movimiento->format($fecha_ultimo_movimiento->format('Y-m-d') == $hoy->format('Y-m-d') ? 'H:i' : 'd/m/Y H:i');
                                        ?>
                                        <i class="fa fa-arrow-right"></i>
                                        <span class="ticket-short-movement"><?= substr($ticket['ultimo_movimiento'], 0, 100); ?>...</span>
                                        <span class="ticket-full-movement d-none">
                                            <?= $ticket['ultimo_movimiento']; ?>
                                            <?php if (!empty($ticket['imagen_ultimo_movimiento'])): ?>
                                                <br>
                                                <a href="<?= base_url('upload/mv/' . $ticket['imagen_ultimo_movimiento']); ?>" target="_blank" class="movement-image-link">
                                                    <img src="<?= base_url('upload/mv/thumbnails/' . $ticket['imagen_ultimo_movimiento']); ?>" alt="Imagen del movimiento" class="img-thumbnail movement-image" style="width: 150px; height: auto;">
                                                </a>
                                            <?php endif; ?>
                                        </span>
                                        <button class="btn btn-link toggle-movement"><i class="fa fa-chevron-down"></i></button>
                                    </small>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Mensaje cuando no hay resultados -->
                <div id="noResultsMessage" class="text-center py-4 d-none">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No se encontraron tickets</h5>
                    <p class="text-muted">No hay tickets que coincidan con el filtro seleccionado.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const clientFilter = document.getElementById('clientFilter');
    const ticketsList = document.getElementById('ticketsList');
    const noResultsMessage = document.getElementById('noResultsMessage');
    const ticketCount = document.getElementById('ticketCount');

    // Función para obtener clientes únicos de los tickets existentes
    function populateClientFilter() {
        const tickets = ticketsList.querySelectorAll('.list-group-item[data-client]');
        const clients = new Set();
        
        // Recopilar todos los nombres de clientes únicos
        tickets.forEach(ticket => {
            const clientName = ticket.getAttribute('data-client').trim();
            if (clientName) {
                clients.add(clientName);
            }
        });
        
        // Convertir a array y ordenar
        const sortedClients = Array.from(clients).sort();
        
        // Limpiar opciones existentes (excepto "Todos los clientes")
        while (clientFilter.children.length > 1) {
            clientFilter.removeChild(clientFilter.lastChild);
        }
        
        // Añadir opciones de clientes
        sortedClients.forEach(client => {
            const option = document.createElement('option');
            option.value = client;
            option.textContent = client;
            clientFilter.appendChild(option);
        });
    }

    // Función para filtrar tickets
    function filterTickets() {
        const selectedClient = clientFilter.value;
        const tickets = ticketsList.querySelectorAll('.list-group-item[data-client]');
        let visibleCount = 0;

        tickets.forEach(ticket => {
            const clientName = ticket.getAttribute('data-client');
            
            if (selectedClient === '' || clientName === selectedClient) {
                ticket.style.display = 'block';
                visibleCount++;
            } else {
                ticket.style.display = 'none';
            }
        });

        // Mostrar/ocultar mensaje de sin resultados
        if (visibleCount === 0) {
            noResultsMessage.classList.remove('d-none');
        } else {
            noResultsMessage.classList.add('d-none');
        }

        // Actualizar contador
        ticketCount.textContent = visibleCount;
    }

    // Inicializar
    populateClientFilter();
    
    // Event listener para el filtro
    clientFilter.addEventListener('change', filterTickets);
    
    // Mantener la funcionalidad existente de toggle movement
    document.querySelectorAll('.toggle-movement').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const ticketItem = this.closest('.list-group-item');
            const shortMovement = ticketItem.querySelector('.ticket-short-movement');
            const fullMovement = ticketItem.querySelector('.ticket-full-movement');
            const icon = this.querySelector('i');
            
            if (shortMovement.classList.contains('d-none')) {
                shortMovement.classList.remove('d-none');
                fullMovement.classList.add('d-none');
                icon.className = 'fa fa-chevron-down';
            } else {
                shortMovement.classList.add('d-none');
                fullMovement.classList.remove('d-none');
                icon.className = 'fa fa-chevron-up';
            }
        });
    });
});
</script>