<template>
  <div class="coordinateur-form">
    <div class="page-header">
      <RouterLink to="/admin/coordinateurs" class="back-link">← Retour</RouterLink>
      <h1 class="page-title">Modifier le coordinateur</h1>
    </div>
    <div v-if="loading" class="loading">Chargement...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <form v-else class="form-card" @submit.prevent="submit">
      <div class="form-grid">
        <div class="field">
          <label>Nom complet</label>
          <input type="text" v-model="form.name" required placeholder="Sandra Martin" />
        </div>
        <div class="field">
          <label>Email</label>
          <input type="email" v-model="form.email" required placeholder="sandra@entreprise.com" />
        </div>
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
          <label>Poste</label>
          <input type="text" v-model="form.poste" placeholder="Responsable RH" />
        </div>
        <div class="field">
          <label>Téléphone</label>
          <input type="text" v-model="form.telephone" placeholder="+41 22 000 00 00" />
        </div>
      </div>
      <p v-if="errorMsg" class="error">{{ errorMsg }}</p>
      <div class="form-actions">
        <RouterLink to="/admin/coordinateurs" class="btn-secondary">Annuler</RouterLink>
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
  name: '',
  email: '',
  entreprise_id: '',
  poste: '',
  telephone: '',
})

function headers() {
  return {
    'Authorization': `Bearer ${auth.token}`,
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  }
}

async function fetchData() {
  try {
    const [coordRes, entreprisesRes] = await Promise.all([
      fetch(`/api/admin/coordinateurs/${route.params.id}`, { headers: headers() }),
      fetch('/api/admin/entreprises', { headers: headers() }),
    ])
    if (!coordRes.ok) throw new Error('Coordinateur introuvable.')
    const coord = await coordRes.json()
    entreprises.value = await entreprisesRes.json()
    form.value = {
      name: coord.user?.name ?? '',
      email: coord.user?.email ?? '',
      entreprise_id: coord.entreprise_id,
      poste: coord.poste ?? '',
      telephone: coord.telephone ?? '',
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
    const res = await fetch(`/api/admin/coordinateurs/${route.params.id}`, {
      method: 'PUT',
      headers: headers(),
      body: JSON.stringify(form.value),
    })
    if (!res.ok) {
      const data = await res.json()
      throw new Error(data.message ?? 'Erreur lors de la sauvegarde.')
    }
    router.push('/admin/coordinateurs')
  } catch (e) {
    errorMsg.value = e.message
  } finally {
    saving.value = false
  }
}

onMounted(fetchData)
</script>

<style scoped>
.coordinateur-form { max-width: 800px; margin: 0 auto; }
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
@media (max-width: 600px) { .form-grid { grid-template-columns: 1fr; } }
</style>
