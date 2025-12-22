<!-- tickets/index_modern.php -->
<!-- Search and Filters for Tickets -->
<div class="px-0 py-2 pb-3">
    <label class="flex flex-col min-w-40 h-11 w-full">
        <div class="flex w-full flex-1 items-stretch rounded-xl h-full shadow-sm">
            <div class="text-text-secondary flex border-none bg-surface-light dark:bg-surface-dark items-center justify-center pl-4 rounded-l-xl border-r-0">
                <span class="material-symbols-outlined text-[22px]">search</span>
            </div>
            <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-[#111418] dark:text-white focus:outline-0 focus:ring-0 border-none bg-surface-light dark:bg-surface-dark focus:border-none h-full placeholder:text-text-secondary px-4 rounded-l-none border-l-0 pl-3 text-base font-normal leading-normal" placeholder="Buscar por palabra clave o ID..." value=""/>
        </div>
    </label>
</div>
<div class="flex gap-3 px-0 py-3 overflow-x-auto no-scrollbar items-center mb-4">
    <button id="btn-filter-open" class="filter-toggle-btn flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full bg-primary pl-5 pr-5 shadow-lg shadow-primary/20 transition-transform active:scale-95 text-white" data-mode="open">
        <span class="material-symbols-outlined text-sm">lock_open</span>
        <p class="text-sm font-semibold leading-normal">Abiertos</p>
    </button>
    <button id="btn-filter-all" class="filter-toggle-btn flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full bg-surface-light dark:bg-surface-dark border border-[#e5e7eb] dark:border-transparent pl-4 pr-5 hover:bg-gray-100 dark:hover:bg-[#2c3b4a] transition-colors active:scale-95 text-[#111418] dark:text-white" data-mode="all">
        <span class="material-symbols-outlined text-sm">list</span>
        <p class="text-sm font-medium leading-normal">Todos</p>
    </button>
</div>

<div class="flex items-center justify-between mb-2">
    <p class="text-sm font-semibold text-text-secondary uppercase tracking-wider">Lista de Tickets</p>
    <button class="flex items-center text-primary text-sm font-medium gap-1">
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
                if (stripos($ticket['prioridad_nombre'], 'alta') !== false) {
                    $priorityColor = 'text-red-400 bg-red-400/10 ring-red-400/20';
                } elseif (stripos($ticket['prioridad_nombre'], 'media') !== false) {
                    $priorityColor = 'text-amber-400 bg-amber-400/10 ring-amber-400/20';
                } elseif (stripos($ticket['prioridad_nombre'], 'baja') !== false) {
                    $priorityColor = 'text-green-400 bg-green-400/10 ring-green-400/20';
                }
            ?>
            <a href="/tickets/detail/<?= $ticket['id'] ?>" 
               class="ticket-item flex flex-col gap-3 bg-surface-light dark:bg-surface-dark p-4 rounded-xl shadow-sm border border-[#e5e7eb] dark:border-transparent active:scale-[0.99] transition-transform cursor-pointer hover:shadow-md"
               data-client="<?= strtolower(esc($ticket['cliente_nombre'])) ?>"
               data-status="<?= strtolower(esc($ticket['estado_nombre'])) ?>"
               data-desc="<?= strtolower(esc($ticket['descripcion'])) ?>"
               data-id="<?= $ticket['id'] ?>"
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
                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset <?= $priorityColor ?>">
                        <?= esc($ticket['prioridad_nombre']) ?>
                    </span>
                </div>
                <div>
                    <p class="text-[#111418] dark:text-white text-base font-semibold leading-normal mb-1 ticket-desc"><?= esc(substr($ticket['descripcion'], 0, 50)) ?>...</p>
                    <p class="text-text-secondary text-sm font-normal leading-relaxed line-clamp-2"><?= esc($ticket['descripcion']) ?></p>
                </div>
                <div class="h-px w-full bg-[#e5e7eb] dark:bg-[#2c3b4a]"></div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[18px]">radio_button_checked</span>
                        <p class="text-text-secondary text-sm font-medium ticket-status"><?= esc($ticket['estado_nombre']) ?></p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-text-secondary font-medium">Agente:</span>
                        <div class="flex items-center gap-2 bg-background-light dark:bg-background-dark/50 pr-3 pl-1 py-1 rounded-full">
                            <div class="h-5 w-5 rounded-full bg-gray-400 flex items-center justify-center text-[10px] text-white font-bold">
                                <?= $ticket['responsable_nombre'] ? strtoupper(substr($ticket['responsable_nombre'], 0, 2)) : 'NA' ?>
                            </div>
                            <span class="text-xs font-medium text-[#111418] dark:text-white">
                                <?= $ticket['responsable_nombre'] ? esc($ticket['responsable_nombre']) : 'Sin Asignar' ?>
                            </span>
                        </div>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[placeholder="Buscar por palabra clave o ID..."]');
    const ticketItems = document.querySelectorAll('.ticket-item');
    const filterBtns = document.querySelectorAll('.filter-toggle-btn');
    
    // Status Logic
    const closedStatuses = ['cerrado', 'cerrada', 'finalizada', 'anulada'];
    let currentMode = 'open'; // 'open' or 'all'

    // Init
    applyFilters();

    // Search Event
    searchInput.addEventListener('input', applyFilters);

    // Button Events
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            currentMode = this.dataset.mode;
            
            // Visual Update Buttons
            filterBtns.forEach(b => {
                // Reset to unassigned style
                b.classList.remove('bg-primary', 'text-white', 'shadow-lg');
                b.classList.add('bg-surface-light', 'dark:bg-surface-dark', 'text-[#111418]', 'dark:text-white', 'border');
            });
            
            // Set active style
            this.classList.remove('bg-surface-light', 'dark:bg-surface-dark', 'text-[#111418]', 'dark:text-white', 'border');
            this.classList.add('bg-primary', 'text-white', 'shadow-lg');

            applyFilters();
        });
    });

    function applyFilters() {
        const searchTerm = searchInput.value.toLowerCase();

        ticketItems.forEach(item => {
            const client = item.dataset.client;
            const status = item.dataset.status;
            const desc = item.dataset.desc;
            const id = item.dataset.id;
            
            // 1. Check Search Term
            const matchesSearch = client.includes(searchTerm) || 
                                  desc.includes(searchTerm) || 
                                  status.includes(searchTerm) || 
                                  id.includes(searchTerm);

            // 2. Check Status Filter
            let matchesStatus = true;
            if (currentMode === 'open') {
                const isClosed = closedStatuses.some(s => status.includes(s));
                if (isClosed) matchesStatus = false;
            }

            // Final Visibility
            if (matchesSearch && matchesStatus) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }
});
</script>
