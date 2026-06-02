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
        meta: { requiresAuth: true, role: 'admin' },
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
            {
                path: 'coordinateurs',
                component: () => import('./pages/admin/coordinateurs/Index.vue'),
            },
            {
                path: 'coordinateurs/create',
                component: () => import('./pages/admin/coordinateurs/Create.vue'),
            },
            {
                path: 'coordinateurs/:id/edit',
                component: () => import('./pages/admin/coordinateurs/Edit.vue'),
            },
            {
                path: 'labels',
                component: () => import('./pages/admin/labels/Index.vue'),
            },
            {
                path: 'trophees',
                component: () => import('./pages/admin/trophees/Index.vue'),
            },
        ],
    },
    {
        path: '/coordinateur',
        component: AdminLayout,
        meta: { requiresAuth: true, role: 'coordinateur' },
        children: [
            { path: '', component: () => import('./pages/coordinateurs/Dashboard.vue') },
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
        if (to.meta.role === 'admin' && !auth.isAdmin) {
            return auth.isCoordinateur ? '/coordinateur' : '/login'
        }
        if (to.meta.role === 'coordinateur' && !auth.isCoordinateur) {
            return auth.isAdmin ? '/admin' : '/login'
        }
        if (to.path === '/login' && auth.isLoggedIn) {
            return auth.isAdmin ? '/admin' : '/coordinateur'
        }
    })
}
