<template>
    <div class="dashboard">
        <h1 class="page-title">Vue globale</h1>

        <div v-if="loading" class="loading">Chargement...</div>
        <div v-else-if="error" class="error">{{ error }}</div>

        <template v-else>
            <!-- Stats -->
            <div class="stats-row">
                <div class="stat-card">
                    <p class="stat-value">{{ totalInscrits }}</p>
                    <p class="stat-label">
                        Employées ont passé le questionnaire
                    </p>
                </div>
                <div class="stat-card">
                    <p class="stat-value">{{ collectes.length }}</p>
                    <p class="stat-label">Nombre total de collectes</p>
                </div>
            </div>

            <!-- Chart placeholder -->
            <div class="chart-card">
                <p class="chart-title">Nombres d'inscriptions totaux</p>
                <div class="chart-placeholder">
                    <svg viewBox="0 0 600 100" class="chart-svg">
                        <polyline
                            fill="none"
                            stroke="#e60f48"
                            stroke-width="2"
                            points="0,60 60,40 120,55 180,30 240,50 300,20 360,40 420,15 480,35 540,25 600,40"
                        />
                    </svg>
                    <div class="chart-labels">
                        <span>Lun</span><span>Mar</span><span>Mer</span
                        ><span>Jeu</span><span>Ven</span>
                    </div>
                </div>
                <div class="chart-toggles">
                    <button
                        :class="[
                            'toggle-btn',
                            chartMode === 'jours' && 'active',
                        ]"
                        @click="chartMode = 'jours'"
                    >
                        Jours
                    </button>
                    <button
                        :class="[
                            'toggle-btn',
                            chartMode === 'mois' && 'active',
                        ]"
                        @click="chartMode = 'mois'"
                    >
                        Mois
                    </button>
                    <button
                        :class="[
                            'toggle-btn',
                            chartMode === 'annees' && 'active',
                        ]"
                        @click="chartMode = 'annees'"
                    >
                        Années
                    </button>
                </div>
            </div>

            <!-- Campagnes -->
            <div class="section-header">
                <h2 class="section-title">Campagnes de collectes</h2>
                <button class="btn-nouvelle-collecte" @click="router.push(`/entreprise/${auth.entrepriseSlug}/nouvelle-collecte`)">
                    <span class="material-symbols-outlined" style="font-size: 18px">add</span>
                    Nouvelle collecte
                </button>
            </div>

            <div v-if="collectes.length === 0" class="empty">
                Aucune collecte pour votre entreprise.
            </div>

            <div v-else class="cards-grid">
                <div
                    v-for="collecte in collectes"
                    :key="collecte.id"
                    class="collecte-card"
                >
                    <div class="card-top">
                        <div>
                            <p class="card-company">{{ entreprise?.nom }}</p>
                            <p class="card-date">
                                {{ formatDate(collecte.date_debut) }}
                            </p>
                        </div>
                        <button class="card-menu">···</button>
                    </div>
                    <span :class="['badge', badgeClass(collecte)]">{{
                        badgeLabel(collecte)
                    }}</span>
                    <p class="card-inscrits">
                        <span class="material-symbols-outlined" style="font-size:14px;vertical-align:middle">group</span> {{ collecte.nb_inscrits_estime }} inscrit(s)
                    </p>
                    <p class="card-lieu">
                        {{ collecte.lieu ?? "Lieu à définir" }}
                    </p>
                </div>
            </div>
        </template>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useAuthStore } from "../../stores/auth";
import { useRouter } from "vue-router";

const auth = useAuthStore();
const router = useRouter();
const entreprise = ref(null);
const collectes = ref([]);
const loading = ref(true);
const error = ref(null);
const chartMode = ref("jours");

const totalInscrits = computed(() =>
    collectes.value.reduce((sum, c) => sum + (c.nb_inscrits_estime ?? 0), 0),
);

function formatDate(date) {
    return new Date(date).toLocaleDateString("fr-FR", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
    });
}

function badgeLabel(c) {
    if (c.statut === "terminee") return "Complétée";
    if (c.statut === "en_attente") return "À confirmer";
    if (c.active) return "En cours";
    const today = new Date().toISOString().split("T")[0];
    return c.date_debut >= today ? "À venir" : "Complétée";
}

function badgeClass(c) {
    const label = badgeLabel(c);
    if (label === "En cours") return "badge-encours";
    if (label === "À confirmer") return "badge-aconfirmer";
    if (label === "À venir") return "badge-avenir";
    return "badge-complete";
}

onMounted(async () => {
    try {
        const res = await fetch("/api/coordinateur/collectes", {
            headers: {
                Authorization: `Bearer ${auth.token}`,
                Accept: "application/json",
            },
        });
        if (!res.ok) throw new Error("Erreur lors du chargement.");
        const data = await res.json();
        entreprise.value = data.entreprise;
        collectes.value = data.collectes;
    } catch (e) {
        error.value = e.message;
    } finally {
        loading.value = false;
    }
});
</script>

<style scoped>
.dashboard {
    width: 100%;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2c4140;
    text-align: center;
    margin: 0 0 2rem;
}

.stats-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.25rem;
    margin-bottom: 2rem;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
}
.stat-card {
    background: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(44, 65, 64, 0.06);
}
.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #2c4140;
    margin: 0 0 0.25rem;
}
.stat-label {
    font-size: 0.8rem;
    color: #497371;
    margin: 0;
}

.chart-card {
    background: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(44, 65, 64, 0.06);
    margin-bottom: 2.5rem;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
}
.chart-title {
    font-size: 1rem;
    font-weight: 600;
    color: #2c4140;
    text-align: center;
    margin: 0 0 1rem;
}
.chart-placeholder {
    background: #f9fafb;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1rem;
}
.chart-svg {
    width: 100%;
    height: 80px;
}
.chart-labels {
    display: flex;
    justify-content: space-between;
    font-size: 0.75rem;
    color: #497371;
    margin-top: 0.5rem;
}
.chart-toggles {
    display: flex;
    justify-content: center;
    gap: 0.75rem;
}
.toggle-btn {
    border: 1px solid #e2e8f0;
    border-radius: 9999px;
    padding: 0.35rem 1.1rem;
    font-size: 0.875rem;
    background: white;
    color: #497371;
    cursor: pointer;
    transition: all 0.15s;
}
.toggle-btn.active {
    background: #2c4140;
    color: white;
    border-color: #2c4140;
}

.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.25rem;
}
.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c4140;
    margin: 0;
}
.btn-nouvelle-collecte {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: #2c4140;
    color: white;
    border: none;
    border-radius: 9999px;
    padding: 0.5rem 1.1rem;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    font-family: inherit;
    transition: opacity 0.15s;
    white-space: nowrap;
}
.btn-nouvelle-collecte:hover { opacity: 0.8; }

.cards-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.25rem;
}
.collecte-card {
    background: white;
    border-radius: 0.75rem;
    padding: 1.25rem;
    box-shadow: 0 2px 8px rgba(44, 65, 64, 0.06);
    display: flex;
    flex-direction: column;
    gap: 0.6rem;
}
.card-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}
.card-company {
    font-weight: 700;
    font-size: 1rem;
    color: #2c4140;
    margin: 0;
}
.card-date {
    font-size: 0.8rem;
    color: #497371;
    margin: 0.1rem 0 0;
}
.card-inscrits,
.card-lieu {
    font-size: 0.85rem;
    color: #497371;
    margin: 0;
}
.card-menu {
    background: none;
    border: none;
    color: #497371;
    font-size: 1.25rem;
    cursor: pointer;
    padding: 0;
    line-height: 1;
    letter-spacing: 2px;
}

.badge {
    display: inline-block;
    padding: 0.2rem 0.65rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
}
.badge-encours {
    background: #d1fae5;
    color: #065f46;
}
.badge-aconfirmer {
    background: #fee2e2;
    color: #991b1b;
}
.badge-avenir {
    background: #fef3c7;
    color: #92400e;
}
.badge-complete {
    background: #f2f4f3;
    color: #497371;
}

.empty,
.loading,
.error {
    color: #497371;
    padding: 2rem 0;
}
</style>
