<script setup>
import { ref, onMounted } from "vue";
import HugNavbar from "../components/HugNavbar.vue";
import Footer from "../components/Footer.vue";

const palmares = ref([]);
const palmaresLoading = ref(true);

const criteres = [
    {
        num: "01",
        title: "Taux de participation",
        desc: "Le pourcentage de collaborateurs présentés par rapport au nombre total d'employés de l'entreprise.",
    },
    {
        num: "02",
        title: "Régularité de l'engagement",
        desc: "La constance de l'entreprise dans l'organisation de collectes sur plusieurs années consécutives.",
    },
    {
        num: "03",
        title: "Mobilisation interne",
        desc: "La qualité de la communication interne et les efforts déployés pour encourager les collaborateurs à participer.",
    },
];

const etapes = [
    {
        num: "01",
        title: "Soumettez votre candidature",
        desc: "Remplissez le formulaire avant le 30 novembre. Indiquez vos collectes réalisées, le nombre de participants et vos actions de mobilisation interne.",
    },
    {
        num: "02",
        title: "Instruction par le jury HUG",
        desc: "Le jury constitué de membres des HUG étudie toutes les candidatures reçues selon les 3 critères d'attribution.",
    },
    {
        num: "03",
        title: "Délibération en décembre",
        desc: "Le jury se réunit et sélectionne le ou les lauréats de l'année. Toutes les entreprises candidates sont notifiées.",
    },
    {
        num: "04",
        title: "Cérémonie de remise",
        desc: "Le Trophée est remis lors d'une cérémonie officielle organisée par les HUG — un moment de reconnaissance et de célébration pour toute votre équipe.",
    },
];

onMounted(async () => {
    document.title = "Trophée de la générosité — HUG";
    try {
        const res = await fetch("/api/palmares");
        if (res.ok) palmares.value = await res.json();
    } catch {
        // silent fail — palmarès reste vide
    } finally {
        palmaresLoading.value = false;
    }
});
</script>

<template>
    <div class="min-h-screen bg-white">
        <HugNavbar />

        <!-- Hero -->
        <section
            class="section-hero relative flex items-end pb-16 lg:items-center lg:pb-0"
            style="background: var(--default-titles)"
        >
            <img
                :src="'/images/HomePage_TropheeGenerosite.webp'"
                alt=""
                class="absolute inset-0 w-full h-full object-cover"
                style="opacity: 0.55"
            />
            <div
                class="absolute inset-0"
                style="background: rgba(44, 65, 64, 0.5)"
            ></div>
            <div class="relative max-w-7xl mx-auto px-8 w-full z-10">
                <div class="max-w-4xl">
                    <h1 class="font-bold text-white leading-tight mb-4">
                        Le Trophée de la Générosité
                    </h1>
                    <h3 class="text-white">
                        La distinction annuelle qui récompense les entreprises
                        genevoises les plus engagées pour le don du sang.
                    </h3>
                </div>
            </div>
        </section>

        <!-- Stats -->
        <section class="bg-white py-10">
            <div class="max-w-4xl mx-auto px-8 grid grid-cols-3 gap-4">
                <div class="rounded-xl px-5 py-4 bg-light-grey">
                    <h2 class="font-bold mb-1">2008</h2>
                    <p class="captions">
                        La distinction existe depuis plus de 15 ans
                    </p>
                </div>
                <div class="rounded-xl px-5 py-4 bg-light-grey">
                    <h2 class="font-bold mb-1">10%</h2>
                    <p class="captions">des dons proviennent des entreprises</p>
                </div>
                <div class="rounded-xl px-5 py-4 bg-light-grey">
                    <h2 class="font-bold mb-1">+30</h2>
                    <p class="captions">
                        entreprises se présentent chaque année
                    </p>
                </div>
            </div>
        </section>

        <!-- Qu'est-ce que c'est ? -->
        <section class="bg-light-grey py-20">
            <div
                class="max-w-7xl mx-auto px-8 grid grid-cols-2 gap-16 items-center"
            >
                <div>
                    <h2 class="font-bold mb-5 text-black">
                        Qu'est-ce que c'est ?
                    </h2>
                    <p class="mb-5">
                        Le Trophée de la générosité est né d'une conviction
                        simple : les entreprises qui s'engagent pour le don de
                        sang méritent d'être reconnues. Chaque collaborateur qui
                        donne son sang représente un geste anonyme, discret,
                        mais dont l'impact est immédiat et concret. En
                        mobilisant leurs équipes autour de cette cause,
                        certaines entreprises vont plus loin que la simple
                        participation.
                    </p>
                    <p>
                        Elles font du don de sang une valeur partagée, un moment
                        de cohésion, une façon d'ancrer leur responsabilité
                        citoyenne dans le quotidien. Le Trophée de la générosité
                        est là pour célébrer ces entreprises-là. Celles qui ne
                        se contentent pas de cocher une case RSE, mais qui
                        créent une culture du don durable au sein de leurs
                        équipes.
                    </p>
                </div>
                <div
                    class="relative rounded-2xl overflow-hidden"
                    style="height: 320px; background: var(--light-grey)"
                >
                    <img
                        :src="'/images/thumbnail_trophee.webp'"
                        alt="Trophée de la générosité"
                        class="absolute inset-0 w-full h-full object-cover"
                    />
                </div>
            </div>
        </section>

        <!-- Qu'est-ce que c'est ? -->
        <section class="bg-white py-20">
            <div
                class="max-w-7xl mx-auto px-8 grid grid-cols-2 gap-16 items-center"
            >
                <div>
                    <h2 class="font-bold mb-5 text-black">
                        Édition 2026 — Lauréat : Groupe Mercier SA
                    </h2>
                    <p>
                        Avec trois collectes organisées en une année et un taux
                        de participation de 34% parmi ses collaborateurs, le
                        Groupe Mercier SA s'est imposé comme un exemple
                        d'engagement collectif. Un résultat qui a permis de
                        collecter plus de 180 poches de sang, contribuant
                        directement aux besoins du canton.
                    </p>
                </div>
                <div
                    class="relative rounded-2xl overflow-hidden"
                    style="height: 320px; background: var(--light-grey)"
                >
                    <img
                        :src="'/images/thumbnail_winner.webp'"
                        alt="Gagnant du trophée 2026"
                        class="absolute inset-0 w-full h-full object-cover"
                    />
                </div>
            </div>
        </section>

<section
        class="bg-gradient py-20"
        >
            <div class="max-w-2xl mx-auto px-8 text-center">
                <h2
                    class="font-bold mb-4 text-black"
                >
                    Rejoignez les entreprises partenaires
                </h2>
                <p
                    class="mb-8"
                    style="
                        color: var(--default-titles);
                        opacity: 0.75;
                        line-height: 1.7;
                    "
                >
                    Devenez un acteur majeur de la santé publique genevoise.
                    Offrez à vos équipes la possibilité de sauver des vies sous
                    tous les toits du travail, tout en valorisant l'impact et la
                    solidarité de votre culture d'entreprise.
                </p>
        <RouterLink
            to="/entreprises"
            class="btn btn-filled-red"
        >
            Découvrez nos entreprises partenaires →
        </RouterLink>            </div>
        </section>

        <!-- Palmarès -->
        <section class="bg-white py-20">
            <div class="max-w-4xl mx-auto px-8">
                <h2 class="font-bold text-center mb-4 text-black">
                    Palmarès des lauréats
                </h2>
                <p class="text-center mb-12" style="color: var(--default-text)">
                    Depuis la création du Trophée, ces entreprises se sont
                    distinguées par leur engagement exceptionnel.
                </p>

                <div
                    v-if="palmaresLoading"
                    class="text-center py-8"
                    style="color: var(--default-text)"
                >
                    Chargement...
                </div>

                <template v-else>
                    <div
                        class="flex items-center gap-6 py-5 border-b"
                        style="border-color: var(--light-grey)"
                    >
                        <span
                            class="font-black flex-shrink-0"
                            style="
                                font-size: 20px;
                                color: var(--default-titles);
                                width: 56px;
                            "
                            >2026</span
                        >
                        <span
                            class="captions"
                            style="
                                background: #fef3c7;
                                color: #92400e;
                                padding: 2px 10px;
                            "
                            >À venir</span
                        >
                        <span
                            style="font-size: 14px; color: var(--default-text)"
                            >Cérémonie décembre 2026</span
                        >
                    </div>

                    <template v-if="palmares.length > 0">
                        <div
                            v-for="annee in palmares"
                            :key="annee.annee"
                            class="flex items-center gap-6 py-5 border-b"
                            style="border-color: var(--light-grey)"
                        >
                            <span
                                class="font-black flex-shrink-0"
                                style="
                                    font-size: 20px;
                                    color: var(--default-titles);
                                    width: 56px;
                                "
                                >{{ annee.annee }}</span
                            >
                            <div class="flex flex-wrap gap-2">
                                <span
                                    v-for="l in annee.laureats"
                                    :key="l.entreprise"
                                    style="
                                        font-size: 13px;
                                        font-weight: 600;
                                        background: var(--light-grey);
                                        color: var(--default-titles);
                                        padding: 3px 12px;
                                    "
                                    >{{ l.entreprise }}</span
                                >
                            </div>
                        </div>
                    </template>

                    <template v-else>
                        <div
                            v-for="y in [
                                2025, 2024, 2023, 2022, 2010, 2009, 2008,
                            ]"
                            :key="y"
                            class="flex items-center gap-6 py-5 border-b"
                            style="border-color: var(--light-grey)"
                        >
                            <span
                                class="font-black flex-shrink-0"
                                style="
                                    font-size: 20px;
                                    color: var(--default-titles);
                                    width: 56px;
                                "
                                >{{ y }}</span
                            >
                            <span
                                style="
                                    font-size: 14px;
                                    color: #c0cac9;
                                    font-style: italic;
                                "
                                >Données à compléter avec le CTS</span
                            >
                        </div>
                    </template>
                </template>

                <p class="mt-8 text-center italic" style="color: #c0cac9">
                    * Les données historiques seront complétées en collaboration
                    avec le CTS des HUG.
                </p>
            </div>
        </section>

        <!-- Process candidature -->
        <section class="py-20 bg-light-grey">
            <div class="max-w-7xl mx-auto px-8">
                <h2 class="font-bold text-center mb-12 text-black">
                    Comment candidater ?
                </h2>
                <div class="grid grid-cols-4 gap-6">
                    <div
                        v-for="e in etapes"
                        :key="e.num"
                        class="flex flex-col gap-4"
                        style="
                            border-top: 2px solid var(--color-default-red);
                            padding-top: 1.5rem;
                        "
                    >
                        <p
                            class="font-black"
                            style="
                                color: var(--color-default-red);
                                line-height: 1;
                            "
                        >
                            {{ e.num }}
                        </p>
                        <h3 class="font-bold text-black">
                            {{ e.title }}
                        </h3>
                        <p
                            style="
                                color: var(--default-text);
                                line-height: 1.65;
                            "
                        >
                            {{ e.desc }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA final -->
        <section class="py-20 text-center bg-gradient">
            <div class="max-w-2xl mx-auto px-8">
                <h2 class="font-bold mb-4 text-black" style="line-height: 1.25">
                    Votre entreprise mérite<br />d'être reconnue.
                </h2>
                <p
                    class="mb-10"
                    style="
                        color: var(--default-titles);
                        opacity: 0.8;
                        line-height: 1.6;
                    "
                >
                    Rejoignez les entreprises qui font la différence pour la
                    santé publique genevoise.
                </p>
                <div class="flex items-center justify-center gap-4 flex-wrap">
                    <RouterLink to="/login" class="btn btn-filled-red">
                        Candidater au Trophée 2026
                    </RouterLink>
                    <RouterLink
                        to="/label"
                        class="inline-block font-semibold rounded-full px-8 py-3 border-2 transition hover:opacity-75"
                        style="
                            color: var(--default-titles);
                            border-color: var(--default-titles);
                            text-decoration: none;
                            background: transparent;
                        "
                    >
                        Découvrir le Label CTS
                    </RouterLink>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <Footer />
    </div>
</template>
