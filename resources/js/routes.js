import Home from './pages/Home.vue'
import Login from './pages/Login.vue'
import Entreprise from './pages/Entreprise.vue'
import Label from './pages/Label.vue'
import Trophee from './pages/Trophee.vue'
import AdminLayout from './layouts/AdminLayout.vue'
import Contact from './pages/Contact.vue'
import FAQ from './pages/FAQ.vue'
import InscriptionCollecte from './pages/InscriptionCollecte.vue'
import { useAuthStore } from './stores/auth'

const routes = [
    { path: '/', component: Home },
    { path: '/login', component: Login },
    { path: '/label', component: Label },
    { path: '/trophee', component: Trophee },
    { path: '/contact', component: Contact },
    { path: '/faq', component: FAQ },
    { path: '/politique-confidentialite', component: () => import('./pages/PolitiqueConfidentalite.vue') },
    { path: '/conditions-generales', component: () => import('./pages/ConditionsGenerales.vue') },
    { path: '/entreprise/:slug', component: Entreprise },
    { path: '/entreprise/:slug/inscription', component: InscriptionCollecte },
    { path: '/entreprise/:slug/label', component: () => import('./pages/CoLabel.vue') },
    { path: '/entreprise/:slug/trophee', component: () => import('./pages/CoTrophee.vue') },
    { path: '/entreprise/:slug/quiz', component: () => import('./pages/CoQuiz.vue') },
    { path: '/quiz', component: () => import('./pages/QuizPage.vue') },
    { path: '/entreprise/:slug/espace', component: () => import('./pages/CoEspaceEntreprise.vue') },
    { path: '/entreprise/:slug/nouvelle-collecte', component: () => import('./pages/CoNouvelleCollecte.vue'), meta: { requiresAuth: true, role: 'coordinateur' } },
    { path: '/entreprise/:slug/collecte/:id', component: () => import('./pages/CoDetailCollecte.vue') },
    {
        path: '/admin',
        component: AdminLayout,
        meta: { requiresAuth: true, role: 'admin' },
        children: [
            { path: '', component: () => import('./pages/admin/Dashboard.vue') },
            { path: 'collectes', component: () => import('./pages/admin/collectes/Index.vue') },
            { path: 'collectes/create', component: () => import('./pages/admin/collectes/Create.vue') },
            { path: 'collectes/:id/edit', component: () => import('./pages/admin/collectes/Edit.vue') },
            { path: 'coordinateurs', component: () => import('./pages/admin/coordinateurs/Index.vue') },
            { path: 'coordinateurs/create', component: () => import('./pages/admin/coordinateurs/Create.vue') },
            { path: 'coordinateurs/:id/edit', component: () => import('./pages/admin/coordinateurs/Edit.vue') },
            { path: 'labels', component: () => import('./pages/admin/labels/Index.vue') },
            { path: 'trophees', component: () => import('./pages/admin/trophees/Index.vue') },
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
        if (to.meta.requiresAuth && !auth.isLoggedIn) return '/login'
        if (to.meta.role === 'admin' && !auth.isAdmin) return auth.isCoordinateur ? `/entreprise/${auth.entrepriseSlug}/espace` : '/login'
        if (to.meta.role === 'coordinateur' && !auth.isCoordinateur) return auth.isAdmin ? '/admin' : '/login'
        if (to.path === '/login' && auth.isLoggedIn) return auth.isAdmin ? '/admin' : (auth.entrepriseSlug ? `/entreprise/${auth.entrepriseSlug}` : '/coordinateur')
    })
}
