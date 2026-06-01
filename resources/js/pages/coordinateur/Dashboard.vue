<template>
  <div class="dashboard">
    <h1 class="page-title">Mon tableau de bord</h1>

    <div v-if="loading" class="loading">Chargement...</div>
    <div v-else-if="error" class="error">{{ error }}</div>

    <template v-else>
      <div class="entreprise-header">
        <p class="entreprise-label">Votre entreprise</p>
        <h2 class="entreprise-nom">{{ entreprise?.nom }}</h2>
        <p class="entreprise-ville">{{ entreprise?.ville }}</p>
      </div>

      <section class="collectes-section">
        <h2 class="section-title">Vos collectes</h2>

        <div v-if="collectes.length === 0" class="empty">
          Aucune collecte pour votre entreprise.
        </div>

        <div v-else class="collectes-list">
          <div v-for="collecte in collectes" :key="collecte.id" class="collecte-card">
            <div class="collecte-header">
              <span :class="['badge', collecte.statut]">{{ collecte.statut }}</span>
              <span class="collecte-date">{{ formatDate(collecte.date_debut) }}</span>
            </div>
            <p class="collecte-lieu">{{ collecte.lieu ?? 'Lieu à définir' }}</p>
            <p class="collecte-horaires">{{ collecte.horaires ?? '' }}</p>
            <div class="collecte-stats">
              <span>{{ collecte.nb_inscrits_estime }} inscrits</span>
              <span>Objectif : {{ collecte.objectif_dons ?? '—' }}</span>
            </div>
          </div>
        </div>
      </section>
    </template>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '../../stores/auth'

const auth = useAuthStore()
const entreprise = ref(null)
const collectes = ref([])
const loading = ref(true)
const error = ref(null)

function formatDate(date) {
  return new Date(date).toLocaleDateString('fr-FR', {
    day: '2-digit', month: '2-digit', year: 'numeric'
  })
}

onMounted(async () => {
  try {
    const res = await fetch('/api/me', {
      headers: {
        'Authorization': `Bearer ${auth.token}`,
        'Accept': 'application/json',
      }
    })
    if (!res.ok) throw new Error('Erreur chargement.')
    const data = await res.json()
    entreprise.value = data.user?.coordinateur?.entreprise ?? null

    if (entreprise.value?.slug) {
      const res2 = await fetch(`/api/entreprises/${entreprise.value.slug}`)
      const data2 = await res2.json()
      collectes.value = data2.collecte ? [data2.collecte] : []
    }
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
})
</script>

<style scoped>
.dashboard {
  max-width: 800px;
  margin: 0 auto;
}

.page-title {
  font-size: 1.75rem;
  font-weight: 700;
  color: #0f172a;
  margin-bottom: 2rem;
}

.entreprise-header {
  background: white;
  border-radius: 1rem;
  padding: 1.5rem 2rem;
  box-shadow: 0 4px 16px rgba(15, 23, 42, 0.07);
  margin-bottom: 2rem;
}

.entreprise-label {
  font-size: 0.8rem;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  color: #64748b;
  margin: 0 0 0.4rem;
}

.entreprise-nom {
  font-size: 1.5rem;
  font-weight: 700;
  color: #0f172a;
  margin: 0 0 0.25rem;
}

.entreprise-ville {
  color: #64748b;
  margin: 0;
}

.section-title {
  font-size: 1.1rem;
  font-weight: 600;
  color: #0f172a;
  margin-bottom: 1.25rem;
}

.collectes-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.collecte-card {
  background: white;
  border-radius: 1rem;
  padding: 1.5rem;
  box-shadow: 0 4px 16px rgba(15, 23, 42, 0.07);
}

.collecte-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.75rem;
}

.collecte-date {
  font-size: 0.875rem;
  color: #64748b;
}

.collecte-lieu {
  font-weight: 600;
  color: #0f172a;
  margin: 0 0 0.25rem;
}

.collecte-horaires {
  color: #64748b;
  font-size: 0.9rem;
  margin: 0 0 1rem;
}

.collecte-stats {
  display: flex;
  gap: 1.5rem;
  font-size: 0.875rem;
  color: #475569;
}

.badge {
  display: inline-block;
  padding: 0.25rem 0.65rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 600;
}

.badge.validee   { background: #dcfce7; color: #166534; }
.badge.en_attente { background: #fef9c3; color: #854d0e; }
.badge.terminee  { background: #f1f5f9; color: #475569; }

.empty, .loading, .error { color: #94a3b8; padding: 2rem 0; }
</style>
