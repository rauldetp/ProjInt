<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import { useCobrandStore } from "../stores/cobrand";
import CoNavbar from "../components/CoNavbar.vue";

const route = useRoute();
const cobrand = useCobrandStore();

const entreprise = ref(null);
const collecte  = ref(null);
const loading   = ref(true);

const brandColor  = computed(() => cobrand.couleurPrimaire || "#e60f48");
const textOnBrand = computed(() => cobrand.textOnBrand);

// ── State ────────────────────────────────────────────────────────
const step           = ref("intro"); // intro | quiz | info | recap | result
const currentQ       = ref(0);
const answers        = ref([]);
const selectedAnswer = ref(null);
const currentInfo    = ref(null);   // { message, reassurance?, type: 'warn'|'tip' }
const resultat       = ref(null);

const storageKey = computed(() => `quizResult_${route.params.slug}`);

onMounted(async () => {
    try {
        const res = await fetch(`/api/entreprises/${route.params.slug}`);
        if (res.ok) {
            const data = await res.json();
            entreprise.value = data.entreprise;
            collecte.value   = data.collecte ?? null;
            if (data.entreprise) cobrand.set(data.entreprise);
        }
    } catch {}
    finally { loading.value = false; }

    const saved = localStorage.getItem(storageKey.value);
    if (saved) resultat.value = saved;

    if (route.query.voir === "resultat") {
        step.value = resultat.value ? "result" : "intro";
    }

    document.title = `Quiz d'éligibilité — ${cobrand.nom || "HUG"}`;
});

// ── Questions ────────────────────────────────────────────────────
const questions = [
    {
        icon: "🎂",
        text: "Avez-vous entre 18 et 75 ans ?",
        options: ["Oui", "Non"],
        feedback: { trigger: "Non", type: "warn", message: "Le don du sang est soumis à certaines limites d'âge afin de garantir la sécurité des donneurs et des patients." },
    },
    {
        icon: "❤️",
        text: "Vous sentez-vous en bonne santé aujourd'hui ?",
        options: ["Oui", "Non"],
        feedback: { trigger: "Non", type: "warn", message: "Même un simple refroidissement peut empêcher temporairement un don. Le plus important est de venir lorsque vous êtes en pleine forme." },
        micro: { type: "tip", message: "Le don dure généralement moins de 10 minutes." },
    },
    {
        icon: "💉",
        text: "Avez-vous eu un tatouage ou un piercing récemment ?",
        options: ["Oui", "Non"],
        feedback: { trigger: "Oui", type: "warn", message: "Après un tatouage ou un piercing, un délai est parfois nécessaire avant de pouvoir donner son sang.", reassurance: "Cela ne signifie pas que vous ne pourrez plus donner à l'avenir." },
    },
    {
        icon: "🏥",
        text: "Avez-vous subi une opération ou un traitement médical récemment ?",
        options: ["Oui", "Non"],
        feedback: { trigger: "Oui", type: "warn", message: "Certaines interventions nécessitent un délai avant un don. Le personnel médical vérifiera cela avec vous." },
        micro: { type: "tip", message: "Le personnel médical vous accompagne à chaque étape." },
    },
    {
        icon: "💊",
        text: "Prenez-vous actuellement des médicaments importants ?",
        options: ["Oui", "Non"],
        feedback: { trigger: "Oui", type: "warn", message: "Certains traitements sont compatibles avec le don, d'autres nécessitent un délai temporaire." },
    },
    {
        icon: "🍽️",
        text: "Avez-vous suffisamment mangé et bu aujourd'hui ?",
        options: ["Oui", "Non"],
        feedback: { trigger: "Non", type: "warn", message: "Il est fortement recommandé de manger et de bien s'hydrater avant un don afin d'éviter les malaises." },
        micro: { type: "tip", message: "La majorité des donneurs reprennent leur journée normalement." },
    },
    {
        icon: "👶",
        text: "Êtes-vous enceinte ou avez-vous accouché récemment ?",
        options: ["Oui", "Non"],
        feedback: { trigger: "Oui", type: "warn", message: "Un délai est nécessaire après une grossesse avant de pouvoir donner son sang." },
    },
    {
        icon: "✈️",
        text: "Avez-vous voyagé récemment dans certaines régions à risque ?",
        options: ["Oui", "Non", "Je ne sais pas"],
        feedback: { trigger: "Oui", type: "warn", message: "Certaines destinations nécessitent un délai temporaire avant le don." },
        micro: { type: "tip", message: "Vous n'êtes pas seul : votre entreprise participe avec vous." },
    },
    {
        icon: "🌟",
        text: "Souhaitez-vous participer à la collecte de votre entreprise ?",
        options: ["Oui", "J'hésite encore"],
        feedback: { trigger: "J'hésite encore", type: "tip", message: "C'est normal d'avoir des questions avant un premier don. Courage est là pour vous accompagner." },
    },
];

const progress = computed(() => Math.round(((currentQ.value) / questions.length) * 100));

// ── Actions ──────────────────────────────────────────────────────
function startQuiz() {
    currentQ.value = 0;
    answers.value  = [];
    selectedAnswer.value = null;
    currentInfo.value    = null;
    step.value = "quiz";
}

function viewPreviousResult() {
    if (resultat.value) step.value = "result";
    else startQuiz();
}

function selectAnswer(opt) {
    selectedAnswer.value = opt;
}

function isGoodAnswer(opt) {
    const q = questions[currentQ.value];
    return !q.feedback || opt !== q.feedback.trigger;
}

function confirm() {
    const opt = selectedAnswer.value;
    if (!opt) return;

    answers.value = [...answers.value.slice(0, currentQ.value), opt];

    const q = questions[currentQ.value];
    const hasFeedback = q.feedback && opt === q.feedback.trigger;
    const hasMicro    = q.micro && opt !== q.feedback?.trigger;

    if (hasFeedback) {
        currentInfo.value = q.feedback;
        step.value = "info";
    } else if (hasMicro) {
        currentInfo.value = q.micro;
        step.value = "info";
    } else {
        advanceQuestion();
    }
}

function continueFromInfo() {
    currentInfo.value = null;
    advanceQuestion();
}

function advanceQuestion() {
    selectedAnswer.value = null;
    if (currentQ.value < questions.length - 1) {
        currentQ.value++;
        step.value = "quiz";
    } else {
        step.value = "recap";
    }
}

function goBack() {
    if (step.value === "info") {
        // undo last answer, back to question
        answers.value = answers.value.slice(0, currentQ.value);
        selectedAnswer.value = null;
        currentInfo.value    = null;
        step.value = "quiz";
    } else if (step.value === "quiz") {
        if (currentQ.value > 0) {
            currentQ.value--;
            selectedAnswer.value = answers.value[currentQ.value] ?? null;
        } else {
            step.value = "intro";
        }
    } else if (step.value === "recap") {
        currentQ.value = questions.length - 1;
        selectedAnswer.value = answers.value[currentQ.value] ?? null;
        step.value = "quiz";
    }
}

function showResult() {
    const r = computeResult();
    resultat.value = r;
    localStorage.setItem(storageKey.value, r);
    if (collecte.value?.id) {
        fetch(`/api/collectes/${collecte.value.id}/quiz-result`, {
            method: "POST",
            headers: { "Content-Type": "application/json", Accept: "application/json" },
            body: JSON.stringify({ resultat: r }),
        }).catch(() => {});
    }
    step.value = "result";
}

function computeResult() {
    const a = answers.value;
    if (a[0] === "Non") return "non-eligible";
    if (a[1] === "Non") return "non-eligible";
    if (a[2] === "Oui") return "non-eligible";
    if (a[3] === "Oui") return "non-eligible";
    if (a[5] === "Non") return "non-eligible";
    if (a[6] === "Oui") return "non-eligible";
    if (a[7] === "Oui") return "non-eligible";
    if (a[4] === "Oui") return "incertain";
    if (a[7] === "Je ne sais pas") return "incertain";
    return "eligible";
}

function retakeQuiz() {
    resultat.value = null;
    localStorage.removeItem(storageKey.value);
    startQuiz();
}

function getAnswerBadgeStyle(answer, q) {
    if (!answer) return { background: '#f2f4f3', color: '#8fa8a6' };
    const isGood = q.feedback ? answer !== q.feedback.trigger : true;
    if (isGood) return { background: '#dcfce7', color: '#166534' };
    return { background: '#fee2e2', color: '#991b1b' };
}
</script>

<template>
    <div v-if="loading" class="state-loading">Chargement…</div>
    <div v-else class="page">

        <CoNavbar :collecte="collecte" />

        <!-- ══════════════════════════════════════════════════════
             INTRO — split screen
        ═══════════════════════════════════════════════════════ -->
        <div v-if="step === 'intro'" class="split-screen">

            <!-- Mascotte -->
            <div class="mascotte-col" :style="{ background: brandColor + '0c' }">
                <div class="mascotte-wrap">
                    <!-- TODO: remplacer par <img src="/images/courage-intro.png" alt="Courage"> -->
                    <div class="mascotte-placeholder" :style="{ borderColor: brandColor + '40', color: brandColor }">
                        🦫
                        <span class="mascotte-label">Courage</span>
                    </div>
                </div>
            </div>

            <!-- Contenu -->
            <div class="content-col">
                <div class="intro-content">
                    <div class="intro-badge" :style="{ background: brandColor + '15', color: brandColor }">
                        9 questions · 2 minutes
                    </div>
                    <h1 class="intro-title">Je suis Courage,<br>votre guide !</h1>
                    <p class="intro-sub">
                        En quelques questions simples, je vous aide à savoir si vous pouvez probablement participer à la collecte
                        <strong v-if="entreprise?.nom">de {{ entreprise.nom }}</strong>.
                    </p>
                    <p class="intro-disclaimer">
                        Ce quiz ne remplace pas le questionnaire médical officiel réalisé sur place avec le personnel du CTS.
                    </p>
                    <div class="intro-actions">
                        <button
                            class="btn-primary"
                            :style="{ background: brandColor, color: textOnBrand }"
                            @click="startQuiz"
                        >
                            Commencer le quiz →
                        </button>
                        <button
                            class="btn-outline"
                            :style="{ color: brandColor, borderColor: brandColor }"
                            @click="viewPreviousResult"
                        >
                            Voir mon résultat →
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════════════
             QUESTION
        ═══════════════════════════════════════════════════════ -->
        <div v-else-if="step === 'quiz'" class="quiz-screen">

            <!-- Progress dots -->
            <div class="progress-dots">
                <span
                    v-for="(_, i) in questions"
                    :key="i"
                    class="dot"
                    :class="{ 'dot-active': i === currentQ, 'dot-done': i < currentQ }"
                    :style="i === currentQ || i < currentQ ? { background: brandColor } : {}"
                ></span>
            </div>

            <div class="quiz-inner">
                <!-- Icon question -->
                <div class="question-icon" :style="{ background: brandColor + '15' }">
                    <span>{{ questions[currentQ].icon }}</span>
                </div>

                <p class="quiz-counter" :style="{ color: brandColor }">
                    Question {{ currentQ + 1 }} / {{ questions.length }}
                </p>
                <h2 class="quiz-question">{{ questions[currentQ].text }}</h2>

                <!-- Options -->
                <div class="quiz-options">
                    <button
                        v-for="opt in questions[currentQ].options"
                        :key="opt"
                        class="quiz-option"
                        :class="{
                            'is-good': selectedAnswer === opt && isGoodAnswer(opt),
                            'is-bad':  selectedAnswer === opt && !isGoodAnswer(opt),
                        }"
                        @click="selectAnswer(opt)"
                    >
                        <span class="opt-icon">
                            <svg v-if="selectedAnswer === opt && isGoodAnswer(opt)" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            <svg v-else-if="selectedAnswer === opt && !isGoodAnswer(opt)" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </span>
                        <span>{{ opt }}</span>
                    </button>
                </div>

                <!-- Prochaine question -->
                <Transition name="slide-up">
                    <button
                        v-if="selectedAnswer"
                        class="btn-continuer"
                        @click="confirm"
                    >
                        Prochaine question →
                    </button>
                </Transition>

                <button class="btn-back" @click="goBack">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                    Retour
                </button>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════════════
             SLIDE INFO — "Courage vous informe !"
        ═══════════════════════════════════════════════════════ -->
        <div v-else-if="step === 'info'" class="info-screen">
            <div class="info-inner">

                <div class="info-header">
                    <div class="info-avatar" :style="currentInfo?.type === 'tip'
                        ? { background: '#f0fdf8', borderColor: '#a7f3d0' }
                        : { background: '#fff8e1', borderColor: '#fde68a' }">
                        <!-- TODO: remplacer par <img src="/images/courage-thumbs.png" alt="Courage"> -->
                        <span class="info-avatar-emoji">🦫</span>
                    </div>
                    <h2 class="info-title">Courage vous informe !</h2>
                </div>

                <div
                    class="info-card"
                    :class="currentInfo?.type === 'tip' ? 'info-card-tip' : 'info-card-warn'"
                >
                    <p class="info-msg">{{ currentInfo?.message }}</p>
                    <p v-if="currentInfo?.reassurance" class="info-reassurance">
                        {{ currentInfo.reassurance }}
                    </p>
                </div>

                <button
                    class="btn-primary"
                    :style="{ background: brandColor, color: textOnBrand }"
                    @click="continueFromInfo"
                >
                    Continuer
                </button>
                <button class="btn-back" style="margin-top: 1rem" @click="goBack">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                    Retour
                </button>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════════════
             RECAP DES RÉPONSES
        ═══════════════════════════════════════════════════════ -->
        <div v-else-if="step === 'recap'" class="recap-screen">
            <div class="recap-inner">
                <h2 class="recap-title">Réponses données</h2>
                <p class="recap-sub">Vérifiez vos réponses avant de voir votre résultat.</p>

                <div class="answers-grid">
                    <div
                        v-for="(q, i) in questions"
                        :key="i"
                        class="answer-row"
                    >
                        <div class="answer-q">
                            <span class="answer-icon">{{ q.icon }}</span>
                            <span class="answer-text">{{ q.text }}</span>
                        </div>
                        <span
                            class="answer-badge"
                            :style="getAnswerBadgeStyle(answers[i], q)"
                        >
                            {{ answers[i] ?? '—' }}
                        </span>
                    </div>
                </div>

                <!-- Section "Prêt ?" -->
                <div class="ready-section" :style="{ background: brandColor }">
                    <p class="ready-title" :style="{ color: textOnBrand }">Vous vous sentez prêt ?</p>
                    <button
                        class="btn-ready"
                        :style="{ background: textOnBrand, color: brandColor }"
                        @click="showResult"
                    >
                        Voir mon résultat →
                    </button>
                </div>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════════════
             RÉSULTAT — split screen
        ═══════════════════════════════════════════════════════ -->
        <div v-else-if="step === 'result'" class="split-screen">

            <!-- Mascotte -->
            <div
                class="mascotte-col"
                :style="resultat === 'eligible'
                    ? { background: brandColor + '0c' }
                    : { background: '#f2f4f3' }"
            >
                <div class="mascotte-wrap">
                    <!-- TODO: remplacer par image selon resultat -->
                    <div
                        class="mascotte-placeholder"
                        :style="resultat === 'eligible'
                            ? { borderColor: brandColor + '40', color: brandColor }
                            : { borderColor: '#c5d0cf', color: '#497371' }"
                    >
                        {{ resultat === 'eligible' ? '🦫🏆' : resultat === 'non-eligible' ? '🦫😟' : '🦫🤔' }}
                        <span class="mascotte-label">Courage</span>
                    </div>
                </div>
            </div>

            <!-- Résultat contenu -->
            <div class="content-col">
                <div class="result-content">

                    <!-- ÉLIGIBLE -->
                    <template v-if="resultat === 'eligible'">
                        <div class="result-check" :style="{ background: brandColor + '15' }">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" :stroke="brandColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </div>
                        <div class="result-badge" :style="{ background: brandColor + '15', color: brandColor }">Bonne nouvelle !</div>
                        <h2 class="result-title">Bravo ! Vous êtes la star du don.</h2>
                        <div class="result-highlight" :style="{ borderColor: brandColor + '40', background: brandColor + '08' }">
                            <p :style="{ color: brandColor }">
                                D'après vos réponses, vous pouvez probablement participer à la collecte.
                                Le personnel du CTS vérifiera les derniers critères sur place.
                            </p>
                        </div>
                        <p class="result-emotion">❤️ Un seul don peut aider jusqu'à trois personnes.</p>
                        <div class="result-actions">
                            <RouterLink
                                v-if="collecte?.active"
                                :to="`/entreprise/${route.params.slug}/inscription`"
                                class="btn-primary btn-link"
                                :style="{ background: brandColor, color: textOnBrand }"
                            >
                                Participer à la collecte →
                            </RouterLink>
                            <RouterLink
                                :to="`/entreprise/${route.params.slug}`"
                                class="btn-outline btn-link"
                                :style="{ color: brandColor, borderColor: brandColor }"
                            >
                                Voir les informations pratiques
                            </RouterLink>
                        </div>
                    </template>

                    <!-- NON ÉLIGIBLE -->
                    <template v-else-if="resultat === 'non-eligible'">
                        <div class="result-check result-check-nok">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#497371" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                        </div>
                        <div class="result-badge result-badge-nok">Pas cette fois-ci</div>
                        <h2 class="result-title">Certains points ne sont pas éligibles.</h2>
                        <div class="result-highlight result-highlight-nok">
                            <p>D'après vos réponses, il est possible qu'un délai temporaire soit nécessaire avant de pouvoir donner votre sang.</p>
                        </div>
                        <p class="result-reassurance">Cela ne signifie pas que vous ne pourrez jamais donner.</p>
                        <div class="result-actions">
                            <a href="https://www.hug.ch/don-du-sang" target="_blank" class="btn-primary btn-link btn-teal">
                                En savoir plus
                            </a>
                            <RouterLink
                                :to="`/entreprise/${route.params.slug}`"
                                class="btn-outline btn-link btn-outline-teal"
                            >
                                Découvrir les prochaines collectes
                            </RouterLink>
                        </div>
                    </template>

                    <!-- INCERTAIN -->
                    <template v-else>
                        <div class="result-check result-check-incertain">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#8b6914" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                            </svg>
                        </div>
                        <div class="result-badge result-badge-incertain">Quelques vérifications nécessaires</div>
                        <h2 class="result-title">Certaines situations nécessitent une validation médicale.</h2>
                        <div class="result-highlight result-highlight-incertain">
                            <p>Le personnel du CTS pourra vous renseigner lors de votre rendez-vous.</p>
                        </div>
                        <div class="result-actions">
                            <RouterLink
                                v-if="collecte?.active"
                                :to="`/entreprise/${route.params.slug}/inscription`"
                                class="btn-primary btn-link btn-amber"
                            >
                                Prendre rendez-vous quand même
                            </RouterLink>
                            <RouterLink
                                :to="`/entreprise/${route.params.slug}`"
                                class="btn-outline btn-link btn-outline-teal"
                            >
                                Retour à l'accueil
                            </RouterLink>
                        </div>
                    </template>

                    <button class="btn-retake" :style="{ color: brandColor }" @click="retakeQuiz">
                        ↺ Refaire le quiz
                    </button>
                </div>
            </div>
        </div>

    </div>
</template>

<style scoped>
.state-loading {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Instrument Sans', sans-serif;
    color: #497371;
}
.page {
    font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    background: white;
}

/* ── Split screen (intro + result) ──────────────────── */
.split-screen {
    flex: 1;
    display: grid;
    grid-template-columns: 2fr 3fr;
    min-height: calc(100vh - 76px);
}
.mascotte-col {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem 2rem;
}
.mascotte-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    max-width: 320px;
}
.mascotte-placeholder {
    width: 220px;
    height: 280px;
    border-radius: 24px;
    border: 3px dashed;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    font-size: 4rem;
    opacity: 0.7;
}
.mascotte-label {
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    opacity: 0.5;
}
.content-col {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem 4rem;
    background: white;
}

/* ── Intro ──────────────────────────────────────────── */
.intro-content {
    max-width: 420px;
    width: 100%;
}
.intro-badge {
    display: inline-block;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    border-radius: 9999px;
    padding: 0.35rem 1rem;
    margin-bottom: 1.5rem;
}
.intro-title {
    font-size: 2.25rem;
    font-weight: 800;
    color: #2c4140;
    margin: 0 0 1rem;
    line-height: 1.2;
}
.intro-sub {
    font-size: 1rem;
    color: #497371;
    line-height: 1.7;
    margin: 0 0 0.6rem;
}
.intro-disclaimer {
    font-size: 0.82rem;
    color: #8fa8a6;
    font-style: italic;
    line-height: 1.6;
    margin: 0 0 2.5rem;
}
.intro-actions {
    display: flex;
    flex-direction: column;
    gap: 0.85rem;
}

/* Shared buttons */
.btn-primary {
    font-size: 1rem;
    font-weight: 700;
    border: none;
    border-radius: 9999px;
    padding: 0.9rem 2.5rem;
    cursor: pointer;
    transition: opacity 0.15s;
    text-align: center;
    font-family: inherit;
    white-space: nowrap;
}
.btn-primary:hover { opacity: 0.85; }
.btn-link { text-decoration: none; display: inline-block; }
.btn-outline {
    font-size: 0.95rem;
    font-weight: 600;
    background: white;
    border: 2px solid;
    border-radius: 9999px;
    padding: 0.8rem 2rem;
    cursor: pointer;
    transition: opacity 0.15s;
    text-align: center;
    font-family: inherit;
    white-space: nowrap;
    text-decoration: none;
    display: inline-block;
}
.btn-outline:hover { opacity: 0.75; }
.btn-teal { background: #2c4140 !important; color: white !important; border-color: #2c4140 !important; }
.btn-amber { background: #d97706 !important; color: white !important; border-color: #d97706 !important; }
.btn-outline-teal { color: #497371 !important; border-color: #c5d0cf !important; }

/* ── QUIZ screen ─────────────────────────────────────── */
.quiz-screen {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: #f7f8f8;
}
.progress-dots {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 1.5rem 2rem 0;
}
.dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #e8ecec;
    transition: background 0.3s, transform 0.2s;
}
.dot-active {
    transform: scale(1.25);
}
.dot-done {
    opacity: 0.5;
}
.quiz-inner {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    max-width: 640px;
    width: 100%;
    margin: 0 auto;
    padding: 2rem 2rem 3rem;
    text-align: center;
}
.question-icon {
    width: 64px;
    height: 64px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    margin-bottom: 1.25rem;
}
.quiz-counter {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.14em;
    margin: 0 0 0.75rem;
}
.quiz-question {
    font-size: 1.75rem;
    font-weight: 800;
    color: #2c4140;
    margin: 0 0 2.5rem;
    line-height: 1.25;
}
.quiz-options {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    width: 100%;
    max-width: 460px;
    margin-bottom: 2rem;
}
.quiz-option {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    width: 100%;
    background: white;
    border: 2px solid #dde3e3;
    border-radius: 9999px;
    padding: 1rem 1.5rem;
    cursor: pointer;
    transition: border-color 0.18s, color 0.18s, box-shadow 0.18s;
    font-size: 1.05rem;
    font-weight: 600;
    color: #2c4140;
    font-family: inherit;
    text-align: left;
    box-shadow: 0 1px 3px rgba(44,65,64,0.06);
}
.quiz-option:hover:not(.is-good):not(.is-bad) {
    border-color: #b8c5c4;
    box-shadow: 0 2px 8px rgba(44,65,64,0.1);
}
.quiz-option.is-good {
    border-color: #22c55e;
    color: #16a34a;
    box-shadow: 0 2px 8px rgba(34,197,94,0.15);
}
.quiz-option.is-bad {
    border-color: #ef4444;
    color: #dc2626;
    box-shadow: 0 2px 8px rgba(239,68,68,0.15);
}
.opt-icon {
    width: 20px;
    height: 20px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-continuer {
    font-size: 0.95rem;
    font-weight: 600;
    background: #e8f4f3;
    color: #2c4140;
    border: none;
    border-radius: 9999px;
    padding: 0.9rem 2.5rem;
    cursor: pointer;
    transition: background 0.15s, opacity 0.15s;
    font-family: inherit;
    margin-bottom: 1.5rem;
    min-width: 240px;
}
.btn-continuer:hover { background: #d4ecea; }

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: none;
    border: none;
    font-size: 0.85rem;
    font-weight: 600;
    color: #8fa8a6;
    cursor: pointer;
    padding: 0;
    font-family: inherit;
    transition: opacity 0.15s;
}
.btn-back:hover { opacity: 0.65; }

/* slide-up transition */
.slide-up-enter-active { transition: opacity 0.25s, transform 0.25s; }
.slide-up-enter-from   { opacity: 0; transform: translateY(10px); }

/* ── INFO screen ─────────────────────────────────────── */
.info-screen {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f7f8f8;
    padding: 3rem 1.5rem;
}
.info-inner {
    max-width: 520px;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 1.5rem;
}
.info-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}
.info-avatar {
    width: 96px;
    height: 96px;
    border-radius: 50%;
    border: 3px solid;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
}
.info-avatar-emoji { line-height: 1; }
.info-title {
    font-size: 1.5rem;
    font-weight: 800;
    color: #2c4140;
    margin: 0;
}
.info-card {
    border-radius: 16px;
    padding: 1.5rem 1.75rem;
    width: 100%;
    text-align: left;
}
.info-card-warn { background: #fff8e1; border: 1.5px solid #fde68a; }
.info-card-tip  { background: #f0fdf8; border: 1.5px solid #a7f3d0; }
.info-msg {
    font-size: 1rem;
    color: #2c4140;
    line-height: 1.65;
    margin: 0;
    font-weight: 500;
}
.info-reassurance {
    font-size: 0.88rem;
    color: #497371;
    font-style: italic;
    margin: 0.75rem 0 0;
}

/* ── RECAP screen ────────────────────────────────────── */
.recap-screen {
    flex: 1;
    background: #f7f8f8;
    padding: 3rem 1.5rem;
}
.recap-inner {
    max-width: 800px;
    margin: 0 auto;
}
.recap-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: #2c4140;
    margin: 0 0 0.5rem;
    text-align: center;
}
.recap-sub {
    font-size: 0.95rem;
    color: #497371;
    text-align: center;
    margin: 0 0 2.5rem;
}
.answers-grid {
    display: flex;
    flex-direction: column;
    gap: 0.65rem;
    margin-bottom: 2.5rem;
}
.answer-row {
    background: white;
    border-radius: 12px;
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    box-shadow: 0 1px 4px rgba(44,65,64,0.05);
}
.answer-q {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex: 1;
    min-width: 0;
}
.answer-icon { font-size: 1.1rem; flex-shrink: 0; }
.answer-text {
    font-size: 0.9rem;
    color: #2c4140;
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.answer-badge {
    font-size: 0.8rem;
    font-weight: 700;
    border-radius: 9999px;
    padding: 0.3rem 0.9rem;
    white-space: nowrap;
    flex-shrink: 0;
}
.ready-section {
    border-radius: 20px;
    padding: 2.5rem 2rem;
    text-align: center;
}
.ready-title {
    font-size: 1.5rem;
    font-weight: 800;
    margin: 0 0 1.5rem;
}
.btn-ready {
    font-size: 1rem;
    font-weight: 700;
    border: none;
    border-radius: 9999px;
    padding: 0.9rem 2.5rem;
    cursor: pointer;
    transition: opacity 0.15s;
    font-family: inherit;
}
.btn-ready:hover { opacity: 0.9; }

/* ── RESULT content ──────────────────────────────────── */
.result-content {
    max-width: 420px;
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
.result-check {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.result-check-nok      { background: #f2f4f3; }
.result-check-incertain { background: #fefce8; }
.result-badge {
    display: inline-block;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    border-radius: 9999px;
    padding: 0.3rem 1rem;
    align-self: flex-start;
}
.result-badge-nok      { background: #f2f4f3; color: #497371; }
.result-badge-incertain { background: #fefce8; color: #854d0e; }
.result-title {
    font-size: 1.6rem;
    font-weight: 800;
    color: #2c4140;
    margin: 0;
    line-height: 1.25;
}
.result-highlight {
    border-radius: 12px;
    border: 1.5px solid;
    padding: 1rem 1.25rem;
    font-size: 0.95rem;
    line-height: 1.65;
    color: #2c4140;
}
.result-highlight-nok { border-color: #c5d0cf; background: #f7f8f8; }
.result-highlight-incertain { border-color: #fde68a; background: #fefce8; }
.result-highlight p, .result-highlight-nok p, .result-highlight-incertain p { margin: 0; }
.result-emotion {
    font-size: 0.9rem;
    font-weight: 600;
    color: #497371;
    margin: 0;
}
.result-reassurance {
    font-size: 0.88rem;
    color: #497371;
    font-style: italic;
    margin: 0;
}
.result-actions {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}
.btn-retake {
    background: none;
    border: none;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    padding: 0;
    font-family: inherit;
    transition: opacity 0.15s;
    margin-top: 0.5rem;
    align-self: flex-start;
}
.btn-retake:hover { opacity: 0.7; }

/* ── Responsive ─────────────────────────────────────── */
@media (max-width: 900px) {
    .split-screen { grid-template-columns: 1fr; }
    .mascotte-col { min-height: 200px; padding: 2rem; }
    .mascotte-placeholder { width: 140px; height: 180px; font-size: 2.5rem; }
    .content-col { padding: 2rem; }
    .intro-title, .result-title { font-size: 1.75rem; }
    .result-actions .btn-primary,
    .result-actions .btn-outline { width: 100%; text-align: center; display: block; }
}
@media (max-width: 600px) {
    .quiz-question { font-size: 1.35rem; }
    .quiz-inner { padding: 1.5rem 1.25rem 2rem; }
    .recap-inner { padding: 0; }
}
</style>
