<!-- tickets/detail.php -->
<h2 class="mt-4">Detalle del Ticket</h2>
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <p class="mb-0">
                <strong>Cliente:</strong> <?= $cliente_nombre ?>
            </p>
            <a href="/tickets/editar/<?= $ticket['id']; ?>" class="text-decoration-none">
                <i class="fas fa-pencil-alt ml-2"></i>
            </a>
        </div>
        <form action="/tickets/actualizar/<?= $ticket['id']; ?>" method="post">
            <input type="hidden" name="ticket_id" value="<?= $ticket['id']; ?>">
            <div class="form-group">
                <label for="estado_ticket_id"><strong>Estado:</strong></label>
                <select name="estado_ticket_id" id="estado_ticket_id" class="form-select">
                    <?php foreach ($estados as $estado): ?>
                        <option value="<?= $estado['id']; ?>" <?= $estado['id'] == $ticket['estado_ticket_id'] ? 'selected' : ''; ?>><?= $estado['nombre']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="responsable_id"><strong>Responsable:</strong></label>
                <div class="custom-select-wrapper d-flex align-items-center">
                    <?php if (!empty($responsables)): ?>
                        <img src="<?= base_url('upload/' . ($responsables[0]['imagen'] ?? 'default.jpg')); ?>" id="responsable_img" class="img-thumbnail" style="width: 50px; height: 50px; margin-right: 10px;">
                    <?php endif; ?>
                    <select name="responsable_id" id="responsable_id" class="form-select">
                        <option value="">Seleccione un responsable</option>
                        <?php foreach ($responsables as $responsable): ?>
                            <option value="<?= $responsable['id']; ?>" 
                                    data-img-src="<?= base_url('upload/' . ($responsable['imagen'] ?? 'default.jpg')); ?>" 
                                    <?= $responsable['id'] == $ticket['responsable_id'] ? 'selected' : ''; ?>>
                                <?= $responsable['nombre']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group mb-3">
                <label for="fecha_inicio_publicacion"><strong>Fecha de Inicio de Publicación:</strong></label>
                <input type="datetime-local" name="fecha_inicio_publicacion" id="fecha_inicio_publicacion" 
                       class="form-control" 
                       value="<?= isset($ticket['fecha_inicio_publicacion']) ? date('Y-m-d\TH:i', strtotime($ticket['fecha_inicio_publicacion'])) : ''; ?>">
            </div>
            <button type="submit" class="btn btn-primary mt-3">Actualizar Ticket</button>
        </form>
        <p><strong>Tipo:</strong> <?= $tipo_ticket_nombre ?></p>
        <p><strong>Prioridad:</strong> <?= $prioridad_ticket_nombre ?></p>
        <p><strong>Descripción:</strong> <?= $ticket['descripcion'] ?></p>
    </div>
</div>

<?php
$mediaFiles = json_decode($ticket['media'], true);
if ($mediaFiles && is_array($mediaFiles) && !empty($mediaFiles)):
?>
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Archivos multimedia</h5>
        <div class="d-flex flex-wrap" id="media-gallery">
            <?php
            $mediaFiles = json_decode($ticket['media'], true);
            if ($mediaFiles && is_array($mediaFiles)) {
                foreach ($mediaFiles as $index => $file) {
                    $filePath = base_url('upload/tickets/' . $file['filename']);
                    if ($file['type'] === 'image') {
                        $thumbnailPath = base_url('upload/tickets/thumbnails/' . $file['filename']);
                        echo "<div class='m-2'>";
                        echo "<img src='{$thumbnailPath}' class='img-thumbnail' style='width: 100px; height: 100px; object-fit: cover; cursor: pointer;' onclick='openMediaViewer(\"{$filePath}\", \"image\")'>";
                        echo "</div>";
                    } elseif ($file['type'] === 'video') {
                        echo "<div class='m-2'>";
                        echo "<video src='{$filePath}' class='img-thumbnail' style='width: 100px; height: 100px; object-fit: cover; cursor: pointer;' onclick='openMediaViewer(\"{$filePath}\", \"video\")' preload='metadata'></video>";
                        echo "</div>";
                    }
                }
            }
            ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Modal para visualizar imágenes y videos -->
<div class="modal fade" id="mediaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body p-0">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-2" data-bs-dismiss="modal" aria-label="Close"></button>
                <img id="modalImage" src="" class="img-fluid d-none" alt="Imagen en tamaño completo">
                <video id="modalVideo" class="w-100 d-none" controls>
                    <source src="" type="video/mp4">
                    Tu navegador no soporta el tag de video.
                </video>
            </div>
        </div>
    </div>
</div>

<script>
    function openMediaViewer(src, type) {
        const modal = new bootstrap.Modal(document.getElementById('mediaModal'));
        const modalImage = document.getElementById('modalImage');
        const modalVideo = document.getElementById('modalVideo');
        
        if (type === 'image') {
            modalImage.src = src;
            modalImage.classList.remove('d-none');
            modalVideo.classList.add('d-none');
        } else if (type === 'video') {
            modalVideo.src = src;
            modalVideo.classList.remove('d-none');
            modalImage.classList.add('d-none');
        }
        
        modal.show();
    }
</script>

<h3 class="mt-4">Movimientos del Ticket</h3>
<div class="list-group" id="movementsList">
    <?php foreach ($movimientos as $movimiento): ?>
        <div class="list-group-item list-group-item-action">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">
                    <?php 
                        $types = explode(',', $movimiento['tipo_movimiento']);
                    foreach ($types as $type): 
                    ?>
                    <span class="badge bg-info me-1"><?= trim($type) ?></span>
                    <?php endforeach; ?>
                    
                </h5>
                <small><?= $movimiento['fecha_movimiento']; ?></small>
            </div>
            <p class="mb-1"><?= $movimiento['descripcion']; ?></p>
            <small>Usuario: <?= $movimiento['usuario_nombre']; ?></small>
            
            <?php if (!empty($movimiento['media'])): ?>
                <div class="media-previews d-flex flex-wrap mt-2">
                    <?php 
                    $mediaFiles = json_decode($movimiento['media'], true);
                    foreach ($mediaFiles as $media):
                        $filePath = base_url('upload/mv/' . $media['filename']);
                        if ($media['type'] === 'image'):
                            $thumbnailPath = base_url('upload/mv/thumbnails/' . $media['filename']);
                    ?>
                        <div class="media-item m-1">
                            <img src="<?= $thumbnailPath ?>" alt="Imagen del movimiento" style="width: 100px; height: 100px; object-fit: cover; cursor: pointer;" onclick="openMediaViewer('<?= $filePath ?>', 'image')">
                        </div>
                    <?php else: ?>
                        <div class="media-item m-1">
                            <video style="width: 100px; height: 100px; object-fit: cover; cursor: pointer;" preload="metadata" onclick="openMediaViewer('<?= $filePath ?>', 'video')">
                                <source src="<?= $filePath ?>" type="video/mp4">
                            </video>
                        </div>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<!-- Modal para visualizar imágenes y videos -->
<div class="modal fade" id="mediaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body p-0">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-2" data-bs-dismiss="modal" aria-label="Close"></button>
                <img id="modalImage" src="" class="img-fluid d-none" alt="Imagen en tamaño completo">
                <video id="modalVideo" class="w-100 d-none" controls>
                    <source src="" type="video/mp4">
                    Tu navegador no soporta el tag de video.
                </video>
            </div>
        </div>
    </div>
</div>

<script>
function openMediaViewer(src, type) {
    const modal = new bootstrap.Modal(document.getElementById('mediaModal'));
    const modalImage = document.getElementById('modalImage');
    const modalVideo = document.getElementById('modalVideo');
    
    if (type === 'image') {
        modalImage.src = src;
        modalImage.classList.remove('d-none');
        modalVideo.classList.add('d-none');
    } else if (type === 'video') {
        modalVideo.src = src;
        modalVideo.classList.remove('d-none');
        modalImage.classList.add('d-none');
    }
    
    modal.show();
}
</script>


<div class="card mt-4">
  <div class="card-header bg-light">
    <h5 class="mb-0">Añadir Nuevo Movimiento</h5>
  </div>
  <div class="card-body">
    <form action="/ticketmovimientos/insertar" method="post" enctype="multipart/form-data" id="movimientoForm">
      <input type="hidden" name="ticket_id" value="<?= $ticket['id']; ?>">
      
      <div class="mb-3">
        <label for="tipo_movimiento" class="form-label">Tipo de Movimiento:</label>
        <input type="text" name="tipo_movimiento" id="tipo_movimiento" class="form-control" required>
      </div>
      
      <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción:</label>
        <textarea name="descripcion" id="descripcion" class="form-control" rows="3" required></textarea>
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
      
      <div class="text-end mt-4">
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-plus-circle"></i> Añadir Movimiento
        </button>
      </div>
    </form>
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
    
    fileCount++;
    document.getElementById('file-count').textContent = fileCount;
    
    // Añadir el archivo al formulario
    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    const input = document.createElement('input');
    input.type = 'file';
    input.name = 'media[]';
    input.style.display = 'none';
    input.files = dataTransfer.files;
    preview.appendChild(input);
}

function removeMedia(button) {
    button.closest('.media-preview').remove();
    fileCount--;
    document.getElementById('file-count').textContent = fileCount;
}

// Manejar el pegado de imágenes
document.addEventListener('paste', function(event) {
    const items = event.clipboardData.items;
    for (let item of items) {
        if (item.type.indexOf('image') !== -1) {
            const blob = item.getAsFile();
            addFilePreview(blob);
            event.preventDefault();
            break;
        }
    }
});

// Manejar el arrastrar y soltar
const dropZone = document.getElementById('movimientoForm');
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