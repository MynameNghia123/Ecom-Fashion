import './assets/fonts/fonts.css'
import './assets/client/css/app.css'
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'

const app = createApp(App)
const pinia = createPinia()
app.use(pinia)
app.use(router)

// Restore client auth session on startup
import { useClientAuthStore } from '@/stores/client/authStore'
const authStore = useClientAuthStore(pinia)
authStore.initAuth()

app.mount('#app')
