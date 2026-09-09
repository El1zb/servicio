// Service worker mínimo: solo recibe el push del servidor y lo muestra
// como notificación nativa del SO, aunque el sitio esté cerrado. No cachea
// nada (no es un service worker de "modo offline").

self.addEventListener('push', (event) => {
    if (!event.data) return;

    const payload = event.data.json();
    const url = payload.data?.url || '/';

    event.waitUntil(
        Promise.all([
            self.registration.showNotification(payload.title || 'Servicio Social ITSCO', {
                body: payload.body,
                icon: payload.icon || '/apple-touch-icon.png',
                badge: payload.badge,
                data: { url },
            }),
            // Avisa a cualquier pestaña abierta del sitio para que refresque
            // la campanita al vuelo — así la UI no necesita un wire:poll
            // corriendo todo el tiempo para cientos de sesiones a la vez.
            self.clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientsList) => {
                clientsList.forEach((client) => client.postMessage({ type: 'push-received' }));
            }),
        ])
    );
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    const url = event.notification.data?.url || '/';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((windowClients) => {
            for (const client of windowClients) {
                if (client.url.includes(url) && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(url);
            }
        })
    );
});
