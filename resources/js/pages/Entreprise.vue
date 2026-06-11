<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import { useCobrandStore } from "../stores/cobrand";
import AppNavbar from "../components/AppNavbar.vue";
import Footer from "../components/Footer.vue";

const route = useRoute();
const cobrand = useCobrandStore();

const entreprise = ref({});
const collecte = ref(null);
const label = ref(null);
const trophees = ref([]);
const loading = ref(true);
const error = ref(null);

onMounted(async () => {
    try {
        const res = await fetch(`/api/entreprises/${route.params.slug}`);
        if (!res.ok) throw new Error("Entreprise introuvable");
        const data = await res.json();
        entreprise.value = data.entreprise;
        collecte.value = data.collecte;
        label.value = data.label;
        trophees.value = data.trophees ?? [];
        if (data.entreprise) {
            cobrand.set(data.entreprise);
        }
        document.title = `${data.entreprise?.nom ?? "Entreprise"} × HUG — Don du sang`;
    } catch (e) {
        error.value = e.message;
    } finally {
        loading.value = false;
    }
});

const brandColor = computed(
    () => entreprise.value.couleur_primaire || "var(--color-default-red)",
);
const textOnBrand = computed(() => cobrand.textOnBrand);
const sectionGradient = computed(
    () => `linear-gradient(135deg, ${brandColor.value}, #ffffff)`,
);

const dateRange = computed(() => {
    if (!collecte.value?.date_debut) return "Dates à définir";
    const fmt = (d) =>
        new Date(d).toLocaleDateString("fr-FR", {
            day: "2-digit",
            month: "long",
            year: "numeric",
        });
    const start = fmt(collecte.value.date_debut);
    const end = collecte.value.date_fin ? fmt(collecte.value.date_fin) : null;
    return end && end !== start ? `${start} – ${end}` : start;
});
</script>

<template>
    <div v-if="loading" class="state-center">Chargement...</div>
    <div
        v-else-if="error"
        class="state-center"
        style="color: var(--color-default-red)"
    >
        {{ error }}
    </div>

    <div v-else class="page">
        <!-- Navbar -->
        <AppNavbar />

        <!-- Hero -->
        <section class="hero">
            <div class="hero-overlay"></div>
            <div class="hero-inner">
                <!-- Left: text -->
                <div class="hero-text">
                    <h1 class="hero-title">
                        Collecte de don du sang<br />
                        {{ entreprise.nom }} × HUG
                    </h1>
                    <h3 class="mb-8" v-if="collecte">
                        Faites un geste citoyen directement sur votre lieu de
                        travail les 25 et 26 mai 2026. Testez votre éligibilité
                        en 2 minutes et réservez votre créneau pour soutenir le
                        Centre de Transfusion Sanguine.
                    </h3>
                    <h3 class="mb-8" v-else>
                        Notre entreprise est fière de soutenir le don du sang et
                        de contribuer à cette démarche solidaire essentielle. En
                        quelques minutes, vérifiez votre éligibilité et réservez
                        votre créneau pour participer à cet élan de générosité
                        au profit du Centre de Transfusion Sanguine. Chaque don
                        peut faire la différence.
                    </h3>
                    <!--
                    <p class="hero-date" v-if="collecte">
                        <span
                            class="material-symbols-outlined"
                            style="vertical-align: middle"
                            >calendar_month</span
                        >
                        {{ dateRange }}
                    </p>
                    -->
                    <div class="hero-actions">
                        <RouterLink
                            v-if="collecte && collecte.active"
                            :to="`/entreprise/${route.params.slug}/inscription`"
                            class="hero-btn-primary"
                            :style="{
                                background: brandColor,
                                color: textOnBrand,
                            }"
                        >
                            S'inscrire →
                        </RouterLink>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats : infos dynamiques de la collecte en cours -->
        <section class="bg-white py-10">
            <div
                class="max-w-4xl mx-auto px-4 md:px-8 grid grid-cols-1 gap-4"
            >
                <template v-if="collecte">
                    <div class="rounded-xl px-5 py-4 bg-light-grey">
                        <span
                            class="material-symbols-outlined feature-icon mb-2"
                            :style="{ color: brandColor }"
                            >location_on</span
                        >
                        <p class="captions">
                            {{
                                collecte.sur_site
                                    ? (entreprise.adresse ??
                                      entreprise.ville ??
                                      entreprise.nom)
                                    : "Centre de transfusion sanguine"
                            }}
                        </p>
                    </div>
                    <div class="rounded-xl px-5 py-4 bg-light-grey">
                        <span
                            class="material-symbols-outlined feature-icon mb-2"
                            :style="{ color: brandColor }"
                            >schedule</span
                        >
                        <p class="captions">
                            {{ collecte.horaires ?? "Horaires à confirmer" }}
                        </p>
                    </div>
                    <div class="rounded-xl px-5 py-4 bg-light-grey">
                        <span
                            class="material-symbols-outlined feature-icon mb-2"
                            :style="{ color: brandColor }"
                            >calendar_month</span
                        >
                        <p class="captions">{{ dateRange }}</p>
                    </div>
                </template>
                <div v-else class="rounded-xl px-5 py-4 bg-light-grey">
                    <span
                        class="material-symbols-outlined feature-icon mb-2"
                        :style="{ color: brandColor }"
                        >event_busy</span
                    >
                    <p class="captions">
                        Aucune collecte en cours en ce moment
                    </p>
                </div>
            </div>
        </section>

        <!-- Quiz CTA section -->
        <section class="quiz-section" :style="{ background: sectionGradient }">
            <div class="quiz-section-inner">
                <div class="quiz-insight">
                    <div class="quiz-insight-icon">
                        <span
                            class="material-symbols-outlined"
                            style="font-size: 24px"
                            >assignment</span
                        >
                    </div>
                    <p class="quiz-insight-text">
                        <template v-if="collecte?.nb_inscrits_estime"
                            >Déjà
                            <strong>{{ collecte.nb_inscrits_estime }}</strong>
                            autres employés ont passé le test !</template
                        >
                        <template v-else
                            >Rejoignez vos collègues et passez le test
                            d'éligibilité !</template
                        >
                    </p>
                </div>
                <h2 class="quiz-section-title" :style="{ color: textOnBrand }">
                    Suis-je éligible ?
                </h2>
                <p class="quiz-section-sub" :style="{ color: textOnBrand }">
                    Testez votre éligibilité en 2 minutes et réservez votre
                    créneau pour soutenir le Centre de Transfusion Sanguine.
                </p>
                <div class="quiz-section-actions">
                    <RouterLink
                        :to="`/entreprise/${route.params.slug}/quiz`"
                        class="btn-filled btn-link"
                        :style="{ background: textOnBrand, color: brandColor }"
                    >
                        Passer les questions →
                    </RouterLink>
                    <RouterLink
                        :to="`/entreprise/${route.params.slug}/quiz?voir=resultat`"
                        class="btn-outlined btn-link"
                        :style="{
                            color: textOnBrand,
                            borderColor: textOnBrand,
                        }"
                    >
                        Voir mon résultat →
                    </RouterLink>
                </div>
            </div>
        </section>

        <!-- 3 steps -->
        <section class="bg-light-grey py-20">
            <div class="max-w-7xl mx-auto px-4 md:px-8">
                <h2 class="text-center font-bold mb-12 text-black">
                    La démarche en trois étapes
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-8">
                    <div class="bg-white rounded-xl p-8 shadow-light">
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="w-11 h-11 rounded-full flex items-center justify-center font-bold flex-shrink-0"
                                :style="{
                                    border: '1px solid ' + brandColor,
                                    color: brandColor,
                                    background: 'white',
                                }"
                            >
                                1
                            </div>
                            <div
                                class="flex-1 h-px"
                                style="background: var(--light-grey)"
                            ></div>
                        </div>
                        <h3 class="font-bold mb-3 text-black">
                            Accueil & quiz
                        </h3>
                        <p>
                            Passez notre quiz d'éligibilité rapide en 9
                            questions conçu avec le CTS pour lever vos doutes en
                            toute confidentialité avant de vous inscrire.
                        </p>
                    </div>
                    <div class="bg-white rounded-xl p-8 shadow-light">
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="w-11 h-11 rounded-full flex items-center justify-center font-bold flex-shrink-0"
                                :style="{
                                    border: '1px solid ' + brandColor,
                                    color: brandColor,
                                    background: 'white',
                                }"
                            >
                                2
                            </div>
                            <div
                                class="flex-1 h-px"
                                style="background: var(--light-grey)"
                            ></div>
                        </div>
                        <h3 class="font-bold mb-3 text-black">
                            Questionnaire médical
                        </h3>
                        <p>
                            Une fois votre créneau réservé, vous remplirez le
                            questionnaire officiel des HUG qui sera validé sur
                            place par l'équipe médicale.
                        </p>
                    </div>
                    <div class="bg-white rounded-xl p-8 shadow-light">
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="w-11 h-11 rounded-full flex items-center justify-center font-bold flex-shrink-0"
                                :style="{
                                    border: '1px solid ' + brandColor,
                                    color: brandColor,
                                    background: 'white',
                                }"
                            >
                                3
                            </div>
                            <div
                                class="flex-1 h-px"
                                style="background: var(--light-grey)"
                            ></div>
                        </div>
                        <h3 class="font-bold mb-3 text-black">Le don</h3>
                        <p>
                            Le jour J, vous êtes pris en charge dans vos locaux.
                            Le prélèvement dure moins de 10 minutes et se
                            termine par une collation conviviale.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Engagement -->
        <section class="engagement-section">
            <div class="engagement-inner">
                <div class="engagement-text">
                    <h2 class="engagement-title">
                        {{ entreprise.nom }} s'engage
                    </h2>
                    <p class="engagement-body">
                        Dans le cadre de sa politique de Responsabilité Sociale
                        d'Entreprise (RSE),
                        {{ entreprise.nom }} est fière de s'associer aux
                        Hôpitaux Universitaires de Genève. Parce que votre temps
                        est précieux, la direction libère chaque collaborateur
                        volontaire sur son temps de travail pour accomplir ce
                        geste solidaire essentiel.
                    </p>
                    <RouterLink
                        v-if="collecte && collecte.active"
                        :to="`/entreprise/${route.params.slug}/inscription`"
                        class="btn-filled btn-link"
                        :style="{ background: brandColor, color: textOnBrand }"
                    >
                        S'inscrire à la collecte →
                    </RouterLink>
                </div>
                <div
                    class="aspect-video bg-white flex flex-col items-center justify-center gap-4 rounded-2xl p-12 shadow-light"
                >
                    <img
                        v-if="entreprise.logo"
                        :src="entreprise.logo"
                        :alt="entreprise.nom"
                        class="engagement-logo"
                    />
                    <h3 v-else class="font-bold" :style="{ color: brandColor }">
                        {{ entreprise.nom }}
                    </h3>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <Footer :slug="route.params.slug" />
    </div>
</template>

<style scoped>
.state-center {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    font-size: 1.1rem;
    color: var(--default-text);
    font-family: inherit;
}

.page {
    font-family: inherit;
    background: white;
}

/* ── Hero ────────────────────────────────────────────── */
.hero {
    position: relative;
    overflow: hidden;
    height: 512px;
    display: flex;
    align-items: center;
    background-image: url("/images/Hero_Cobrand.webp");
    background-size: cover;
    background-position: center;
}
.hero-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.42);
    z-index: 1;
}
.hero-inner {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 2rem;
    display: grid;
    grid-template-columns: 1fr;
    gap: 3rem;
    align-items: center;
}
.hero-text {
    color: white;
    max-width: 720px;
}
.hero-eyebrow {
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    color: rgba(255, 255, 255, 0.75);
    margin: 0 0 0.75rem;
}
.hero-title {
    font-size: 3rem;
    font-weight: 800;
    color: white;
    margin: 0 0 1.25rem;
    line-height: 1.15;
}
.hero-date {
    font-size: 1rem;
    color: rgba(255, 255, 255, 0.85);
    margin: 0 0 2rem;
    font-weight: 500;
}
.hero-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}
.hero-btn-primary {
    font-size: 1rem;
    font-weight: 700;
    border: none;
    border-radius: 9999px;
    padding: 0.75rem 1.75rem;
    cursor: pointer;
    transition: opacity 0.15s;
    text-decoration: none;
    display: inline-block;
}
.hero-btn-primary:hover {
    opacity: 0.85;
}

/* ── Quiz overlay ─────────────────────────────────────── */
.quiz-overlay {
    position: fixed;
    inset: 0;
    z-index: 100;
    background: white;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2rem;
}
.quiz-close {
    position: fixed;
    top: 1.25rem;
    left: 1.25rem;
    background: none;
    border: none;
    font-size: 0.95rem;
    color: var(--color-default-red);
    cursor: pointer;
    font-weight: 600;
}

/* ── Quiz CTA section ────────────────────────────────── */
.quiz-section {
    padding: 5rem 2rem 8rem;
}
.quiz-section-inner {
    max-width: 680px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 0;
}
.quiz-section-title {
    font-size: 2.25rem;
    font-weight: 800;
    margin: 0 0 0.75rem;
}
.quiz-section-sub {
    font-size: 1rem;
    line-height: 1.7;
    margin: 0 0 2rem;
    opacity: 0.85;
}
.quiz-section-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    justify-content: center;
}
.quiz-insight {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    background: white;
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    margin-bottom: 2.5rem;
    width: 100%;
}
.quiz-insight-icon {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: var(--light-grey);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    flex-shrink: 0;
}
.quiz-insight-text {
    font-size: 1rem;
    color: var(--default-titles);
    margin: 0;
    line-height: 1.5;
    font-weight: 500;
}
.quiz-result-btn {
    cursor: pointer;
}
.quiz-mascot-placeholder {
    border: 2px dashed;
    border-radius: 16px;
    padding: 3rem 2rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    background: white;
}

/* ── Buttons ─────────────────────────────────────────── */
.btn-filled {
    display: inline-block;
    font-size: 1rem;
    font-weight: 600;
    color: white;
    border: none;
    border-radius: 9999px;
    padding: 0.8rem 1.75rem;
    cursor: pointer;
    transition: opacity 0.15s;
    text-decoration: none;
}
.btn-filled:hover {
    opacity: 0.85;
}
.btn-link {
    display: inline-block;
    text-decoration: none;
}
.btn-outlined {
    font-size: 1rem;
    font-weight: 600;
    border: 2px solid;
    border-radius: 9999px;
    padding: 0.8rem 1.75rem;
    background: transparent;
    cursor: pointer;
    transition: opacity 0.15s;
}
.btn-outlined:hover {
    opacity: 0.75;
}

/* ── Engagement ──────────────────────────────────────── */
.engagement-section {
    background: white;
    padding: 5rem 2rem;
}
.engagement-inner {
    max-width: 1100px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
}
.engagement-eyebrow {
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    margin: 0 0 0.5rem;
}
.engagement-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--default-titles);
    margin: 0 0 1rem;
}
.engagement-body {
    font-size: 1rem;
    color: var(--default-text);
    line-height: 1.7;
    margin: 0 0 2rem;
}
.engagement-logo {
    max-width: 220px;
    max-height: 110px;
    object-fit: contain;
}
/* ── Responsive ──────────────────────────────────────── */
@media (max-width: 900px) {
    /* Hauteur adaptée au contenu empilé (sinon coupé par height fixe) */
    .hero {
        height: auto;
        padding: 2.5rem 0;
    }
    .hero-inner,
    .quiz-section-inner,
    .engagement-inner {
        grid-template-columns: 1fr;
    }
    .hero-title {
        font-size: 2.2rem;
    }
    /* Marges latérales réduites en mobile (16px) */
    .hero-inner {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    .quiz-section {
        padding: 3rem 1rem 4rem;
    }
    .engagement-section {
        padding: 3rem 1rem;
    }
    /* Gap vertical réduit une fois les colonnes empilées */
    .engagement-inner {
        gap: 1.5rem;
    }
}
</style>
