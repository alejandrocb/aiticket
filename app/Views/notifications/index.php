<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Mis Notificaciones</h1>
            <?php if (!empty($notifications)): ?>
                <a href="<?= base_url('/notifications/markAllRead') ?>" class="btn btn-outline-secondary btn-sm">Marcar todas como leídas</a>
            <?php endif; ?>
        </div>

        <?php if (empty($notifications)): ?>
            <div class="alert alert-info">No tienes notificaciones.</div>
        <?php else: ?>
            <div class="list-group">
                <?php foreach ($notifications as $notification): ?>
                    <?php 
                        $readClass = $notification['is_read'] ? 'list-group-item-light' : 'list-group-item-action fw-bold';
                        $icon = $notification['is_read'] ? 'fa-envelope-open' : 'fa-envelope';
                    ?>
                    <a href="<?= base_url('/notifications/markRead/' . $notification['id']) ?>" class="list-group-item list-group-item-action <?= $readClass ?>" aria-current="true">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">
                                <i class="fas <?= $icon ?> me-2"></i><?= esc($notification['title']) ?>
                            </h5>
                            <small><?= date('d/m/Y H:i', strtotime($notification['created_at'])) ?></small>
                        </div>
                        <p class="mb-1"><?= esc($notification['message']) ?></p>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
