import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'
import tailwindcss from '@tailwindcss/vite'

// https://vite.dev/config/
export default defineConfig({
  plugins: [
    vue(),
    vueDevTools(),
    tailwindcss(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url)),
      '@css': fileURLToPath(new URL('./src/assets/client/css', import.meta.url)),
      '@js': fileURLToPath(new URL('./src/assets/client/js', import.meta.url)),
      '@client': fileURLToPath(new URL('./src/assets/client', import.meta.url)),
      '@errors': fileURLToPath(new URL('./src/views/errors', import.meta.url)),
    },
  },
  server: {
    host: '0.0.0.0',
    port: 5173,
    strictPort: true,
    watch: {
      usePolling: true,
    },
  },
  optimizeDeps: {
    include: ['vue-inner-image-zoom'],
  },
})
