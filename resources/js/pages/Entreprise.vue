<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import { useCobrandStore } from "../stores/cobrand";
import CoNavbar from "../components/CoNavbar.vue";

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
        document.title = `${data.entreprise?.nom ?? 'Entreprise'} × HUG — Don du sang`;
    } catch (e) {
        error.value = e.message;
    } finally {
        loading.value = false;
    }
});

const brandColor = computed(() => entreprise.value.couleur_primaire || "#e60f48");
const textOnBrand = computed(() => cobrand.textOnBrand);
const sectionGradient = computed(() => `linear-gradient(135deg, ${brandColor.value}, #ffffff)`);


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
    <div v-else-if="error" class="state-center" style="color: #e60f48">{{ error }}</div>

    <div v-else class="page">

        <!-- Navbar -->
        <CoNavbar :collecte="collecte" />

        <!-- Hero -->
        <section class="hero">
            <div class="hero-overlay"></div>
            <div class="hero-inner">
                <!-- Left: text -->
                <div class="hero-text">
                    <p class="hero-eyebrow">Collecte de don du sang</p>
                    <h1 class="hero-title">
                        {{ entreprise.nom }}<br />× HUG
                    </h1>
                    <p class="hero-date" v-if="collecte"><span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle">calendar_month</span> {{ dateRange }}</p>
                    <div class="hero-actions">
                        <RouterLink
                            v-if="collecte && collecte.active"
                            :to="`/entreprise/${route.params.slug}/inscription`"
                            class="hero-btn-primary"
                            :style="{ background: brandColor, color: textOnBrand }"
                        >
                            S'inscrire →
                        </RouterLink>
                    </div>
                </div>

                <!-- Right: info card -->
                <div class="hero-card" v-if="collecte">
                    <p class="hero-card-title">Informations pratiques</p>
                    <ul class="hero-card-list">
                        <li>
                            <span class="info-icon material-symbols-outlined" style="font-size:18px">location_on</span>
                            <span>{{
                                collecte.sur_site
                                    ? (entreprise.adresse ?? entreprise.ville ?? entreprise.nom)
                                    : "Centre de transfusion sanguine"
                            }}</span>
                        </li>
                        <li>
                            <span class="info-icon material-symbols-outlined" style="font-size:18px">schedule</span>
                            <span>{{ collecte.horaires ?? "Horaires à confirmer" }}</span>
                        </li>
                        <li>
                            <span class="info-icon material-symbols-outlined" style="font-size:18px">calendar_month</span>
                            <span>{{ dateRange }}</span>
                        </li>
                        <li v-if="collecte.nb_inscrits_estime">
                            <span class="info-icon material-symbols-outlined" style="font-size:18px">group</span>
                            <span>{{ collecte.nb_inscrits_estime }} inscrits</span>
                        </li>
                    </ul>
                    <RouterLink
                        :to="`/entreprise/${route.params.slug}/inscription`"
                        class="hero-card-cta"
                        :style="{ background: brandColor, color: textOnBrand }"
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

        <!-- Quiz CTA section -->
        <section class="quiz-section" :style="{ background: sectionGradient }">
            <div class="quiz-section-inner">
                <div class="quiz-insight">
                    <div class="quiz-insight-icon"><span class="material-symbols-outlined" style="font-size:24px">assignment</span></div>
                    <p class="quiz-insight-text">
                        <template v-if="collecte?.nb_inscrits_estime">Déjà <strong>{{ collecte.nb_inscrits_estime }}</strong> autres employés ont passé le test !</template>
                        <template v-else>Rejoignez vos collègues et passez le test d'éligibilité !</template>
                    </p>
                </div>
                <h2 class="quiz-section-title" :style="{ color: textOnBrand }">Suis-je éligible ?</h2>
                <p class="quiz-section-sub" :style="{ color: textOnBrand }">
                    Testez votre éligibilité en 2 minutes et réservez votre créneau pour soutenir le Centre de Transfusion Sanguine.
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
                        :style="{ color: textOnBrand, borderColor: textOnBrand }"
                    >
                        Voir mon résultat →
                    </RouterLink>
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

        <!-- Moins de 500 salariés -->
        <section class="small-co-section">
            <div class="small-co-inner">
                <div class="small-co-text">
                    <p class="small-co-eyebrow" :style="{ color: brandColor }">Toutes les entreprises peuvent agir</p>
                    <h2 class="small-co-title">Votre entreprise compte<br />moins de 500 salariés ?</h2>
                    <p class="small-co-body">
                        Découvrez nos solutions adaptées pour organiser le don du sang, même sans espace dédié dans vos locaux.
                    </p>
                    <ul class="small-co-list">
                        <li class="small-co-item">
                            <span class="material-symbols-outlined small-co-icon" :style="{ color: brandColor }">local_hospital</span>
                            <div>
                                <p class="small-co-item-title">Aux HUG</p>
                                <p class="small-co-item-body">Vos collaborateurs se rendent directement au Centre de Transfusion Sanguine des HUG. Simple, encadré et sans organisation logistique.</p>
                            </div>
                        </li>
                        <li class="small-co-item">
                            <span class="material-symbols-outlined small-co-icon" :style="{ color: brandColor }">location_city</span>
                            <div>
                                <p class="small-co-item-title">Dans votre commune</p>
                                <p class="small-co-item-body">Rejoignez une collecte mobile organisée dans votre région. Nous vous informons des prochaines dates à proximité.</p>
                            </div>
                        </li>
                    </ul>
                    <a href="#" class="small-co-btn" :style="{ borderColor: brandColor, color: brandColor }">
                        Découvrir les aspects réglementaires
                    </a>
                </div>
                <div class="small-co-img">
                    <img src="/images/thumbnail_mouvement.webp" alt="" />
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
                        v-if="collecte && collecte.active"
                        :to="`/entreprise/${route.params.slug}/inscription`"
                        class="btn-filled btn-link"
                        :style="{ background: brandColor, color: textOnBrand }"
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
                        <span class="material-symbols-outlined" style="font-size:16px; vertical-align: middle">emoji_events</span>
                        Label CTS {{ label.date_attribution ? new Date(label.date_attribution).getFullYear() : '' }}
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

/* ── Hero ────────────────────────────────────────────── */
.hero {
    position: relative;
    overflow: hidden;
    height: 512px;
    display: flex;
    align-items: center;
    background-image: url('/images/Hero_Cobrand.webp');
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

/* Hero info card */
.hero-card {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: none;
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
    background: #f2f4f3;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    flex-shrink: 0;
}
.quiz-insight-text {
    font-size: 1rem;
    color: #2c4140;
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

/* ── Moins de 500 salariés ───────────────────────────── */
.small-co-section {
    background: #f2f4f3;
    padding: 72px 0;
}
.small-co-inner {
    max-width: 1152px;
    margin: 0 auto;
    padding: 0 2rem;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
}
.small-co-eyebrow {
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin: 0 0 12px;
}
.small-co-title {
    font-size: 32px;
    font-weight: 700;
    color: #2c4140;
    line-height: 1.2;
    margin: 0 0 16px;
}
.small-co-body {
    font-size: 16px;
    color: #497371;
    line-height: 1.7;
    margin: 0 0 24px;
}
.small-co-list {
    list-style: none;
    padding: 0;
    margin: 0 0 32px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.small-co-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}
.small-co-icon {
    font-size: 22px;
    flex-shrink: 0;
    margin-top: 2px;
}
.small-co-item-title {
    font-size: 16px;
    font-weight: 600;
    color: #2c4140;
    margin: 0 0 4px;
}
.small-co-item-body {
    font-size: 14px;
    color: #497371;
    line-height: 1.6;
    margin: 0;
}
.small-co-btn {
    display: inline-block;
    border: 2px solid;
    border-radius: 9999px;
    padding: 12px 24px;
    font-size: 15px;
    font-weight: 600;
    background: transparent;
    text-decoration: none;
    transition: opacity 0.15s;
}
.small-co-btn:hover { opacity: 0.7; }
.small-co-img {
    border-radius: 1rem;
    overflow: hidden;
    height: 380px;
}
.small-co-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* ── Responsive ──────────────────────────────────────── */
@media (max-width: 900px) {
    .hero-inner,
    .quiz-section-inner,
    .engagement-inner,
    .small-co-inner {
        grid-template-columns: 1fr;
    }
    .steps-grid {
        grid-template-columns: 1fr;
    }
    .hero-title {
        font-size: 2.2rem;
    }
    .footer-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .small-co-img {
        height: 220px;
    }
}
</style>
