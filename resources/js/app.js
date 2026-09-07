import * as pdfjsLib from 'pdfjs-dist';
import pdfjsWorker from 'pdfjs-dist/build/pdf.worker.min.mjs?url';
import * as docxPreview from 'docx-preview';

pdfjsLib.GlobalWorkerOptions.workerSrc = pdfjsWorker;

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

function updateZoomLabel(container) {
    const label = document.getElementById('pdf-zoom-label');
    if (!label) return;
    const zoom = parseFloat(container.dataset.zoom) || 1;
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
