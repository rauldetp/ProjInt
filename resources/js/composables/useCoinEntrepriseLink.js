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
        if (auth.isAdmin) return '/entreprises'
        if (auth.isCoordinateur) return '/coordinateur'
        return '/login'
    })

    return { coinEntrepriseLink }
}
