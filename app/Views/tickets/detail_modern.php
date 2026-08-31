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
                <div class="bg-primary/10 text-primary flex items-center justify-center rounded-full h-10 w-10 ring-2 ring-slate-100 dark:ring-slate-700 font-bold text-sm">
                    <?= strtoupper(substr($cliente_nombre, 0, 2)) ?>
                </div>
                <div>
                    <p class="text-slate-500 dark:text-slate-400 text-[10px] uppercase font-semibold tracking-wider mb-0.5"><?= etiqueta('cliente') ?></p>
                    <p class="text-slate-900 dark:text-white text-sm font-bold"><?= $cliente_nombre ?></p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div class="bg-surface-light dark:bg-surface-dark rounded-xl p-3 shadow-sm border border-slate-200 dark:border-slate-800/60">
                <p class="text-slate-500 dark:text-slate-400 text-[10px] uppercase font-semibold tracking-wider mb-2">Responsable</p>
                <div class="flex items-center gap-2">
                    <div class="h-6 w-6 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-[10px] ring-1 ring-slate-200 dark:ring-slate-700 relative overflow-hidden">
                         <!-- Initials Fallback -->
                         <span><?= $responsable_nombre ? strtoupper(substr($responsable_nombre, 0, 2)) : 'NA' ?></span>
                         
                         <!-- Image Overlay -->
                         <?php if ($responsable_imagen): ?>
                            <div class="absolute inset-0 bg-center bg-no-repeat bg-cover" style='background-image: url("<?= base_url($responsable_imagen) ?>");'></div>
                         <?php endif; ?>
                    </div>
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

            <?php if (!empty($ticket['media'])): ?>
                <?php $media = json_decode($ticket['media'], true); ?>
                <?php if ($media): ?>
                    <div class="flex flex-wrap gap-2 mt-4">
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
                                    <img src="<?= $thumbPath ?>" alt="Adjunto" class="h-20 w-20 object-cover rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm transition-transform group-hover:scale-105">
                                    <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 rounded-lg flex items-center justify-center transition-opacity text-white">
                                        <span class="material-symbols-outlined text-[18px]">zoom_in</span>
                                    </div>
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('upload/tickets/' . $item['filename']) ?>" target="_blank" class="flex flex-col items-center justify-center h-20 w-20 bg-slate-100 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-500 hover:text-primary transition-colors">
                                    <span class="material-symbols-outlined text-[30px]">videocam</span>
                                    <span class="text-[10px] uppercase font-bold">Video</span>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Movimientos Preview (Last few) -->
    <div class="px-5 py-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-slate-900 dark:text-white text-base font-bold">Movimientos Recientes</h3>
            <a href="<?= base_url('tickets/history/'.$ticket['id']) ?>" class="text-primary text-xs font-semibold">Ver Historial Completo</a>
            <a href="<?= base_url('tickets/informe/'.$ticket['id']) ?>" class="text-primary text-xs font-semibold inline-flex items-center gap-1 ml-3">
                <span class="material-symbols-outlined text-[14px]">print</span>
                Informe para imprimir
            </a>
        </div>
        
        <div class="relative pl-2">
            <div class="absolute left-6 top-2 bottom-0 w-0.5 bg-slate-200 dark:bg-slate-800"></div>
            
            <?php if (!empty($movimientos)): ?>
                <?php foreach (array_slice($movimientos, 0, 3) as $movimiento): ?>
                    <div class="relative flex gap-4 pb-8 group">
                         <div class="z-10 flex-shrink-0 size-8 mt-1 rounded-full bg-primary/10 text-primary flex items-center justify-center ring-4 ring-background-light dark:ring-background-dark font-bold text-xs border border-primary/20 relative overflow-hidden">
                            <!-- Initials Fallback -->
                            <span><?= strtoupper(substr($movimiento['usuario_nombre'], 0, 2)) ?></span>
                            
                            <!-- Image Overlay -->
                            <?php if ($movimiento['usuario_imagen']): ?>
                                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('<?= base_url($movimiento['usuario_imagen']) ?>')"></div>
                            <?php endif; ?>
                         </div>
                        <div class="flex-1">
                            <div class="flex items-baseline justify-between mb-1">
                                <p class="text-slate-900 dark:text-white text-sm font-semibold"><?= $movimiento['usuario_nombre'] ?></p>
                                <span class="text-slate-400 text-xs"><?= $movimiento['fecha_movimiento'] ?></span>
                            </div>
                            <div class="bg-surface-light dark:bg-surface-dark p-3 rounded-lg rounded-tl-none border border-slate-200 dark:border-slate-800/60 shadow-sm">
                                <p class="text-slate-600 dark:text-slate-300 text-sm"><?= $movimiento['descripcion'] ?></p>
                                
                                <?php if (!empty($movimiento['media'])): ?>
                                    <?php $media = json_decode($movimiento['media'], true); ?>
                                    <?php if ($media): ?>
                                        <div class="flex flex-wrap gap-2 mt-3 pt-3 border-t border-slate-100 dark:border-slate-800">
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
                                                        <img src="<?= $thumbPath ?>" alt="Adjunto" class="h-16 w-16 object-cover rounded border border-slate-200 dark:border-slate-700 shadow-xs transition-transform group-hover:scale-105">
                                                        <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 rounded flex items-center justify-center transition-opacity text-white">
                                                            <span class="material-symbols-outlined text-[16px]">visibility</span>
                                                        </div>
                                                    </a>
                                                <?php else: ?>
                                                    <a href="<?= base_url('upload/tickets/' . $item['filename']) ?>" target="_blank" class="flex flex-col items-center justify-center h-16 w-16 bg-slate-50 dark:bg-slate-900 rounded border border-slate-200 dark:border-slate-700 text-slate-400 hover:text-primary transition-colors">
                                                        <span class="material-symbols-outlined text-[24px]">movie</span>
                                                        <span class="text-[8px] uppercase font-bold tracking-tighter">Video</span>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
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
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Selector de Estado -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Estado</label>
                    <div class="relative">
                        <select name="estado_ticket_id" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-[#1c2127] text-slate-900 dark:text-white p-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none appearance-none cursor-pointer">
                            <?php foreach ($estados as $est): ?>
                                <option value="<?= $est['id'] ?>" <?= $est['id'] == $ticket['estado_ticket_id'] ? 'selected' : '' ?>>
                                    <?= $est['nombre'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-500">
                             <span class="material-symbols-outlined text-[20px]">expand_more</span>
                        </div>
                    </div>
                </div>

                <!-- Selector de Responsable (Solo visible para internos si se desea, o para todos) -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Responsable</label>
                    <div class="relative">
                         <select name="responsable_id" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-[#1c2127] text-slate-900 dark:text-white p-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none appearance-none cursor-pointer">
                            <option value="">Sin Asignar</option>
                            <?php foreach ($responsables as $resp): ?>
                                <option value="<?= $resp['id'] ?>" <?= $resp['id'] == $ticket['responsable_id'] ? 'selected' : '' ?>>
                                    <?= $resp['nombre'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                         <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-500">
                             <span class="material-symbols-outlined text-[20px]">expand_more</span>
                        </div>
                    </div>
                </div>
            </div>

            <textarea name="descripcion" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-[#1c2127] text-slate-900 dark:text-white min-h-[100px] p-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" placeholder="Escribe tu respuesta o comentario aquí... (Opcional si cambias estado/responsable)"></textarea>
            
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
                    <!-- Contenedor de Previsualización -->
                    <div id="detail-file-preview" class="flex gap-2 flex-wrap mt-2"></div>
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

<script>
let detailSelectedFiles = [];

document.getElementById('detail-dropzone-file').addEventListener('change', function(e) {
    const files = Array.from(this.files);
    
    files.forEach(file => {
        if (!detailSelectedFiles.some(f => f.name === file.name && f.size === file.size)) {
            detailSelectedFiles.push(file);
        }
    });

    renderDetailPreviews();
    updateDetailInput();
});

function renderDetailPreviews() {
    const preview = document.getElementById('detail-file-preview');
    preview.innerHTML = '';
    
    detailSelectedFiles.forEach((file, index) => {
        const container = document.createElement('div');
        container.className = 'relative group w-20 h-20 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 shadow-sm';
        
        if (file.type.startsWith('image/')) {
            const img = document.createElement('img');
            img.className = 'w-full h-full object-cover';
            const reader = new FileReader();
            reader.onload = e => img.src = e.target.result;
            reader.readAsDataURL(file);
            container.appendChild(img);
        } else {
            const iconWrap = document.createElement('div');
            iconWrap.className = 'w-full h-full flex flex-col items-center justify-center text-slate-400';
            iconWrap.innerHTML = `
                <span class="material-symbols-outlined text-2xl">description</span>
                <span class="text-[8px] uppercase font-bold mt-1">${file.name.split('.').pop()}</span>
            `;
            container.appendChild(iconWrap);
        }
        
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-md hover:bg-red-600';
        removeBtn.innerHTML = '<span class="material-symbols-outlined text-[14px]">close</span>';
        removeBtn.onclick = (e) => {
            e.preventDefault();
            detailSelectedFiles.splice(index, 1);
            renderDetailPreviews();
            updateDetailInput();
        };
        
        container.appendChild(removeBtn);
        preview.appendChild(container);
    });
}

function updateDetailInput() {
    const dt = new DataTransfer();
    detailSelectedFiles.forEach(file => dt.items.add(file));
    document.getElementById('detail-dropzone-file').files = dt.files;
}
</script>
