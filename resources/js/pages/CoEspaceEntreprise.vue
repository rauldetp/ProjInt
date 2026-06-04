<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useCobrandStore } from "../stores/cobrand";
import { useAuthStore } from "../stores/auth";

const route  = useRoute();
const router = useRouter();
const cobrand = useCobrandStore();
const auth   = useAuthStore();

const entreprise    = ref(null);
const collectes     = ref([]);
const loading       = ref(true);
const error         = ref(null);
const chartMode     = ref("jours");
const filterStatut  = ref("tout");

const brandColor   = computed(() => cobrand.couleurPrimaire || "#e60f48");
const hasActiveCollecte = computed(() =>
    collectes.value.some(c => c.active && c.statut === "validee")
);

/* ── Totaux ─────────────────────────────────────────────────── */
const totalInscrits = computed(() =>
    collectes.value.reduce((s, c) => s + (c.nb_inscrits_estime ?? 0), 0)
);

/* ── Statut badge ────────────────────────────────────────────── */
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

const filters = [
    { key: "tout",       label: "Tout" },
    { key: "avenir",     label: "À venir" },
    { key: "encours",    label: "En cours" },
    { key: "aconfirmer", label: "À confirmer" },
    { key: "terminee",   label: "Terminées" },
];

const collectesFiltrees = computed(() => {
    if (filterStatut.value === "tout") return collectes.value;
    return collectes.value.filter(c => badgeClass(c) === `badge-${filterStatut.value}`);
});

/* ── Chart ────────────────────────────────────────────────────── */
const chartData = computed(() => {
    const W = 600, H = 100;
    if (!collectes.value.length) {
        return { points: `0,${H * 0.9}`, labels: ["—"] };
    }

    const groups = {};
    [...collectes.value]
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

/* ── Format date ─────────────────────────────────────────────── */
function formatDate(date) {
    if (!date) return "—";
    return new Date(date).toLocaleDateString("fr-FR", {
        day: "2-digit", month: "2-digit", year: "numeric",
    });
}

/* ── Fetch ────────────────────────────────────────────────────── */
onMounted(async () => {
    try {
        const res = await fetch("/api/coordinateur/collectes", {
            headers: { Authorization: `Bearer ${auth.token}`, Accept: "application/json" },
        });
        if (!res.ok) throw new Error("Erreur lors du chargement.");
        const data = await res.json();
        entreprise.value = data.entreprise;
        collectes.value  = data.collectes ?? [];
        if (data.entreprise) cobrand.set(data.entreprise);
        document.title = `Espace entreprise — ${data.entreprise?.nom ?? "HUG"}`;
    } catch (e) {
        error.value = e.message;
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <div class="min-h-screen bg-white" style="font-family: 'Instrument Sans', sans-serif">

        <!-- Navbar co-brandée -->
        <header class="bg-white sticky top-0 z-50" style="height: 76px; border-bottom: 1px solid #f2f4f3">
            <div class="max-w-7xl mx-auto px-8 h-full flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <RouterLink to="/" style="font-weight: 800; font-size: 20px; color: #2c4140; text-decoration: none">HUG</RouterLink>
                    <span style="color: rgba(44,65,64,0.3); font-size: 18px; margin: 0 4px">|</span>
                    <span style="font-size: 15px; font-weight: 600; color: #497371">Don du sang</span>
                    <span style="color: rgba(44,65,64,0.3); font-size: 18px; margin: 0 4px">×</span>
                    <span style="font-size: 15px; font-weight: 700" :style="{ color: brandColor }">
                        <img v-if="cobrand.logo" :src="cobrand.logo" :alt="cobrand.nom" style="max-height: 28px; object-fit: contain" />
                        <span v-else>{{ cobrand.nom || entreprise?.nom }}</span>
                    </span>
                </div>
                <nav class="hidden md:flex items-center gap-7 text-base font-medium">
                    <RouterLink :to="`/entreprise/${route.params.slug}`" style="color: #2c4140; text-decoration: none" class="hover:opacity-60 transition">Accueil</RouterLink>
                    <RouterLink :to="`/entreprise/${route.params.slug}/label`" style="color: #2c4140; text-decoration: none" class="hover:opacity-60 transition">Label CTS</RouterLink>
                    <RouterLink :to="`/entreprise/${route.params.slug}/trophee`" style="color: #2c4140; text-decoration: none" class="hover:opacity-60 transition">Trophée de la générosité</RouterLink>
                    <RouterLink
                        :to="`/entreprise/${route.params.slug}/espace`"
                        style="text-decoration: none; font-weight: 700"
                        :style="{ color: brandColor }"
                    >Espace entreprise</RouterLink>
                </nav>
                <RouterLink
                    v-if="hasActiveCollecte"
                    :to="`/entreprise/${route.params.slug}/inscription`"
                    class="border-2 rounded-full px-5 py-2 text-sm font-semibold transition hover:opacity-75"
                    style="text-decoration: none; white-space: nowrap"
                    :style="{ color: brandColor, borderColor: brandColor }"
                >S'inscrire à la collecte</RouterLink>
            </div>
        </header>

        <div v-if="loading" class="flex items-center justify-center py-20" style="color: #497371">Chargement…</div>
        <div v-else-if="error" class="flex items-center justify-center py-20" style="color: #e60f48">{{ error }}</div>

        <template v-else>

            <!-- Vue globale -->
            <section style="background: #f2f4f3; padding: 2.5rem 0 3rem">
                <div class="max-w-6xl mx-auto px-8">
                    <h1 class="font-bold text-center mb-8" style="font-size: 36px; color: #2c4140">Vue globale</h1>

                    <!-- Stats -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; max-width: 640px; margin: 0 auto 2rem">
                        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(44,65,64,0.06)">
                            <p class="font-bold" style="font-size: 2rem; color: #2c4140; margin: 0 0 0.25rem">{{ totalInscrits }}</p>
                            <p style="font-size: 0.8rem; color: #497371; margin: 0">Employés ont passé le questionnaire</p>
                        </div>
                        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(44,65,64,0.06)">
                            <p class="font-bold" style="font-size: 2rem; color: #2c4140; margin: 0 0 0.25rem">{{ collectes.length }}</p>
                            <p style="font-size: 0.8rem; color: #497371; margin: 0">Nombre total de collectes</p>
                        </div>
                    </div>

                    <!-- Chart -->
                    <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(44,65,64,0.06); max-width: 640px; margin: 0 auto">
                        <p class="font-semibold text-center" style="font-size: 1rem; color: #2c4140; margin: 0 0 1rem">Nombres d'inscriptions totaux</p>
                        <div style="background: #f9fafb; border-radius: 8px; padding: 1rem; margin-bottom: 1rem">
                            <svg viewBox="0 0 600 100" style="width: 100%; height: 80px">
                                <polyline
                                    fill="none"
                                    :stroke="brandColor"
                                    stroke-width="2.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    :points="chartData.points"
                                />
                            </svg>
                            <div style="display: flex; justify-content: space-between; font-size: 0.7rem; color: #497371; margin-top: 0.5rem">
                                <span v-for="label in chartData.labels" :key="label">{{ label }}</span>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: center; gap: 0.75rem">
                            <button
                                v-for="m in [{key:'jours',label:'Jours'},{key:'mois',label:'Mois'},{key:'annees',label:'Années'}]"
                                :key="m.key"
                                @click="chartMode = m.key"
                                style="border-radius: 9999px; padding: 0.35rem 1.1rem; font-size: 0.875rem; cursor: pointer; transition: all 0.15s"
                                :style="chartMode === m.key
                                    ? { background: brandColor, color: 'white', border: `1px solid ${brandColor}` }
                                    : { background: 'white', color: '#497371', border: '1px solid #e2e8f0' }"
                            >{{ m.label }}</button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Campagnes de collectes -->
            <section style="background: white; padding: 3rem 0 4rem">
                <div class="max-w-6xl mx-auto px-8">

                    <!-- Header section -->
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem">
                        <h2 class="font-bold" style="font-size: 28px; color: #2c4140; margin: 0">Campagnes de collectes</h2>
                        <RouterLink
                            :to="`/entreprise/${route.params.slug}/nouvelle-collecte`"
                            style="width: 36px; height: 36px; border: 1px solid #e2e8f0; border-radius: 9999px; display: flex; align-items: center; justify-content: center; color: #497371; text-decoration: none; font-size: 1.25rem; transition: border-color 0.15s"
                            class="hover:border-gray-400"
                        >+</RouterLink>
                    </div>

                    <!-- Filtres -->
                    <div style="display: flex; gap: 0.5rem; margin-bottom: 2rem; flex-wrap: wrap">
                        <button
                            v-for="f in filters"
                            :key="f.key"
                            @click="filterStatut = f.key"
                            style="border-radius: 9999px; padding: 0.35rem 1rem; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all 0.15s"
                            :style="filterStatut === f.key
                                ? { background: brandColor, color: 'white', border: `1.5px solid ${brandColor}` }
                                : { background: 'white', color: '#497371', border: '1.5px solid #e2e8f0' }"
                        >{{ f.label }}</button>
                    </div>

                    <!-- Cards -->
                    <div v-if="collectesFiltrees.length === 0" style="text-align: center; padding: 3rem; color: #497371">
                        Aucune collecte pour ce filtre.
                    </div>
                    <div v-else style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem">
                        <div
                            v-for="c in collectesFiltrees"
                            :key="c.id"
                            style="background: white; border-radius: 12px; padding: 1.25rem; box-shadow: 0 2px 8px rgba(44,65,64,0.08); display: flex; flex-direction: column; gap: 0.6rem; border: 1px solid #f2f4f3"
                        >
                            <div style="display: flex; justify-content: space-between; align-items: flex-start">
                                <div>
                                    <p class="font-bold" style="font-size: 1rem; color: #2c4140; margin: 0">
                                        {{ c.titre || entreprise?.nom }}
                                    </p>
                                    <p style="font-size: 0.8rem; color: #497371; margin: 0.1rem 0 0">{{ formatDate(c.date_debut) }}</p>
                                </div>
                                <button style="background: none; border: none; color: #497371; font-size: 1.1rem; cursor: pointer; padding: 0; letter-spacing: 2px">···</button>
                            </div>
                            <span
                                style="display: inline-block; padding: 0.2rem 0.65rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; width: fit-content"
                                :style="badgeClass(c) === 'badge-encours'    ? { background: '#d1fae5', color: '#065f46' }
                                      : badgeClass(c) === 'badge-aconfirmer' ? { background: '#fee2e2', color: '#991b1b' }
                                      : badgeClass(c) === 'badge-avenir'     ? { background: '#fef3c7', color: '#92400e' }
                                      : { background: '#f2f4f3', color: '#497371' }"
                            >{{ badgeLabel(c) }}</span>
                            <p style="font-size: 0.85rem; color: #497371; margin: 0">
                                👥 {{ c.nb_inscrits_estime ?? 0 }} inscrit(s)
                            </p>
                            <p style="font-size: 0.82rem; color: #497371; margin: 0">{{ c.lieu || "Lieu à définir" }}</p>
                        </div>
                    </div>
                </div>
            </section>

        </template>

        <!-- Footer -->
        <footer style="background: #2c4140; padding: 3.5rem 0 2.5rem">
            <div class="max-w-6xl mx-auto px-8">
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 2.5rem; margin-bottom: 3rem">
                    <div>
                        <div class="font-extrabold text-2xl mb-1 text-white">HUG</div>
                        <div style="font-size: 12px; color: #93cfa9; line-height: 1.6">Hôpitaux<br />Universitaires<br />Genève</div>
                    </div>
                    <div>
                        <p class="font-bold mb-5 text-white" style="font-size: 18px">Pages</p>
                        <ul class="space-y-3">
                            <li><RouterLink :to="`/entreprise/${route.params.slug}/label`" class="text-white hover:opacity-70 transition" style="font-size: 15px; text-decoration: none">Label CTS</RouterLink></li>
                            <li><RouterLink :to="`/entreprise/${route.params.slug}/trophee`" class="text-white hover:opacity-70 transition" style="font-size: 15px; text-decoration: none">Trophée de la générosité</RouterLink></li>
                            <li><RouterLink to="/entreprises" class="text-white hover:opacity-70 transition" style="font-size: 15px; text-decoration: none">Entreprises partenaires</RouterLink></li>
                        </ul>
                    </div>
                    <div>
                        <p class="font-bold mb-5 text-white" style="font-size: 18px">Support</p>
                        <ul class="space-y-3">
                            <li><RouterLink to="/faq" class="text-white hover:opacity-70 transition" style="font-size: 15px; text-decoration: none">FAQ</RouterLink></li>
                            <li><RouterLink to="/contact" class="text-white hover:opacity-70 transition" style="font-size: 15px; text-decoration: none">Contact</RouterLink></li>
                        </ul>
                    </div>
                    <div>
                        <p class="font-bold mb-5 text-white" style="font-size: 18px">Mentions légales</p>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-white hover:opacity-70 transition" style="font-size: 15px; text-decoration: none">Politique de confidentialité</a></li>
                            <li><a href="#" class="text-white hover:opacity-70 transition" style="font-size: 15px; text-decoration: none">Conditions générales</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t pt-6" style="border-color: rgba(242,244,243,0.15)">
                    <p class="text-center" style="font-size: 14px; color: rgba(242,244,243,0.5)">
                        © {{ new Date().getFullYear() }} Hôpitaux Universitaires Genève. Tous droits réservés.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
