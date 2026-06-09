<script setup>
import { ref, computed, onMounted } from "vue";
import { useAuthStore } from "../stores/auth";
import HugNavbar from "../components/HugNavbar.vue";
import QuizNavbar from "../components/QuizNavbar.vue";

const auth = useAuthStore();

const BRAND = "var(--color-default-red)";
const TEAL = "var(--color-default-blue-59)";
const COOKIE_RESULT = "quizResult_hug";
const COOKIE_ANSWERS = "quizAnswers_hug";
const COOKIE_DAYS = 7;

// ── Cookie helpers ────────────────────────────────────────────────
function setCookie(name, value, days) {
    const exp = new Date(Date.now() + days * 864e5).toUTCString();
    document.cookie = `${name}=${encodeURIComponent(value)};expires=${exp};path=/;SameSite=Lax`;
}
function getCookie(name) {
    const m = document.cookie.match(new RegExp("(?:^|; )" + name + "=([^;]*)"));
    return m ? decodeURIComponent(m[1]) : null;
}
function deleteCookie(name) {
    document.cookie = `${name}=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/`;
}

// ── State ────────────────────────────────────────────────────────
const step = ref("intro"); // intro | quiz | info | recap | result
const currentQ = ref(0);
const answers = ref([]);
const selectedAnswer = ref(null);
const currentInfo = ref(null);
const resultat = ref(null);

const participerLink = computed(() => {
    if (auth.isAdmin) return "/entreprises";
    if (auth.isCoordinateur && auth.entrepriseSlug)
        return `/entreprise/${auth.entrepriseSlug}`;
    return "/login";
});

onMounted(() => {
    const savedResult = getCookie(COOKIE_RESULT);
    if (savedResult) {
        resultat.value = savedResult;
        const savedAnswers = getCookie(COOKIE_ANSWERS);
        if (savedAnswers) {
            try {
                answers.value = JSON.parse(savedAnswers);
            } catch {}
        }
    }
    document.title = "Quiz d'éligibilité — HUG Don du sang";
});

// ── Questions ────────────────────────────────────────────────────
const questions = [
    {
        icon: "person",
        text: "Avez-vous entre 18 et 75 ans ?",
        options: ["Oui", "Non"],
        feedback: {
            trigger: "Non",
            type: "warn",
            message:
                "Le don du sang est réservé aux personnes âgées de 18 à 75 ans afin de garantir la sécurité des donneurs et des receveurs.",
        },
        info: {
            message:
                "À 18 ans, vous pouvez effectuer votre tout premier don. Les HUG et le CTS accueillent les donneurs jusqu'à 75 ans, avec un entretien médical complémentaire pour les donneurs de plus de 60 ans.",
            tip: "Plus vous commencez à donner tôt, plus vous contribuez à maintenir les stocks de sang disponibles pour les patients tout au long de l'année.",
        },
    },
    {
        icon: "wine_bar",
        text: "Avez-vous consommé de l'alcool au cours des 24 dernières heures ?",
        options: ["Oui", "Non"],
        feedback: {
            trigger: "Oui",
            type: "warn",
            message:
                "L'alcool peut perturber la récupération de votre organisme après un don de sang.\n\nIl est recommandé d'éviter d'en consommer avant votre rendez-vous.",
            tip: "Pensez à bien vous hydrater avant votre don : buvez de l'eau régulièrement tout au long de journée.",
        },
        info: {
            message:
                "Éviter l'alcool dans les 24 heures précédant un don est une excellente pratique. L'alcool favorise la déshydratation et peut accentuer les effets du prélèvement sur votre tension artérielle.",
            tip: "Buvez au moins 500 ml d'eau supplémentaires dans les heures qui précèdent votre don pour optimiser la récupération de votre organisme.",
        },
    },
    {
        icon: "health_and_safety",
        text: "Vous sentez-vous en bonne santé aujourd'hui ?",
        options: ["Oui", "Non"],
        feedback: {
            trigger: "Non",
            type: "warn",
            message:
                "Même un simple refroidissement peut empêcher temporairement un don. Le plus important est de venir lorsque vous êtes en pleine forme.",
        },
        info: {
            message:
                "Être en forme le jour du don est la meilleure des conditions. Si des symptômes apparaissent le matin même de votre rendez-vous, il est préférable de reporter le don.",
            tip: "Un simple rhume ou une légère fièvre est une raison valable d'annuler — votre santé passe avant tout !",
        },
    },
    {
        icon: "medical_services",
        text: "Avez-vous subi une opération ou un traitement médical récemment ?",
        options: ["Oui", "Non"],
        feedback: {
            trigger: "Oui",
            type: "warn",
            message:
                "Certaines interventions nécessitent un délai avant un don. Le personnel médical vérifiera cela avec vous lors de l'entretien de pré-don.",
        },
        info: {
            message:
                "Sans intervention médicale récente, vous n'avez pas de délai supplémentaire à respecter pour ce critère. L'infirmière vérifiera tout de même vos antécédents lors de l'entretien.",
            tip: "Si vous avez eu une opération il y a plusieurs mois, mentionnez-le quand même lors de l'entretien — certains actes chirurgicaux nécessitent un délai plus long.",
        },
    },
    {
        icon: "draw",
        text: "Avez-vous eu un tatouage ou un piercing récemment ?",
        options: ["Oui", "Non"],
        feedback: {
            trigger: "Oui",
            type: "warn",
            message:
                "Après un tatouage ou un piercing, un délai de 4 mois est requis avant de pouvoir donner son sang.",
            reassurance:
                "Passé ce délai, vous pourrez à nouveau donner sans restriction.",
        },
        info: {
            message:
                "Sans tatouage ni piercing récent, vous n'avez aucune restriction liée à ce critère. Les tatouages réalisés dans des établissements professionnels agréés en Suisse imposent un délai de 4 mois, puis le don est à nouveau possible.",
            tip: "Les techniques modernes de tatouage présentent un faible risque d'infection, mais le délai de précaution reste en vigueur dans tous les centres de transfusion suisses.",
        },
    },
    {
        icon: "medication",
        text: "Prenez-vous actuellement des médicaments importants ?",
        options: ["Oui", "Non"],
        feedback: {
            trigger: "Oui",
            type: "warn",
            message:
                "Certains traitements sont compatibles avec le don, d'autres nécessitent un délai temporaire. Le médecin du CTS pourra vous renseigner lors de l'entretien.",
        },
        info: {
            message:
                "Sans traitement médicamenteux en cours, vous n'avez pas de restriction liée aux médicaments. Certains médicaments courants comme l'ibuprofène sont acceptés, mais doivent toujours être mentionnés lors de l'entretien.",
            tip: "Même les compléments alimentaires, les vitamines ou les contraceptifs oraux doivent être signalés lors de l'entretien de pré-don, par mesure de précaution.",
        },
    },
    {
        icon: "restaurant",
        text: "Avez-vous suffisamment mangé et bu aujourd'hui ?",
        options: ["Oui", "Non"],
        feedback: {
            trigger: "Non",
            type: "warn",
            message:
                "Il est fortement recommandé de manger et de bien s'hydrater avant un don afin d'éviter les malaises.",
        },
        info: {
            message:
                "Un repas léger et une bonne hydratation avant le don sont essentiels pour éviter tout malaise. Un repas trop riche en graisses peut temporairement rendre le plasma trouble et affecter certaines analyses.",
            tip: "Après votre don, reposez-vous 10 à 15 minutes et consommez les collations proposées par l'équipe soignante avant de quitter le centre.",
        },
    },
    {
        icon: "pregnant_woman",
        text: "Êtes-vous enceinte ou avez-vous accouché récemment ?",
        options: ["Oui", "Non"],
        feedback: {
            trigger: "Oui",
            type: "warn",
            message:
                "Un délai de 6 mois après l'accouchement est nécessaire avant de pouvoir donner son sang, afin de permettre à l'organisme de se reconstituer pleinement.",
        },
        info: {
            message:
                "Les femmes peuvent donner leur sang aussi régulièrement que les hommes, avec un intervalle minimum de 4 mois entre chaque don. En cas d'allaitement, il est conseillé d'attendre la fin de cette période.",
            tip: "Le fer contenu dans le sang est particulièrement important pour les femmes. Une alimentation riche en fer (viande rouge, légumineuses, épinards) aide à maintenir un bon taux d'hémoglobine entre les dons.",
        },
    },
    {
        icon: "flight",
        text: "Avez-vous voyagé récemment dans certaines régions à risque ?",
        options: ["Oui", "Non", "Je ne sais pas"],
        feedback: {
            trigger: "Oui",
            type: "warn",
            message:
                "Certaines destinations — notamment en zone tropicale ou dans des régions touchées par des maladies vectorielles comme le paludisme — nécessitent un délai d'attente après le retour.",
        },
        info: {
            message:
                "Sans voyage récent dans une zone à risque, vous n'avez aucune restriction liée aux voyages. En cas de doute, le CTS dispose d'une liste mise à jour des destinations imposant un délai d'attente.",
            tip: "En cas de retour d'une zone endémique pour le paludisme, un délai de 28 jours minimum est généralement requis avant de pouvoir donner.",
        },
    },
];

// ── Helpers ──────────────────────────────────────────────────────
function isGoodAnswer(opt) {
    const q = questions[currentQ.value];
    return !q.feedback || opt !== q.feedback.trigger;
}

function isGoodForDonation(i) {
    const q = questions[i];
    const a = answers.value[i];
    return !a || !q.feedback || a !== q.feedback.trigger;
}

function getAnswerBadgeStyle(answer) {
    if (!answer) return { background: "var(--light-grey)", color: "#8fa8a6" };
    if (answer === "Oui") return { background: "#d1fae5", color: "#065f46" };
    if (answer === "Non") return { background: "#fee2e2", color: "#991b1b" };
    return { background: "#f0f9f8", color: "var(--default-titles)" };
}

// ── Actions ──────────────────────────────────────────────────────
function startQuiz() {
    currentQ.value = 0;
    answers.value = [];
    selectedAnswer.value = null;
    currentInfo.value = null;
    step.value = "quiz";
}

function viewPreviousResult() {
    if (resultat.value) step.value = "result";
    else startQuiz();
}

function selectAnswer(opt) {
    selectedAnswer.value = opt;
}

function confirm() {
    const opt = selectedAnswer.value;
    if (!opt) return;

    answers.value = [...answers.value.slice(0, currentQ.value), opt];

    const q = questions[currentQ.value];
    if (q.feedback && opt === q.feedback.trigger) {
        currentInfo.value = q.feedback;
        step.value = "info";
    } else if (q.info) {
        currentInfo.value = q.info;
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
        answers.value = answers.value.slice(0, currentQ.value);
        selectedAnswer.value = null;
        currentInfo.value = null;
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
    setCookie(COOKIE_RESULT, r, COOKIE_DAYS);
    setCookie(COOKIE_ANSWERS, JSON.stringify(answers.value), COOKIE_DAYS);
    step.value = "result";
}

function computeResult() {
    const a = answers.value;
    if (a[0] === "Non") return "non-eligible"; // age
    if (a[2] === "Non") return "non-eligible"; // health
    if (a[3] === "Oui") return "non-eligible"; // medical treatment
    if (a[6] === "Non") return "non-eligible"; // food/drink
    if (a[7] === "Oui") return "non-eligible"; // pregnancy
    if (a[4] === "Oui") return "incertain"; // tattoo/piercing
    if (a[5] === "Oui") return "incertain"; // medication
    if (a[8] === "Oui" || a[8] === "Je ne sais pas") return "incertain"; // travel
    return "eligible";
}

function retakeQuiz() {
    resultat.value = null;
    deleteCookie(COOKIE_RESULT);
    deleteCookie(COOKIE_ANSWERS);
    startQuiz();
}
</script>

<template>
    <div class="page">
        <!-- Nav standard sur l'intro, nav quiz sur les autres étapes -->
        <HugNavbar v-if="step === 'intro'" />
        <QuizNavbar v-else :on-back="goBack" />

        <!-- ══════════════════════════════════════════════════════
             INTRO — split screen
        ═══════════════════════════════════════════════════════ -->
        <Transition name="slide" mode="out-in">
        <div v-if="step === 'intro'" class="split-screen" key="intro">
            <div class="mascotte-col">
                <div class="mascotte-circle">
                    <img
                        :src="'/images/courage/Mascotte_default.png'"
                        alt="Courage"
                        class="mascotte-img"
                    />
                </div>
            </div>

            <div class="content-col">
                <div class="intro-content">
                    <h1 class="text-black">Je suis Courage, votre guide!</h1>
                    <p>
                        Je vais vous poser 9 questions rapides et vous donner
                        des conseils utiles à chaque étape pour vérifier si vous
                        pouvez donner votre sang.
                    </p>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="rounded-xl px-5 py-4 bg-light-grey">
                            <span
                                class="material-symbols-outlined feature-icon mb-2"
                                >schedule</span
                            >
                            <p class="captions">Seulement 5 minutes</p>
                        </div>
                        <div class="rounded-xl px-5 py-4 bg-light-grey">
                            <span class="material-symbols-outlined feature-icon"
                                >security</span
                            >
                            <p class="captions">100 % confidentiel</p>
                        </div>
                        <div class="rounded-xl px-5 py-4 bg-light-grey">
                            <span class="material-symbols-outlined feature-icon"
                                >favorite</span
                            >
                            <p class="captions">Préparer votre don</p>
                        </div>
                    </div>
                    <button class="btn btn-filled-blue" @click="startQuiz">
                        Commencer le test
                        <span class="material-symbols-outlined"
                            >arrow_forward</span
                        >
                    </button>

                    <button
                        v-if="resultat"
                        class="btn-retake"
                        @click="viewPreviousResult"
                    >
                        Voir mon résultat précédent
                    </button>
                </div>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════════════
             QUESTION
        ═══════════════════════════════════════════════════════ -->
        <div v-else-if="step === 'quiz'" class="quiz-screen" :key="'quiz-' + currentQ">
            <!-- Progress steps -->
            <div class="progress-steps">
                <template v-for="(_, i) in questions" :key="i">
                    <div
                        class="step"
                        :class="{
                            'step-done': i < currentQ,
                            'step-active': i === currentQ,
                        }"
                    >
                        {{ i + 1 }}
                    </div>
                    <div
                        v-if="i < questions.length - 1"
                        class="step-line"
                        :class="{ 'step-line-done': i < currentQ }"
                    ></div>
                </template>
            </div>

            <div class="quiz-inner">
                <!-- Icon -->
                <div
                    class="circle-icon rounded-full bg-light-grey flex items-center justify-center mb-8"
                >
                    <span class="material-symbols-outlined">{{
                        questions[currentQ].icon
                    }}</span>
                </div>
                <h2 class="quiz-question">{{ questions[currentQ].text }}</h2>

                <div class="quiz-options">
                    <button
                        v-for="opt in questions[currentQ].options"
                        :key="opt"
                        class="btn"
                        :class="[
                            opt === 'Oui'
                                ? 'btn-outlined-green'
                                : opt === 'Non'
                                  ? 'btn-outlined-red'
                                  : 'btn-outlined-blue',
                            { 'is-selected': selectedAnswer === opt },
                        ]"
                        @click="selectAnswer(opt)"
                    >
                        <span class="opt-icon">
                            <svg
                                v-if="opt === 'Oui'"
                                width="16"
                                height="16"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                            <svg
                                v-else-if="opt === 'Non'"
                                width="16"
                                height="16"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <line x1="18" y1="6" x2="6" y2="18" />
                                <line x1="6" y1="6" x2="18" y2="18" />
                            </svg>
                        </span>
                        {{ opt }}
                    </button>
                </div>

                <button
                    class="btn btn-filled-blue mb-6"
                    style="width: 100%; max-width: 440px"
                    :disabled="!selectedAnswer"
                    @click="confirm"
                >
                    Prochaine question
                    <span class="material-symbols-outlined">arrow_forward</span>
                </button>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════════════
             SLIDE INFO — split screen
        ═══════════════════════════════════════════════════════ -->
        <div v-else-if="step === 'info'" class="split-screen" key="info">
            <div class="mascotte-col mascotte-col-full">
                <img
                    :src="'/images/courage/Mascotte_insight.png'"
                    alt="Courage"
                    class="mascotte-img-full"
                />
            </div>

            <div class="content-col">
                <div class="intro-content">
                    <h2 class="text-black">Courage vous informe !</h2>

                    <h3
                        v-for="(line, i) in currentInfo.message.split('\n\n')"
                        :key="i"
                    >
                        {{ line }}
                    </h3>

                    <p v-if="currentInfo.reassurance">
                        {{ currentInfo.reassurance }}
                    </p>

                    <div
                        v-if="currentInfo.tip"
                        class="tip-card rounded-xl p-4 gap-6 flex items-center"
                    >
                        <div class="tip-icon-wrap">
                            <span class="material-symbols-outlined tip-icon"
                                >lightbulb</span
                            >
                        </div>
                        <p class="text-black">{{ currentInfo.tip }}</p>
                    </div>

                    <button
                        class="btn btn-filled-blue"
                        style="margin-top: 0.5rem"
                        @click="continueFromInfo"
                    >
                        J'ai bien compris
                        <span class="material-symbols-outlined"
                            >arrow_forward</span
                        >
                    </button>
                </div>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════════════
             RECAP DES RÉPONSES
        ═══════════════════════════════════════════════════════ -->
        <div v-else-if="step === 'recap'" class="recap-screen" key="recap">
            <div class="recap-inner">
                <h2 class="recap-title">Réponses données</h2>

                <div class="answers-grid">
                    <div
                        v-for="(q, i) in questions"
                        :key="i"
                        class="answer-card"
                    >
                        <div class="card-top">
                            <div
                                class="card-check"
                                :class="
                                    isGoodForDonation(i)
                                        ? 'check-good'
                                        : 'check-bad'
                                "
                            >
                                <svg
                                    v-if="isGoodForDonation(i)"
                                    width="14"
                                    height="14"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                                <svg
                                    v-else
                                    width="14"
                                    height="14"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <line x1="18" y1="6" x2="6" y2="18" />
                                    <line x1="6" y1="6" x2="18" y2="18" />
                                </svg>
                            </div>
                            <p class="card-question">{{ q.text }}</p>
                            <div class="card-info-btn">
                                <span
                                    class="material-symbols-outlined"
                                    style="font-size: 18px; color: #000"
                                    >info</span
                                >
                            </div>
                        </div>
                        <div class="card-bottom">
                            <span class="card-answer-label"
                                >Vous avez répondu :</span
                            >
                            <span
                                class="answer-badge"
                                :style="getAnswerBadgeStyle(answers[i])"
                            >
                                {{ answers[i] ?? "—" }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="ready-section">
                    <p class="ready-title">Vous vous sentez prêt ?</p>
                    <button class="btn-ready" @click="showResult">
                        Voir mon résultat
                        <span class="material-symbols-outlined"
                            >arrow_forward</span
                        >
                    </button>
                </div>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════════════
             RÉSULTAT — split screen
        ═══════════════════════════════════════════════════════ -->
        <div v-else-if="step === 'result'" class="split-screen" key="result">
            <div class="mascotte-col">
                <div class="mascotte-circle">
                    <img
                        :src="
                            resultat === 'eligible'
                                ? '/images/courage/Mascotte_award.png'
                                : resultat === 'non-eligible'
                                  ? '/images/courage/Mascotte_failure.png'
                                  : '/images/courage/Mascotte_default.png'
                        "
                        alt="Courage"
                        class="mascotte-img"
                    />
                </div>
            </div>

            <div class="content-col">
                <div class="result-content">
                    <!-- ÉLIGIBLE -->
                    <template v-if="resultat === 'eligible'">
                        <h2 class="result-title">
                            Bravo ! Vous êtes la star du don.
                        </h2>
                        <p class="result-sub">
                            Sur la base de vos réponses, vous remplissez les
                            principales conditions de don.
                        </p>
                        <div class="result-tip-card">
                            <span
                                class="material-symbols-outlined"
                                style="font-size: 18px; color: #000"
                                >info</span
                            >
                            <p>
                                La validation finale sera effectuée sur place
                                par l'équipe médicale.
                            </p>
                        </div>
                        <RouterLink
                            :to="participerLink"
                            class="btn btn-filled-blue"
                        >
                            Prendre rendez-vous
                            <span
                                class="material-symbols-outlined"
                                style="font-size: 18px; color: #000"
                                >calendar_month</span
                            >
                        </RouterLink>
                    </template>

                    <!-- NON ÉLIGIBLE -->
                    <template v-else-if="resultat === 'non-eligible'">
                        <h2 class="result-title">
                            Certains points ne sont pas éligibles.
                        </h2>
                        <p class="result-sub">
                            Malheureusement, sur la base de vos réponses,
                            certains points ne remplissent pas les conditions de
                            don adéquates.
                        </p>
                        <button class="btn btn-filled-blue" @click="retakeQuiz">
                            Découvrir pourquoi
                            <span class="material-symbols-outlined"
                                >arrow_forward</span
                            >
                        </button>
                    </template>

                    <!-- INCERTAIN -->
                    <template v-else>
                        <h2 class="result-title">
                            Certaines situations nécessitent une validation
                            médicale.
                        </h2>
                        <p class="result-sub">
                            Le personnel du CTS pourra vous renseigner lors de
                            votre rendez-vous.
                        </p>
                        <div class="result-tip-card">
                            <span
                                class="material-symbols-outlined"
                                style="font-size: 18px; color: #000"
                                >info</span
                            >
                            <p>
                                Vous pouvez tout de même vous inscrire et venir
                                rencontrer notre équipe médicale.
                            </p>
                        </div>
                        <RouterLink
                            :to="participerLink"
                            class="btn btn-filled-blue"
                        >
                            Prendre rendez-vous
                            <span
                                class="material-symbols-outlined"
                                style="font-size: 18px; color: #000"
                                >calendar_month</span
                            >
                        </RouterLink>
                    </template>

                    <button
                        class="btn-retake"
                        @click="retakeQuiz"
                        style="margin-top: 1.5rem"
                    >
                        ↺ Refaire le quiz
                    </button>
                </div>
            </div>
        </div>
        </Transition>
    </div>
</template>

<style scoped>
.page {
    font-family: inherit;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    background: white;
    overflow-x: hidden;
}

/* Transition slide entre écrans et questions */
.slide-enter-active,
.slide-leave-active {
    transition:
        opacity 0.25s ease,
        transform 0.25s ease;
}
.slide-enter-from {
    opacity: 0;
    transform: translateX(30px);
}
.slide-leave-to {
    opacity: 0;
    transform: translateX(-30px);
}

/* ── Split screen ────────────────────────────────────── */
.split-screen {
    flex: 1;
    display: grid;
    grid-template-columns: 1fr 1fr;
    min-height: calc(100vh - 76px);
    width: 100%;
    max-width: 80rem;
    margin: 0 auto;
}
.mascotte-col {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 4rem 2rem;
    background: white;
}
.mascotte-circle {
    width: 360px;
    height: 360px;
    border-radius: 50%;
    background: var(--light-grey);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.mascotte-img {
    width: 100%;
    height: 126%;
    object-fit: cover;
    object-position: top center;
    transform: translateY(50px);
}
.mascotte-col-full {
    margin: 2rem;
}
.mascotte-img-full {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}
.content-col {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    background: white;
}

.intro-content {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

/* ── Shared buttons ──────────────────────────────────── */

.btn-link {
    text-decoration: none;
    display: inline-flex;
}
.btn-retake {
    background: none;
    border: none;
    font-size: 0.88rem;
    font-weight: 600;
    color: #8fa8a6;
    cursor: pointer;
    padding: 0;
    font-family: inherit;
    transition: opacity 0.15s;
    display: block;
}
.btn-retake:hover {
    opacity: 0.7;
}

/* ── QUIZ screen ─────────────────────────────────────── */
.quiz-screen {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: white;
    min-height: calc(100vh - 76px);
}

/* Progress numbered steps */
.progress-steps {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2.5rem 2rem 0;
    gap: 0;
}
.step {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 1.5px solid #afbfbf;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.82rem;
    font-weight: 700;
    color: #afbfbf;
    background: white;
    flex-shrink: 0;
    transition:
        background 0.3s,
        border-color 0.3s,
        color 0.3s;
}
.step-active {
    border-color: var(--color-default-red);
    color: var(--color-default-red);
    background: white;
}
.step-done {
    background: var(--color-default-red);
    border-color: var(--color-default-red);
    color: white;
}
.step-line {
    flex: 1;
    height: 2px;
    background: #afbfbf;
    min-width: 8px;
    max-width: 32px;
    margin: 0 5px;
    transition: background 0.3s;
}
.step-line-done {
    background: var(--color-default-red);
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
    padding: 2rem 2rem 4rem;
    text-align: center;
}

.quiz-question {
    font-size: 1.9rem;
    font-weight: 800;
    color: var(--default-titles);
    margin: 0 0 2rem;
    line-height: 1.25;
    max-width: 520px;
}
.quiz-options {
    display: flex;
    flex-direction: column;
    gap: 0.65rem;
    width: 100%;
    max-width: 440px;
    margin-bottom: 2rem;
}
.quiz-option {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    width: 100%;
    background: white;
    border: 2px solid #dde8e7;
    border-radius: 9999px;
    padding: 0.9rem 1.5rem;
    cursor: pointer;
    transition:
        border-color 0.18s,
        color 0.18s,
        box-shadow 0.18s;
    font-size: 1rem;
    font-weight: 600;
    color: var(--default-titles);
    font-family: inherit;
    text-align: left;
}
.quiz-option.opt-oui {
    border-color: var(--color-default-green-39);
    color: var(--color-default-green-39);
}
.quiz-option.opt-non {
    border-color: var(--color-default-red);
    color: var(--color-default-red);
}
.quiz-option.opt-oui.is-selected {
    background: var(--color-default-green-39);
    border-color: var(--color-default-green-39);
    color: white;
}
.quiz-option.opt-non.is-selected {
    background: var(--color-default-red);
    border-color: var(--color-default-red);
    color: white;
}
.quiz-option.opt-other.is-selected {
    border-color: var(--color-default-blue-39);
    background: var(--color-default-blue-39);
    color: white;
}
.quiz-option:hover:not(.is-selected) {
    opacity: 0.75;
}
.opt-icon {
    width: 18px;
    height: 18px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.slide-up-enter-active {
    transition:
        opacity 0.25s,
        transform 0.25s;
}
.slide-up-enter-from {
    opacity: 0;
    transform: translateY(10px);
}

/* ── RECAP screen ────────────────────────────────────── */
.recap-screen {
    flex: 1;
    background: white;
    padding: 3rem 1.5rem 0;
}
.recap-inner {
    max-width: 1000px;
    margin: 0 auto;
}
.recap-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: var(--default-titles);
    margin: 0 0 1.75rem;
}
.answers-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 0;
}
.answer-card {
    background: white;
    border: 1.5px solid #f0f4f4;
    border-radius: 14px;
    padding: 1.1rem 1.25rem 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.85rem;
}
.card-top {
    display: flex;
    align-items: flex-start;
    gap: 0.7rem;
}
.card-check {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: var(--light-grey);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-top: 1px;
}
.check-good {
    color: #16a34a;
}
.check-bad {
    color: #dc2626;
}
.card-question {
    font-size: 0.88rem;
    color: var(--default-titles);
    font-weight: 500;
    margin: 0;
    flex: 1;
    line-height: 1.45;
}
.card-info-btn {
    flex-shrink: 0;
    cursor: default;
    display: flex;
    align-items: center;
}
.card-bottom {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}
.card-answer-label {
    font-size: 0.82rem;
    color: #8fa8a6;
    font-weight: 500;
}
.answer-badge {
    font-size: 0.78rem;
    font-weight: 700;
    border-radius: 9999px;
    padding: 0.2rem 0.75rem;
    white-space: nowrap;
}

.ready-section {
    margin-top: 3rem;
    padding: 3.5rem 2rem;
    text-align: center;
    background: linear-gradient(
        135deg,
        var(--color-default-blue-59),
        var(--color-default-green)
    );
}
.ready-title {
    font-size: 2rem;
    font-weight: 800;
    color: var(--default-titles);
    margin: 0 0 1.75rem;
}
.btn-ready {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1rem;
    font-weight: 700;
    background: var(--color-default-red);
    color: white;
    border: none;
    border-radius: 9999px;
    padding: 0.9rem 2rem;
    cursor: pointer;
    transition: opacity 0.15s;
    font-family: inherit;
}
.btn-ready:hover {
    opacity: 0.9;
}

/* ── RESULT content ──────────────────────────────────── */
.result-content {
    max-width: 460px;
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
.result-title {
    font-size: 2rem;
    font-weight: 800;
    color: var(--default-titles);
    margin: 0;
    line-height: 1.2;
}
.result-sub {
    font-size: 0.95rem;
    color: var(--default-text);
    line-height: 1.65;
    margin: 0;
}
.result-tip-card {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    background: #f0f9f8;
    border-radius: 12px;
    padding: 1rem 1.25rem;
}
.result-tip-card p {
    font-size: 0.9rem;
    color: var(--default-titles);
    line-height: 1.6;
    margin: 0;
    font-style: italic;
}

/* ── Responsive ─────────────────────────────────────── */
@media (max-width: 900px) {
    .split-screen {
        grid-template-columns: 1fr;
    }
    .mascotte-col {
        min-height: 220px;
        padding: 2rem;
    }
    .mascotte-circle {
        width: 200px;
        height: 200px;
    }
    .content-col {
        justify-content: center;
        padding: 2rem;
    }

    .result-title {
        font-size: 1.75rem;
    }
    .quiz-question {
        font-size: 1.4rem;
    }
    .answers-grid {
        grid-template-columns: 1fr;
    }
}
@media (max-width: 600px) {
    .quiz-inner {
        padding: 1.5rem 1.25rem 3rem;
    }
    .progress-steps {
        padding: 1.5rem 1rem 0;
    }
    .step {
        width: 28px;
        height: 28px;
        font-size: 0.7rem;
    }
    .answers-grid {
        grid-template-columns: 1fr 1fr;
    }
}
@media (max-width: 480px) {
    .answers-grid {
        grid-template-columns: 1fr;
    }
}
</style>
