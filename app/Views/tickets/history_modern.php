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
                        
                        <?php if (!empty($movimiento['media'])): ?>
                            <?php $media = json_decode($movimiento['media'], true); ?>
                            <?php if ($media): ?>
                                <div class="flex flex-wrap gap-2 mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                                    <?php foreach ($media as $item): ?>
                                        <?php if ($item['type'] === 'image'): ?>
                                            <?php 
                                                $filePath = base_url('upload/tickets/' . $item['filename']);
                                                $dirName = dirname($item['filename']);
                                                $baseName = basename($item['filename']);
                                                $thumbPath = ($dirName === '.') 
                                                    ? base_url('upload/tickets/thumbnails/' . $baseName) 
                                                    : base_url('upload/tickets/' . $dirName . '/thumbnails/' . $baseName);
                                            ?>
                                            <a href="<?= $filePath ?>" target="_blank" class="block group relative">
                                                <img src="<?= $thumbPath ?>" alt="Adjunto" class="h-14 w-14 object-cover rounded border border-gray-200 dark:border-gray-700 shadow-sm transition-transform group-hover:scale-105">
                                                <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 rounded flex items-center justify-center transition-opacity text-white">
                                                    <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                                                </div>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= base_url('upload/tickets/' . $item['filename']) ?>" target="_blank" class="flex flex-col items-center justify-center h-14 w-14 bg-gray-50 dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700 text-gray-400 hover:text-primary transition-colors">
                                                <span class="material-symbols-outlined text-[20px]">movie</span>
                                                <span class="text-[8px] uppercase font-bold tracking-tighter">Video</span>
                                            </a>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <div class="flex items-center gap-2 mt-2">
                        <div class="w-5 h-5 rounded-full bg-primary/10 text-primary flex items-center justify-center text-[10px] font-bold border border-primary/20 relative overflow-hidden shadow-sm">
                            <!-- Initials Fallback -->
                            <span><?= strtoupper(substr($movimiento['usuario_nombre'], 0, 2)) ?></span>
                            
                            <!-- Image Overlay -->
                            <?php if ($movimiento['usuario_imagen']): ?>
                                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('<?= base_url($movimiento['usuario_imagen']) ?>')"></div>
                            <?php endif; ?>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Por <span class="text-gray-700 dark:text-gray-300 font-medium"><?= $movimiento['usuario_nombre'] ?></span></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>
