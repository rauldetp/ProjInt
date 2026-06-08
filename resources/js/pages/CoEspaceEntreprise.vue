<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useCobrandStore } from "../stores/cobrand";
import { useAuthStore } from "../stores/auth";
import CoNavbar from "../components/CoNavbar.vue";

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

const brandColor   = computed(() => cobrand.couleurPrimaire || "var(--color-default-red)");
const activeCollecte = computed(() =>
    collectes.value.find(c => c.active && c.statut === "validee") ?? null
);
const hasActiveCollecte = computed(() => !!activeCollecte.value);

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

/* ── Menu contextuel ─────────────────────────────────────────── */
const openMenu = ref(null);

function toggleMenu(id) {
    openMenu.value = openMenu.value === id ? null : id;
}
function closeMenu() {
    openMenu.value = null;
}

function goVoir(c) {
    router.push(`/entreprise/${route.params.slug}/collecte/${c.id}`);
}
function goModifier(c) {
    closeMenu();
    router.push(`/entreprise/${route.params.slug}/nouvelle-collecte?edit=${c.id}`);
}
async function goAnnuler(c) {
    closeMenu();
    if (!confirm("Confirmer l'annulation de cette collecte ?")) return;
    try {
        await fetch(`/api/coordinateur/collectes/${c.id}/annuler`, {
            method: "POST",
            headers: { Authorization: `Bearer ${auth.token}`, Accept: "application/json" },
        });
        collectes.value = collectes.value.filter(x => x.id !== c.id);
    } catch {
        alert("Erreur lors de l'annulation.");
    }
}

onBeforeUnmount(() => document.removeEventListener("click", closeMenu));

/* ── Fetch ────────────────────────────────────────────────────── */
onMounted(async () => {
    document.addEventListener("click", closeMenu);
    try {
        const res = await fetch(`/api/entreprises/${route.params.slug}/collectes`, {
            headers: { Accept: "application/json" },
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
    <div class="min-h-screen bg-white">

        <!-- Navbar co-brandée -->
        <CoNavbar :collecte="activeCollecte" />

        <div v-if="loading" class="flex items-center justify-center py-20" style="color: var(--default-text)">Chargement…</div>
        <div v-else-if="error" class="flex items-center justify-center py-20" style="color: var(--color-default-red)">{{ error }}</div>

        <template v-else>

            <!-- Vue globale -->
            <section style="background: var(--light-grey); padding: 2.5rem 0 3rem">
                <div class="max-w-6xl mx-auto px-8">
                    <h1 class="font-bold text-center mb-8" style="font-size: 36px; color: var(--default-titles)">Vue globale</h1>

                    <!-- Stats -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; max-width: 640px; margin: 0 auto 2rem">
                        <div style="background: white; border-radius: 12px; padding: 1.5rem">
                            <p class="font-bold" style="font-size: 2rem; color: var(--default-titles); margin: 0 0 0.25rem">{{ totalInscrits }}</p>
                            <p style="font-size: 0.8rem; color: var(--default-text); margin: 0">Employés ont passé le questionnaire</p>
                        </div>
                        <div style="background: white; border-radius: 12px; padding: 1.5rem">
                            <p class="font-bold" style="font-size: 2rem; color: var(--default-titles); margin: 0 0 0.25rem">{{ collectes.length }}</p>
                            <p style="font-size: 0.8rem; color: var(--default-text); margin: 0">Nombre total de collectes</p>
                        </div>
                    </div>

                    <!-- Chart -->
                    <div style="background: white; border-radius: 12px; padding: 1.5rem; max-width: 640px; margin: 0 auto">
                        <p class="font-semibold text-center" style="font-size: 1rem; color: var(--default-titles); margin: 0 0 1rem">Nombres d'inscriptions totaux</p>
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
                            <div style="display: flex; justify-content: space-between; font-size: 0.7rem; color: var(--default-text); margin-top: 0.5rem">
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
                                    : { background: 'white', color: 'var(--default-text)', border: '1px solid #e2e8f0' }"
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
                        <h2 class="font-bold" style="font-size: 28px; color: var(--default-titles); margin: 0">Campagnes de collectes</h2>
                        <RouterLink
                            v-if="auth.isCoordinateur"
                            :to="`/entreprise/${route.params.slug}/nouvelle-collecte`"
                            style="display: inline-flex; align-items: center; gap: 0.4rem; background: var(--default-titles); color: white; border-radius: 9999px; padding: 0.5rem 1.1rem; font-size: 0.875rem; font-weight: 600; text-decoration: none; transition: opacity 0.15s; white-space: nowrap"
                            class="hover:opacity-80"
                        >
                            <span class="material-symbols-outlined" style="font-size: 18px">add</span>
                            Nouvelle collecte
                        </RouterLink>
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
                                : { background: 'white', color: 'var(--default-text)', border: '1.5px solid #e2e8f0' }"
                        >{{ f.label }}</button>
                    </div>

                    <!-- Cards -->
                    <div v-if="collectesFiltrees.length === 0" style="text-align: center; padding: 3rem; color: var(--default-text)">
                        Aucune collecte pour ce filtre.
                    </div>
                    <div v-else style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem">
                        <div
                            v-for="c in collectesFiltrees"
                            :key="c.id"
                            style="background: white; border-radius: 12px; padding: 1.25rem; display: flex; flex-direction: column; gap: 0.6rem; border: 1px solid var(--light-grey); cursor: pointer"
                            @click="goVoir(c)"
                        >
                            <div style="display: flex; justify-content: space-between; align-items: flex-start">
                                <div>
                                    <p class="font-bold" style="font-size: 1rem; color: var(--default-titles); margin: 0">
                                        {{ c.titre || entreprise?.nom }}
                                    </p>
                                    <p style="font-size: 0.8rem; color: var(--default-text); margin: 0.1rem 0 0">{{ formatDate(c.date_debut) }}</p>
                                </div>
                                <div v-if="auth.isCoordinateur" style="position: relative">
                                    <button
                                        @click.stop="toggleMenu(c.id)"
                                        style="background: none; border: none; color: var(--default-text); font-size: 1.1rem; cursor: pointer; padding: 2px 6px; letter-spacing: 2px; border-radius: 6px; transition: background 0.15s"
                                        :style="openMenu === c.id ? { background: 'var(--light-grey)' } : {}"
                                    >···</button>
                                    <div
                                        v-if="openMenu === c.id"
                                        style="position: absolute; top: calc(100% + 6px); right: 0; background: white; border-radius: 10px; box-shadow: 0 4px 20px rgba(44,65,64,0.14); border: 1px solid var(--light-grey); min-width: 148px; z-index: 100; overflow: hidden"
                                    >
                                        <button @click.stop="goModifier(c)" style="display: flex; align-items: center; gap: 8px; width: 100%; padding: 10px 14px; background: none; border: none; font-size: 0.875rem; color: var(--default-titles); cursor: pointer; font-family: inherit; text-align: left; transition: background 0.12s" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='none'">
                                            <span class="material-symbols-outlined" style="font-size:16px">edit</span> Modifier
                                        </button>
                                        <button @click.stop="goAnnuler(c)" style="display: flex; align-items: center; gap: 8px; width: 100%; padding: 10px 14px; background: none; border: none; font-size: 0.875rem; color: var(--color-default-red); cursor: pointer; font-family: inherit; text-align: left; transition: background 0.12s" onmouseover="this.style.background='#fff1f4'" onmouseout="this.style.background='none'">
                                            <span class="material-symbols-outlined" style="font-size:16px">cancel</span> Annuler
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <span
                                style="display: inline-block; padding: 0.2rem 0.65rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; width: fit-content"
                                :style="badgeClass(c) === 'badge-encours'    ? { background: '#d1fae5', color: '#065f46' }
                                      : badgeClass(c) === 'badge-aconfirmer' ? { background: '#fee2e2', color: '#991b1b' }
                                      : badgeClass(c) === 'badge-avenir'     ? { background: '#fef3c7', color: '#92400e' }
                                      : { background: 'var(--light-grey)', color: 'var(--default-text)' }"
                            >{{ badgeLabel(c) }}</span>
                            <p style="font-size: 0.85rem; color: var(--default-text); margin: 0">
                                <span class="material-symbols-outlined" style="font-size:14px;vertical-align:middle">group</span> {{ c.nb_inscrits_estime ?? 0 }} inscrit(s)
                            </p>
                            <p style="font-size: 0.82rem; color: var(--default-text); margin: 0">{{ c.lieu || "Lieu à définir" }}</p>
                        </div>
                    </div>
                </div>
            </section>

        </template>

        <!-- Footer -->
        <footer style="background: var(--default-titles); padding: 3.5rem 0 2.5rem">
            <div class="max-w-6xl mx-auto px-8">
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 2.5rem; margin-bottom: 3rem">
                    <div>
                        <div class="font-extrabold text-2xl mb-1 text-white">HUG</div>
                        <div class="captions" style="color: var(--color-default-green); line-height: 1.6">Hôpitaux<br />Universitaires<br />Genève</div>
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
