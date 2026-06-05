<template>
    <div class="trophees-index">
        <div class="page-header">
            <h1 class="page-title">Trophées de la générosité</h1>
            <button class="btn-primary" @click="showForm = true">
                + Attribuer un trophée
            </button>
        </div>

        <div v-if="showForm" class="form-card">
            <h2 class="form-title">Nouveau trophée</h2>
            <div class="form-grid">
                <div class="field">
                    <label>Entreprise *</label>
                    <select v-model="form.entreprise_id" required>
                        <option value="" disabled>
                            Choisir une entreprise
                        </option>
                        <option
                            v-for="e in entreprises"
                            :key="e.id"
                            :value="e.id"
                        >
                            {{ e.nom }}
                        </option>
                    </select>
                </div>
                <div class="field">
                    <label>Année *</label>
                    <input
                        type="number"
                        v-model="form.annee"
                        :min="2000"
                        :max="2100"
                        placeholder="2026"
                        required
                    />
                </div>
                <div class="field" style="grid-column: span 2">
                    <label>Commentaire</label>
                    <input
                        type="text"
                        v-model="form.commentaire"
                        placeholder="Meilleure participation 2026…"
                    />
                </div>
            </div>
            <p v-if="errorMsg" class="error">{{ errorMsg }}</p>
            <div class="form-actions">
                <button class="btn-secondary" @click="showForm = false">
                    Annuler
                </button>
                <button class="btn-primary" @click="submit" :disabled="saving">
                    {{ saving ? "Enregistrement…" : "Attribuer" }}
                </button>
            </div>
        </div>

        <div v-if="loading" class="loading">Chargement...</div>
        <div v-else-if="error" class="error">{{ error }}</div>

        <table v-else class="table">
            <thead>
                <tr>
                    <th>Entreprise</th>
                    <th>Année</th>
                    <th>Commentaire</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="trophee in trophees" :key="trophee.id">
                    <td>{{ trophee.entreprise?.nom }}</td>
                    <td>
                        <span class="badge-annee">{{ trophee.annee }}</span>
                    </td>
                    <td>{{ trophee.commentaire ?? "—" }}</td>
                    <td>
                        <button class="btn-delete" @click="supprimer(trophee)">
                            Supprimer
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useAuthStore } from "../../../stores/auth";

const auth = useAuthStore();
const trophees = ref([]);
const entreprises = ref([]);
const loading = ref(true);
const error = ref(null);
const errorMsg = ref("");
const showForm = ref(false);
const saving = ref(false);

const form = ref({
    entreprise_id: "",
    annee: new Date().getFullYear(),
    commentaire: "",
});

function headers() {
    return {
        Authorization: `Bearer ${auth.token}`,
        Accept: "application/json",
        "Content-Type": "application/json",
    };
}

async function fetchData() {
    try {
        const [tropheesRes, entreprisesRes] = await Promise.all([
            fetch("/api/admin/trophees", { headers: headers() }),
            fetch("/api/admin/entreprises", { headers: headers() }),
        ]);
        trophees.value = await tropheesRes.json();
        entreprises.value = await entreprisesRes.json();
    } catch (e) {
        error.value = e.message;
    } finally {
        loading.value = false;
    }
}

async function submit() {
    saving.value = true;
    errorMsg.value = "";
    try {
        const res = await fetch("/api/admin/trophees", {
            method: "POST",
            headers: headers(),
            body: JSON.stringify(form.value),
        });
        if (!res.ok) {
            const data = await res.json();
            throw new Error(data.message ?? "Erreur.");
        }
        const newTrophee = await res.json();
        trophees.value.unshift(newTrophee);
        showForm.value = false;
        form.value = {
            entreprise_id: "",
            annee: new Date().getFullYear(),
            commentaire: "",
        };
    } catch (e) {
        errorMsg.value = e.message;
    } finally {
        saving.value = false;
    }
}

async function supprimer(trophee) {
    if (
        !confirm(
            `Supprimer le trophée ${trophee.annee} de ${trophee.entreprise?.nom} ?`,
        )
    )
        return;
    await fetch(`/api/admin/trophees/${trophee.id}`, {
        method: "DELETE",
        headers: headers(),
    });
    trophees.value = trophees.value.filter((t) => t.id !== trophee.id);
}

onMounted(fetchData);
</script>

<style scoped>
.trophees-index {
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

.form-card {
    background: white;
    border-radius: 1rem;
    padding: 1.75rem 2rem;
    margin-bottom: 1.75rem;
}

.form-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #0f172a;
    margin: 0 0 1.25rem;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1.25rem;
    margin-bottom: 1.25rem;
}

.field {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}
.field label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #475569;
}
.field input,
.field select {
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    padding: 0.6rem 0.75rem;
    font-size: 0.95rem;
    color: #0f172a;
    background: white;
}
.field input:focus,
.field select:focus {
    outline: none;
    border-color: #38bdf8;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
}

.btn-primary {
    background: #0f172a;
    color: white;
    border: none;
    border-radius: 0.5rem;
    padding: 0.6rem 1.25rem;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: background 0.15s;
}
.btn-primary:hover:not(:disabled) {
    background: #1e293b;
}
.btn-primary:disabled {
    opacity: 0.5;
    cursor: default;
}

.btn-secondary {
    background: #f1f5f9;
    color: #334155;
    border-radius: 0.5rem;
    padding: 0.6rem 1.25rem;
    font-weight: 600;
    font-size: 0.9rem;
    border: none;
    cursor: pointer;
}
.btn-secondary:hover {
    background: #e2e8f0;
}

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
.table tr:last-child td {
    border-bottom: none;
}

.badge-annee {
    display: inline-block;
    padding: 0.25rem 0.65rem;
    border-radius: 9999px;
    font-size: 0.8rem;
    font-weight: 700;
    background: #fef9c3;
    color: #854d0e;
}

.btn-delete {
    background: none;
    border: none;
    color: #ef4444;
    font-size: 0.875rem;
    cursor: pointer;
    font-weight: 500;
    padding: 0;
}
.btn-delete:hover {
    text-decoration: underline;
}

.loading,
.error {
    color: #94a3b8;
    padding: 2rem 0;
}
</style>
