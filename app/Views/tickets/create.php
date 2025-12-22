<div class="container-fluid px-4">
    
    <h2 class="mt-4">Crear Ticket</h2>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Inicio</a></li>
        <li class="breadcrumb-item active">Crear Ticket</li>
    </ol>

    <div class="card mb-4">
        <div class="card-body">
            <form action="/tickets/insertar" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="cliente_id" class="form-label">Cliente:</label>
                    <select name="cliente_id" id="cliente_id" class="form-select" required>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?= $cliente['id']; ?>"><?= $cliente['nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="responsable_id" class="form-label">Asignar a:</label>
                    <select class="form-select" name="responsable_id" id="responsable_id" required>
                        <option value="" selected disabled>Seleccione un usuario</option>
                        <?php foreach ($usuarios as $usuario): ?>
                            <option value="<?= $usuario['id']; ?>"><?= $usuario['nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="tipo_ticket_id" class="form-label">Tipo:</label>
                    <select name="tipo_ticket_id" id="tipo_ticket_id" class="form-select" required>
                        <?php foreach ($tipos as $tipo): ?>
                            <option value="<?= $tipo['id']; ?>"><?= $tipo['nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="prioridad_ticket_id" class="form-label">Prioridad:</label>
                    <input type="range" class="form-range" min="1" max="4" step="1" id="prioridad_ticket_id" name="prioridad_ticket_id" value="2" oninput="updatePriorityLabel(this.value)">
                    <div class="d-flex justify-content-between">
                        <span class="badge bg-success">Baja</span>
                        <span id="priorityLabel" class="badge bg-primary">Media</span>
                        <span class="badge bg-warning text-dark">Alta</span>
                        <span class="badge bg-danger">Urgente</span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="estado_ticket_id" class="form-label">Estado:</label>
                    <select name="estado_ticket_id" id="estado_ticket_id" class="form-select" required>
                        <?php foreach ($estados as $estado): ?>
                            <option value="<?= $estado['id']; ?>"><?= $estado['nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción:</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" rows="4" required></textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Archivos multimedia:</label>
                    <div id="media-container" class="d-flex align-items-center">
                        <button type="button" class="btn btn-outline-secondary me-2" id="add-media-btn" onclick="addMediaInput()">
                            <i class="fas fa-paperclip"></i> Adjuntar archivo
                        </button>
                        <small class="text-muted">Archivos adjuntos: <span id="file-count">0</span></small>
                    </div>
                    <div id="paste-instructions" class="text-muted mt-2">
                        Puedes pegar imágenes directamente en este formulario o arrastrar y soltar archivos aquí.
                    </div>
                    <div id="media-previews" class="mt-2 d-flex flex-wrap"></div>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Crear Ticket
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    let fileCount = 0;

    function addMediaInput() {
        const input = document.createElement('input');
        input.type = 'file';
        input.name = 'media[]';
        input.accept = 'image/*,video/*';
        input.style.display = 'none';
        input.multiple = true;
        input.onchange = function() { handleFileSelect(this.files); };
        
        document.getElementById('media-container').appendChild(input);
        input.click();
    }

    function handleFileSelect(files) {
        for (let file of files) {
            if (file.type.startsWith('image/') || file.type.startsWith('video/')) {
                addFilePreview(file);
            }
        }
    }

    function addFilePreview(file) {
        const preview = document.createElement('div');
        preview.className = 'media-preview m-2 position-relative';
        preview.style.width = '100px';
        preview.style.height = '100px';
        
        const removeBtn = document.createElement('button');
        removeBtn.className = 'btn btn-danger btn-sm position-absolute top-0 end-0';
        removeBtn.innerHTML = '<i class="fas fa-times"></i>';
        removeBtn.onclick = function() { removeMedia(this); };
        
        if (file.type.startsWith('image/')) {
            const img = document.createElement('img');
            img.className = 'img-thumbnail w-100 h-100';
            img.style.objectFit = 'cover';
            img.file = file;
            preview.appendChild(img);
            const reader = new FileReader();
            reader.onload = (function(aImg) { return function(e) { aImg.src = e.target.result; }; })(img);
            reader.readAsDataURL(file);
        } else if (file.type.startsWith('video/')) {
            const video = document.createElement('video');
            video.className = 'img-thumbnail w-100 h-100';
            video.style.objectFit = 'cover';
            video.preload = 'metadata';
            video.src = URL.createObjectURL(file);
            preview.appendChild(video);
        }
        
        preview.appendChild(removeBtn);
        document.getElementById('media-previews').appendChild(preview);
        
        // Crear un nuevo input file oculto y añadir el archivo
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'file';
        hiddenInput.name = 'media[]';
        hiddenInput.style.display = 'none';
        
        // Crear un nuevo FileList con el archivo
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        hiddenInput.files = dataTransfer.files;
        
        preview.appendChild(hiddenInput);
        
        fileCount++;
        document.getElementById('file-count').textContent = fileCount;
    }

    function removeMedia(button) {
        button.closest('.media-preview').remove();
        fileCount--;
        document.getElementById('file-count').textContent = fileCount;
    }

    // ... (resto del código sin cambios)

    // Manejar el arrastrar y soltar
    const dropZone = document.querySelector('form');
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.style.background = '#e9ecef';
    });

    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.style.background = '';
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.style.background = '';
        handleFileSelect(e.dataTransfer.files);
    });
</script>