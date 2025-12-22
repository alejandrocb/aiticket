<!-- tickets/detail_modern.php -->
<div class="flex-1 overflow-y-auto no-scrollbar pb-24 bg-background-light dark:bg-background-dark">
    <div class="p-5 flex flex-col gap-4">
        <!-- Header Section -->
        <div class="flex flex-col gap-2">
            <div class="flex items-center gap-2 mb-1">
                 <span class="text-sm font-bold text-text-secondary uppercase">Ticket #<?= $ticket['id'] ?></span>
            </div>
            <h1 class="text-slate-900 dark:text-white text-2xl font-bold leading-tight tracking-[-0.015em]"><?= $ticket['descripcion'] ?></h1> <!-- Assuming Description is title for now, or add a proper Title field -->
            <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400 text-sm">
                <span class="material-symbols-outlined text-[18px]">schedule</span>
                <span>Creado el <?= $ticket['fecha_creacion'] ?></span>
            </div>
        </div>

        <!-- Badges -->
        <div class="flex gap-2 flex-wrap">
            <div class="flex h-7 items-center justify-center gap-x-1.5 rounded-full bg-blue-100 dark:bg-blue-900/30 pl-2 pr-3 border border-blue-200 dark:border-blue-800">
                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-[16px]">radio_button_checked</span>
                <p class="text-blue-700 dark:text-blue-300 text-xs font-semibold"><?= $estado_nombre ?></p>
            </div>
            <div class="flex h-7 items-center justify-center gap-x-1.5 rounded-full bg-red-100 dark:bg-red-900/30 pl-2 pr-3 border border-red-200 dark:border-red-800">
                <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-[16px]">priority_high</span>
                <p class="text-red-700 dark:text-red-300 text-xs font-semibold"><?= $prioridad_ticket_nombre ?></p>
            </div>
            <div class="flex h-7 items-center justify-center gap-x-1.5 rounded-full bg-slate-200 dark:bg-slate-800 pl-2 pr-3 border border-slate-300 dark:border-slate-700">
                <span class="material-symbols-outlined text-slate-600 dark:text-slate-400 text-[16px]">dns</span>
                <p class="text-slate-700 dark:text-slate-300 text-xs font-semibold"><?= $tipo_ticket_nombre ?></p>
            </div>
        </div>
    </div>

    <!-- Info Cards -->
    <div class="px-5 mb-4 flex flex-col gap-3">
        <div class="bg-surface-light dark:bg-surface-dark rounded-xl p-3 shadow-sm border border-slate-200 dark:border-slate-800/60 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="bg-center bg-no-repeat bg-cover rounded-full h-10 w-10 ring-2 ring-slate-100 dark:ring-slate-700" style='background-image: url("https://ui-avatars.com/api/?name=<?= urlencode($cliente_nombre) ?>&background=random");'></div>
                <div>
                    <p class="text-slate-500 dark:text-slate-400 text-[10px] uppercase font-semibold tracking-wider mb-0.5">Cliente</p>
                    <p class="text-slate-900 dark:text-white text-sm font-bold"><?= $cliente_nombre ?></p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div class="bg-surface-light dark:bg-surface-dark rounded-xl p-3 shadow-sm border border-slate-200 dark:border-slate-800/60">
                <p class="text-slate-500 dark:text-slate-400 text-[10px] uppercase font-semibold tracking-wider mb-2">Responsable</p>
                <div class="flex items-center gap-2">
                    <div class="bg-center bg-no-repeat bg-cover rounded-full h-6 w-6 ring-1 ring-slate-200 dark:ring-slate-700" style='background-image: url("<?= $responsable_imagen ?: 'https://ui-avatars.com/api/?name='.urlencode($responsable_nombre) ?>");'></div>
                    <p class="text-slate-900 dark:text-white text-xs font-bold truncate"><?= $responsable_nombre ?: 'Sin Asignar' ?></p>
                </div>
            </div>
            <!-- Additional info can go here -->
        </div>
    </div>

    <!-- Description -->
    <div class="px-5 py-2">
        <h3 class="text-slate-900 dark:text-white text-base font-bold mb-3">Descripción</h3>
        <div class="bg-surface-light dark:bg-surface-dark rounded-xl p-4 shadow-sm border border-slate-200 dark:border-slate-800/60">
            <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed">
                <?= nl2br($ticket['descripcion']) ?>
            </p>
        </div>
    </div>

    <!-- Movimientos Preview (Last few) -->
    <div class="px-5 py-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-slate-900 dark:text-white text-base font-bold">Movimientos Recientes</h3>
            <a href="<?= base_url('tickets/history/'.$ticket['id']) ?>" class="text-primary text-xs font-semibold">Ver Historial Completo</a>
        </div>
        
        <div class="relative pl-2">
            <div class="absolute left-6 top-2 bottom-0 w-0.5 bg-slate-200 dark:bg-slate-800"></div>
            
            <?php if (!empty($movimientos)): ?>
                <?php foreach (array_slice($movimientos, 0, 3) as $movimiento): ?>
                    <div class="relative flex gap-4 pb-8 group">
                         <div class="z-10 flex-shrink-0 size-8 mt-1 rounded-full bg-primary flex items-center justify-center ring-4 ring-background-light dark:ring-background-dark">
                            <span class="material-symbols-outlined text-white text-[16px]">comment</span>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-baseline justify-between mb-1">
                                <p class="text-slate-900 dark:text-white text-sm font-semibold"><?= $movimiento['usuario_nombre'] ?></p>
                                <span class="text-slate-400 text-xs"><?= $movimiento['fecha_movimiento'] ?></span>
                            </div>
                            <div class="bg-surface-light dark:bg-surface-dark p-3 rounded-lg rounded-tl-none border border-slate-200 dark:border-slate-800/60 shadow-sm">
                                <p class="text-slate-600 dark:text-slate-300 text-sm"><?= $movimiento['descripcion'] ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-sm text-gray-500 pl-8">No hay movimientos registrados.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Add Message Form Section -->
    <div class="px-5 py-4 pb-8 border-t border-slate-200 dark:border-slate-800/60 mb-20" id="reply-form-container">
        <h3 class="text-slate-900 dark:text-white text-base font-bold mb-4">Añadir Nuevo Detalle</h3>
        <form action="<?= base_url('ticketmovimientos/insertar') ?>" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
            <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
            <input type="hidden" name="tipo_movimiento" value="Comentario">
            
            <textarea name="descripcion" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-[#1c2127] text-slate-900 dark:text-white min-h-[100px] p-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" placeholder="Escribe tu respuesta o comentario aquí..." required></textarea>
            
            <div class="flex flex-col gap-2">
                <label class="text-[#111418] dark:text-white text-base font-medium leading-normal">Adjuntos</label>
                <div class="flex items-center justify-center w-full">
                    <label for="detail-dropzone-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-300 border-dashed rounded-lg cursor-pointer bg-slate-50 dark:hover:bg-slate-800 dark:bg-slate-700 hover:bg-slate-100 dark:border-slate-600 dark:hover:border-slate-500 dark:hover:bg-slate-600 transition-colors">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <span class="material-symbols-outlined text-gray-500 dark:text-gray-400 text-3xl mb-2">cloud_upload</span>
                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Haga clic para subir</span> o arrastre y suelte</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Imágenes o Videos</p>
                        </div>
                        <input id="detail-dropzone-file" name="media[]" type="file" class="hidden" multiple />
                    </label>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-primary hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">send</span>
                    Enviar
                </button>
            </div>
        </form>
    </div>

    <div class="fixed bottom-0 left-0 right-0 md:left-64 bg-surface-light dark:bg-background-dark border-t border-slate-200 dark:border-slate-800/50 p-4 pb-6 flex gap-3 backdrop-blur-lg bg-opacity-90 dark:bg-opacity-90 z-30 justify-end">
        <a href="<?= base_url('tickets/edit/'.$ticket['id']) ?>" class="h-10 px-4 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-medium text-xs hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors flex items-center justify-center gap-1 border border-slate-200 dark:border-slate-700">
            <span class="material-symbols-outlined text-[16px]">edit</span>
            Editar
        </a>
    </div>
</div>
