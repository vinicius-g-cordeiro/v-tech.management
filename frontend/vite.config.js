import { fileURLToPath , URL} from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import tailwindcss from "@tailwindcss/vite"
import { PrimeVueResolver } from '@primevue/auto-import-resolver';

export default defineConfig({
    plugins: [vue(), tailwindcss(), PrimeVueResolver()],
    server: {
        host: '0.0.0.0',
        port: 5173,
        watch: {
            usePolling: true
        }
    },
    build: {
        outDir: 'dist',
        assetsDir: 'assets',
        sourcemap: false
    },
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./src', import.meta.url))
        },
    },
})