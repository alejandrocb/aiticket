<!-- clientes/index_modern.php -->
<div class="flex-1 overflow-y-auto no-scrollbar bg-background-light dark:bg-background-dark pb-24">
    <div class="sticky top-0 z-30 bg-background-light/95 dark:bg-background-dark/95 backdrop-blur-sm border-b border-gray-200 dark:border-gray-800">
        <div class="px-4 py-3">
            <div class="flex gap-3 overflow-x-auto no-scrollbar">
                <button class="flex h-9 shrink-0 items-center justify-center px-4 rounded-full bg-primary text-white shadow-sm shadow-primary/20 transition-transform active:scale-95">
                    <span class="text-sm font-medium">Todos</span>
                </button>
                <button class="flex h-9 shrink-0 items-center justify-center px-4 rounded-full bg-white dark:bg-surface-dark border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 transition-colors hover:bg-gray-50 dark:hover:bg-gray-800">
                    <span class="text-sm font-medium">Activos</span>
                </button>
            </div>
            
            <div class="mt-3 relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                <input type="text" placeholder="Buscar clientes..." class="w-full h-10 pl-10 pr-4 rounded-xl bg-gray-100 dark:bg-surface-dark border-none text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-primary/50 transition-all">
            </div>
        </div>
    </div>

    <div class="p-4 flex flex-col gap-3">
        <?php if (!empty($clientes)): ?>
            <?php foreach ($clientes as $cliente): ?>
                <div class="bg-white dark:bg-surface-dark p-4 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm hover:border-primary/50 dark:hover:border-primary/50 transition-colors group relative">
                    <div class="flex justify-between items-start mb-2">
                         <div class="flex items-center gap-3">
                            <div class="bg-center bg-no-repeat bg-cover rounded-full h-10 w-10 ring-2 ring-gray-100 dark:ring-gray-700" style='background-image: url("https://ui-avatars.com/api/?name=<?= urlencode($cliente['nombre']) ?>&background=random");'></div>
                            <div>
                                <h3 class="font-bold text-gray-900 dark:text-white leading-tight"><?= $cliente['nombre'] ?></h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"><?= $cliente['email'] ?></p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                             <a href="<?= base_url('clientes/editar/'.$cliente['id']) ?>" class="p-2 text-gray-400 hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </a>
                        </div>
                    </div>
                    
                    <div class="mt-3 grid grid-cols-2 gap-2">
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-gray-400 text-[18px]">call</span>
                             <span class="text-xs font-medium text-gray-600 dark:text-gray-300"><?= $cliente['telefono'] ?: 'N/A' ?></span>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-gray-400 text-[18px]">location_on</span>
                             <span class="text-xs font-medium text-gray-600 dark:text-gray-300 truncate"><?= $cliente['direccion'] ?: 'N/A' ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
             <div class="flex flex-col items-center justify-center py-12 text-center">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined text-3xl text-gray-400">group_off</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">No hay clientes</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 max-w-[250px] mt-1">No se encontraron clientes registrados en el sistema.</p>
            </div>
        <?php endif; ?>
    </div>
    
    <button onclick="location.href='<?= base_url('clientes/crear') ?>'" class="fixed bottom-6 right-6 h-14 w-14 bg-primary text-white rounded-full shadow-lg shadow-primary/30 flex items-center justify-center hover:bg-blue-600 hover:scale-105 transition-all z-40">
        <span class="material-symbols-outlined text-[28px]">add</span>
    </button>
</div>
