<!-- tickets/form_modern.php -->
<div class="flex-1 flex flex-col gap-5 px-4 pt-5 pb-32">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-[#111418] dark:text-white tracking-tight text-[22px] font-bold leading-tight">Detalles del Ticket</h2>
            <p class="text-[#9dabb9] text-sm mt-1">Complete la información para registrar una nueva solicitud.</p>
        </div>
        <?php if(isset($ticket)): ?>
        <a href="<?= base_url('tickets/detail/' . $ticket['id']) ?>" class="flex items-center gap-2 bg-surface-light dark:bg-surface-dark text-primary dark:text-white px-4 py-2 rounded-lg border border-[#e5e7eb] dark:border-[#2c3b4a] hover:bg-gray-100 dark:hover:bg-[#2c3b4a] transition-colors text-sm font-medium">
            <span class="material-symbols-outlined text-[18px]">visibility</span>
            Ver Detalle / Añadir Movimiento
        </a>
        <?php endif; ?>
    </div>
    
    <form action="<?= isset($ticket) ? base_url('tickets/actualizar/' . $ticket['id']) : base_url('tickets/insertar') ?>" method="POST" enctype="multipart/form-data">
        <div class="flex flex-col gap-4">
            <div class="flex flex-col gap-2">
                <label class="text-[#111418] dark:text-white text-base font-medium leading-normal">Cliente</label>
                <div class="relative">
                    <select class="w-full appearance-none rounded-lg border border-gray-300 dark:border-[#3b4754] bg-white dark:bg-[#1c2127] text-[#111418] dark:text-white h-14 px-4 pr-10 text-base font-normal focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" name="cliente_id" required>
                        <option disabled="" selected="" value="">Seleccionar Cliente</option>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?= $cliente['id'] ?>" <?= (isset($ticket) && $ticket['cliente_id'] == $cliente['id']) ? 'selected' : '' ?>><?= $cliente['nombre'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[#9dabb9]">
                        <span class="material-symbols-outlined">keyboard_arrow_down</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-[#111418] dark:text-white text-base font-medium leading-normal">Responsable</label>
                <div class="relative">
                    <select class="w-full appearance-none rounded-lg border border-gray-300 dark:border-[#3b4754] bg-white dark:bg-[#1c2127] text-[#111418] dark:text-white h-14 px-4 pr-10 text-base font-normal focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" name="responsable_id">
                        <option value="">Asignar a...</option>
                        <?php foreach ($usuarios as $usuario): ?>
                             <?php if (in_array($usuario['tipo_usuario_id'], [1, 2])): // Admin or Support ?>
                                <option value="<?= $usuario['id'] ?>" <?= (isset($ticket) && $ticket['responsable_id'] == $usuario['id']) ? 'selected' : '' ?>><?= $usuario['nombre'] ?></option>
                             <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[#9dabb9]">
                        <span class="material-symbols-outlined">person</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-[#111418] dark:text-white text-base font-medium leading-normal">Tipo de Ticket</label>
                <div class="relative">
                    <select class="w-full appearance-none rounded-lg border border-gray-300 dark:border-[#3b4754] bg-white dark:bg-[#1c2127] text-[#111418] dark:text-white h-14 px-4 pr-10 text-base font-normal focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" name="tipo_ticket_id" required>
                        <option disabled="" selected="" value="">Seleccione tipo</option>
                         <?php foreach ($tipos as $tipo): ?>
                            <option value="<?= $tipo['id'] ?>" <?= (isset($ticket) ? ($ticket['tipo_ticket_id'] == $tipo['id']) : (1 == $tipo['id'])) ? 'selected' : '' ?>><?= $tipo['nombre'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[#9dabb9]">
                        <span class="material-symbols-outlined">keyboard_arrow_down</span>
                    </div>
                </div>
            </div>
            
             <!-- Escenario is usually tied to Cliente now, but if needed explicitly: -->
             <!-- For now, we assume Escenario is derived from something else or we can list them if passed -->

            <div class="flex flex-col gap-2">
                <label class="text-[#111418] dark:text-white text-base font-medium leading-normal">Prioridad</label>
                <div class="relative">
                    <select class="w-full appearance-none rounded-lg border border-gray-300 dark:border-[#3b4754] bg-white dark:bg-[#1c2127] text-[#111418] dark:text-white h-14 px-4 pr-10 text-base font-normal focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" name="prioridad_ticket_id" required>
                        <?php foreach ($prioridades as $prioridad): ?>
                            <option value="<?= $prioridad['id'] ?>" <?= (isset($ticket) ? ($ticket['prioridad_ticket_id'] == $prioridad['id']) : (2 == $prioridad['id'])) ? 'selected' : '' ?>><?= $prioridad['nombre'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[#9dabb9]">
                        <span class="material-symbols-outlined">keyboard_arrow_down</span>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col gap-2">
                <label class="text-[#111418] dark:text-white text-base font-medium leading-normal">Estado</label>
                 <div class="relative">
                    <select class="w-full appearance-none rounded-lg border border-gray-300 dark:border-[#3b4754] bg-white dark:bg-[#1c2127] text-[#111418] dark:text-white h-14 px-4 pr-10 text-base font-normal focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" name="estado_ticket_id" required>
                        <?php foreach ($estados as $estado): ?>
                            <option value="<?= $estado['id'] ?>" <?= (isset($ticket) && $ticket['estado_ticket_id'] == $estado['id']) ? 'selected' : '' ?>><?= $estado['nombre'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[#9dabb9]">
                        <span class="material-symbols-outlined">keyboard_arrow_down</span>
                    </div>
                </div>
            </div>

            <hr class="border-gray-200 dark:border-[#222a33] my-2"/>

            <div class="flex flex-col gap-2">
                <label class="text-[#111418] dark:text-white text-base font-medium leading-normal">Descripción</label>
                <textarea class="w-full rounded-lg border border-gray-300 dark:border-[#3b4754] bg-white dark:bg-[#1c2127] text-[#111418] dark:text-white min-h-[140px] p-4 text-base font-normal placeholder:text-[#9dabb9] focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors resize-none" name="descripcion" placeholder="Por favor describa el problema en detalle, incluyendo pasos para reproducirlo..."><?= isset($ticket) ? $ticket['descripcion'] : '' ?></textarea>
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-[#111418] dark:text-white text-base font-medium leading-normal">Adjuntos</label>
                <div class="flex items-center justify-center w-full">
                    <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600 transition-colors">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <span class="material-symbols-outlined text-gray-500 dark:text-gray-400 text-3xl mb-2">cloud_upload</span>
                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Haga clic para subir</span> o arrastre y suelte</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Imágenes o Videos</p>
                        </div>
                        <input id="dropzone-file" name="media[]" type="file" class="hidden" multiple />
                    </label>
                </div>
                <!-- Preview Container (Optional, basic JS would be needed for preview) -->
                <div id="file-preview" class="flex gap-2 flex-wrap mt-2"></div>
            </div>
        </div>

        <div class="fixed bottom-0 left-0 right-0 p-4 bg-background-light dark:bg-background-dark border-t border-gray-200 dark:border-[#222a33] z-40 md:static md:bg-transparent md:border-none md:p-0 md:mt-4">
            <button id="btn-submit-ticket" type="submit" class="w-full h-12 bg-primary hover:bg-blue-600 text-white font-bold rounded-lg shadow-lg flex items-center justify-center gap-2 transition-colors">
                <span class="material-symbols-outlined text-[20px]">add_circle</span>
                <?= isset($ticket) ? 'Actualizar Ticket' : 'Crear Ticket' ?>
            </button>
        </div>
    </form>
</div>

<script>
// Evitar envíos duplicados del formulario
document.querySelector('form').addEventListener('submit', function() {
    const btn = document.getElementById('btn-submit-ticket');
    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined text-[20px] animate-spin">progress_activity</span> <?= isset($ticket) ? "Actualizando..." : "Creando..." ?>';
});

let selectedFiles = [];

document.getElementById('dropzone-file').addEventListener('change', function(e) {
    const preview = document.getElementById('file-preview');
    const files = Array.from(this.files);
    
    files.forEach(file => {
        // Evitar duplicados si el usuario selecciona el mismo archivo varias veces
        if (!selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
            selectedFiles.push(file);
        }
    });

    renderPreviews();
    updateInput();
});

function renderPreviews() {
    const preview = document.getElementById('file-preview');
    preview.innerHTML = '';
    
    selectedFiles.forEach((file, index) => {
        const container = document.createElement('div');
        container.className = 'relative group w-20 h-20 rounded-lg overflow-hidden border border-gray-200 dark:border-[#3b4754] bg-gray-100 dark:bg-[#1c2127] shadow-sm';
        
        if (file.type.startsWith('image/')) {
            const img = document.createElement('img');
            img.className = 'w-full h-full object-cover';
            const reader = new FileReader();
            reader.onload = e => img.src = e.target.result;
            reader.readAsDataURL(file);
            container.appendChild(img);
        } else {
            const iconWrap = document.createElement('div');
            iconWrap.className = 'w-full h-full flex flex-col items-center justify-center text-gray-400';
            iconWrap.innerHTML = `
                <span class="material-symbols-outlined text-2xl">description</span>
                <span class="text-[8px] uppercase font-bold mt-1">${file.name.split('.').pop()}</span>
            `;
            container.appendChild(iconWrap);
        }
        
        // Botón Eliminar
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-md hover:bg-red-600';
        removeBtn.innerHTML = '<span class="material-symbols-outlined text-[14px]">close</span>';
        removeBtn.onclick = (e) => {
            e.preventDefault();
            selectedFiles.splice(index, 1);
            renderPreviews();
            updateInput();
        };
        
        container.appendChild(removeBtn);
        preview.appendChild(container);
    });
}

function updateInput() {
    const dt = new DataTransfer();
    selectedFiles.forEach(file => dt.items.add(file));
    document.getElementById('dropzone-file').files = dt.files;
}
</script>
