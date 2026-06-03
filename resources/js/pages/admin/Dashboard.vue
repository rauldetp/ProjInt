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
                    <p class="stat-label">Inscriptions aux collectes</p>
                </div>
                <div class="stat-card">
                    <p class="stat-value">{{ stats.collectes_actives ?? 0 }}</p>
                    <p class="stat-label">Campagnes en cours</p>
                </div>
                <div class="stat-card stat-card--alert" v-bind:class="{ 'stat-card--alert-active': (stats.collectes_en_attente ?? 0) > 0 }">
                    <p class="stat-value" :style="(stats.collectes_en_attente ?? 0) > 0 ? { color: '#e60f48' } : {}">
                        {{ stats.collectes_en_attente ?? 0 }}
                    </p>
                    <p class="stat-label">En attente de validation</p>
                </div>
            </div>

            <!-- Chart -->
            <div class="chart-card">
                <p class="chart-title">Évolution des inscriptions aux collectes</p>
                <div class="chart-placeholder">
                    <svg viewBox="0 0 600 100" class="chart-svg">
                        <polyline
                            fill="none"
                            stroke="#e60f48"
                            stroke-width="2.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            :points="chartData.points"
                        />
                    </svg>
                    <div class="chart-labels">
                        <span v-for="label in chartData.labels" :key="label">{{ label }}</span>
                    </div>
                </div>
                <div class="chart-toggles">
                    <button
                        v-for="m in [{key:'jours',label:'Jours'},{key:'mois',label:'Mois'},{key:'annees',label:'Années'}]"
                        :key="m.key"
                        :class="['toggle-btn', chartMode === m.key && 'active']"
                        @click="chartMode = m.key"
                    >{{ m.label }}</button>
                </div>
            </div>

            <!-- Collectes en attente de validation -->
            <template v-if="collectesEnAttente.length > 0">
                <div class="section-header">
                    <h2 class="section-title">
                        Collectes en attente de validation
                        <span class="badge-count">{{ collectesEnAttente.length }}</span>
                    </h2>
                </div>
                <div class="cards-grid" style="margin-bottom: 2.5rem">
                    <div
                        v-for="collecte in collectesEnAttente"
                        :key="'att-' + collecte.id"
                        class="collecte-card collecte-card--pending"
                    >
                        <div class="card-top">
                            <div>
                                <p class="card-company">{{ collecte.entreprise?.nom }}</p>
                                <p class="card-subtitle">{{ collecte.titre || '—' }}</p>
                                <p class="card-date">{{ formatDate(collecte.date_debut) }}</p>
                            </div>
                            <span class="badge badge-aconfirmer">À confirmer</span>
                        </div>
                        <p class="card-lieu">{{ collecte.lieu ?? "Lieu à définir" }}</p>
                        <div class="card-actions">
                            <button
                                class="btn-valider"
                                :disabled="validating === collecte.id"
                                @click="validerCollecte(collecte)"
                            >
                                {{ validating === collecte.id ? "…" : "✓ Valider" }}
                            </button>
                            <RouterLink :to="`/admin/collectes/${collecte.id}/edit`" class="btn-edit">
                                Modifier
                            </RouterLink>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Dernières campagnes -->
            <div class="section-header">
                <h2 class="section-title">Dernières campagnes</h2>
                <RouterLink to="/admin/collectes" class="section-link">↗</RouterLink>
            </div>
            <div class="cards-grid">
                <div
                    v-for="collecte in collectesRecentes"
                    :key="collecte.id"
                    class="collecte-card"
                >
                    <div class="card-top">
                        <div>
                            <p class="card-company">{{ collecte.entreprise?.nom }}</p>
                            <p class="card-subtitle">{{ collecte.titre || '—' }}</p>
                            <p class="card-date">{{ formatDate(collecte.date_debut) }}</p>
                        </div>
                        <button class="card-menu">···</button>
                    </div>
                    <span :class="['badge', badgeClass(collecte)]">{{ badgeLabel(collecte) }}</span>
                    <p class="card-inscrits">👥 {{ collecte.nb_inscrits_estime ?? 0 }} inscrit(s)</p>
                    <p class="card-lieu">{{ collecte.lieu ?? "—" }}</p>
                </div>
            </div>

            <!-- Dernières inscriptions -->
            <div class="section-header" style="margin-top: 2.5rem">
                <h2 class="section-title">Dernières inscriptions</h2>
                <RouterLink to="/admin/collectes" class="section-link">↗</RouterLink>
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
                        <span v-else class="insc-logo-placeholder">{{ collecte.entreprise?.nom?.charAt(0) }}</span>
                    </div>
                    <div class="insc-info">
                        <p class="card-company">{{ collecte.entreprise?.nom }}</p>
                        <p class="card-subtitle">{{ collecte.titre || '—' }}</p>
                        <p class="card-date">{{ formatDate(collecte.date_debut) }}</p>
                        <span :class="['badge', badgeClass(collecte)]">{{ badgeLabel(collecte) }}</span>
                        <p class="card-lieu" style="margin-top: 0.5rem">{{ collecte.lieu ?? "—" }}</p>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useAuthStore } from "../../stores/auth";

const auth     = useAuthStore();
const stats    = ref({});
const collectes = ref([]);
const loading  = ref(true);
const error    = ref(null);
const chartMode = ref("jours");
const validating = ref(null);

/* ── Computed lists ─────────────────────────────────────────── */
const collectesEnAttente = computed(() =>
    collectes.value.filter(c => c.statut === "en_attente")
);
const collectesRecentes = computed(() =>
    collectes.value.filter(c => c.statut !== "en_attente").slice(0, 6)
);
const collectesInscrits = computed(() =>
    collectes.value
        .filter(c => c.statut !== "en_attente" && (c.nb_inscrits_estime ?? 0) > 0)
        .slice(0, 6)
);

/* ── Chart ────────────────────────────────────────────────────── */
const chartData = computed(() => {
    const W = 600, H = 100;
    const src = collectes.value.filter(c => c.statut !== "en_attente");
    if (!src.length) return { points: `0,${H * 0.9}`, labels: ["—"] };

    const groups = {};
    [...src]
        .sort((a, b) => new Date(a.date_debut) - new Date(b.date_debut))
        .forEach(c => {
            const d = new Date(c.date_debut);
            let key;
            if (chartMode.value === "jours") {
                key = d.toLocaleDateString("fr-FR", { day: "2-digit", month: "2-digit" });
            } else if (chartMode.value === "mois") {
                key = d.toLocaleDateString("fr-FR", { month: "short", year: "2-digit" });
            } else {
                key = d.getFullYear().toString();
            }
            groups[key] = (groups[key] ?? 0) + (c.nb_inscrits_estime ?? 0);
        });

    const entries = Object.entries(groups);
    const values  = entries.map(([, v]) => v);
    const maxVal  = Math.max(...values, 1);

    const points = entries.map(([, v], i) => {
        const x = entries.length === 1 ? W / 2 : (i / (entries.length - 1)) * W;
        const y = H - (v / maxVal) * H * 0.75 - H * 0.1;
        return `${x.toFixed(1)},${y.toFixed(1)}`;
    }).join(" ");

    const step = Math.ceil(entries.length / 5);
    const labels = entries.filter((_, i) => i % step === 0).map(([k]) => k);
    return { points, labels };
});

/* ── Helpers ─────────────────────────────────────────────────── */
function formatDate(date) {
    if (!date) return "—";
    return new Date(date).toLocaleDateString("fr-FR", {
        day: "2-digit", month: "2-digit", year: "numeric",
    });
}

function badgeLabel(c) {
    if (c.statut === "terminee")   return "Terminée";
    if (c.statut === "en_attente") return "À confirmer";
    if (c.active) return "En cours";
    const today = new Date().toISOString().split("T")[0];
    const debut = c.date_debut ? String(c.date_debut).split("T")[0] : null;
    return debut && debut >= today ? "À venir" : "Terminée";
}

function badgeClass(c) {
    const l = badgeLabel(c);
    if (l === "En cours")    return "badge-encours";
    if (l === "À confirmer") return "badge-aconfirmer";
    if (l === "À venir")     return "badge-avenir";
    return "badge-complete";
}

/* ── Actions ─────────────────────────────────────────────────── */
async function validerCollecte(collecte) {
    validating.value = collecte.id;
    try {
        const res = await fetch(`/api/admin/collectes/${collecte.id}/statut`, {
            method: "PATCH",
            headers: {
                Authorization: `Bearer ${auth.token}`,
                "Content-Type": "application/json",
                Accept: "application/json",
            },
            body: JSON.stringify({ statut: "validee" }),
        });
        if (!res.ok) throw new Error();
        // Mettre à jour localement
        const idx = collectes.value.findIndex(c => c.id === collecte.id);
        if (idx !== -1) {
            collectes.value[idx].statut = "validee";
            collectes.value[idx].active = true;
        }
        stats.value.collectes_en_attente = Math.max((stats.value.collectes_en_attente ?? 1) - 1, 0);
        stats.value.collectes_actives    = (stats.value.collectes_actives ?? 0) + 1;
    } catch {
        // silencieux
    } finally {
        validating.value = null;
    }
}

/* ── Fetch ────────────────────────────────────────────────────── */
async function fetchData() {
    try {
        const headers = {
            Authorization: `Bearer ${auth.token}`,
            Accept: "application/json",
        };
        const [statsRes, collectesRes] = await Promise.all([
            fetch("/api/admin/stats",    { headers }),
            fetch("/api/admin/collectes", { headers }),
        ]);
        if (!statsRes.ok || !collectesRes.ok) throw new Error("Erreur lors du chargement.");
        stats.value    = await statsRes.json();
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
.dashboard { width: 100%; }

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2c4140;
    text-align: center;
    margin: 0 0 2rem;
}

/* Stats */
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
    border: 1px solid transparent;
}
.stat-card--alert-active {
    border-color: #fee2e2;
    background: #fff8f8;
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
.chart-svg { width: 100%; height: 80px; }
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

/* Section headers */
.section-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    justify-content: space-between;
    margin-bottom: 1.25rem;
}
.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c4140;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.6rem;
}
.badge-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #fee2e2;
    color: #991b1b;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.15rem 0.55rem;
    min-width: 22px;
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
.section-link:hover { border-color: #2c4140; color: #2c4140; }

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
    gap: 0.5rem;
}
.collecte-card--pending {
    border: 1.5px solid #fca5a5;
    background: #fff9f9;
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
.card-subtitle {
    font-size: 0.82rem;
    color: #2c4140;
    opacity: 0.7;
    margin: 0.05rem 0 0;
    font-style: italic;
}
.card-date {
    font-size: 0.8rem;
    color: #497371;
    margin: 0.1rem 0 0;
}
.card-inscrits, .card-lieu {
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
.card-actions {
    display: flex;
    gap: 0.6rem;
    margin-top: 0.4rem;
}
.btn-valider {
    flex: 1;
    background: #e60f48;
    color: white;
    border: none;
    border-radius: 9999px;
    padding: 0.4rem 0.9rem;
    font-size: 0.8rem;
    font-weight: 700;
    cursor: pointer;
    transition: opacity 0.15s;
}
.btn-valider:hover { opacity: 0.85; }
.btn-valider:disabled { opacity: 0.5; cursor: not-allowed; }
.btn-edit {
    flex: 1;
    border: 1px solid #e2e8f0;
    border-radius: 9999px;
    padding: 0.4rem 0.9rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: #497371;
    text-decoration: none;
    text-align: center;
    transition: border-color 0.15s;
}
.btn-edit:hover { border-color: #2c4140; color: #2c4140; }

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
    width: 56px; height: 56px;
    background: #f2f4f3;
    border-radius: 0.5rem;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.insc-logo img { width: 100%; height: 100%; object-fit: contain; }
.insc-logo-placeholder { font-weight: 700; font-size: 1.25rem; color: #497371; }
.insc-info { flex: 1; }

/* Badges */
.badge {
    display: inline-block;
    padding: 0.2rem 0.65rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
}
.badge-encours    { background: #d1fae5; color: #065f46; }
.badge-aconfirmer { background: #fee2e2; color: #991b1b; }
.badge-avenir     { background: #fef3c7; color: #92400e; }
.badge-complete   { background: #f2f4f3; color: #497371; }

.loading, .error { color: #497371; padding: 2rem 0; }
</style>
