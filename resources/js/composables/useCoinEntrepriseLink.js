import { computed } from 'vue'
import { useAuthStore } from '../stores/auth'

/**
 * Retourne le lien "Coin entreprise" adapté au contexte :
 * - Admin connecté → /admin
 * - Coordinateur connecté → /entreprise/:slug
 * - Non connecté → /login
 */
export function useCoinEntrepriseLink() {
    const auth = useAuthStore()

    const coinEntrepriseLink = computed(() => {
        if (auth.isAdmin) return '/admin'
        if (auth.isCoordinateur) {
            const slug = auth.user?.coordinateur?.entreprise?.slug
            if (slug) return `/entreprise/${slug}/espace`
        }
        return '/login'
    })

    return { coinEntrepriseLink }
}
