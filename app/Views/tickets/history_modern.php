<!-- tickets/history_modern.php -->
<section class="px-4 py-6">
    <div class="flex items-start justify-between">
        <div class="flex flex-col gap-1">
            <span class="text-sm font-semibold text-primary uppercase tracking-wider">Ticket #<?= $ticket['id'] ?></span>
            <h2 class="text-2xl font-bold leading-tight text-gray-900 dark:text-white"><?= $ticket['descripcion'] ?></h2>
        </div>
    </div>
</section>

<main class="flex-1 pb-10">
    <div class="grid grid-cols-[48px_1fr] gap-x-0 px-4">
        <?php foreach ($movimientos as $movimiento): ?>
            <div class="flex flex-col items-center">
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-primary/10 dark:bg-primary/20 text-primary mt-1 ring-4 ring-background-light dark:ring-background-dark z-10">
                    <span class="material-symbols-outlined text-[18px]">history</span>
                </div>
                <div class="w-[2px] bg-gray-200 dark:bg-gray-800 h-full grow -mt-2"></div>
            </div>
            <div class="pb-8 pt-1 pl-2">
                <div class="flex flex-col gap-1 p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-white/5 transition-colors -ml-2 -mt-2">
                    <div class="flex justify-between items-start">
                        <p class="text-base font-medium text-gray-900 dark:text-white"><?= $movimiento['tipo_movimiento'] ?></p>
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-medium whitespace-nowrap ml-2"><?= $movimiento['fecha_movimiento'] ?></span>
                    </div>
                    
                    <div class="bg-gray-100 dark:bg-surface-dark border border-gray-200 dark:border-gray-700 rounded-md p-3 mt-2">
                        <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed"><?= $movimiento['descripcion'] ?></p>
                    </div>

                    <div class="flex items-center gap-2 mt-2">
                        <div class="w-5 h-5 rounded-full bg-indigo-500 flex items-center justify-center text-[10px] text-white font-bold">U</div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Por <span class="text-gray-700 dark:text-gray-300 font-medium"><?= $movimiento['usuario_nombre'] ?></span></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>
