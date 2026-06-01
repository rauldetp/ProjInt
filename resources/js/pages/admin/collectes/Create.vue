<template>
  <div class="collecte-form">
    <div class="page-header">
      <RouterLink to="/admin/collectes" class="back-link">← Retour</RouterLink>
      <h1 class="page-title">Nouvelle collecte</h1>
    </div>

    <form class="form-card" @submit.prevent="submit">
      <div class="form-grid">

        <div class="field">
          <label>Entreprise *</label>
          <select v-model="form.entreprise_id" required>
            <option value="" disabled>Choisir une entreprise</option>
            <option v-for="e in entreprises" :key="e.id" :value="e.id">
              {{ e.nom }}
            </option>
          </select>
        </div>

        <div class="field">
          <label>Statut</label>
          <select v-model="form.statut">
            <option value="en_attente">En attente</option>
            <option value="validee">Validée</option>
            <option value="terminee">Terminée</option>
          </select>
        </div>

        <div class="field">
          <label>Date de début *</label>
          <input type="date" v-model="form.date_debut" required />
        </div>

        <div class="field">
          <label>Date de fin</label>
          <input type="date" v-model="form.date_fin" />
        </div>

        <div class="field">
          <label>Lieu</label>
          <input type="text" v-model="form.lieu" placeholder="Salle de conférence A" />
        </div>

        <div class="field">
          <label>Horaires</label>
          <input type="text" v-model="form.horaires" placeholder="09:00 - 17:00" />
        </div>

        <div class="field">
          <label>Objectif de dons</label>
          <input type="number" v-model="form.objectif_dons" min="1" placeholder="50" />
        </div>

        <div class="field">
          <label>Lien RDV externe</label>
          <input type="url" v-model="form.lien_rdv_externe" placeholder="https://..." />
        </div>

        <div class="field field-checkbox">
          <label>
            <input type="checkbox" v-model="form.sur_site" />
            Collecte sur site entreprise
          </label>
        </div>

      </div>

      <p v-if="errorMsg" class="error">{{ errorMsg }}</p>

      <div class="form-actions">
        <RouterLink to="/admin/collectes" class="btn-secondary">Annuler</RouterLink>
        <button type="submit" class="btn-primary" :disabled="loading">
          {{ loading ? 'Création…' : 'Créer la collecte' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../../stores/auth'

const auth = useAuthStore()
const router = useRouter()

const entreprises = ref([])
const loading = ref(false)
const errorMsg = ref('')

const form = ref({
  entreprise_id: '',
  statut: 'en_attente',
  date_debut: '',
  date_fin: '',
  lieu: '',
  horaires: '',
  objectif_dons: '',
  lien_rdv_externe: '',
  sur_site: true,
})

function headers() {
  return {
    'Authorization': `Bearer ${auth.token}`,
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  }
}

async function fetchEntreprises() {
  const res = await fetch('/api/admin/entreprises', { headers: headers() })
  entreprises.value = await res.json()
}

async function submit() {
  loading.value = true
  errorMsg.value = ''
  try {
    const res = await fetch('/api/admin/collectes', {
      method: 'POST',
      headers: headers(),
      body: JSON.stringify(form.value),
    })
    if (!res.ok) {
      const data = await res.json()
      throw new Error(data.message ?? 'Erreur lors de la création.')
    }
    router.push('/admin/collectes')
  } catch (e) {
    errorMsg.value = e.message
  } finally {
    loading.value = false
  }
}

onMounted(fetchEntreprises)
</script>

<style scoped>
.collecte-form { max-width: 800px; margin: 0 auto; }

.page-header { margin-bottom: 1.75rem; }

.back-link {
  color: #64748b;
  text-decoration: none;
  font-size: 0.9rem;
  display: block;
  margin-bottom: 0.5rem;
}

.back-link:hover { color: #0f172a; }

.page-title { font-size: 1.75rem; font-weight: 700; color: #0f172a; margin: 0; }

.form-card {
  background: white;
  border-radius: 1rem;
  padding: 2rem;
  box-shadow: 0 4px 16px rgba(15, 23, 42, 0.07);
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 1.25rem;
  margin-bottom: 1.5rem;
}

.field { display: flex; flex-direction: column; gap: 0.4rem; }

.field label { font-size: 0.85rem; font-weight: 600; color: #475569; }

.field input,
.field select {
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  padding: 0.6rem 0.75rem;
  font-size: 0.95rem;
  color: #0f172a;
  background: white;
  transition: border-color 0.15s;
}

.field input:focus,
.field select:focus { outline: none; border-color: #38bdf8; }

.field-checkbox { justify-content: flex-end; }

.field-checkbox label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-weight: 500;
  cursor: pointer;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  margin-top: 1rem;
}

.btn-primary {
  background: #0f172a;
  color: white;
  border: none;
  border-radius: 0.5rem;
  padding: 0.65rem 1.5rem;
  font-weight: 600;
  cursor: pointer;
  font-size: 0.9rem;
  transition: background 0.15s;
}

.btn-primary:hover:not(:disabled) { background: #1e293b; }
.btn-primary:disabled { opacity: 0.5; cursor: default; }

.btn-secondary {
  background: #f1f5f9;
  color: #334155;
  border-radius: 0.5rem;
  padding: 0.65rem 1.5rem;
  font-weight: 600;
  text-decoration: none;
  font-size: 0.9rem;
  transition: background 0.15s;
}

.btn-secondary:hover { background: #e2e8f0; }

.error { color: #ef4444; font-size: 0.875rem; margin-bottom: 1rem; }

@media (max-width: 600px) { .form-grid { grid-template-columns: 1fr; } }
</style>
