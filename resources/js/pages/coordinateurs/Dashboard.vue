<template>
    <div class="dashboard">
        <h1 class="page-title">Vue globale</h1>

        <div v-if="loading" class="state">Chargement...</div>
        <div v-else-if="error" class="state">{{ error }}</div>

        <template v-else>
            <!-- Stats -->
            <div class="stats-row">
                <div class="stat-card shadow-light">
                    <h2 class="stat-value">{{ totalInscrits }}</h2>
                    <p class="captions stat-label">
                        Employées ont passé le questionnaire
                    </p>
                </div>
                <div class="stat-card shadow-light">
                    <h2 class="stat-value">{{ collectes.length }}</h2>
                    <p class="captions stat-label">Nombre total de collectes</p>
                </div>
            </div>

            <!-- Chart placeholder -->
            <div class="chart-card shadow-light">
                <h3 class="chart-title">Nombres d'inscriptions totaux</h3>
                <div class="chart-placeholder">
                    <svg viewBox="0 0 600 100" class="chart-svg">
                        <polyline
                            fill="none"
                            stroke="var(--color-default-red)"
                            stroke-width="2"
                            points="0,60 60,40 120,55 180,30 240,50 300,20 360,40 420,15 480,35 540,25 600,40"
                        />
                    </svg>
                    <div class="chart-labels">
                        <span class="captions">Lun</span
                        ><span class="captions">Mar</span
                        ><span class="captions">Mer</span
                        ><span class="captions">Jeu</span
                        ><span class="captions">Ven</span>
                    </div>
                </div>
                <div class="chart-toggles">
                    <button
                        class="btn btn-outlined-blue"
                        :class="{ 'is-selected': chartMode === 'jours' }"
                        @click="chartMode = 'jours'"
                    >
                        Jours
                    </button>
                    <button
                        class="btn btn-outlined-blue"
                        :class="{ 'is-selected': chartMode === 'mois' }"
                        @click="chartMode = 'mois'"
                    >
                        Mois
                    </button>
                    <button
                        class="btn btn-outlined-blue"
                        :class="{ 'is-selected': chartMode === 'annees' }"
                        @click="chartMode = 'annees'"
                    >
                        Années
                    </button>
                </div>
            </div>

            <!-- Campagnes -->
            <div class="section-header">
                <h2 class="section-title">Campagnes de collectes</h2>
                <button
                    class="btn-circle"
                    title="Nouvelle collecte"
                    aria-label="Nouvelle collecte"
                    @click="router.push(`/entreprise/${auth.entrepriseSlug}/nouvelle-collecte`)"
                >
                    <span class="material-symbols-outlined btn-circle-icon">add</span>
                </button>
            </div>

            <div v-if="collectes.length === 0" class="state">
                Aucune collecte pour votre entreprise.
            </div>

            <div v-else class="cards-grid">
                <div
                    v-for="collecte in collectes"
                    :key="collecte.id"
                    class="card shadow-light card-clickable"
                    @click="goVoir(collecte)"
                >
                    <div class="card-top">
                        <div>
                            <h3 class="card-company">{{ entreprise?.nom }}</h3>
                            <p class="captions card-muted">
                                {{ formatDate(collecte.date_debut) }}
                            </p>
                        </div>
                        <div class="card-menu-wrap">
                            <button
                                class="btn-circle btn-circle-red"
                                aria-label="Options"
                                @click.stop="toggleMenu(collecte.id)"
                            >
                                <span class="material-symbols-outlined btn-circle-icon">more_horiz</span>
                            </button>
                            <div
                                v-if="openMenu === collecte.id"
                                class="card-dropdown"
                                @click.stop
                            >
                                <button @click="goVoir(collecte)">Voir la page</button>
                                <button @click="goModifier(collecte)">Modifier</button>
                                <button @click="copierLien(collecte)">
                                    {{ copied === collecte.id ? "Lien copié !" : "Copier le lien" }}
                                </button>
                                <button class="danger" @click="goAnnuler(collecte)">Annuler</button>
                            </div>
                        </div>
                    </div>
                    <span class="captions badge" :class="badgeClass(collecte)">{{
                        badgeLabel(collecte)
                    }}</span>
                    <p class="captions card-muted">
                        <span class="material-symbols-outlined card-icon">group</span>
                        {{ collecte.nb_inscrits_estime }} inscrit(s)
                    </p>
                    <p class="captions card-muted">
                        {{ collecte.lieu ?? "Lieu à définir" }}
                    </p>
                </div>
            </div>
        </template>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import { useAuthStore } from "../../stores/auth";
import { useRouter } from "vue-router";

const auth = useAuthStore();
const router = useRouter();
const entreprise = ref(null);
const collectes = ref([]);
const loading = ref(true);
const error = ref(null);
const chartMode = ref("jours");
const openMenu = ref(null);
const copied = ref(null);

function toggleMenu(id) {
    openMenu.value = openMenu.value === id ? null : id;
}

function copierLien(collecte) {
    const url = `${window.location.origin}/entreprise/${auth.entrepriseSlug}`;
    navigator.clipboard?.writeText(url);
    copied.value = collecte.id;
    setTimeout(() => { copied.value = null; closeMenu(); }, 1200);
}

function closeMenu() {
    openMenu.value = null;
}

function goVoir() {
    router.push(`/entreprise/${auth.entrepriseSlug}`);
}

function goModifier(collecte) {
    closeMenu();
    router.push(`/entreprise/${auth.entrepriseSlug}/nouvelle-collecte?edit=${collecte.id}`);
}

async function goAnnuler(collecte) {
    closeMenu();
    if (!confirm("Confirmer l'annulation de cette collecte ?")) return;
    try {
        await fetch(`/api/coordinateur/collectes/${collecte.id}/annuler`, {
            method: "POST",
            headers: {
                Authorization: `Bearer ${auth.token}`,
                Accept: "application/json",
            },
        });
        collectes.value = collectes.value.filter(c => c.id !== collecte.id);
    } catch (e) {
        alert("Erreur lors de l'annulation.");
    }
}

onBeforeUnmount(() => {
    document.removeEventListener("click", closeMenu);
});

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
    document.addEventListener("click", closeMenu);
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
    color: var(--default-titles);
    text-align: center;
    margin: 0 0 2rem;
}

/* ── Stats ─────────────────────────────────────────────── */
.stats-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.25rem;
    margin: 0 auto 2rem;
    max-width: 700px;
}
.stat-card {
    background: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
}
.stat-value {
    color: var(--default-titles);
    margin: 0 0 0.25rem;
}
.stat-label {
    color: var(--default-text);
    margin: 0;
}

/* ── Chart ─────────────────────────────────────────────── */
.chart-card {
    background: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    margin: 0 auto 2.5rem;
    max-width: 700px;
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
.chart-svg {
    width: 100%;
    height: 80px;
}
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

/* ── Section header ────────────────────────────────────── */
.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.25rem;
}
.section-title {
    color: var(--default-titles);
    margin: 0;
}
/* ── Cards ─────────────────────────────────────────────── */
.cards-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.25rem;
}
.card-clickable {
    cursor: pointer;
    transition: transform 0.15s;
}
.card-clickable:hover {
    transform: translateY(-2px);
}
.card-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}
.card-company {
    color: var(--default-titles);
    margin: 0;
}
.card-muted {
    color: var(--default-text);
    margin: 0.1rem 0 0;
}
.card-icon {
    font-size: 14px;
    vertical-align: middle;
}

/* Badge : couleurs dans app.css, alignement spécifique à la carte */
.badge {
    align-self: flex-start;
}

.state {
    color: var(--default-text);
    padding: 2rem 0;
}

@media (max-width: 768px) {
    .cards-grid {
        grid-template-columns: 1fr;
    }
}
</style>
