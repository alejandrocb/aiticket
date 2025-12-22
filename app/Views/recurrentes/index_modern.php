<!-- recurrentes/index_modern.php -->
<div class="flex-1 overflow-y-auto no-scrollbar bg-background-light dark:bg-background-dark pb-24">
    <div class="sticky top-0 z-30 bg-background-light/95 dark:bg-background-dark/95 backdrop-blur-sm border-b border-gray-200 dark:border-gray-800">
        <div class="px-4 py-3">
             <h2 class="text-lg font-bold text-gray-900 dark:text-white">Tickets Recurrentes</h2>
        </div>
    </div>

    <div class="p-4 flex flex-col gap-3">
        <?php if (!empty($tickets)): ?>
            <?php foreach ($tickets as $ticket): ?>
                <div class="bg-white dark:bg-surface-dark p-4 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm hover:border-primary/50 dark:hover:border-primary/50 transition-colors group relative">
                    <div class="flex justify-between items-start mb-2">
                         <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                                <span class="material-symbols-outlined">repeat</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 dark:text-white leading-tight line-clamp-1"><?= $ticket['descripcion'] ?></h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"><?= $ticket['cliente_nombre'] ?></p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                             <a href="<?= base_url('recurrentes/editar/'.$ticket['id']) ?>" class="p-2 text-gray-400 hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </a>
                        </div>
                    </div>
                    
                    <div class="mt-3 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                             <div class="px-2 py-1 rounded text-[10px] font-bold uppercase bg-<?= $ticket['frecuencia'] == 'mensual' ? 'purple' : 'teal' ?>-100 text-<?= $ticket['frecuencia'] == 'mensual' ? 'purple' : 'teal' ?>-700">
                                <?= ucfirst($ticket['frecuencia']) ?>
                             </div>
                             <?php if ($ticket['frecuencia'] == 'mensual'): ?>
                                <span class="text-xs text-gray-500">Día <?= $ticket['dia_mes'] ?></span>
                             <?php elseif ($ticket['frecuencia'] == 'semanal'): ?>
                                <span class="text-xs text-gray-500"><?= $ticket['dia_semana'] ?></span>
                             <?php endif; ?>
                        </div>
                         <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden" title="Responsable">
                                 <!-- Responsable Avatar if available, else initial -->
                                 <div class="w-full h-full flex items-center justify-center text-[10px] font-bold"><?= substr($ticket['responsable_nombre'], 0, 1) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
             <div class="flex flex-col items-center justify-center py-12 text-center">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined text-3xl text-gray-400">event_busy</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">No hay tickets recurrentes</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 max-w-[250px] mt-1">No se han configurado tickets automáticos.</p>
            </div>
        <?php endif; ?>
    </div>
    
    <button onclick="location.href='<?= base_url('recurrentes/crear') ?>'" class="fixed bottom-6 right-6 h-14 w-14 bg-primary text-white rounded-full shadow-lg shadow-primary/30 flex items-center justify-center hover:bg-blue-600 hover:scale-105 transition-all z-40">
        <span class="material-symbols-outlined text-[28px]">add</span>
    </button>
</div>
