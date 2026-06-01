import Home from './pages/Home.vue'
import Login from './pages/Login.vue'
import Entreprise from './pages/Entreprise.vue'
import { useAuthStore } from './stores/auth'

const routes = [
    { path: '/', component: Home },
    { path: '/login', component: Login },
    { path: '/entreprise/:slug', component: Entreprise },
    {
        path: '/admin',
        component: () => import('./pages/admin/Dashboard.vue'),
        meta: { requiresAuth: true },
    },
]

export default routes

export function setupGuards(router) {
    router.beforeEach((to) => {
        const auth = useAuthStore()
        if (to.meta.requiresAuth && !auth.isLoggedIn) {
            return '/login'
        }
        if (to.path === '/login' && auth.isLoggedIn) {
            return '/admin'
        }
    })
}
