<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useCobrandStore } from "../stores/cobrand";
import { useAuthStore } from "../stores/auth";
import AppNavbar from "../components/AppNavbar.vue";
import Footer from "../components/Footer.vue";

const route  = useRoute();
const router = useRouter();
const cobrand = useCobrandStore();
const auth   = useAuthStore();

const collecte  = ref(null);
const entreprise = ref(null);
const loading   = ref(true);
const error     = ref(null);

const brandColor  = computed(() => cobrand.couleurPrimaire || "var(--color-default-red)");
const textOnBrand = computed(() => cobrand.textOnBrand);

function formatDate(d) {
    if (!d) return "—";
    return new Date(d).toLocaleDateString("fr-FR", { day: "2-digit", month: "long", year: "numeric" });
}

function badgeLabel(c) {
    if (!c) return "";
    if (c.statut === "terminee")   return "Terminée";
    if (c.statut === "annulee")    return "Annulée";
    if (c.statut === "en_attente") return "À confirmer";
    if (c.active) return "En cours";
    const today = new Date().toISOString().split("T")[0];
    const debut = c.date_debut ? String(c.date_debut).split("T")[0] : null;
    return debut && debut >= today ? "À venir" : "Terminée";
}

function badgeStyle(c) {
    const l = badgeLabel(c);
    if (l === "En cours")    return { background: "#d1fae5", color: "#065f46" };
    if (l === "À confirmer") return { background: "#fee2e2", color: "#991b1b" };
    if (l === "À venir")     return { background: "#fef3c7", color: "#92400e" };
    return { background: "var(--light-grey)", color: "var(--default-text)" };
}

onMounted(async () => {
    try {
        // Cobrand
        const resE = await fetch(`/api/entreprises/${route.params.slug}`);
        if (resE.ok) {
            const data = await resE.json();
            entreprise.value = data.entreprise;
            if (data.entreprise) cobrand.set(data.entreprise);
        }
        // Collecte
        const resC = await fetch(`/api/collectes/${route.params.id}`, {
            headers: { Accept: "application/json" },
        });
        if (!resC.ok) throw new Error("Collecte introuvable.");
        collecte.value = await resC.json();
        document.title = `Collecte — ${cobrand.nom || "HUG"}`;
    } catch (e) {
        error.value = e.message;
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <div class="min-h-screen bg-white">

        <AppNavbar />

        <div v-if="loading" class="flex items-center justify-center py-20" style="color: var(--default-text)">Chargement…</div>
        <div v-else-if="error" class="flex items-center justify-center py-20" style="color: var(--color-default-red)">{{ error }}</div>

        <template v-else>
            <section style="background: var(--light-grey); min-height: calc(100vh - 76px - 160px); padding: 3rem 0 5rem">
                <div class="max-w-3xl mx-auto px-4 md:px-8">

                    <!-- Back -->
                    <button
                        @click="router.push(`/entreprise/${route.params.slug}/espace`)"
                        style="display: inline-flex; align-items: center; gap: 6px; background: none; border: none; color: var(--default-text); cursor: pointer; padding: 0; margin-bottom: 2rem; font-family: inherit"
                    >
                        <span class="material-symbols-outlined" style="font-size: 18px">arrow_back</span>
                        Retour à l'espace entreprise
                    </button>

                    <!-- Header collecte -->
                    <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem">
                        <div>
                            <h1 class="font-bold text-black" style="margin: 0 0 6px">
                                {{ collecte.titre || entreprise?.nom }}
                            </h1>
                            <p style="color: var(--default-text); margin: 0">
                                {{ formatDate(collecte.date_debut) }}
                                <template v-if="collecte.date_fin && collecte.date_fin !== collecte.date_debut">
                                    → {{ formatDate(collecte.date_fin) }}
                                </template>
                            </p>
                        </div>
                        <span
                            :style="badgeStyle(collecte)"
                            style="display: inline-block; padding: 0.3rem 0.9rem; border-radius: 9999px; font-size: 0.8rem; font-weight: 700"
                        >{{ badgeLabel(collecte) }}</span>
                    </div>

                    <!-- Infos pratiques -->
                    <div style="background: white; border-radius: 14px; padding: 2rem; margin-bottom: 1.5rem">
                        <p class="font-bold" style="color: var(--default-titles); margin: 0 0 1.25rem">
                            <span class="material-symbols-outlined" style="font-size: 18px; vertical-align: middle; margin-right: 6px">info</span>
                            Informations pratiques
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="info-row">
                                <span class="material-symbols-outlined info-icon" :style="{ color: brandColor }">location_on</span>
                                <div>
                                    <p class="info-label">Lieu</p>
                                    <p class="info-value">{{ collecte.sur_site ? (collecte.lieu || "En entreprise") : "Centre de transfusion sanguine" }}</p>
                                </div>
                            </div>
                            <div class="info-row">
                                <span class="material-symbols-outlined info-icon" :style="{ color: brandColor }">schedule</span>
                                <div>
                                    <p class="info-label">Horaires</p>
                                    <p class="info-value">{{ collecte.horaires || "À confirmer" }}</p>
                                </div>
                            </div>
                            <div class="info-row">
                                <span class="material-symbols-outlined info-icon" :style="{ color: brandColor }">group</span>
                                <div>
                                    <p class="info-label">Inscrits estimés</p>
                                    <p class="info-value">{{ collecte.nb_inscrits_estime ?? 0 }} personne(s)</p>
                                </div>
                            </div>
                            <div class="info-row" v-if="collecte.objectif_dons">
                                <span class="material-symbols-outlined info-icon" :style="{ color: brandColor }">favorite</span>
                                <div>
                                    <p class="info-label">Objectif de dons</p>
                                    <p class="info-value">{{ collecte.objectif_dons }} don(s)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CTA inscription -->
                    <div v-if="collecte.active && collecte.statut === 'validee'" style="margin-bottom: 1.5rem">
                        <a
                            v-if="collecte.lien_rdv_externe"
                            :href="collecte.lien_rdv_externe"
                            target="_blank"
                            rel="noopener"
                            class="btn-inscrire"
                            :style="{ background: brandColor, color: textOnBrand }"
                        >
                            <span class="material-symbols-outlined" style="font-size: 18px">how_to_reg</span>
                            S'inscrire à cette collecte
                        </a>
                        <RouterLink
                            v-else
                            :to="`/entreprise/${route.params.slug}/inscription`"
                            class="btn-inscrire"
                            :style="{ background: brandColor, color: textOnBrand }"
                        >
                            <span class="material-symbols-outlined" style="font-size: 18px">how_to_reg</span>
                            S'inscrire à cette collecte
                        </RouterLink>
                    </div>

                    <!-- Recommandations -->
                    <div style="background: white; border-radius: 14px; padding: 2rem; margin-bottom: 1.5rem">
                        <p class="font-bold" style="color: var(--default-titles); margin: 0 0 1.25rem">
                            <span class="material-symbols-outlined" style="font-size: 18px; vertical-align: middle; margin-right: 6px">health_and_safety</span>
                            Recommandations avant le don
                        </p>

                        <p class="reco-subtitle">La veille et le jour du don</p>
                        <ul class="reco-list">
                            <li>
                                <span class="material-symbols-outlined reco-icon" :style="{ color: brandColor }">directions_run</span>
                                Ne pratiquez pas un sport de façon intense la veille et le jour de votre don. Un délai de 48 heures est nécessaire avant et après le don pour la plongée sous-marine, l'escalade et toute compétition.
                            </li>
                            <li>
                                <span class="material-symbols-outlined reco-icon" :style="{ color: brandColor }">wine_bar</span>
                                Évitez les repas lourds (p. ex. fondue) et la prise d'alcool 24 heures avant votre don.
                            </li>
                            <li>
                                <span class="material-symbols-outlined reco-icon" :style="{ color: brandColor }">bedtime</span>
                                Abstenez-vous si vous ressentez une fatigue.
                            </li>
                            <li>
                                <span class="material-symbols-outlined reco-icon" :style="{ color: brandColor }">water_drop</span>
                                Le jour du don, ne venez pas à jeun et prenez soin de boire avant et après (un litre minimum en plus de vos boissons habituelles sur la journée).
                            </li>
                        </ul>
                    </div>

                    <!-- Contact CTS -->
                    <div style="border-radius: 14px; padding: 1.75rem 2rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem" :style="{ background: brandColor }">
                        <div>
                            <p class="font-bold" :style="{ fontSize: '15px', color: textOnBrand, margin: '0 0 4px' }">
                                Vous êtes souffrant ? Vous prenez de nouveaux médicaments ? Vous avez d'autres questions ?
                            </p>
                            <p :style="{ fontSize: '14px', color: textOnBrand, margin: '0', opacity: '0.8' }">Contactez le CTS avant de vous déplacer.</p>
                        </div>
                        <a
                            href="https://www.hug.ch/don-du-sang/contact"
                            target="_blank"
                            rel="noopener"
                            class="btn-contact"
                            style="background: white; color: var(--default-titles)"
                        >
                            Contacter le CTS
                            <span class="material-symbols-outlined" style="font-size: 16px">open_in_new</span>
                        </a>
                    </div>

                    <!-- Bouton modifier (coordinateur uniquement) -->
                    <div v-if="auth.isCoordinateur" style="margin-top: 2rem; display: flex; justify-content: flex-end">
                        <button
                            @click="router.push(`/entreprise/${route.params.slug}/nouvelle-collecte?edit=${collecte.id}`)"
                            style="display: inline-flex; align-items: center; gap: 6px; border-radius: 9999px; padding: 0.6rem 1.4rem; font-weight: 600; cursor: pointer; border: 2px solid var(--default-titles); background: white; color: var(--default-titles); font-family: inherit; transition: background 0.15s, color 0.15s"
                            onmouseover="this.style.background='var(--default-titles)';this.style.color='white'"
                            onmouseout="this.style.background='white';this.style.color='var(--default-titles)'"
                        >
                            <span class="material-symbols-outlined" style="font-size: 16px">edit</span>
                            Modifier cette collecte
                        </button>
                    </div>

                </div>
            </section>
        </template>

        <!-- Footer -->
        <Footer compact />
    </div>
</template>

<style scoped>
.info-row {
    display: flex;
    align-items: flex-start;
    gap: 10px;
}
.info-icon {
    font-size: 20px;
    flex-shrink: 0;
    margin-top: 2px;
}
.info-label {
    font-size: 11px;
    font-weight: 600;
    color: var(--default-text);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin: 0 0 2px;
}
.info-value {
    font-size: 14px;
    color: var(--default-titles);
    font-weight: 600;
    margin: 0;
}

.reco-subtitle {
    font-size: 13px;
    font-weight: 700;
    color: var(--default-text);
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin: 0 0 1rem;
}
.reco-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 14px;
}
.reco-list li {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    font-size: 14px;
    color: var(--default-text);
    line-height: 1.65;
}
.reco-icon {
    font-size: 20px;
    flex-shrink: 0;
    margin-top: 1px;
}

.btn-inscrire {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border-radius: 9999px;
    padding: 0.85rem 2rem;
    font-weight: 700;
    text-decoration: none;
    border: none;
    cursor: pointer;
    font-family: inherit;
    transition: opacity 0.15s;
    width: 100%;
    justify-content: center;
}
.btn-inscrire:hover { opacity: 0.85; }

.btn-contact {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: white;
    border-radius: 9999px;
    padding: 0.55rem 1.25rem;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    white-space: nowrap;
    transition: opacity 0.15s;
}
.btn-contact:hover { opacity: 0.85; }
</style>
