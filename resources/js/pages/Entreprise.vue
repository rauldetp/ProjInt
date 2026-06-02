<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import Quiz from "../components/Quiz.vue";
import QuizResult from "../components/QuizResult.vue";

const route = useRoute();
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
    } catch (e) {
        error.value = e.message;
    } finally {
        loading.value = false;
    }
});

const brandColor = computed(
    () => entreprise.value.couleur_primaire || "#e60f48",
);

const dateRange = computed(() => {
    if (!collecte.value?.date_debut) return "Dates à définir";
    const fmt = (d) =>
        new Date(d).toLocaleDateString("fr-FR", {
            day: "2-digit",
            month: "2-digit",
            year: "numeric",
        });
    const start = fmt(collecte.value.date_debut);
    const end = collecte.value.date_fin ? fmt(collecte.value.date_fin) : start;
    return start === end ? `Le ${start}` : `Le ${start} et ${end}`;
});

function formatDate(date) {
    return new Date(date).toLocaleDateString("fr-FR", {
        day: "2-digit",
        month: "long",
        year: "numeric",
    });
}

async function handleQuizResult(resultat) {
    quizResultat.value = resultat;
    if (!collecte.value?.id) return;
    try {
        await fetch(`/api/collectes/${collecte.value.id}/quiz-result`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
            },
            body: JSON.stringify({ resultat }),
        });
    } catch (e) {
        console.error("Erreur sauvegarde quiz", e);
    }
}
</script>

<template>
    <div v-if="loading" class="state-center">Chargement...</div>
    <div v-else-if="error" class="state-center" style="color: #e60f48">
        {{ error }}
    </div>

    <div v-else class="page" :style="{ '--brand': brandColor }">
        <!-- Navbar -->
        <header class="navbar">
            <div class="navbar-inner">
                <div class="navbar-brand">
                    <RouterLink
                        to="/"
                        class="brand-hug"
                        style="text-decoration: none"
                        >HUG</RouterLink
                    >
                    <div class="brand-sep"></div>
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
                <nav class="navbar-links">
                    <RouterLink to="/label">Label CTS</RouterLink>
                    <RouterLink to="/trophee"
                        >Trophée de la générosité</RouterLink
                    >
                    <a href="#">Coin entreprise</a>
                    <RouterLink
                        to="/contact"
                        style="color: #2c4140; text-decoration: none"
                        class="hover:opacity-60 transition"
                        >Contact</RouterLink
                    >
                </nav>
                <RouterLink
                    to="/login"
                    class="navbar-cta"
                    :style="{ color: brandColor, borderColor: brandColor }"
                >
                    S'inscrire
                </RouterLink>
            </div>
        </header>

        <!-- Hero -->
        <section class="hero">
            <img
                :src="'/images/Hero_Entreprise.webp'"
                alt=""
                class="hero-img"
            />
            <div
                class="hero-overlay"
                :style="{ background: `rgba(44,65,64,0.5)` }"
            ></div>
            <div class="hero-content">
                <p class="hero-eyebrow">Collecte de sang</p>
                <h1 class="hero-title">{{ entreprise.nom }} x HUG</h1>
                <p class="hero-sub" v-if="collecte">
                    Faites un geste citoyen directement sur votre lieu de
                    travail. Testez votre éligibilité en 2 minutes et réservez
                    votre créneau pour soutenir le Centre de Transfusion
                    Sanguine.
                </p>
                <button
                    class="hero-btn"
                    :style="{ background: brandColor }"
                    @click="showQuiz = true"
                >
                    S'inscrire →
                </button>
            </div>
        </section>

        <!-- Info cards -->
        <section id="info" class="info-section">
            <p class="info-title">Informations pratiques</p>
            <div class="info-grid">
                <div class="info-card">
                    <span class="info-icon">📍</span>
                    <p class="info-text">
                        {{
                            collecte?.sur_site
                                ? (entreprise.adresse ?? entreprise.ville)
                                : "Centre de transfusion"
                        }}
                    </p>
                </div>
                <div class="info-card">
                    <span class="info-icon">🕐</span>
                    <p class="info-text">
                        {{ collecte?.horaires ?? "Horaires à définir" }}
                    </p>
                </div>
                <div class="info-card">
                    <span class="info-icon">📅</span>
                    <p class="info-text">{{ dateRange }}</p>
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
                @click="
                    showQuiz = false;
                    quizResultat = null;
                "
            >
                ✕ Fermer
            </button>
        </div>

        <!-- Eligibility section -->
        <section id="eligibilite" class="eligibilite-section">
            <h2 class="eligibilite-title">Suis-je éligible ?</h2>
            <p class="eligibilite-sub">
                Faites le point rapidement sur les conditions principales de
                don.
            </p>
            <div class="eligibilite-card" v-if="collecte">
                <span class="eligibilite-icon">📋</span>
                <span
                    >Déjà
                    <strong>{{ collecte.nb_inscrits_estime }}</strong> autres
                    employés ont passé le test !</span
                >
            </div>
            <div class="eligibilite-actions">
                <button
                    class="btn-filled"
                    :style="{ background: brandColor }"
                    @click="
                        showQuiz = true;
                        quizResultat = null;
                    "
                >
                    Participer dès maintenant →
                </button>
                <button
                    v-if="quizResultat"
                    class="btn-outlined"
                    :style="{ color: brandColor, borderColor: brandColor }"
                    @click="showQuiz = true"
                >
                    Récapitulatif du test précédent
                </button>
            </div>
        </section>

        <!-- 3 steps -->
        <section class="steps-section">
            <h2 class="steps-title">La démarche en trois étapes</h2>
            <div class="steps-grid">
                <div class="step-card">
                    <div
                        class="step-num"
                        :style="{
                            color: brandColor,
                            background: `${brandColor}18`,
                        }"
                    >
                        1
                    </div>
                    <h3 class="step-heading">Accueil</h3>
                    <p class="step-body">
                        Passez notre quiz d'éligibilité rapide en 9 questions
                        conçu avec le CTS pour lever vos doutes en toute
                        confidentialité avant de vous inscrire.
                    </p>
                </div>
                <div class="step-card">
                    <div
                        class="step-num"
                        :style="{
                            color: brandColor,
                            background: `${brandColor}18`,
                        }"
                    >
                        2
                    </div>
                    <h3 class="step-heading">Questionnaire médical</h3>
                    <p class="step-body">
                        Une fois votre créneau réservé, vous remplirez le
                        questionnaire officiel des HUG qui sera validé sur place
                        par l'équipe médicale.
                    </p>
                </div>
                <div class="step-card">
                    <div
                        class="step-num"
                        :style="{
                            color: brandColor,
                            background: `${brandColor}18`,
                        }"
                    >
                        3
                    </div>
                    <h3 class="step-heading">Don</h3>
                    <p class="step-body">
                        Le jour J, vous êtes pris en charge dans vos locaux. Le
                        prélèvement dure moins de 10 minutes et se termine par
                        une collation conviviale offerte par le personnel.
                    </p>
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
                        Dans le cadre de notre politique de Responsabilité
                        Sociale d'Entreprise (RSE),
                        {{ entreprise.nom }} est fière de s'associer
                        historiquement aux Hôpitaux Universitaires de Genève.
                        Parce que votre temps est précieux, la direction libère
                        chaque collaborateur volontaire sur son temps de travail
                        pour lui permettre d'accomplir ce geste solidaire
                        essentiel.
                    </p>
                    <button
                        class="btn-filled"
                        :style="{ background: brandColor }"
                        @click="
                            showQuiz = true;
                            quizResultat = null;
                        "
                    >
                        S'inscrire à la collecte
                    </button>
                </div>
                <div class="engagement-logo-card">
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
                        >{{ entreprise.nom }}</span
                    >
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="site-footer">
            <div class="footer-inner">
                <div class="footer-grid">
                    <div>
                        <span class="footer-hug">HUG</span>
                        <p class="footer-tagline">
                            Hôpitaux<br />Universitaires<br />Genève
                        </p>
                    </div>
                    <div>
                        <p class="footer-col-title">Pages</p>
                        <ul>
                            <li>
                                <RouterLink
                                    to="/label"
                                    style="
                                        font-size: 1rem;
                                        color: white;
                                        text-decoration: none;
                                    "
                                    class="hover:opacity-70 transition"
                                    >Label CTS</RouterLink
                                >
                            </li>
                            <li>
                                <a
                                    href="/#trophee"
                                    style="
                                        font-size: 1rem;
                                        color: white;
                                        text-decoration: none;
                                    "
                                    class="hover:opacity-70 transition"
                                    >Trophée de la générosité</a
                                >
                            </li>
                            <li>
                                <a
                                    href="/#temoignages"
                                    style="
                                        font-size: 1rem;
                                        color: white;
                                        text-decoration: none;
                                    "
                                    class="hover:opacity-70 transition"
                                    >Témoignages</a
                                >
                            </li>
                        </ul>
                    </div>
                    <div>
                        <p class="footer-col-title">Support</p>
                        <ul>
                            <li>
                                <a
                                    href="/#faq"
                                    style="
                                        font-size: 1rem;
                                        color: white;
                                        text-decoration: none;
                                    "
                                    class="hover:opacity-70 transition"
                                    >FAQ</a
                                >
                            </li>
                            <li><a href="#">Contact</a></li>
                        </ul>
                    </div>
                    <div>
                        <p class="footer-col-title">Mentions légales</p>
                        <ul>
                            <li>
                                <a href="#">Politique de confidentialité</a>
                            </li>
                            <li><a href="#">Conditions générales</a></li>
                        </ul>
                    </div>
                </div>
                <div class="footer-copy">
                    <p>
                        © {{ new Date().getFullYear() }} Hôpitaux Universitaire
                        Genève. Tous droits réservés.
                    </p>
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
}

.page {
    font-family: "Instrument Sans", ui-sans-serif, system-ui, sans-serif;
    background: white;
}

/* Navbar */
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
    gap: 0.5rem;
    flex-shrink: 0;
}
.brand-hug {
    font-weight: 800;
    font-size: 1.25rem;
    color: #2c4140;
}
.brand-sep {
    width: 1px;
    height: 20px;
    background: rgba(44, 65, 64, 0.3);
    margin: 0 0.5rem;
}
.brand-company {
    font-weight: 700;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
}
.company-logo-img {
    max-height: 36px;
    object-fit: contain;
}
.navbar-links {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}
.navbar-links a {
    font-size: 1rem;
    font-weight: 500;
    color: #2c4140;
    text-decoration: none;
    transition: opacity 0.15s;
}
.navbar-links a:hover {
    opacity: 0.6;
}
.navbar-cta {
    font-size: 1rem;
    font-weight: 600;
    border: 2px solid;
    border-radius: 9999px;
    padding: 0.4rem 1.25rem;
    text-decoration: none;
    transition: opacity 0.15s;
    flex-shrink: 0;
}
.navbar-cta:hover {
    opacity: 0.75;
}

/* Hero */
.hero {
    position: relative;
    height: 420px;
    display: flex;
    align-items: flex-end;
    padding-bottom: 3rem;
    overflow: hidden;
    background: #2c4140;
}
.hero-img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.65;
}
.hero-overlay {
    position: absolute;
    inset: 0;
}
.hero-content {
    position: relative;
    max-width: 1280px;
    width: 100%;
    margin: 0 auto;
    padding: 0 2rem;
    z-index: 1;
}
.hero-eyebrow {
    font-size: 0.85rem;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.8);
    text-transform: uppercase;
    letter-spacing: 0.15em;
    margin: 0 0 0.5rem;
}
.hero-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: white;
    margin: 0 0 1rem;
    line-height: 1.15;
}
.hero-sub {
    font-size: 1rem;
    color: rgba(255, 255, 255, 0.85);
    max-width: 520px;
    margin: 0 0 1.5rem;
    line-height: 1.65;
}
.hero-btn {
    font-size: 1rem;
    font-weight: 600;
    color: white;
    border: none;
    border-radius: 9999px;
    padding: 0.75rem 1.75rem;
    cursor: pointer;
    transition: opacity 0.15s;
}
.hero-btn:hover {
    opacity: 0.85;
}

/* Info section */
.info-section {
    padding: 3rem 2rem;
    max-width: 1280px;
    margin: 0 auto;
}
.info-title {
    font-size: 1rem;
    font-weight: 600;
    color: #2c4140;
    text-align: center;
    margin: 0 0 1.5rem;
}
.info-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}
.info-card {
    background: #f2f4f3;
    border-radius: 0.75rem;
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    align-items: flex-start;
}
.info-icon {
    font-size: 1.25rem;
}
.info-text {
    font-size: 0.9rem;
    color: #497371;
    margin: 0;
}

/* Quiz overlay */
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

/* Eligibility */
.eligibilite-section {
    padding: 4rem 2rem;
    text-align: center;
    background: linear-gradient(135deg, #65c6c1, #93cfa9);
}
.eligibilite-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2c4140;
    margin: 0 0 0.75rem;
}
.eligibilite-sub {
    font-size: 1rem;
    color: #2c4140;
    opacity: 0.8;
    margin: 0 0 2rem;
}
.eligibilite-card {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    background: white;
    border-radius: 0.75rem;
    padding: 0.85rem 1.5rem;
    font-size: 0.95rem;
    color: #2c4140;
    margin-bottom: 2rem;
}
.eligibilite-icon {
    font-size: 1.1rem;
}
.eligibilite-actions {
    display: flex;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
}
.btn-filled {
    font-size: 1rem;
    font-weight: 600;
    color: white;
    border: none;
    border-radius: 9999px;
    padding: 0.8rem 1.75rem;
    cursor: pointer;
    transition: opacity 0.15s;
}
.btn-filled:hover {
    opacity: 0.85;
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

/* Steps */
.steps-section {
    background: #f2f4f3;
    padding: 4.5rem 2rem;
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

/* Engagement */
.engagement-section {
    background: white;
    padding: 4.5rem 2rem;
}
.engagement-inner {
    max-width: 1100px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
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
    padding: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 200px;
}
.engagement-logo {
    max-width: 200px;
    max-height: 80px;
    object-fit: contain;
}
.engagement-logo-text {
    font-size: 2rem;
    font-weight: 800;
}

/* Footer */
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
    font-size: 1.5rem;
    color: white;
    margin: 0 0 1.25rem;
}
.site-footer ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}
.site-footer ul li a {
    font-size: 1rem;
    color: white;
    text-decoration: none;
    transition: opacity 0.15s;
}
.site-footer ul li a:hover {
    opacity: 0.7;
}
.footer-copy {
    border-top: 1px solid rgba(242, 244, 243, 0.15);
    padding-top: 1.5rem;
    text-align: center;
}
.footer-copy p {
    font-size: 1rem;
    color: #f2f4f3;
    margin: 0;
}

@media (max-width: 900px) {
    .info-grid,
    .steps-grid,
    .engagement-inner {
        grid-template-columns: 1fr;
    }
    .navbar-links {
        display: none;
    }
}
</style>
