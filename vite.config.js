import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                // Fraunces stays for the admin panel's existing font-serif usage — untouched this phase.
                bunny('Fraunces', {
                    weights: [400, 500, 600],
                    styles: ['normal', 'italic'],
                }),
                // Storefront's new bold display face (font-display), athletic redesign.
                bunny('Barlow Condensed', {
                    weights: [500, 600, 700, 800],
                }),
                bunny('Inter', {
                    weights: [400, 500, 600, 700],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
