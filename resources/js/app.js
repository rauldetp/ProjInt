import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import { createPinia } from 'pinia'
import App from './App.vue'
import routes, { setupGuards } from './routes'
import { useAuthStore } from './stores/auth'

const router = createRouter({
    history: createWebHistory(),
    routes,
})

const pinia = createPinia()
const app = createApp(App)

app.use(pinia)
app.use(router)

setupGuards(router)

const auth = useAuthStore()
auth.fetchMe().finally(() => {
    app.mount('#app')
})
