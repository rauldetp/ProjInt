import Home from './pages/Home.vue'
import Login from './pages/Login.vue'
import Entreprise from './pages/Entreprise.vue'
import AdminLayout from './layouts/AdminLayout.vue'
import { useAuthStore } from './stores/auth'

const routes = [
    { path: '/', component: Home },
    { path: '/login', component: Login },
    { path: '/entreprise/:slug', component: Entreprise },
    {
        path: '/admin',
        component: AdminLayout,
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                component: () => import('./pages/admin/Dashboard.vue'),
            },
            {
                path: 'collectes',
                component: () => import('./pages/admin/collectes/Index.vue'),
            },
            {
                path: 'collectes/create',
                component: () => import('./pages/admin/collectes/Create.vue'),
            },
            {
                path: 'collectes/:id/edit',
                component: () => import('./pages/admin/collectes/Edit.vue'),
            },
        ],
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
