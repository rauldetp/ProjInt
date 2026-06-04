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

    // Luminance perçue (0–1). Seuil 0.5 : au-dessus → fond clair → texte noir.
    const textOnBrand = computed(() => {
        const hex = couleurPrimaire.value || '#e60f48'
        const r = parseInt(hex.slice(1, 3), 16)
        const g = parseInt(hex.slice(3, 5), 16)
        const b = parseInt(hex.slice(5, 7), 16)
        const lum = (0.299 * r + 0.587 * g + 0.114 * b) / 255
        return lum > 0.5 ? '#1a1a1a' : '#ffffff'
    })

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

    return { couleurPrimaire, logo, nom, slug, gradientStyle, isActive, textOnBrand, set, clear }
})
