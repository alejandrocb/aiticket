// sw.js - Service Worker for Aiticket
const CACHE_NAME = 'aiticket-v6';
const ASSETS_TO_CACHE = [
    './',
    'assets/images/icon-192.png',
    'assets/images/icon-512.png',
    'assets/images/badge-white.png',
    'favicon.ico'
];

// Install Event
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            return cache.addAll(ASSETS_TO_CACHE);
        })
    );
});

// Fetch Event (for offline support)
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request).then(response => {
            return response || fetch(event.request);
        })
    );
});

// Push Event
self.addEventListener('push', function (event) {
    if (!(self.Notification && self.Notification.permission === 'granted')) {
        return;
    }

    const data = event.data ? event.data.json() : {};
    const title = data.title || 'Aiticket';
    const options = {
        body: data.body || 'Tienes una nueva notificación.',
        icon: data.userPhoto || 'assets/images/icon-192.png',  // Foto del usuario si existe, si no, logo de la app
        badge: 'assets/images/badge-white.png',  // Logo monocromo (esquina y barra de estado móvil)
        image: data.userPhoto || null,  // Foto del usuario (grande, visible en panel de notificaciones)
        vibrate: [200, 100, 200],
        tag: data.tag || 'aiticket-notification',
        requireInteraction: false,
        data: {
            url: data.url || './'
        },
        actions: [
            {
                action: 'view',
                title: '👁️ Ver',
            },
            {
                action: 'close',
                title: 'Cerrar',
            }
        ]
    };

    event.waitUntil(
        self.registration.showNotification(title, options)
    );
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    if (event.action === 'view' || !event.action) {
        event.waitUntil(
            clients.openWindow(event.notification.data.url)
        );
    }
});

