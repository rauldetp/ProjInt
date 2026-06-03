<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import { useCobrandStore } from "../stores/cobrand";
import { useCoinEntrepriseLink } from "../composables/useCoinEntrepriseLink";
import Quiz from "../components/Quiz.vue";
import QuizResult from "../components/QuizResult.vue";

const route = useRoute();
const cobrand = useCobrandStore();
const { coinEntrepriseLink } = useCoinEntrepriseLink();

const entreprise = ref({});
const collecte = ref(null);
const label = ref(null);
const trophees = ref([]);
const loading = ref(true);
const error = ref(null);
const showQuiz = ref(false);
const quizResultat = ref(null);

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
        document.title = `${data.entreprise?.nom ?? 'Entreprise'} × HUG — Don du sang`;
    } catch (e) {
        error.value = e.message;
    } finally {
        loading.value = false;
    }
});

const brandColor = computed(() => entreprise.value.couleur_primaire || "#e60f48");

const heroGradient = computed(() =>
    `linear-gradient(135deg, ${brandColor.value}, #ffffff)`
);

const quizGradient = computed(() =>
    `linear-gradient(135deg, ${brandColor.value}22, ${brandColor.value}0a)`
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

async function handleQuizResult(resultat) {
    quizResultat.value = resultat;
    if (!collecte.value?.id) return;
    try {
        await fetch(`/api/collectes/${collecte.value.id}/quiz-result`, {
            method: "POST",
            headers: { "Content-Type": "application/json", Accept: "application/json" },
            body: JSON.stringify({ resultat }),
        });
    } catch (e) {
        console.error("Erreur sauvegarde quiz", e);
    }
}
</script>

<template>
    <div v-if="loading" class="state-center">Chargement...</div>
    <div v-else-if="error" class="state-center" style="color: #e60f48">{{ error }}</div>

    <div v-else class="page">

        <!-- Navbar -->
        <header class="navbar">
            <div class="navbar-inner">
                <!-- Brand -->
                <div class="navbar-brand">
                    <RouterLink to="/" class="brand-hug">HUG</RouterLink>
                    <span class="brand-pipe">|</span>
                    <span class="brand-subtitle">Don du sang</span>
                    <span class="brand-cross">×</span>
                    <span class="brand-company" :style="{ color: brandColor }">
                        <img
                            v-if="entreprise.logo"
                            :src="entreprise.logo"
                            :alt="entreprise.nom"
                            class="company-logo-img"
                        />
                        <span v-else>{{ entreprise.nom }}</span>
                    </span>
                </div>

                <!-- Nav links -->
                <nav class="navbar-links">
                    <RouterLink :to="`/entreprise/${route.params.slug}`">Accueil</RouterLink>
                    <RouterLink :to="`/entreprise/${route.params.slug}/label`">Label CTS</RouterLink>
                    <RouterLink :to="`/entreprise/${route.params.slug}/trophee`">Trophée de la générosité</RouterLink>
                    <RouterLink :to="coinEntrepriseLink">Coin entreprise</RouterLink>
                </nav>

                <!-- CTA -->
                <RouterLink
                    :to="`/entreprise/${route.params.slug}/inscription`"
                    class="navbar-cta"
                    :style="{ color: brandColor, borderColor: brandColor }"
                >
                    S'inscrire à la collecte
                </RouterLink>
            </div>
        </header>

        <!-- Hero -->
        <section class="hero" :style="{ background: heroGradient }">
            <div class="hero-inner">
                <!-- Left: text -->
                <div class="hero-text">
                    <p class="hero-eyebrow">Collecte de don du sang</p>
                    <h1 class="hero-title">
                        {{ entreprise.nom }}<br />× HUG
                    </h1>
                    <p class="hero-date" v-if="collecte">
                        📅 {{ dateRange }}
                    </p>
                    <div class="hero-actions">
                        <button
                            class="hero-btn-primary"
                            @click="showQuiz = true; quizResultat = null"
                        >
                            Tester mon éligibilité →
                        </button>
                        <RouterLink
                            :to="`/entreprise/${route.params.slug}/inscription`"
                            class="hero-btn-secondary"
                        >
                            S'inscrire directement
                        </RouterLink>
                    </div>
                </div>

                <!-- Right: info card -->
                <div class="hero-card" v-if="collecte">
                    <p class="hero-card-title">Informations pratiques</p>
                    <ul class="hero-card-list">
                        <li>
                            <span class="info-icon">📍</span>
                            <span>{{
                                collecte.sur_site
                                    ? (entreprise.adresse ?? entreprise.ville ?? entreprise.nom)
                                    : "Centre de transfusion sanguine"
                            }}</span>
                        </li>
                        <li>
                            <span class="info-icon">🕐</span>
                            <span>{{ collecte.horaires ?? "Horaires à confirmer" }}</span>
                        </li>
                        <li>
                            <span class="info-icon">📅</span>
                            <span>{{ dateRange }}</span>
                        </li>
                        <li v-if="collecte.nb_inscrits_estime">
                            <span class="info-icon">👥</span>
                            <span>{{ collecte.nb_inscrits_estime }} inscrits</span>
                        </li>
                    </ul>
                    <RouterLink
                        :to="`/entreprise/${route.params.slug}/inscription`"
                        class="hero-card-cta"
                        :style="{ background: brandColor }"
                    >
                        Réserver mon créneau →
                    </RouterLink>
                </div>

                <!-- Fallback card if no collecte -->
                <div class="hero-card" v-else>
                    <p class="hero-card-title">Prochaine collecte</p>
                    <p style="font-size: 14px; color: #497371; margin: 0">
                        Aucune collecte active pour le moment. Revenez bientôt !
                    </p>
                </div>
            </div>
        </section>

        <!-- Quiz overlay -->
        <div v-if="showQuiz" class="quiz-overlay">
            <QuizResult
                v-if="quizResultat"
                :resultat="quizResultat"
                :collecte="collecte"
            />
            <Quiz v-else @result="handleQuizResult" />
            <button
                class="quiz-close"
                @click="showQuiz = false; quizResultat = null"
            >
                ✕ Fermer
            </button>
        </div>

        <!-- Quiz CTA section -->
        <section class="quiz-section" :style="{ background: quizGradient }">
            <div class="quiz-section-inner">
                <div class="quiz-section-text">
                    <h2 class="quiz-section-title">Suis-je éligible au don ?</h2>
                    <p class="quiz-section-sub">
                        Faites le point en 2 minutes sur les conditions principales de don.
                        Notre quiz confidentiel vous guide avant votre inscription.
                    </p>
                    <div class="quiz-section-actions">
                        <button
                            class="btn-filled"
                            :style="{ background: brandColor }"
                            @click="showQuiz = true; quizResultat = null"
                        >
                            Faire le quiz →
                        </button>
                        <button
                            v-if="quizResultat"
                            class="btn-outlined"
                            :style="{ color: brandColor, borderColor: brandColor }"
                            @click="showQuiz = true"
                        >
                            Voir mon résultat
                        </button>
                    </div>
                    <div class="quiz-stat" v-if="collecte?.nb_inscrits_estime">
                        <span>📋</span>
                        <span>Déjà <strong>{{ collecte.nb_inscrits_estime }}</strong> employés ont participé</span>
                    </div>
                </div>
                <div class="quiz-section-mascot">
                    <div class="quiz-mascot-placeholder" :style="{ borderColor: brandColor + '40' }">
                        <span style="font-size: 48px">🩸</span>
                        <p :style="{ color: brandColor, fontWeight: 600, fontSize: '14px', margin: '8px 0 0' }">Quiz à venir</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3 steps -->
        <section class="steps-section">
            <h2 class="steps-title">La démarche en trois étapes</h2>
            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-num" :style="{ color: brandColor, background: brandColor + '18' }">1</div>
                    <h3 class="step-heading">Accueil & quiz</h3>
                    <p class="step-body">
                        Passez notre quiz d'éligibilité rapide en 9 questions conçu avec le CTS
                        pour lever vos doutes en toute confidentialité avant de vous inscrire.
                    </p>
                </div>
                <div class="step-card">
                    <div class="step-num" :style="{ color: brandColor, background: brandColor + '18' }">2</div>
                    <h3 class="step-heading">Questionnaire médical</h3>
                    <p class="step-body">
                        Une fois votre créneau réservé, vous remplirez le questionnaire officiel
                        des HUG qui sera validé sur place par l'équipe médicale.
                    </p>
                </div>
                <div class="step-card">
                    <div class="step-num" :style="{ color: brandColor, background: brandColor + '18' }">3</div>
                    <h3 class="step-heading">Le don</h3>
                    <p class="step-body">
                        Le jour J, vous êtes pris en charge dans vos locaux. Le prélèvement
                        dure moins de 10 minutes et se termine par une collation conviviale.
                    </p>
                </div>
            </div>
        </section>

        <!-- Engagement -->
        <section class="engagement-section">
            <div class="engagement-inner">
                <div class="engagement-text">
                    <p class="engagement-eyebrow" :style="{ color: brandColor }">Notre engagement RSE</p>
                    <h2 class="engagement-title">{{ entreprise.nom }} s'engage</h2>
                    <p class="engagement-body">
                        Dans le cadre de sa politique de Responsabilité Sociale d'Entreprise (RSE),
                        {{ entreprise.nom }} est fière de s'associer aux Hôpitaux Universitaires de Genève.
                        Parce que votre temps est précieux, la direction libère chaque collaborateur
                        volontaire sur son temps de travail pour accomplir ce geste solidaire essentiel.
                    </p>
                    <RouterLink
                        :to="`/entreprise/${route.params.slug}/inscription`"
                        class="btn-filled btn-link"
                        :style="{ background: brandColor }"
                    >
                        S'inscrire à la collecte →
                    </RouterLink>
                </div>
                <div class="engagement-logo-card">
                    <div class="engagement-logo-hug">
                        <span style="font-weight: 800; font-size: 28px; color: #2c4140">HUG</span>
                        <span style="font-size: 24px; color: #c0cac9; margin: 0 12px">×</span>
                        <img
                            v-if="entreprise.logo"
                            :src="entreprise.logo"
                            :alt="entreprise.nom"
                            class="engagement-logo"
                        />
                        <span
                            v-else
                            class="engagement-logo-text"
                            :style="{ color: brandColor }"
                        >{{ entreprise.nom }}</span>
                    </div>
                    <p class="engagement-label" v-if="label">
                        🏆 Label CTS {{ label.date_attribution ? new Date(label.date_attribution).getFullYear() : '' }}
                    </p>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="site-footer">
            <div class="footer-inner">
                <div class="footer-grid">
                    <div>
                        <span class="footer-hug">HUG</span>
                        <p class="footer-tagline">Hôpitaux<br />Universitaires<br />Genève</p>
                    </div>
                    <div>
                        <p class="footer-col-title">Pages</p>
                        <ul>
                            <li><RouterLink to="/label">Label CTS</RouterLink></li>
                            <li><RouterLink to="/trophee">Trophée de la générosité</RouterLink></li>
                            <li><RouterLink :to="`/entreprise/${route.params.slug}`">Accueil collecte</RouterLink></li>
                        </ul>
                    </div>
                    <div>
                        <p class="footer-col-title">Support</p>
                        <ul>
                            <li><RouterLink to="/faq">FAQ</RouterLink></li>
                            <li><RouterLink to="/contact">Contact</RouterLink></li>
                        </ul>
                    </div>
                    <div>
                        <p class="footer-col-title">Mentions légales</p>
                        <ul>
                            <li><a href="#">Politique de confidentialité</a></li>
                            <li><a href="#">Conditions générales</a></li>
                        </ul>
                    </div>
                </div>
                <div class="footer-copy">
                    <p>© {{ new Date().getFullYear() }} Hôpitaux Universitaires Genève. Tous droits réservés.</p>
                </div>
            </div>
        </footer>

    </div>
</template>

<style scoped>
.state-center {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    font-size: 1.1rem;
    color: #497371;
    font-family: 'Instrument Sans', sans-serif;
}

.page {
    font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
    background: white;
}

/* ── Navbar ─────────────────────────────────────────── */
.navbar {
    background: white;
    border-bottom: 1px solid #f2f4f3;
    height: 76px;
    position: sticky;
    top: 0;
    z-index: 50;
}
.navbar-inner {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 2rem;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 2rem;
}
.navbar-brand {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    flex-shrink: 0;
}
.brand-hug {
    font-weight: 800;
    font-size: 1.2rem;
    color: #2c4140;
    text-decoration: none;
}
.brand-pipe {
    color: rgba(44, 65, 64, 0.3);
    font-size: 1.1rem;
    margin: 0 0.2rem;
}
.brand-subtitle {
    font-size: 0.9rem;
    font-weight: 600;
    color: #497371;
}
.brand-cross {
    color: rgba(44, 65, 64, 0.3);
    font-size: 1.1rem;
    margin: 0 0.2rem;
}
.brand-company {
    font-weight: 700;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
}
.company-logo-img {
    max-height: 32px;
    object-fit: contain;
}
.navbar-links {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}
.navbar-links a {
    font-size: 0.9rem;
    font-weight: 500;
    color: #2c4140;
    text-decoration: none;
    transition: opacity 0.15s;
}
.navbar-links a:hover {
    opacity: 0.6;
}
.navbar-cta {
    font-size: 0.85rem;
    font-weight: 600;
    border: 2px solid;
    border-radius: 9999px;
    padding: 0.4rem 1.1rem;
    text-decoration: none;
    transition: opacity 0.15s;
    flex-shrink: 0;
    white-space: nowrap;
}
.navbar-cta:hover {
    opacity: 0.75;
}

/* ── Hero ────────────────────────────────────────────── */
.hero {
    padding: 5rem 2rem;
}
.hero-inner {
    max-width: 1100px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 3rem;
    align-items: center;
}
.hero-text {
    color: white;
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
    color: white;
    background: rgba(255, 255, 255, 0.25);
    border: 2px solid rgba(255, 255, 255, 0.6);
    border-radius: 9999px;
    padding: 0.75rem 1.75rem;
    cursor: pointer;
    transition: background 0.15s;
    backdrop-filter: blur(4px);
}
.hero-btn-primary:hover {
    background: rgba(255, 255, 255, 0.35);
}
.hero-btn-secondary {
    font-size: 0.95rem;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.85);
    background: transparent;
    border: 2px solid rgba(255, 255, 255, 0.4);
    border-radius: 9999px;
    padding: 0.75rem 1.5rem;
    text-decoration: none;
    transition: opacity 0.15s;
    display: inline-flex;
    align-items: center;
}
.hero-btn-secondary:hover {
    opacity: 0.75;
}

/* Hero info card */
.hero-card {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 8px 40px rgba(0, 0, 0, 0.15);
}
.hero-card-title {
    font-size: 1rem;
    font-weight: 700;
    color: #2c4140;
    margin: 0 0 1.25rem;
}
.hero-card-list {
    list-style: none;
    padding: 0;
    margin: 0 0 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 0.85rem;
}
.hero-card-list li {
    display: flex;
    align-items: flex-start;
    gap: 0.6rem;
    font-size: 0.9rem;
    color: #497371;
    line-height: 1.5;
}
.info-icon {
    flex-shrink: 0;
    font-size: 1rem;
}
.hero-card-cta {
    display: block;
    text-align: center;
    color: white;
    font-weight: 700;
    font-size: 0.95rem;
    text-decoration: none;
    border-radius: 9999px;
    padding: 0.75rem 1.5rem;
    transition: opacity 0.15s;
}
.hero-card-cta:hover {
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
    color: #e60f48;
    cursor: pointer;
    font-weight: 600;
}

/* ── Quiz CTA section ────────────────────────────────── */
.quiz-section {
    padding: 5rem 2rem;
}
.quiz-section-inner {
    max-width: 1100px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 4rem;
    align-items: center;
}
.quiz-section-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2c4140;
    margin: 0 0 0.75rem;
}
.quiz-section-sub {
    font-size: 1rem;
    color: #497371;
    line-height: 1.7;
    margin: 0 0 2rem;
    max-width: 520px;
}
.quiz-section-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 1.5rem;
}
.quiz-stat {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: white;
    border-radius: 9999px;
    padding: 0.5rem 1rem;
    font-size: 0.85rem;
    color: #497371;
    box-shadow: 0 1px 6px rgba(0,0,0,0.06);
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
    background: white;
    cursor: pointer;
    transition: opacity 0.15s;
}
.btn-outlined:hover {
    opacity: 0.75;
}

/* ── Steps ───────────────────────────────────────────── */
.steps-section {
    background: #f2f4f3;
    padding: 5rem 2rem;
}
.steps-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2c4140;
    text-align: center;
    margin: 0 0 2.5rem;
}
.steps-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    max-width: 1100px;
    margin: 0 auto;
}
.step-card {
    background: white;
    border-radius: 1rem;
    padding: 2rem;
}
.step-num {
    width: 2.25rem;
    height: 2.25rem;
    border-radius: 9999px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1rem;
    margin-bottom: 1rem;
}
.step-heading {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2c4140;
    margin: 0 0 0.75rem;
}
.step-body {
    font-size: 0.9rem;
    color: #497371;
    line-height: 1.65;
    margin: 0;
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
    color: #2c4140;
    margin: 0 0 1rem;
}
.engagement-body {
    font-size: 1rem;
    color: #497371;
    line-height: 1.7;
    margin: 0 0 2rem;
}
.engagement-logo-card {
    background: #f2f4f3;
    border-radius: 1rem;
    padding: 3rem 2.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 1.5rem;
    min-height: 220px;
}
.engagement-logo-hug {
    display: flex;
    align-items: center;
    justify-content: center;
}
.engagement-logo {
    max-width: 160px;
    max-height: 64px;
    object-fit: contain;
}
.engagement-logo-text {
    font-size: 1.75rem;
    font-weight: 800;
}
.engagement-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #497371;
    background: white;
    border-radius: 9999px;
    padding: 0.4rem 1rem;
    margin: 0;
}

/* ── Footer ──────────────────────────────────────────── */
.site-footer {
    background: #2c4140;
    padding: 3.5rem 0 2.5rem;
}
.footer-inner {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 2rem;
}
.footer-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 2.5rem;
    margin-bottom: 3rem;
}
.footer-hug {
    display: block;
    font-weight: 800;
    font-size: 1.5rem;
    color: white;
    margin-bottom: 0.25rem;
}
.footer-tagline {
    font-size: 0.75rem;
    color: #93cfa9;
    line-height: 1.6;
    margin: 0;
}
.footer-col-title {
    font-weight: 700;
    font-size: 0.9rem;
    color: white;
    margin: 0 0 1.25rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
}
.site-footer ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}
.site-footer ul li a,
.site-footer ul li .router-link-active,
.site-footer ul li a:visited {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.75);
    text-decoration: none;
    transition: opacity 0.15s;
}
.site-footer ul li a:hover {
    opacity: 1;
    color: white;
}
.footer-copy {
    border-top: 1px solid rgba(242, 244, 243, 0.15);
    padding-top: 1.5rem;
    text-align: center;
}
.footer-copy p {
    font-size: 0.8rem;
    color: rgba(242, 244, 243, 0.45);
    margin: 0;
}

/* ── Responsive ──────────────────────────────────────── */
@media (max-width: 900px) {
    .hero-inner,
    .quiz-section-inner,
    .engagement-inner {
        grid-template-columns: 1fr;
    }
    .steps-grid {
        grid-template-columns: 1fr;
    }
    .navbar-links {
        display: none;
    }
    .hero-title {
        font-size: 2.2rem;
    }
    .footer-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
