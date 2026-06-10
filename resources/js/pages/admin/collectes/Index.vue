<template>
  <div class="collectes-index">
    <div class="page-header">
      <h1 class="page-title">Collectes</h1>
      <RouterLink
        to="/admin/collectes/create"
        class="btn-circle"
        title="Nouvelle collecte"
        aria-label="Nouvelle collecte"
      >
        <span class="material-symbols-outlined btn-circle-icon">add</span>
      </RouterLink>
    </div>

    <div v-if="loading" class="loading">Chargement...</div>
    <div v-else-if="error" class="error">{{ error }}</div>

    <template v-else>
      <div class="filters">
        <button
          v-for="f in filtres"
          :key="f.value"
          class="btn btn-outlined-blue"
          :class="{ 'is-selected': filtre === f.value }"
          @click="filtre = f.value"
        >
          {{ f.label }}
        </button>
      </div>

      <div v-if="collectesFiltrees.length === 0" class="empty">
        Aucune collecte pour ce filtre.
      </div>

      <div v-else class="cards-grid">
        <div
          v-for="collecte in collectesFiltrees"
          :key="collecte.id"
          class="collecte-card shadow-light"
        >
          <div class="card-top">
            <div class="card-head">
              <h3 class="card-company">{{ collecte.entreprise?.nom }}</h3>
              <p v-if="collecte.titre" class="captions card-subtitle">{{ collecte.titre }}</p>
              <p class="captions card-muted">{{ formatDate(collecte.date_debut) }}</p>
            </div>
            <div class="card-menu-wrap">
              <button class="btn-circle btn-circle-red" aria-label="Options" @click.stop="toggleMenu(collecte.id)">
                <span class="material-symbols-outlined btn-circle-icon">more_horiz</span>
              </button>
              <div v-if="openMenu === collecte.id" class="card-dropdown" @click.stop>
                <RouterLink :to="`/entreprise/${collecte.entreprise?.slug}/collecte/${collecte.id}`">Voir la page</RouterLink>
                <RouterLink :to="`/admin/collectes/${collecte.id}/edit`">Modifier</RouterLink>
                <button @click="copierLien(collecte)">
                  {{ copied === collecte.id ? 'Lien copié !' : 'Copier le lien' }}
                </button>
                <button class="danger" @click="supprimer(collecte)">Supprimer</button>
              </div>
            </div>
          </div>

          <span class="captions badge" :class="badgeClass(collecte)">{{ badgeLabel(collecte) }}</span>

          <p class="captions card-muted">
            <span class="material-symbols-outlined card-icon">group</span>
            {{ collecte.nb_inscrits_estime }} / {{ collecte.objectif_dons ?? '—' }} inscrit(s)
          </p>
          <p class="captions card-muted">{{ collecte.lieu ?? 'Lieu à définir' }}</p>
        </div>
      </div>
    </template>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { useAuthStore } from '../../../stores/auth'

const auth = useAuthStore()
const collectes = ref([])
const loading = ref(true)
const error = ref(null)
const filtre = ref('tous')
const openMenu = ref(null)
const copied = ref(null)

const filtres = [
  { value: 'tous', label: 'Toutes' },
  { value: 'en_attente', label: 'À confirmer' },
  { value: 'validee', label: 'En cours' },
  { value: 'terminee', label: 'Complétées' },
]

const collectesFiltrees = computed(() => {
  if (filtre.value === 'tous') return collectes.value
  return collectes.value.filter(c => c.statut === filtre.value)
})

function toggleMenu(id) {
  openMenu.value = openMenu.value === id ? null : id
}
function closeMenu() {
  openMenu.value = null
}

function formatDate(date) {
  return new Date(date).toLocaleDateString('fr-FR', {
    day: '2-digit', month: '2-digit', year: 'numeric'
  })
}

function badgeLabel(c) {
  if (c.statut === 'terminee') return 'Complétée'
  if (c.statut === 'en_attente') return 'À confirmer'
  if (c.active) return 'En cours'
  const today = new Date().toISOString().split('T')[0]
  const debut = c.date_debut ? String(c.date_debut).split('T')[0] : null
  return debut && debut >= today ? 'À venir' : 'Complétée'
}

function badgeClass(c) {
  const l = badgeLabel(c)
  if (l === 'En cours') return 'badge-encours'
  if (l === 'À confirmer') return 'badge-aconfirmer'
  if (l === 'À venir') return 'badge-avenir'
  return 'badge-complete'
}

function headers() {
  return {
    'Authorization': `Bearer ${auth.token}`,
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  }
}

async function fetchCollectes() {
  try {
    const res = await fetch('/api/admin/collectes', { headers: headers() })
    if (!res.ok) throw new Error('Erreur lors du chargement.')
    collectes.value = await res.json()
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}

async function changerStatut(collecte, statut) {
  closeMenu()
  await fetch(`/api/admin/collectes/${collecte.id}/statut`, {
    method: 'PATCH',
    headers: headers(),
    body: JSON.stringify({ statut }),
  })
  collecte.statut = statut
  collecte.active = statut === 'validee'
}

function copierLien(collecte) {
  const slug = collecte.entreprise?.slug
  const url = `${window.location.origin}/entreprise/${slug}/collecte/${collecte.id}`
  navigator.clipboard?.writeText(url)
  copied.value = collecte.id
  setTimeout(() => { copied.value = null; closeMenu() }, 1200)
}

async function supprimer(collecte) {
  closeMenu()
  if (!confirm(`Supprimer la collecte de ${collecte.entreprise?.nom} ?`)) return
  await fetch(`/api/admin/collectes/${collecte.id}`, {
    method: 'DELETE',
    headers: headers(),
  })
  collectes.value = collectes.value.filter(c => c.id !== collecte.id)
}

onMounted(() => {
  document.addEventListener('click', closeMenu)
  fetchCollectes()
})
onBeforeUnmount(() => {
  document.removeEventListener('click', closeMenu)
})
</script>

<style scoped>
.collectes-index {
  width: 100%;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.75rem;
}

.page-title {
  color: var(--default-titles);
  margin: 0;
}

/* Filtres pills */
.filters {
  display: flex;
  gap: 0.5rem;
  margin-bottom: 1.5rem;
  flex-wrap: wrap;
}

/* Grille de cartes */
.cards-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.25rem;
}
.collecte-card {
  background: white;
  border-radius: 0.75rem;
  padding: 1.25rem;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  transition: transform 0.15s;
}
.collecte-card:hover {
  transform: translateY(-2px);
}
.card-top {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
}
.card-head {
  min-width: 0;
}
.card-company {
  color: var(--default-titles);
  margin: 0;
}
.card-subtitle {
  color: var(--default-titles);
  opacity: 0.7;
  margin: 0.05rem 0 0;
  font-style: italic;
}
.card-muted {
  color: var(--default-text);
  margin: 0.1rem 0 0;
}
.card-icon {
  font-size: 14px;
  vertical-align: middle;
}

/* Menu circulaire + dropdown */
.card-menu-wrap {
  position: relative;
}
.card-dropdown {
  position: absolute;
  top: calc(100% + 6px);
  right: 0;
  background: white;
  border-radius: 0.6rem;
  box-shadow: 0 4px 20px rgba(44, 65, 64, 0.14);
  border: 1px solid var(--light-grey);
  min-width: 170px;
  z-index: 100;
  overflow: hidden;
}
.card-dropdown a,
.card-dropdown button {
  display: flex;
  align-items: center;
  gap: 8px;
  width: 100%;
  padding: 10px 14px;
  background: none;
  border: none;
  font-size: 0.875rem;
  color: var(--default-titles);
  cursor: pointer;
  font-family: inherit;
  text-align: left;
  text-decoration: none;
  transition: background 0.12s;
}
.card-dropdown a:hover,
.card-dropdown button:hover {
  background: #f9fafb;
}
.card-dropdown button.danger {
  color: var(--color-default-red);
}
.card-dropdown button.danger:hover {
  background: #fff1f4;
}

/* Badge : couleurs dans app.css, alignement spécifique à la carte */
.badge {
  align-self: flex-start;
}

.empty, .loading, .error {
  color: var(--default-text);
  padding: 2rem 0;
}
</style>
