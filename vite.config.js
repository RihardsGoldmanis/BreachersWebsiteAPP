import fs from 'fs';
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel([
            'resources/css/app.css',
            'resources/js/app.js',
        ]),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        https: {
            key: fs.readFileSync('/etc/ssl/certs/ddev-global-key.pem'),
            cert: fs.readFileSync('/etc/ssl/certs/ddev-global-cert.pem'),
        },
        hmr: {
            host: 'darbs.ddev.site',
            protocol: 'wss',
        },
    },
});
