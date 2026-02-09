<!-- tickets/index_modern.php -->
<!-- Search and Advanced Filters for Tickets -->
<div class="px-0 py-2 pb-3">
    <label class="flex flex-col min-w-40 h-11 w-full">
        <div class="flex w-full flex-1 items-stretch rounded-xl h-full shadow-sm">
            <div class="text-text-secondary flex border-none bg-surface-light dark:bg-surface-dark items-center justify-center pl-4 rounded-l-xl border-r-0">
                <span class="material-symbols-outlined text-[22px]">search</span>
            </div>
            <input id="search-input" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-[#111418] dark:text-white focus:outline-0 focus:ring-0 border-none bg-surface-light dark:bg-surface-dark focus:border-none h-full placeholder:text-text-secondary px-4 rounded-l-none border-l-0 pl-3 text-base font-normal leading-normal" placeholder="Buscar por palabra clave o ID..." value=""/>
        </div>
    </label>
</div>

<!-- Advanced Filters Toggle Button -->
<div class="px-0 py-2 flex items-center justify-between">
    <button id="toggle-filters-btn" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-surface-light dark:bg-surface-dark border border-[#e5e7eb] dark:border-transparent hover:bg-gray-100 dark:hover:bg-[#2c3b4a] transition-colors text-[#111418] dark:text-white">
        <span class="material-symbols-outlined text-[20px]">tune</span>
        <span class="text-sm font-medium">Filtros Avanzados</span>
        <span id="filter-count" class="hidden ml-1 bg-primary text-white text-xs font-bold px-2 py-0.5 rounded-full">0</span>
    </button>
    <button id="clear-filters-btn" class="hidden items-center gap-1 px-3 py-2 rounded-lg text-sm font-medium text-red-500 hover:bg-red-50 dark:hover:bg-red-950/20 transition-colors">
        <span class="material-symbols-outlined text-[18px]">close</span>
        Limpiar filtros
    </button>
</div>

<!-- Advanced Filters Panel -->
<div id="filters-panel" class="hidden px-0 py-3 mb-4 bg-surface-light dark:bg-surface-dark rounded-xl border border-[#e5e7eb] dark:border-transparent shadow-sm">
    <form id="filters-form" method="get" action="/tickets" class="p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Filtro por Fecha Desde -->
            <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-[#111418] dark:text-white">Fecha Desde</label>
                <input type="date" name="fecha_desde" id="filter-fecha-desde" value="<?= esc($filters['fecha_desde'] ?? '') ?>" class="px-3 py-2 rounded-lg border border-[#e5e7eb] dark:border-[#2c3b4a] bg-white dark:bg-[#1a2129] text-[#111418] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary text-sm">
            </div>

            <!-- Filtro por Fecha Hasta -->
            <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-[#111418] dark:text-white">Fecha Hasta</label>
                <input type="date" name="fecha_hasta" id="filter-fecha-hasta" value="<?= esc($filters['fecha_hasta'] ?? '') ?>" class="px-3 py-2 rounded-lg border border-[#e5e7eb] dark:border-[#2c3b4a] bg-white dark:bg-[#1a2129] text-[#111418] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary text-sm">
            </div>

            <!-- Filtro por Cliente -->
            <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-[#111418] dark:text-white">Cliente</label>
                <select name="cliente_id" id="filter-cliente" class="px-3 py-2 rounded-lg border border-[#e5e7eb] dark:border-[#2c3b4a] bg-white dark:bg-[#1a2129] text-[#111418] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary text-sm">
                    <option value="all">Todos los clientes</option>
                    <?php if (!empty($clientes)): ?>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?= $cliente['id'] ?>" <?= (isset($filters['cliente_id']) && $filters['cliente_id'] == $cliente['id']) ? 'selected' : '' ?>>
                                <?= esc($cliente['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Filtro por Agente/Responsable -->
            <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-[#111418] dark:text-white">Agente</label>
                <select name="responsable_id" id="filter-responsable" class="px-3 py-2 rounded-lg border border-[#e5e7eb] dark:border-[#2c3b4a] bg-white dark:bg-[#1a2129] text-[#111418] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary text-sm">
                    <option value="all">Todos los agentes</option>
                    <?php if (!empty($usuarios)): ?>
                        <?php foreach ($usuarios as $usuario): ?>
                            <option value="<?= $usuario['id'] ?>" <?= (isset($filters['responsable_id']) && $filters['responsable_id'] == $usuario['id']) ? 'selected' : '' ?>>
                                <?= esc($usuario['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="flex items-center gap-3 mt-4 pt-4 border-t border-[#e5e7eb] dark:border-[#2c3b4a]">
            <button type="submit" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-primary text-white font-medium text-sm hover:bg-primary/90 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[18px]">filter_alt</span>
                Aplicar Filtros
            </button>
            <button type="button" id="reset-filters-btn" class="px-4 py-2 rounded-lg border border-[#e5e7eb] dark:border-[#2c3b4a] text-[#111418] dark:text-white font-medium text-sm hover:bg-gray-100 dark:hover:bg-[#2c3b4a] transition-colors">
                Resetear
            </button>
        </div>
    </form>
</div>

<div class="flex gap-3 px-0 py-3 overflow-x-auto no-scrollbar items-center mb-4">
    <button id="btn-filter-open" class="filter-toggle-btn flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full bg-primary pl-5 pr-5 shadow-lg shadow-primary/20 transition-transform active:scale-95 text-white" data-mode="open">
        <span class="material-symbols-outlined text-sm">lock_open</span>
        <p class="text-sm font-semibold leading-normal">Abiertos</p>
    </button>
    <button id="btn-filter-closed" class="filter-toggle-btn flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full bg-surface-light dark:bg-surface-dark border border-[#e5e7eb] dark:border-transparent pl-4 pr-5 hover:bg-gray-100 dark:hover:bg-[#2c3b4a] transition-colors active:scale-95 text-[#111418] dark:text-white" data-mode="closed">
        <span class="material-symbols-outlined text-sm">check_circle</span>
        <p class="text-sm font-medium leading-normal">Cerrados</p>
    </button>
</div>

<div class="flex items-center justify-between mb-2">
    <p class="text-sm font-semibold text-text-secondary uppercase tracking-wider">Lista de Tickets</p>
    <button id="btn-sort-date" class="flex items-center text-primary text-sm font-medium gap-1 cursor-pointer hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg px-2 py-1 transition-colors">
        Fecha de creación <span class="material-symbols-outlined text-sm">sort</span>
    </button>
</div>

<!-- Ticket Items List -->
<div class="flex flex-col gap-3" id="tickets-container">
    <?php if (empty($tickets)): ?>
        <div class="p-4 text-center text-text-secondary">
            No hay tickets para mostrar.
        </div>
    <?php else: ?>
        <?php foreach ($tickets as $ticket): ?>
            <?php 
                // Determinar colores basados en prioridad
                $priorityColor = 'text-gray-500 bg-gray-500/10 ring-gray-500/20';
                if (stripos($ticket['prioridad_ticket_nombre'], 'alta') !== false) {
                    $priorityColor = 'text-red-400 bg-red-400/10 ring-red-400/20';
                } elseif (stripos($ticket['prioridad_ticket_nombre'], 'media') !== false) {
                    $priorityColor = 'text-amber-400 bg-amber-400/10 ring-amber-400/20';
                } elseif (stripos($ticket['prioridad_ticket_nombre'], 'baja') !== false) {
                    $priorityColor = 'text-green-400 bg-green-400/10 ring-green-400/20';
                }
            ?>
            <a href="/tickets/detail/<?= $ticket['id'] ?>" 
               class="ticket-item flex flex-col gap-3 bg-surface-light dark:bg-surface-dark p-4 rounded-xl shadow-sm border border-[#e5e7eb] dark:border-transparent active:scale-[0.99] transition-transform cursor-pointer hover:shadow-md"
               data-client="<?= strtolower(esc($ticket['cliente_nombre'])) ?>"
               data-status="<?= strtolower(esc($ticket['estado_nombre'])) ?>"
               data-desc="<?= strtolower(esc($ticket['descripcion'])) ?>"
               data-id="<?= $ticket['id'] ?>"
               data-timestamp="<?= strtotime($ticket['fecha_relevante'] ?? $ticket['fecha_creacion']) ?>"
            >
                <div class="flex justify-between items-start">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <!-- Placeholder inicial del cliente -->
                            <div class="flex items-center justify-center bg-primary text-white rounded-full h-10 w-10 ring-2 ring-white dark:ring-[#283039] font-bold text-lg">
                                <?= strtoupper(substr($ticket['cliente_nombre'], 0, 1)) ?>
                            </div>
                        </div>
                        <div>
                            <p class="text-[#111418] dark:text-white text-sm font-bold leading-tight client-name"><?= esc($ticket['cliente_nombre']) ?></p>
                            <p class="text-text-secondary text-xs font-normal">#Ticket-<?= $ticket['id'] ?> • <?= date('d/m/Y', strtotime($ticket['fecha_creacion'] ?? 'now')) ?></p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset <?= $priorityColor ?>">
                            <?= esc($ticket['prioridad_ticket_nombre']) ?>
                        </span>
                        
                        <?php 
                            $mainMedia = json_decode($ticket['media'] ?? '[]', true);
                            $lastMovMedia = json_decode($ticket['ultimo_movimiento_media'] ?? '[]', true);
                            $galleryMedia = !empty($lastMovMedia) ? $lastMovMedia : $mainMedia;
                        ?>
                        
                        <?php if (!empty($galleryMedia)): ?>
                            <button type="button" 
                                    class="gallery-trigger flex items-center justify-center bg-white dark:bg-slate-800 rounded-lg p-1.5 border border-slate-200 dark:border-slate-700 shadow-sm hover:bg-primary/10 hover:border-primary/30 transition-all group"
                                    onclick="event.preventDefault(); event.stopPropagation(); openGallery(<?= htmlspecialchars(json_encode($galleryMedia)) ?>, '<?= $ticket['id'] ?>')"
                            >
                                <span class="material-symbols-outlined text-[20px] text-slate-400 group-hover:text-primary">images</span>
                                <?php if (count($galleryMedia) > 1): ?>
                                    <span class="absolute -top-1 -right-1 bg-primary text-white text-[9px] font-bold h-4 w-4 rounded-full flex items-center justify-center border-2 border-white dark:border-slate-800">
                                        <?= count($galleryMedia) ?>
                                    </span>
                                <?php endif; ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                <div>
                    <!-- Primary Description (Ticket Title/Desc) -->
                    <p class="text-[#111418] dark:text-white text-base font-bold leading-normal mb-1 ticket-desc"><?= esc(substr($ticket['descripcion'], 0, 80)) . (strlen($ticket['descripcion']) > 80 ? '...' : '') ?></p>
                    
                    <!-- Secondary Description (Last User Movement) -->
                    <?php if (!empty($ticket['ultimo_movimiento'])): ?>
                         <div class="flex items-center gap-2 mt-1">
                            <?php 
                                $movIcon = 'chat';
                                if (($ticket['ultimo_movimiento_tipo'] ?? '') === 'Cambio de Estado') {
                                    $movIcon = 'radio_button_checked';
                                } elseif (($ticket['ultimo_movimiento_tipo'] ?? '') === 'Cambio de Responsable') {
                                    $movIcon = 'person';
                                }
                            ?>
                            <span class="material-symbols-outlined text-xs text-text-secondary"><?= $movIcon ?></span>
                            <p class="text-text-secondary text-sm font-medium leading-relaxed line-clamp-1 italic">"<?= esc($ticket['ultimo_movimiento']) ?>"</p>
                            <span class="text-[10px] text-text-secondary">• <?= date('d/m/Y', strtotime($ticket['fecha_ultimo_movimiento'])) ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="h-px w-full bg-[#e5e7eb] dark:bg-[#2c3b4a]"></div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[18px]">radio_button_checked</span>
                        <p class="text-text-secondary text-sm font-medium ticket-status"><?= esc($ticket['estado_nombre']) ?></p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-text-secondary font-medium">Agente:</span>
                        <div class="flex items-center gap-2 bg-background-light dark:bg-background-dark/50 pr-3 pl-1 py-1 rounded-full border border-slate-100 dark:border-slate-800">
                            <div class="h-6 w-6 rounded-full bg-primary/10 text-primary flex items-center justify-center text-[10px] font-bold border border-primary/20 relative overflow-hidden shadow-sm">
                                <!-- Fallback Initials -->
                                <span><?= $ticket['responsable_nombre'] ? strtoupper(substr($ticket['responsable_nombre'], 0, 2)) : 'NA' ?></span>
                                
                                <!-- Overlay Image -->
                                <?php if ($ticket['responsable_imagen']): ?>
                                    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('<?= base_url($ticket['responsable_imagen']) ?>')"></div>
                                <?php endif; ?>
                            </div>
                            <span class="text-xs font-medium text-[#111418] dark:text-white">
                                <?= $ticket['responsable_nombre'] ? esc($ticket['responsable_nombre']) : 'Sin Asignar' ?>
                            </span>
                            <?php if($ticket['responsable_id']): ?>
                                <?php 
                                    $checkIcon = 'check'; 
                                    $checkColor = 'text-gray-400';
                                    $checkTitle = 'No visto aún';

                                    if (!empty($ticket['leido_responsable_at'])) {
                                        $checkIcon = 'done_all';
                                        $checkColor = 'text-blue-500';
                                        $checkTitle = 'Leído: ' . date('d/m H:i', strtotime($ticket['leido_responsable_at']));
                                    } elseif (!empty($ticket['visto_responsable_at'])) {
                                        $checkIcon = 'check'; // Cambio a check azul para diferenciar
                                        $checkColor = 'text-blue-500';
                                        $checkTitle = 'Visto en lista: ' . date('d/m H:i', strtotime($ticket['visto_responsable_at']));
                                    }
                                ?>
                                <span class="material-symbols-outlined text-[16px] <?= $checkColor ?> ml-0.5" title="<?= $checkTitle ?>">
                                    <?= $checkIcon ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('#search-input');
    const ticketItems = document.querySelectorAll('.ticket-item');
    const filterBtns = document.querySelectorAll('.filter-toggle-btn');
    
    // Configuración de Estados
    const hiddenStatuses = ['anulada', 'anulado']; // Status never shown
    const closedStatuses = ['cerrado', 'cerrada', 'finalizada', 'finalizado']; // Status for 'Closed' tab
    
    let currentMode = 'open'; // 'open' or 'closed'

    // Advanced Filters Elements
    const toggleFiltersBtn = document.getElementById('toggle-filters-btn');
    const filtersPanel = document.getElementById('filters-panel');
    const clearFiltersBtn = document.getElementById('clear-filters-btn');
    const resetFiltersBtn = document.getElementById('reset-filters-btn');
    const filterCount = document.getElementById('filter-count');

    // Toggle Advanced Filters Panel
    if (toggleFiltersBtn) {
        toggleFiltersBtn.addEventListener('click', function() {
            filtersPanel.classList.toggle('hidden');
        });
    }

    // Reset Filters Button
    if (resetFiltersBtn) {
        resetFiltersBtn.addEventListener('click', function() {
            document.getElementById('filter-fecha-desde').value = '';
            document.getElementById('filter-fecha-hasta').value = '';
            document.getElementById('filter-cliente').value = 'all';
            document.getElementById('filter-responsable').value = 'all';
            updateFilterCount();
        });
    }

    // Clear Filters and Reload
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            window.location.href = '/tickets';
        });
    }

    // Update Filter Count
    function updateFilterCount() {
        let count = 0;
        const fechaDesde = document.getElementById('filter-fecha-desde').value;
        const fechaHasta = document.getElementById('filter-fecha-hasta').value;
        const cliente = document.getElementById('filter-cliente').value;
        const responsable = document.getElementById('filter-responsable').value;

        if (fechaDesde) count++;
        if (fechaHasta) count++;
        if (cliente && cliente !== 'all') count++;
        if (responsable && responsable !== 'all') count++;

        if (count > 0) {
            filterCount.textContent = count;
            filterCount.classList.remove('hidden');
            clearFiltersBtn.classList.remove('hidden');
            clearFiltersBtn.classList.add('flex');
        } else {
            filterCount.classList.add('hidden');
            clearFiltersBtn.classList.add('hidden');
            clearFiltersBtn.classList.remove('flex');
        }
    }

    // Check for active filters on page load
    updateFilterCount();

    // Auto-open filters panel if filters are active
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('fecha_desde') || urlParams.has('fecha_hasta') || 
        urlParams.has('cliente_id') || urlParams.has('responsable_id')) {
        filtersPanel.classList.remove('hidden');
    }

    // Init
    applyFilters();

    // Search Event
    if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
    }

    // Button Events
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            currentMode = this.dataset.mode;
            
            // Visual Update Buttons
            filterBtns.forEach(b => {
                // Reset to regular style
                b.classList.remove('bg-primary', 'text-white', 'shadow-lg');
                b.classList.add('bg-surface-light', 'dark:bg-surface-dark', 'text-[#111418]', 'dark:text-white', 'border');
                
                // Reset icon color if needed (optional)
                b.querySelector('span').classList.remove('text-white');
            });
            
            // Set active style
            this.classList.remove('bg-surface-light', 'dark:bg-surface-dark', 'text-[#111418]', 'dark:text-white', 'border');
            this.classList.add('bg-primary', 'text-white', 'shadow-lg');
            this.querySelector('span').classList.add('text-white');

            applyFilters();
        });
    });

    // Sort Logic
    const sortBtn = document.getElementById('btn-sort-date');
    const ticketsContainer = document.getElementById('tickets-container');
    let sortAsc = false; // Default desc (newest first)

    if(sortBtn) {
        sortBtn.addEventListener('click', function() {
            const items = Array.from(ticketsContainer.querySelectorAll('.ticket-item'));
            
            items.sort((a, b) => {
                const timeA = parseInt(a.dataset.timestamp || 0);
                const timeB = parseInt(b.dataset.timestamp || 0);
                return sortAsc ? (timeA - timeB) : (timeB - timeA);
            });

            // Re-append in new order
            items.forEach(item => ticketsContainer.appendChild(item));
            
            // Toggle direction
            sortAsc = !sortAsc;
            
            // Update Icon
            const icon = this.querySelector('span');
            icon.textContent = sortAsc ? 'arrow_upward' : 'arrow_downward';
        });
    }

    function applyFilters() {
        const searchTerm = searchInput.value.toLowerCase();

        ticketItems.forEach(item => {
            const client = item.dataset.client;
            const status = item.dataset.status;
            const desc = item.dataset.desc;
            const id = item.dataset.id;
            
            // 0. Global Filter: Anulados are always hidden
            const isAnnulled = hiddenStatuses.some(s => status.includes(s));
            if (isAnnulled) {
                item.style.display = 'none';
                return; // Skip rest of logic
            }

            // 1. Check Search Term
            const matchesSearch = client.includes(searchTerm) || 
                                  desc.includes(searchTerm) || 
                                  status.includes(searchTerm) || 
                                  id.includes(searchTerm);

            // 2. Check Tab Filter
            let matchesTab = false;
            const isClosed = (status === 'cerrado' || status === 'cerrada' || status === 'finalizado' || status === 'finalizada');

            if (currentMode === 'open') {
                matchesTab = !isClosed;
            } else if (currentMode === 'closed') {
                matchesTab = isClosed;
            }

            // Final Visibility
            if (matchesSearch && matchesTab) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Modal Gallery logic
    window.openGallery = function(media, ticketId) {
        const modal = document.getElementById('gallery-modal');
        const container = document.getElementById('gallery-content');
        container.innerHTML = '';
        
        media.forEach((item, index) => {
            const slide = document.createElement('div');
            slide.className = 'gallery-slide hidden w-full h-full flex items-center justify-center';
            if (index === 0) slide.classList.remove('hidden');
            
            const filePath = `<?= base_url('upload/tickets/') ?>${item.filename}`;
            
            if (item.type === 'image') {
                const img = document.createElement('img');
                img.src = filePath;
                img.className = 'max-w-full max-h-full object-contain rounded-lg shadow-2xl';
                slide.appendChild(img);
            } else {
                const video = document.createElement('video');
                video.src = filePath;
                video.controls = true;
                video.className = 'max-w-full max-h-full rounded-lg shadow-2xl';
                slide.appendChild(video);
            }
            container.appendChild(slide);
        });

        modal.dataset.currentIndex = 0;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Actualizar contador
        updateGalleryCounter(0, media.length);
        
        // Show/Hide Nav Buttons
        document.getElementById('gallery-prev').style.display = media.length > 1 ? 'flex' : 'none';
        document.getElementById('gallery-next').style.display = media.length > 1 ? 'flex' : 'none';
    };

    window.closeGallery = function() {
        const modal = document.getElementById('gallery-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    };

    window.nextSlide = function(direction) {
        const slides = document.querySelectorAll('.gallery-slide');
        const modal = document.getElementById('gallery-modal');
        let index = parseInt(modal.dataset.currentIndex);
        
        slides[index].classList.add('hidden');
        index = (index + direction + slides.length) % slides.length;
        slides[index].classList.remove('hidden');
        
        modal.dataset.currentIndex = index;
        updateGalleryCounter(index, slides.length);
    };

    function updateGalleryCounter(current, total) {
        document.getElementById('gallery-counter').textContent = `${current + 1} / ${total}`;
    }

    // Close on background click
    document.getElementById('gallery-modal').addEventListener('click', function(e) {
        if (e.target === this) closeGallery();
    });

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (document.getElementById('gallery-modal').classList.contains('hidden')) return;
        if (e.key === 'ArrowRight') nextSlide(1);
        if (e.key === 'ArrowLeft') nextSlide(-1);
        if (e.key === 'Escape') closeGallery();
    });
});
</script>

<!-- Gallery Modal Structure -->
<div id="gallery-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/90 backdrop-blur-sm transition-opacity" data-current-index="0">
    <div class="absolute top-5 right-5 z-[110]">
        <button onclick="closeGallery()" class="text-white hover:text-primary transition-colors bg-white/10 rounded-full p-2">
            <span class="material-symbols-outlined text-3xl">close</span>
        </button>
    </div>
    
    <div id="gallery-counter" class="absolute top-5 left-5 text-white bg-black/50 px-3 py-1 rounded-full text-sm font-bold z-[110]"></div>

    <button id="gallery-prev" onclick="nextSlide(-1)" class="absolute left-4 top-1/2 -translate-y-1/2 text-white bg-white/10 hover:bg-white/20 rounded-full p-3 transition-all z-[110]">
        <span class="material-symbols-outlined text-4xl">chevron_left</span>
    </button>
    
    <div id="gallery-content" class="w-full h-full p-6 flex items-center justify-center pointer-events-none">
        <!-- Slides get injected here -->
    </div>

    <button id="gallery-next" onclick="nextSlide(1)" class="absolute right-4 top-1/2 -translate-y-1/2 text-white bg-white/10 hover:bg-white/20 rounded-full p-3 transition-all z-[110]">
        <span class="material-symbols-outlined text-4xl">chevron_right</span>
    </button>
</div>
