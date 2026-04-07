// Service Worker básico para LavadoFácil PWA
// Estrategia: network-first con fallback a caché para el shell

const CACHE_NAME = 'lavadofacil-v1';
const SHELL = [
    '/manifest.webmanifest',
    '/images/lavadofacil_icon.png',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(SHELL))
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(keys.filter((k) => k !== CACHE_NAME).map((k) => caches.delete(k)))
        )
    );
    self.clients.claim();
});

self.addEventListener('push', (event) => {
    if (!event.data) return;
    let data;
    try { data = event.data.json(); } catch { data = { title: 'LavadoFácil', body: event.data.text() }; }

    event.waitUntil(
        self.registration.showNotification(data.title || 'LavadoFácil', {
            body: data.body || '',
            icon: data.icon || '/images/lavadofacil_icon.png',
            badge: data.icon || '/images/lavadofacil_icon.png',
            data: { url: data.url || '/app' },
        })
    );
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    const url = event.notification.data?.url || '/app';
    event.waitUntil(clients.openWindow(url));
});

self.addEventListener('fetch', (event) => {
    const { request } = event;

    // Solo GET y mismo origen
    if (request.method !== 'GET' || !request.url.startsWith(self.location.origin)) {
        return;
    }

    // Network-first para HTML/dinámico
    if (request.headers.get('accept')?.includes('text/html')) {
        event.respondWith(
            fetch(request).catch(() => caches.match(request))
        );
        return;
    }

    // Cache-first para assets estáticos
    event.respondWith(
        caches.match(request).then((cached) => cached || fetch(request).then((response) => {
            if (response.ok) {
                const clone = response.clone();
                caches.open(CACHE_NAME).then((cache) => cache.put(request, clone));
            }
            return response;
        }))
    );
});
