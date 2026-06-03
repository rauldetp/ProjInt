import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

const LS_KEY = 'cobrand'

function loadFromStorage() {
    try {
        const raw = localStorage.getItem(LS_KEY)
        return raw ? JSON.parse(raw) : null
    } catch { return null }
}

export const useCobrandStore = defineStore('cobrand', () => {
    const saved = loadFromStorage()

    const couleurPrimaire = ref(saved?.couleurPrimaire ?? null)
    const logo = ref(saved?.logo ?? null)
    const nom = ref(saved?.nom ?? '')
    const slug = ref(saved?.slug ?? '')

    const gradientStyle = computed(() => {
        if (!couleurPrimaire.value) return 'linear-gradient(135deg, #65c6c1, #93cfa9)'
        return `linear-gradient(135deg, ${couleurPrimaire.value}30, ${couleurPrimaire.value}10)`
    })

    const isActive = computed(() => !!couleurPrimaire.value)

    function persist() {
        localStorage.setItem(LS_KEY, JSON.stringify({
            couleurPrimaire: couleurPrimaire.value,
            logo: logo.value,
            nom: nom.value,
            slug: slug.value,
        }))
    }

    function set(entreprise) {
        // Fallback sur le rouge HUG si l'entreprise n'a pas de couleur personnalisée
        couleurPrimaire.value = entreprise.couleur_primaire || '#e60f48'
        logo.value = entreprise.logo || null
        nom.value = entreprise.nom || ''
        slug.value = entreprise.slug || ''
        persist()
    }

    function clear() {
        couleurPrimaire.value = null
        logo.value = null
        nom.value = ''
        slug.value = ''
        localStorage.removeItem(LS_KEY)
    }

    return { couleurPrimaire, logo, nom, slug, gradientStyle, isActive, set, clear }
})
