// sw.js - Service Worker de Aiticket
//
// Su cometido aquí es recibir notificaciones push. No precarga nada: la
// versión anterior cacheaba tres iconos bajo assets/images/ que no existen en
// el repositorio, y cache.addAll() es todo o nada, así que el evento install
// fallaba y el service worker no llegaba a activarse nunca. Sin service worker
// activo no hay push posible.
const CACHE_NAME = 'aiticket-v7';

self.addEventListener('install', event => {
    // Sin espera: al no precargar nada, no hay motivo para retrasar la
    // activación tras una actualización.
    self.skipWaiting();
});

self.addEventListener('activate', event => {
    // Se limpian las cachés de versiones anteriores, que contenían la portada
    // y la servían indefinidamente.
    event.waitUntil(
        caches.keys()
            .then(nombres => Promise.all(
                nombres.filter(n => n !== CACHE_NAME).map(n => caches.delete(n))
            ))
            .then(() => self.clients.claim())
    );
});

// Red primero, con la caché solo como último recurso.
//
// La versión anterior respondía desde caché antes que desde la red y tenía la
// portada precargada, así que servía una copia congelada del panel. En un
// puesto de mando, ver un listado de incidencias desactualizado es peor que no
// verlo, de modo que aquí la red siempre manda.
self.addEventListener('fetch', event => {
    if (event.request.method !== 'GET') {
        return;
    }

    event.respondWith(
        fetch(event.request).catch(() => caches.match(event.request))
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
        // Foto de quien provoca el aviso; si no la hay, el icono de la
        // instalación. Ambas rutas llegan dentro del aviso porque este fichero
        // es estático y no puede leer la configuración.
        icon: data.userPhoto || data.icono || undefined,
        badge: data.badge || undefined,
        image: data.image || null,  // Imagen del ticket o movimiento (grande)
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

