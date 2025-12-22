<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <a class="navbar-brand ps-3" href="<?php echo base_url(); ?>">
        <?= $title ?? 'Ticket Management' ?>
    </a>
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle"><i class="fas fa-bars"></i></button>
    
    <ul class="navbar-nav ms-auto me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-bell"></i>
                <span class="position-absolute top-10 start-90 translate-middle badge rounded-pill bg-danger" id="notificationBadge" style="display: none;">
                    0
                    <span class="visually-hidden">unread messages</span>
                </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown" style="width: 300px; max-height: 400px; overflow-y: auto;">
                <li><h6 class="dropdown-header">Notificaciones</h6></li>
                <li><hr class="dropdown-divider" /></li>
                <div id="notificationList">
                    <li><a class="dropdown-item" href="#">Cargando...</a></li>
                </div>
                <li><hr class="dropdown-divider" /></li>
                <li><a class="dropdown-item text-center" href="<?= base_url('/notifications') ?>">Ver todas</a></li>
            </ul>
        </li>
    </ul>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function loadNotifications() {
                fetch('<?= base_url('/notifications/list') ?>')
                    .then(response => response.json())
                    .then(data => {
                        const badge = document.getElementById('notificationBadge');
                        const list = document.getElementById('notificationList');
                        
                        // Update badge
                        if (data.unread_count > 0) {
                            badge.innerText = data.unread_count;
                            badge.style.display = 'inline-block';
                        } else {
                            badge.style.display = 'none';
                        }

                        // Update list
                        list.innerHTML = '';
                        if (data.notifications.length > 0) {
                            data.notifications.forEach(notif => {
                                const item = document.createElement('li');
                                item.innerHTML = `
                                    <a class="dropdown-item" href="<?= base_url('/notifications/markRead/') ?>/${notif.id}">
                                        <div class="d-flex w-100 justify-content-between">
                                            <strong class="mb-1 text-truncate" style="max-width: 150px;">${notif.title}</strong>
                                            <small class="text-muted" style="font-size: 0.7em;">${new Date(notif.created_at).toLocaleDateString()}</small>
                                        </div>
                                        <p class="mb-1 text-truncate" style="max-width: 250px; font-size: 0.85em;">${notif.message}</p>
                                    </a>
                                `;
                                list.appendChild(item);
                            });
                        } else {
                            list.innerHTML = '<li><span class="dropdown-item text-muted">Sin notificaciones recientes</span></li>';
                        }
                    })
                    .catch(error => console.error('Error loading notifications:', error));
            }

            // Load on start
            loadNotifications();
            
            // Optional: Refresh every 60 seconds
            setInterval(loadNotifications, 60000);
        });
    </script></nav>

