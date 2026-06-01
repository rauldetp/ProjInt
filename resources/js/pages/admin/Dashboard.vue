<template>
  <div class="dashboard">
    <h1 class="page-title">Dashboard</h1>

    <div v-if="loading" class="loading">Chargement...</div>
    <div v-else-if="error" class="error">{{ error }}</div>

    <template v-else>

      <section class="stats-grid">
        <div class="stat-card">
          <p class="stat-label">Collectes actives</p>
          <p class="stat-value">{{ stats.collectes_actives }}</p>
        </div>
        <div class="stat-card">
          <p class="stat-label">Inscrits (collectes en cours)</p>
          <p class="stat-value">{{ stats.total_inscrits }}</p>
        </div>
        <div class="stat-card">
          <p class="stat-label">Dons réalisés (total)</p>
          <p class="stat-value">{{ stats.total_dons_realises }}</p>
        </div>
        <div class="stat-card">
          <p class="stat-label">Entreprises partenaires</p>
          <p class="stat-value">{{ stats.entreprises_partenaires }}</p>
        </div>
      </section>

      <section class="upcoming-section">
        <h2 class="section-title">Collectes à venir</h2>
        <div v-if="collectesAvenir.length === 0" class="empty">
          Aucune collecte à venir.
        </div>
        <table v-else class="collectes-table">
          <thead>
            <tr>
              <th>Entreprise</th>
              <th>Date</th>
              <th>Lieu</th>
              <th>Inscrits</th>
              <th>Statut</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="collecte in collectesAvenir" :key="collecte.id">
              <td>{{ collecte.entreprise?.nom }}</td>
              <td>{{ formatDate(collecte.date_debut) }}</td>
              <td>{{ collecte.lieu ?? '—' }}</td>
              <td>{{ collecte.nb_inscrits_estime }}</td>
              <td>
                <span :class="['badge', collecte.statut]">{{ collecte.statut }}</span>
              </td>
              <td>
                <RouterLink :to="`/admin/collectes/${collecte.id}/edit`" class="link-edit">
                  Modifier
                </RouterLink>
              </td>
            </tr>
          </tbody>
        </table>
      </section>

    </template>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '../../stores/auth'

const auth = useAuthStore()

const stats = ref(null)
const collectes = ref([])
const loading = ref(true)
const error = ref(null)

const collectesAvenir = computed(() => {
  const today = new Date().toISOString().split('T')[0]
  return collectes.value
    .filter(c => c.date_debut >= today)
    .sort((a, b) => a.date_debut.localeCompare(b.date_debut))
})

function formatDate(date) {
  return new Date(date).toLocaleDateString('fr-FR', {
    day: '2-digit', month: '2-digit', year: 'numeric'
  })
}

async function fetchData() {
  try {
    const headers = {
      'Authorization': `Bearer ${auth.token}`,
      'Accept': 'application/json',
    }
    const [statsRes, collectesRes] = await Promise.all([
      fetch('/api/admin/stats', { headers }),
      fetch('/api/admin/collectes', { headers }),
    ])
    if (!statsRes.ok || !collectesRes.ok) throw new Error('Erreur lors du chargement.')
    stats.value = await statsRes.json()
    collectes.value = await collectesRes.json()
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}

onMounted(fetchData)
</script>

<style scoped>
.dashboard {
  max-width: 1100px;
  margin: 0 auto;
}

.page-title {
  font-size: 1.75rem;
  font-weight: 700;
  color: #0f172a;
  margin-bottom: 2rem;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: 1.25rem;
  margin-bottom: 2.5rem;
}

.stat-card {
  background: white;
  border-radius: 1rem;
  padding: 1.5rem;
  box-shadow: 0 4px 16px rgba(15, 23, 42, 0.07);
}

.stat-label {
  font-size: 0.8rem;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  color: #64748b;
  margin: 0 0 0.5rem;
}

.stat-value {
  font-size: 2.25rem;
  font-weight: 700;
  color: #0f172a;
  margin: 0;
}

.upcoming-section {
  background: white;
  border-radius: 1rem;
  padding: 1.5rem;
  box-shadow: 0 4px 16px rgba(15, 23, 42, 0.07);
}

.section-title {
  font-size: 1.1rem;
  font-weight: 600;
  color: #0f172a;
  margin: 0 0 1.25rem;
}

.collectes-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.9rem;
}

.collectes-table th {
  text-align: left;
  padding: 0.6rem 0.75rem;
  color: #64748b;
  font-weight: 600;
  border-bottom: 1px solid #e2e8f0;
  font-size: 0.8rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.collectes-table td {
  padding: 0.85rem 0.75rem;
  color: #334155;
  border-bottom: 1px solid #f1f5f9;
}

.collectes-table tr:last-child td {
  border-bottom: none;
}

.badge {
  display: inline-block;
  padding: 0.25rem 0.65rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 600;
}

.badge.validee {
  background: #dcfce7;
  color: #166534;
}

.badge.en_attente {
  background: #fef9c3;
  color: #854d0e;
}

.badge.terminee {
  background: #f1f5f9;
  color: #475569;
}

.link-edit {
  color: #0ea5e9;
  text-decoration: none;
  font-weight: 500;
  font-size: 0.875rem;
}

.link-edit:hover {
  text-decoration: underline;
}

.empty {
  color: #94a3b8;
  font-size: 0.95rem;
  padding: 1rem 0;
}

.loading, .error {
  color: #64748b;
  padding: 2rem 0;
}

@media (max-width: 900px) {
  .stats-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

@media (max-width: 560px) {
  .stats-grid {
    grid-template-columns: 1fr;
  }
}
</style>
