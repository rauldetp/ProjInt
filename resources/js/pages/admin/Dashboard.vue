<template>
    <div class="dashboard">
        <h1 class="page-title">Vue globale</h1>

        <div v-if="loading" class="loading">Chargement...</div>
        <div v-else-if="error" class="error">{{ error }}</div>

        <template v-else>
            <!-- Stats -->
            <div class="max-w-4xl mx-auto px-0 md:px-8 grid grid-cols-3 gap-2 md:gap-4 mb-10">
                <div class="rounded-xl px-5 py-4 bg-light-grey">
                    <h2 class="font-bold mb-1">{{ stats.total_inscrits ?? 0 }}</h2>
                    <p class="captions">Inscriptions aux collectes</p>
                </div>
                <div class="rounded-xl px-5 py-4 bg-light-grey">
                    <h2 class="font-bold mb-1">{{ stats.collectes_actives ?? 0 }}</h2>
                    <p class="captions">Campagnes en cours</p>
                </div>
                <div class="rounded-xl px-5 py-4 bg-light-grey">
                    <h2 class="font-bold mb-1" :style="(stats.collectes_en_attente ?? 0) > 0 ? { color: 'var(--color-default-red)' } : null">
                        {{ stats.collectes_en_attente ?? 0 }}
                    </h2>
                    <p class="captions">En attente de validation</p>
                </div>
            </div>

            <!-- Chart -->
            <div class="chart-card shadow-light">
                <h3 class="chart-title">Évolution des inscriptions aux collectes</h3>
                <div class="chart-placeholder">
                    <svg viewBox="0 0 600 100" class="chart-svg">
                        <polyline
                            fill="none"
                            stroke="var(--color-default-red)"
                            stroke-width="2.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            :points="chartData.points"
                        />
                    </svg>
                    <div class="chart-labels">
                        <span v-for="label in chartData.labels" :key="label" class="captions">{{ label }}</span>
                    </div>
                </div>
                <div class="chart-toggles">
                    <button
                        v-for="m in [{key:'jours',label:'Jours'},{key:'mois',label:'Mois'},{key:'annees',label:'Années'}]"
                        :key="m.key"
                        class="btn btn-outlined-blue"
                        :class="{ 'is-selected': chartMode === m.key }"
                        @click="chartMode = m.key"
                    >{{ m.label }}</button>
                </div>
            </div>

            <!-- Zone grise pleine largeur : tout à partir de « À valider » -->
            <div class="grey-zone">
            <div class="grey-zone-inner">

            <!-- Collectes en attente de validation -->
            <div class="section-header">
                <h2 class="section-title">
                    À valider
                    <span v-if="collectesEnAttente.length > 0" class="badge-count">{{ collectesEnAttente.length }}</span>
                </h2>
            </div>

            <div v-if="collectesEnAttente.length === 0" class="attente-empty shadow-light">
                <span class="material-symbols-outlined" style="font-size: 32px; color: #c0cac9">task_alt</span>
                <p class="captions">Aucune collecte en attente de validation.</p>
            </div>

            <div v-else class="attente-list shadow-light">
                <div
                    v-for="collecte in collectesEnAttente"
                    :key="'att-' + collecte.id"
                    class="attente-row"
                >
                    <div class="attente-logo">
                        <img v-if="collecte.entreprise?.logo" :src="collecte.entreprise.logo" :alt="collecte.entreprise?.nom" />
                        <span v-else class="attente-logo-placeholder">{{ collecte.entreprise?.nom?.charAt(0) }}</span>
                    </div>
                    <div class="attente-info">
                        <p class="attente-company">{{ collecte.entreprise?.nom }}</p>
                        <p class="captions attente-meta">{{ collecte.titre || 'Collecte sans titre' }} · {{ formatDate(collecte.date_debut) }}{{ collecte.lieu ? ' · ' + collecte.lieu : '' }}</p>
                    </div>
                    <div class="attente-actions">
                        <RouterLink :to="`/admin/collectes/${collecte.id}/edit`" class="btn-edit">
                            Modifier
                        </RouterLink>
                        <button
                            class="btn-valider"
                            :disabled="validating === collecte.id"
                            @click="validerCollecte(collecte)"
                        >
                            <span class="material-symbols-outlined" style="font-size: 16px">check</span>
                            {{ validating === collecte.id ? "…" : "Valider" }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Dernières campagnes -->
            <div class="section-header">
                <h2 class="section-title">Dernières campagnes</h2>
                <RouterLink to="/admin/collectes" class="btn-circle" aria-label="Voir toutes les collectes">
                    <span class="material-symbols-outlined btn-circle-icon">arrow_outward</span>
                </RouterLink>
            </div>
            <div class="cards-grid">
                <div
                    v-for="collecte in collectesRecentes"
                    :key="collecte.id"
                    class="card shadow-light"
                >
                    <div class="card-top">
                        <div>
                            <h3 class="card-company">{{ collecte.entreprise?.nom }}</h3>
                            <p class="captions card-subtitle">{{ collecte.titre || '—' }}</p>
                            <p class="captions card-muted">{{ formatDate(collecte.date_debut) }}</p>
                        </div>
                        <div class="card-menu-wrap">
                            <button class="btn-circle btn-circle-red" aria-label="Options" @click.stop="toggleMenu(collecte.id)">
                                <span class="material-symbols-outlined btn-circle-icon">more_horiz</span>
                            </button>
                            <div v-if="openMenu === collecte.id" class="card-dropdown" @click.stop>
                                <RouterLink :to="`/entreprise/${collecte.entreprise?.slug}/collecte/${collecte.id}`">Voir la page</RouterLink>
                                <RouterLink :to="`/admin/collectes/${collecte.id}/edit`">Modifier</RouterLink>
                                <button @click="copierLien(collecte)">{{ copied === collecte.id ? 'Lien copié !' : 'Copier le lien' }}</button>
                                <button class="danger" @click="supprimerCollecte(collecte)">Supprimer</button>
                            </div>
                        </div>
                    </div>
                    <span class="captions badge" :class="badgeClass(collecte)">{{ badgeLabel(collecte) }}</span>
                    <p class="captions card-muted"><span class="material-symbols-outlined card-icon">group</span> {{ collecte.nb_inscrits_estime ?? 0 }} inscrit(s)</p>
                    <p class="captions card-muted">{{ collecte.lieu ?? "—" }}</p>
                </div>
            </div>

            <!-- Dernières inscriptions -->
            <div class="section-header">
                <h2 class="section-title">Dernières inscriptions</h2>
                <RouterLink to="/admin/collectes" class="btn-circle" aria-label="Voir toutes les collectes">
                    <span class="material-symbols-outlined btn-circle-icon">arrow_outward</span>
                </RouterLink>
            </div>
            <div class="cards-grid">
                <div
                    v-for="collecte in collectesInscrits"
                    :key="'ins-' + collecte.id"
                    class="insc-card shadow-light"
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
                        <h3 class="card-company">{{ collecte.entreprise?.nom }}</h3>
                        <p class="captions card-subtitle">{{ collecte.titre || '—' }}</p>
                        <p class="captions card-muted">{{ formatDate(collecte.date_debut) }}</p>
                        <span class="captions badge" :class="badgeClass(collecte)">{{ badgeLabel(collecte) }}</span>
                        <p class="captions card-muted" style="margin-top: 0.5rem">{{ collecte.lieu ?? "—" }}</p>
                    </div>
                </div>
            </div>
            </div>
            </div>
        </template>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import { useAuthStore } from "../../stores/auth";

const auth     = useAuthStore();
const stats    = ref({});
const collectes = ref([]);
const loading  = ref(true);
const error    = ref(null);
const chartMode = ref("jours");
const validating = ref(null);
const openMenu = ref(null);
const copied = ref(null);

function toggleMenu(id) {
    openMenu.value = openMenu.value === id ? null : id;
}
function closeMenu() {
    openMenu.value = null;
}
function copierLien(c) {
    const url = `${window.location.origin}/entreprise/${c.entreprise?.slug}/collecte/${c.id}`;
    navigator.clipboard?.writeText(url);
    copied.value = c.id;
    setTimeout(() => { copied.value = null; closeMenu(); }, 1200);
}
async function supprimerCollecte(c) {
    closeMenu();
    if (!confirm(`Supprimer la collecte de ${c.entreprise?.nom} ?`)) return;
    try {
        await fetch(`/api/admin/collectes/${c.id}`, {
            method: "DELETE",
            headers: { Authorization: `Bearer ${auth.token}`, Accept: "application/json" },
        });
        collectes.value = collectes.value.filter(x => x.id !== c.id);
    } catch {}
}

/* ── Computed lists ─────────────────────────────────────────── */
const collectesEnAttente = computed(() =>
    collectes.value.filter(c => c.statut === "en_attente")
);
const collectesRecentes = computed(() =>
    collectes.value.filter(c => c.statut !== "en_attente").slice(0, 6)
);
// Les plus récemment créées (par id décroissant) — une nouvelle entreprise
// apparaît immédiatement, quel que soit son nombre d'inscrits.
const collectesInscrits = computed(() =>
    [...collectes.value]
        .sort((a, b) => (b.id ?? 0) - (a.id ?? 0))
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
    if (c.statut === "terminee")   return "Complétée";
    if (c.statut === "en_attente") return "À confirmer";
    if (c.active) return "En cours";
    const today = new Date().toISOString().split("T")[0];
    const debut = c.date_debut ? String(c.date_debut).split("T")[0] : null;
    return debut && debut >= today ? "À venir" : "Complétée";
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

onMounted(() => {
    document.addEventListener("click", closeMenu);
    fetchData();
});
onBeforeUnmount(() => {
    document.removeEventListener("click", closeMenu);
});
</script>

<style scoped>
.dashboard { width: 100%; }

.page-title {
    color: var(--default-titles);
    text-align: center;
    margin: 0 0 2rem;
}

/* Chart */
.chart-card {
    background: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    margin-bottom: 2.5rem;
}
.chart-title {
    color: var(--default-titles);
    text-align: center;
    margin: 0 0 1rem;
}
.chart-placeholder {
    background: var(--light-grey);
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1rem;
}
.chart-svg { width: 100%; height: 80px; }
.chart-labels {
    display: flex;
    justify-content: space-between;
    color: var(--default-text);
    margin-top: 0.5rem;
}
.chart-toggles {
    display: flex;
    justify-content: center;
    gap: 0.75rem;
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
    color: var(--default-titles);
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

/* Zone grise pleine largeur : déborde du conteneur centré (full-bleed) */
.grey-zone {
    width: 100vw;
    margin-left: 50%;
    transform: translateX(-50%);
    background: var(--light-grey);
    margin-top: 2.5rem;
    /* annule le padding-bas du main-content pour rejoindre le footer */
    margin-bottom: -2.5rem;
    padding: 2.5rem 0 3rem;
}
.grey-zone-inner {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 2rem;
}

/* Cards grid */
.cards-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.25rem;
}
/* ── Attente list ─────────────────────────────────────────── */
.attente-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 2.5rem 1rem;
    background: white;
    border-radius: 0.75rem;
    margin-bottom: 2.5rem;
    color: #8fa8a6;
}
.attente-list {
    display: flex;
    flex-direction: column;
    gap: 0;
    background: white;
    border-radius: 0.75rem;
    overflow: hidden;
    margin-bottom: 2.5rem;
}
.attente-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--light-grey);
    transition: background 0.12s;
}
.attente-row:last-child { border-bottom: none; }
.attente-row:hover { background: #fafcfc; }
.attente-logo {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
    background: var(--light-grey);
    display: flex;
    align-items: center;
    justify-content: center;
}
.attente-logo img { width: 100%; height: 100%; object-fit: contain; }
.attente-logo-placeholder {
    font-size: 1rem;
    font-weight: 700;
    color: var(--default-text);
}
.attente-info {
    flex: 1;
    min-width: 0;
}
.attente-company {
    font-weight: 700;
    color: var(--default-titles);
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.attente-meta {
    color: #8fa8a6;
    margin: 0.15rem 0 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.attente-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-shrink: 0;
}
.btn-valider {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    background: var(--color-default-red);
    color: white;
    border: none;
    border-radius: 9999px;
    padding: 0.45rem 1rem;
    font-size: 0.82rem;
    font-weight: 700;
    cursor: pointer;
    transition: opacity 0.15s;
    font-family: inherit;
}
.btn-valider:hover { opacity: 0.85; }
.btn-valider:disabled { opacity: 0.5; cursor: not-allowed; }
.btn-edit {
    border: 1px solid #e2e8f0;
    border-radius: 9999px;
    padding: 0.45rem 1rem;
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--default-text);
    text-decoration: none;
    transition: border-color 0.15s, color 0.15s;
    white-space: nowrap;
}
.btn-edit:hover { border-color: var(--default-titles); color: var(--default-titles); }
.card-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}
.card-company {
    color: var(--default-titles);
    margin: 0;
}
.card-subtitle {
    color: var(--default-titles);
    opacity: 0.7;
    margin: 0.05rem 0 0;
    font-style: italic;
}
.card-muted {
    color: var(--default-text);
    margin: 0.1rem 0 0;
}
.card-icon {
    font-size: 14px;
    vertical-align: middle;
}

/* Inscriptions cards : logo pleine hauteur à gauche (cf. maquette) */
.insc-card {
    background: white;
    border-radius: 0.9rem;
    display: flex;
    align-items: stretch;
    overflow: hidden;
}
.insc-logo {
    width: 110px;
    flex-shrink: 0;
    background: var(--light-grey);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}
.insc-logo img { width: 100%; height: 100%; object-fit: contain; }
.insc-logo-placeholder { font-weight: 700; color: var(--default-text); }
.insc-info {
    flex: 1;
    min-width: 0;
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

/* Badge : couleurs dans app.css, alignement spécifique à la carte */
.badge {
    align-self: flex-start;
}

.loading, .error { color: var(--default-text); padding: 2rem 0; }

@media (max-width: 768px) {
    .cards-grid {
        grid-template-columns: 1fr;
    }
    .grey-zone {
        padding: 1.5rem 0 2rem;
    }
    .grey-zone-inner {
        padding: 0 1rem;
    }
}
</style>
