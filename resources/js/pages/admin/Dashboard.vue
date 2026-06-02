<template>
    <div class="dashboard">
        <h1 class="page-title">Vue globale</h1>

        <div v-if="loading" class="loading">Chargement...</div>
        <div v-else-if="error" class="error">{{ error }}</div>

        <template v-else>
            <!-- Stats -->
            <div class="stats-row">
                <div class="stat-card">
                    <p class="stat-value">{{ stats.total_inscrits ?? 0 }}</p>
                    <p class="stat-label">
                        Employées ont passé le questionnaire
                    </p>
                </div>
                <div class="stat-card">
                    <p class="stat-value">{{ collectes.length }}</p>
                    <p class="stat-label">Nombre total de collectes</p>
                </div>
                <div class="stat-card">
                    <p class="stat-value">{{ stats.collectes_actives ?? 0 }}</p>
                    <p class="stat-label">Campagnes de collectes en cours</p>
                </div>
            </div>

            <!-- Chart placeholder -->
            <div class="chart-card">
                <p class="chart-title">
                    Évolution des inscriptions aux collectes
                </p>
                <div class="chart-placeholder">
                    <svg viewBox="0 0 600 100" class="chart-svg">
                        <polyline
                            fill="none"
                            stroke="#e60f48"
                            stroke-width="2"
                            points="0,80 60,60 120,70 180,40 240,55 300,30 360,50 420,25 480,45 540,35 600,50"
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

            <!-- Dernières campagnes -->
            <div class="section-header">
                <h2 class="section-title">Dernières campagnes</h2>
                <RouterLink to="/admin/collectes" class="section-link"
                    >↗</RouterLink
                >
            </div>
            <div class="cards-grid">
                <div
                    v-for="collecte in collectesRecentes"
                    :key="collecte.id"
                    class="collecte-card"
                >
                    <div class="card-top">
                        <div>
                            <p class="card-company">
                                {{ collecte.entreprise?.nom }}
                            </p>
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
                        👥 {{ collecte.nb_inscrits_estime }} inscrit(s)
                    </p>
                    <p class="card-lieu">{{ collecte.lieu ?? "—" }}</p>
                </div>
            </div>

            <!-- Dernières inscriptions -->
            <div class="section-header" style="margin-top: 2.5rem">
                <h2 class="section-title">Dernières inscriptions</h2>
                <RouterLink to="/admin/collectes" class="section-link"
                    >↗</RouterLink
                >
            </div>
            <div class="cards-grid">
                <div
                    v-for="collecte in collectesInscrits"
                    :key="'ins-' + collecte.id"
                    class="insc-card"
                >
                    <div class="insc-logo">
                        <img
                            v-if="collecte.entreprise?.logo"
                            :src="collecte.entreprise.logo"
                            :alt="collecte.entreprise.nom"
                        />
                        <span v-else class="insc-logo-placeholder">{{
                            collecte.entreprise?.nom?.charAt(0)
                        }}</span>
                    </div>
                    <div class="insc-info">
                        <p class="card-company">
                            {{ collecte.entreprise?.nom }}
                        </p>
                        <p class="card-date">
                            {{ formatDate(collecte.date_debut) }}
                        </p>
                        <span :class="['badge', badgeClass(collecte)]">{{
                            badgeLabel(collecte)
                        }}</span>
                        <p class="card-lieu" style="margin-top: 0.5rem">
                            {{ collecte.lieu ?? "—" }}
                        </p>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useAuthStore } from "../../stores/auth";

const auth = useAuthStore();
const stats = ref({});
const collectes = ref([]);
const loading = ref(true);
const error = ref(null);
const chartMode = ref("jours");

const collectesRecentes = computed(() => collectes.value.slice(0, 6));
const collectesInscrits = computed(() => collectes.value.slice(0, 6));

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

async function fetchData() {
    try {
        const headers = {
            Authorization: `Bearer ${auth.token}`,
            Accept: "application/json",
        };
        const [statsRes, collectesRes] = await Promise.all([
            fetch("/api/admin/stats", { headers }),
            fetch("/api/admin/collectes", { headers }),
        ]);
        if (!statsRes.ok || !collectesRes.ok)
            throw new Error("Erreur lors du chargement.");
        stats.value = await statsRes.json();
        collectes.value = await collectesRes.json();
    } catch (e) {
        error.value = e.message;
    } finally {
        loading.value = false;
    }
}

onMounted(fetchData);
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
    grid-template-columns: repeat(3, 1fr);
    gap: 1.25rem;
    margin-bottom: 2rem;
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

/* Chart */
.chart-card {
    background: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(44, 65, 64, 0.06);
    margin-bottom: 2.5rem;
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

/* Sections */
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
.section-link {
    width: 32px;
    height: 32px;
    border: 1px solid #e2e8f0;
    border-radius: 9999px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #497371;
    text-decoration: none;
    font-size: 0.9rem;
    transition: border-color 0.15s;
}
.section-link:hover {
    border-color: #2c4140;
    color: #2c4140;
}

/* Cards grid */
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

/* Inscriptions cards */
.insc-card {
    background: white;
    border-radius: 0.75rem;
    padding: 1.25rem;
    box-shadow: 0 2px 8px rgba(44, 65, 64, 0.06);
    display: flex;
    gap: 1rem;
    align-items: flex-start;
}
.insc-logo {
    width: 56px;
    height: 56px;
    background: #f2f4f3;
    border-radius: 0.5rem;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.insc-logo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}
.insc-logo-placeholder {
    font-weight: 700;
    font-size: 1.25rem;
    color: #497371;
}
.insc-info {
    flex: 1;
}

/* Badges */
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

.loading,
.error {
    color: #497371;
    padding: 2rem 0;
}
</style>
