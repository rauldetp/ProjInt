<template>
  <div class="coordinateurs-index">
    <div class="page-header">
      <h1 class="page-title">Coordinateurs</h1>
      <RouterLink to="/admin/coordinateurs/create" class="btn-primary">
        + Nouveau coordinateur
      </RouterLink>
    </div>

    <div v-if="loading" class="loading">Chargement...</div>
    <div v-else-if="error" class="error">{{ error }}</div>

    <table v-else class="table">
      <thead>
        <tr>
          <th>Nom</th>
          <th>Email</th>
          <th>Entreprise</th>
          <th>Poste</th>
          <th>Téléphone</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="coord in coordinateurs" :key="coord.id">
          <td>{{ coord.user?.name }}</td>
          <td>{{ coord.user?.email }}</td>
          <td>{{ coord.entreprise?.nom }}</td>
          <td>{{ coord.poste ?? '—' }}</td>
          <td>{{ coord.telephone ?? '—' }}</td>
          <td class="actions">
            <RouterLink :to="`/admin/coordinateurs/${coord.id}/edit`" class="btn-edit">
              Modifier
            </RouterLink>
            <button class="btn-delete" @click="supprimer(coord)">
              Supprimer
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '../../../stores/auth'

const auth = useAuthStore()
const coordinateurs = ref([])
const loading = ref(true)
const error = ref(null)

function headers() {
  return {
    'Authorization': `Bearer ${auth.token}`,
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  }
}

async function fetchCoordinateurs() {
  try {
    const res = await fetch('/api/admin/coordinateurs', { headers: headers() })
    if (!res.ok) throw new Error('Erreur lors du chargement.')
    coordinateurs.value = await res.json()
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}

async function supprimer(coord) {
  if (!confirm(`Supprimer ${coord.user?.name} ?`)) return
  await fetch(`/api/admin/coordinateurs/${coord.id}`, {
    method: 'DELETE',
    headers: headers(),
  })
  coordinateurs.value = coordinateurs.value.filter(c => c.id !== coord.id)
}

onMounted(fetchCoordinateurs)
</script>

<style scoped>
.coordinateurs-index { max-width: 1100px; margin: 0 auto; }

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.75rem;
}

.page-title { font-size: 1.75rem; font-weight: 700; color: #0f172a; margin: 0; }

.btn-primary {
  background: #0f172a;
  color: white;
  border-radius: 0.5rem;
  padding: 0.6rem 1.25rem;
  text-decoration: none;
  font-weight: 600;
  font-size: 0.9rem;
}

.btn-primary:hover { background: #1e293b; }

.table {
  width: 100%;
  border-collapse: collapse;
  background: white;
  border-radius: 1rem;
  overflow: hidden;
  font-size: 0.9rem;
}

.table th {
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

.table td {
  padding: 0.9rem 1rem;
  color: #334155;
  border-bottom: 1px solid #f1f5f9;
}

.table tr:last-child td { border-bottom: none; }

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

.loading, .error { color: #94a3b8; padding: 2rem 0; }
</style>
