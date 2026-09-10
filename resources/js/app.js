/**
 * Pinta las páginas del PDF ya cargado (container._pdfDoc) como <canvas>
 * dentro de `container`, al zoom actual (container.dataset.zoom). Separado
 * de renderReviewPdf para poder re-pintar al hacer zoom sin recargar el
 * archivo desde el servidor.
 */
async function paintReviewPdfPages(container) {
    const pdf = container._pdfDoc;
    const url = container.dataset.pdfUrl;
    if (!pdf) return;

    const zoom = parseFloat(container.dataset.zoom) || 1;
    container.innerHTML = '';

    for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
        if (container.dataset.pdfUrl !== url) return;

        const page = await page_(pdf, pageNum);
        const containerWidth = container.clientWidth - 48;
        const baseViewport = page.getViewport({ scale: 1 });
        const baseScale = Math.min(containerWidth / baseViewport.width, 1.5);
        const viewport = page.getViewport({ scale: baseScale * zoom });

        const canvas = document.createElement('canvas');
        canvas.width = viewport.width;
        canvas.height = viewport.height;
        canvas.style.display = 'block';
        canvas.style.margin = '0 auto 16px auto';
        canvas.style.boxShadow = '0 1px 4px rgba(0,0,0,0.15)';
        canvas.style.background = '#ffffff';

        await page.render({ canvasContext: canvas.getContext('2d'), viewport }).promise;

        if (container.dataset.pdfUrl !== url) return;
        container.appendChild(canvas);
    }
}

function page_(pdf, pageNum) {
    return pdf.getPage(pageNum);
}

/**
 * Renderiza un PDF como una serie de <canvas> (una por página) dentro de
 * `container`, en vez de usar el visor nativo del navegador — así el fondo
 * alrededor de la hoja es el gris de la app, no el negro por defecto de
 * Chrome cuando se oculta su barra de herramientas.
 */
window.renderReviewPdf = async function (url, container) {
    if (!container) return;

    container.dataset.pdfUrl = url;
    container.dataset.zoom = 1;
    container._pdfDoc = null;
    container.innerHTML = '';

    try {
        const [pdfjsLib, { default: pdfjsWorker }] = await Promise.all([
            import('pdfjs-dist'),
            import('pdfjs-dist/build/pdf.worker.min.mjs?url'),
        ]);
        pdfjsLib.GlobalWorkerOptions.workerSrc = pdfjsWorker;

        const pdf = await pdfjsLib.getDocument({ url, withCredentials: true }).promise;
        if (container.dataset.pdfUrl !== url) return;

        container._pdfDoc = pdf;
        await paintReviewPdfPages(container);
        updateZoomLabel(container);
    } catch (e) {
        if (container.dataset.pdfUrl !== url) return;
        container.innerHTML = '<p style="text-align:center;padding:40px;color:var(--color-secondary);font-size:13px;">No se pudo cargar la vista previa del documento.</p>';
        console.error('renderReviewPdf error', e);
    }
};

/**
 * +/- del control de zoom flotante sobre el visor.
 */
window.zoomReviewPdf = async function (container, delta) {
    if (!container || !container._pdfDoc) return;

    let zoom = parseFloat(container.dataset.zoom) || 1;
    zoom = Math.min(Math.max(zoom + delta, 0.5), 3);
    container.dataset.zoom = zoom;

    await paintReviewPdfPages(container);
    updateZoomLabel(container);
};

/**
 * Pellizco (dos dedos) para hacer zoom SOLO dentro del visor, en mobile —
 * nunca la página completa (así el topbar y el dock de navegación no se
 * mueven). Mientras los dedos se mueven solo previsualizamos con un
 * transform CSS (barato); al soltar, se repinta una vez a la resolución
 * final con paintReviewPdfPages (nítido, como el +/- de escritorio).
 * El propio contenedor ya trae `touch-action: pan-y` en CSS para que el
 * navegador no dispare su zoom nativo de página al detectar el pellizco.
 */
function touchDistance(touches) {
    const [a, b] = touches;
    return Math.hypot(a.clientX - b.clientX, a.clientY - b.clientY);
}

window.initPdfPinchZoom = function (container) {
    if (!container || container._pinchBound) return;
    container._pinchBound = true;

    let startDist = 0;
    let startZoom = 1;

    container.addEventListener('touchstart', (e) => {
        if (e.touches.length !== 2) return;
        startDist = touchDistance(e.touches);
        startZoom = parseFloat(container.dataset.zoom) || 1;

        const rect = container.getBoundingClientRect();
        const midX = (e.touches[0].clientX + e.touches[1].clientX) / 2 - rect.left;
        const midY = (e.touches[0].clientY + e.touches[1].clientY) / 2 - rect.top + container.scrollTop;
        container.style.transformOrigin = `${midX}px ${midY}px`;
    }, { passive: true });

    container.addEventListener('touchmove', (e) => {
        if (e.touches.length !== 2 || !startDist) return;
        e.preventDefault();

        const ratio = touchDistance(e.touches) / startDist;
        const targetZoom = Math.min(Math.max(startZoom * ratio, 0.5), 3);
        container.style.transform = `scale(${targetZoom / startZoom})`;
        updateZoomLabel(container, targetZoom);
    }, { passive: false });

    const commit = async () => {
        if (!startDist) return;
        startDist = 0;

        const preview = container.style.transform;
        container.style.transform = '';
        if (!preview) return;

        const match = preview.match(/scale\(([^)]+)\)/);
        const finalZoom = Math.min(Math.max(startZoom * parseFloat(match?.[1] ?? 1), 0.5), 3);
        container.dataset.zoom = finalZoom;

        await paintReviewPdfPages(container);
        updateZoomLabel(container);
    };

    container.addEventListener('touchend', commit);
    container.addEventListener('touchcancel', commit);
};

function updateZoomLabel(container, zoomOverride = null) {
    const label = document.getElementById('pdf-zoom-label');
    if (!label) return;
    const zoom = zoomOverride ?? (parseFloat(container.dataset.zoom) || 1);
    label.textContent = Math.round(zoom * 100) + '%';
}

/**
 * Renderiza un .docx tal cual se ve en Word (layout, fuentes, tablas,
 * imágenes) dentro de `container`, usando docx-preview del lado del
 * cliente — sin backend de conversión.
 */
window.renderDocxPreview = async function (url, container) {
    if (!container) return;

    container.dataset.docxUrl = url;
    container.innerHTML = '<p style="text-align:center;padding:40px;color:var(--color-secondary);font-size:13px;">Cargando documento...</p>';

    try {
        const response = await fetch(url, { credentials: 'same-origin' });
        if (!response.ok) throw new Error('No se pudo descargar el documento');
        const blob = await response.blob();

        if (container.dataset.docxUrl !== url) return;
        container.innerHTML = '';

        const docxPreview = await import('docx-preview');
        await docxPreview.renderAsync(blob, container, undefined, {
            inWrapper: true,
            ignoreWidth: false,
            ignoreHeight: false,
            experimental: true,
        });
    } catch (e) {
        if (container.dataset.docxUrl !== url) return;
        container.innerHTML = '<p style="text-align:center;padding:40px;color:var(--color-secondary);font-size:13px;">No se pudo cargar la vista previa del documento.</p>';
        console.error('renderDocxPreview error', e);
    }
};

/**
 * Instalar como PWA (botón en Configuración > Perfil, debajo de
 * Apariencia). Android/Chrome/desktop soportan el prompt nativo de
 * instalación ("beforeinstallprompt" — hay que capturarlo apenas carga la
 * página, antes de que el usuario entre a Configuración, porque el
 * navegador solo lo dispara una vez). iOS no tiene ninguna API para esto:
 * ahí solo se puede guiar al usuario a "Compartir > Agregar a pantalla de
 * inicio" (ver public/manifest.json + apple-mobile-web-app-capable).
 */
let deferredInstallPrompt = null;

// true si es un navegador de iOS DISTINTO de Safari (todos son WebKit por
// dentro, pero cada uno agrega su propio token al user agent). En esos,
// "Agregar a pantalla de inicio" normalmente solo deja un acceso directo
// que sigue abriendo dentro de esa app, no una PWA real — hay que avisar
// que abran el sitio en Safari específicamente.
function isNonSafariIOSBrowser() {
    const ua = navigator.userAgent;
    return /iP(hone|od|ad)/.test(ua) && /CriOS|FxiOS|EdgiOS|OPiOS/.test(ua);
}

window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredInstallPrompt = e;
    window.dispatchEvent(new Event('pwa-install-available'));
});

window.addEventListener('appinstalled', () => {
    deferredInstallPrompt = null;
});

window.pwaInstallState = function () {
    const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
    if (isStandalone) return 'installed';
    if (deferredInstallPrompt) return 'installable';
    if (typeof navigator.standalone !== 'undefined') return 'ios';
    return 'unsupported';
};

window.installPwa = async function () {
    if (deferredInstallPrompt) {
        deferredInstallPrompt.prompt();
        const { outcome } = await deferredInstallPrompt.userChoice;
        deferredInstallPrompt = null;
        return outcome === 'accepted';
    }

    alert(isNonSafariIOSBrowser()
        ? 'Para instalar en iPhone/iPad: abre este sitio en Safari (no en este navegador), toca el botón de compartir y elige "Agregar a pantalla de inicio".'
        : 'Para instalar: toca el botón de compartir y luego "Agregar a pantalla de inicio".');
    return false;
};

/**
 * Notificaciones push (switch en la campanita del estudiante, ver
 * livewire/students/notifications/bell.blade.php). APIs nativas del
 * navegador — nada que diferir con import() como el visor de PDF/Word.
 */
function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
    const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    return Uint8Array.from([...rawData].map((c) => c.charCodeAt(0)));
}

window.enablePushNotifications = async function () {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
        // navigator.standalone solo existe en iOS: ahí el Push API no
        // existe en pestaña normal, solo dentro de la app ya instalada en
        // pantalla de inicio (ver public/manifest.json).
        if (typeof navigator.standalone !== 'undefined') {
            alert(isNonSafariIOSBrowser()
                ? 'Para activar las notificaciones: abre este sitio en Safari (no en este navegador) e instálalo desde ahí en tu pantalla de inicio.'
                : 'Instala la app en tu pantalla de inicio para activar las notificaciones.');
        } else {
            alert('Tu navegador no soporta notificaciones push.');
        }
        return false;
    }

    try {
        const registration = await navigator.serviceWorker.register('/sw.js');
        const permission = await Notification.requestPermission();
        if (permission !== 'granted') return false;

        const vapidKey = document.querySelector('meta[name="vapid-key"]')?.content;
        const subscription = await registration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: urlBase64ToUint8Array(vapidKey),
        });

        await fetch('/push/subscribe', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            credentials: 'same-origin',
            body: JSON.stringify(subscription.toJSON()),
        });

        return true;
    } catch (e) {
        console.error('enablePushNotifications error', e);
        alert('No se pudieron activar las notificaciones. Revisa los permisos del navegador.');
        return false;
    }
};

window.disablePushNotifications = async function () {
    try {
        const registration = await navigator.serviceWorker.getRegistration();
        const subscription = await registration?.pushManager.getSubscription();
        if (!subscription) return true;

        await fetch('/push/unsubscribe', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            credentials: 'same-origin',
            body: JSON.stringify({ endpoint: subscription.endpoint }),
        });

        await subscription.unsubscribe();
        return true;
    } catch (e) {
        console.error('disablePushNotifications error', e);
        return false;
    }
};

/**
 * Estado real de la suscripción (no solo el permiso), para pintar el switch
 * ya prendido/apagado al abrir la campanita sin esperar ninguna acción.
 */
window.pushSubscriptionStatus = async function () {
    if (!('serviceWorker' in navigator) || !('PushManager' in window) || !('Notification' in window)) {
        return 'unsupported';
    }
    if (Notification.permission !== 'granted') return 'off';

    try {
        const registration = await navigator.serviceWorker.getRegistration();
        const subscription = await registration?.pushManager.getSubscription();
        return subscription ? 'on' : 'off';
    } catch {
        return 'off';
    }
};

/**
 * El service worker avisa por postMessage cuando entra un push (ver
 * public/sw.js) — con esto la campanita se refresca al vuelo sin depender
 * de ningún wire:poll corriendo en segundo plano para cada alumno.
 */
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.addEventListener('message', (event) => {
        if (event.data?.type === 'push-received') {
            window.Livewire?.dispatch('notification-received');
        }
    });
}
