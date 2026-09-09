import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('pdfjs-dist')) return 'pdf-viewer';
                    if (id.includes('docx-preview')) return 'docx-viewer';
                },
            },
        },
    },
    server: {
        cors: true,
        host: true,
        port: 5173,
        strictPort: true,
        origin: 'http://localhost:5173',
    },
});