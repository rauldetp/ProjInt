<template>
  <div class="collectes-index">
    <div class="page-header">
      <h1 class="page-title">Collectes</h1>
      <RouterLink to="/admin/collectes/create" class="btn-primary">
        + Nouvelle collecte
      </RouterLink>
    </div>

    <div v-if="loading" class="loading">Chargement...</div>
    <div v-else-if="error" class="error">{{ error }}</div>

    <template v-else>
      <div class="filters">
        <button
          v-for="f in filtres"
          :key="f.value"
          :class="['filter-btn', { active: filtre === f.value }]"
          @click="filtre = f.value"
        >
          {{ f.label }}
        </button>
      </div>

      <div v-if="collectesFiltrees.length === 0" class="empty">
        Aucune collecte pour ce filtre.
      </div>

      <table v-else class="collectes-table">
        <thead>
          <tr>
            <th>Entreprise</th>
            <th>Date</th>
            <th>Lieu</th>
            <th>Inscrits / Objectif</th>
            <th>Statut</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="collecte in collectesFiltrees" :key="collecte.id">
            <td>{{ collecte.entreprise?.nom }}</td>
            <td>{{ formatDate(collecte.date_debut) }}</td>
            <td>{{ collecte.lieu ?? '—' }}</td>
            <td>{{ collecte.nb_inscrits_estime }} / {{ collecte.objectif_dons ?? '—' }}</td>
            <td>
              <select
                :value="collecte.statut"
                @change="changerStatut(collecte, $event.target.value)"
                :class="['statut-select', collecte.statut]"
              >
                <option value="en_attente">En attente</option>
                <option value="validee">Validée</option>
                <option value="terminee">Terminée</option>
              </select>
            </td>
            <td class="actions">
              <RouterLink :to="`/admin/collectes/${collecte.id}/edit`" class="btn-edit">
                Modifier
              </RouterLink>
              <button class="btn-delete" @click="supprimer(collecte)">
                Supprimer
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </template>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '../../../stores/auth'

const auth = useAuthStore()
const collectes = ref([])
const loading = ref(true)
const error = ref(null)
const filtre = ref('tous')

const filtres = [
  { value: 'tous', label: 'Toutes' },
  { value: 'en_attente', label: 'En attente' },
  { value: 'validee', label: 'Validées' },
  { value: 'terminee', label: 'Terminées' },
]

const collectesFiltrees = computed(() => {
  if (filtre.value === 'tous') return collectes.value
  return collectes.value.filter(c => c.statut === filtre.value)
})

function formatDate(date) {
  return new Date(date).toLocaleDateString('fr-FR', {
    day: '2-digit', month: '2-digit', year: 'numeric'
  })
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
  await fetch(`/api/admin/collectes/${collecte.id}/statut`, {
    method: 'PATCH',
    headers: headers(),
    body: JSON.stringify({ statut }),
  })
  collecte.statut = statut
  collecte.active = statut === 'validee'
}

async function supprimer(collecte) {
  if (!confirm(`Supprimer la collecte de ${collecte.entreprise?.nom} ?`)) return
  await fetch(`/api/admin/collectes/${collecte.id}`, {
    method: 'DELETE',
    headers: headers(),
  })
  collectes.value = collectes.value.filter(c => c.id !== collecte.id)
}

onMounted(fetchCollectes)
</script>

<style scoped>
.collectes-index {
  max-width: 1100px;
  margin: 0 auto;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.75rem;
}

.page-title {
  font-size: 1.75rem;
  font-weight: 700;
  color: #0f172a;
  margin: 0;
}

.btn-primary {
  background: #0f172a;
  color: white;
  border-radius: 0.5rem;
  padding: 0.6rem 1.25rem;
  text-decoration: none;
  font-weight: 600;
  font-size: 0.9rem;
  transition: background 0.15s;
}

.btn-primary:hover { background: #1e293b; }

.filters {
  display: flex;
  gap: 0.5rem;
  margin-bottom: 1.25rem;
}

.filter-btn {
  padding: 0.4rem 1rem;
  border-radius: 9999px;
  border: 1px solid #e2e8f0;
  background: white;
  color: #64748b;
  font-size: 0.85rem;
  cursor: pointer;
  transition: all 0.15s;
}

.filter-btn.active {
  background: #0f172a;
  color: white;
  border-color: #0f172a;
}

.collectes-table {
  width: 100%;
  border-collapse: collapse;
  background: white;
  border-radius: 1rem;
  overflow: hidden;
  font-size: 0.9rem;
}

.collectes-table th {
  text-align: left;
  padding: 0.75rem 1rem;
  color: #64748b;
  font-weight: 600;
  border-bottom: 1px solid #e2e8f0;
  font-size: 0.8rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  background: #f8fafc;
}

.collectes-table td {
  padding: 0.9rem 1rem;
  color: #334155;
  border-bottom: 1px solid #f1f5f9;
}

.collectes-table tr:last-child td { border-bottom: none; }

.statut-select {
  border: none;
  border-radius: 9999px;
  padding: 0.25rem 0.65rem;
  font-size: 0.8rem;
  font-weight: 600;
  cursor: pointer;
}

.statut-select.validee   { background: #dcfce7; color: #166534; }
.statut-select.en_attente { background: #fef9c3; color: #854d0e; }
.statut-select.terminee  { background: #f1f5f9; color: #475569; }

.actions { display: flex; gap: 0.75rem; align-items: center; }

.btn-edit {
  color: #0ea5e9;
  text-decoration: none;
  font-weight: 500;
  font-size: 0.875rem;
}

.btn-edit:hover { text-decoration: underline; }

.btn-delete {
  background: none;
  border: none;
  color: #ef4444;
  font-size: 0.875rem;
  cursor: pointer;
  font-weight: 500;
  padding: 0;
}

.btn-delete:hover { text-decoration: underline; }

.empty, .loading, .error { color: #94a3b8; padding: 2rem 0; }
</style>
