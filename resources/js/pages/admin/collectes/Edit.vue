<template>
  <div class="collecte-form">
    <div class="page-header">
      <RouterLink to="/admin/collectes" class="back-link">← Retour</RouterLink>
      <h1 class="page-title">Modifier la collecte</h1>
    </div>

    <div v-if="loading" class="loading">Chargement...</div>
    <div v-else-if="error" class="error">{{ error }}</div>

    <form v-else class="form-card" @submit.prevent="submit">
      <div class="form-grid">

        <div class="field">
          <label>Entreprise *</label>
          <select v-model="form.entreprise_id" required>
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
            <option value="annulee">Annulée</option>
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
          <input type="text" v-model="form.lieu" />
        </div>

        <div class="field">
          <label>Horaires</label>
          <input type="text" v-model="form.horaires" />
        </div>

        <div class="field">
          <label>Objectif de dons</label>
          <input type="number" v-model="form.objectif_dons" min="1" />
        </div>

        <div class="field">
          <label>Lien RDV externe</label>
          <input type="url" v-model="form.lien_rdv_externe" />
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
        <button type="submit" class="btn-primary" :disabled="saving">
          {{ saving ? 'Enregistrement…' : 'Enregistrer' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '../../../stores/auth'

const auth = useAuthStore()
const router = useRouter()
const route = useRoute()

const entreprises = ref([])
const loading = ref(true)
const saving = ref(false)
const error = ref(null)
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

function formatDateInput(date) {
  if (!date) return ''
  return new Date(date).toISOString().split('T')[0]
}

async function fetchData() {
  try {
    const [collecteRes, entreprisesRes] = await Promise.all([
      fetch(`/api/admin/collectes/${route.params.id}`, { headers: headers() }),
      fetch('/api/admin/entreprises', { headers: headers() }),
    ])
    if (!collecteRes.ok) throw new Error('Collecte introuvable.')
    const collecte = await collecteRes.json()
    entreprises.value = await entreprisesRes.json()

    form.value = {
      entreprise_id: collecte.entreprise_id,
      statut: collecte.statut,
      date_debut: formatDateInput(collecte.date_debut),
      date_fin: formatDateInput(collecte.date_fin),
      lieu: collecte.lieu ?? '',
      horaires: collecte.horaires ?? '',
      objectif_dons: collecte.objectif_dons ?? '',
      lien_rdv_externe: collecte.lien_rdv_externe ?? '',
      sur_site: collecte.sur_site,
    }
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}

async function submit() {
  saving.value = true
  errorMsg.value = ''
  try {
    const res = await fetch(`/api/admin/collectes/${route.params.id}`, {
      method: 'PUT',
      headers: headers(),
      body: JSON.stringify(form.value),
    })
    if (!res.ok) {
      const data = await res.json()
      throw new Error(data.message ?? 'Erreur lors de la sauvegarde.')
    }
    router.push('/admin/collectes')
  } catch (e) {
    errorMsg.value = e.message
  } finally {
    saving.value = false
  }
}

onMounted(fetchData)
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
}

.btn-secondary:hover { background: #e2e8f0; }

.loading { color: #64748b; padding: 2rem 0; }

.error { color: #ef4444; font-size: 0.875rem; margin-bottom: 1rem; }

@media (max-width: 768px) { .form-grid { grid-template-columns: 1fr; } }
</style>
