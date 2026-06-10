<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import { useCobrandStore } from "../stores/cobrand";
import AppNavbar from "../components/AppNavbar.vue";
import Footer from "../components/Footer.vue";

const route = useRoute();
const cobrand = useCobrandStore();

const palmares = ref([]);
const palmaresLoading = ref(true);

// Mode cobrandé déterminé par la route ; seules les couleurs d'accent changent.
const isCobrand = computed(() => !!route.params.slug);
const brandColor = computed(
    () => cobrand.couleurPrimaire || "var(--color-default-red)",
);
const sectionGradient = computed(
    () => `linear-gradient(135deg, ${brandColor.value}, #ffffff)`,
);

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
    // En cobrandé : récupère l'entreprise pour appliquer ses couleurs.
    if (isCobrand.value) {
        try {
            const res = await fetch(`/api/entreprises/${route.params.slug}`);
            if (res.ok) {
                const data = await res.json();
                if (data.entreprise) cobrand.set(data.entreprise);
            }
        } catch {
            // silent fail
        }
    }
    document.title = `Trophée de la générosité — ${isCobrand.value ? cobrand.nom || "HUG" : "HUG"}`;

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
        <AppNavbar />

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
                        Chaque année, une entreprise est sélectionnée par notre
                        comité pour recevoir le Trophée de la Générosité, une
                        récompense qui met en avant nos valeurs de partage et de
                        communauté.
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
                    class="aspect-video w-full rounded-md overflow-hidden rounded-lg"
                >
                    <img
                        :src="'/images/thumbnail_trophee.webp'"
                        alt=""
                        class="object-cover"
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
                    class="aspect-video w-full rounded-md overflow-hidden rounded-lg"
                >
                    <img
                        :src="'/images/thumbnail_winner.webp'"
                        alt="Gagnant 2026 - Groupe Mercier SA"
                        class="object-cover"
                    />
                </div>
            </div>
        </section>

        <section
            class="py-20"
            :class="isCobrand ? '' : 'bg-gradient'"
            :style="isCobrand ? { background: sectionGradient } : null"
        >
            <div class="max-w-7xl mx-auto px-8 text-center">
                <h1
                    class="font-bold mb-4 text-black"
                    :style="isCobrand ? { color: cobrand.textOnBrand } : null"
                >
                    “Aujourd'hui, le don de sang fait partie de notre culture
                    d'entreprise, et nous en sommes fiers.”
                </h1>
                <p :style="isCobrand ? { color: cobrand.textOnBrand } : null">
                    Marc-Antoine Favre, Directeur des Ressources Humaines,
                    Groupe Mercier SA
                </p>
            </div>
        </section>

        <!-- Valeurs -->
        <section
            class="bg-white py-20 border-t"
            style="border-color: var(--light-grey)"
        >
            <div class="max-w-7xl mx-auto px-8">
                <h2 class="font-bold text-center mb-12 text-black">
                    Les valeurs du Trophée de la Générosité
                </h2>
                <div class="grid grid-cols-3 gap-8">
                    <div class="flex flex-col items-center text-center gap-4">
                        <div
                            class="circle-icon rounded-full bg-light-grey flex items-center justify-center"
                        >
                            <span class="material-symbols-outlined"
                                >diamond</span
                            >
                        </div>
                        <h3 class="font-bold text-black">L’excellence</h3>
                        <p>
                            Le Trophée récompense ceux qui vont plus loin. Ce
                            n'est pas une récompense de participation. C'est une
                            distinction pour les entreprises qui ont fait de
                            leur engagement une véritable priorité.
                        </p>
                    </div>
                    <div class="flex flex-col items-center text-center gap-4">
                        <div
                            class="circle-icon rounded-full bg-light-grey flex items-center justify-center"
                        >
                            <span class="material-symbols-outlined"
                                >visibility</span
                            >
                        </div>
                        <h3 class="font-bold text-black">La transparence</h3>
                        <p>
                            La sélection est assurée par un comité indépendant,
                            selon des critères clairs. Pas de favoritisme, pas
                            de politique, c’est simplement la reconnaissance
                            d'un engagement sincère et mesurable.
                        </p>
                    </div>
                    <div class="flex flex-col items-center text-center gap-4">
                        <div
                            class="circle-icon rounded-full bg-light-grey flex items-center justify-center"
                        >
                            <span class="material-symbols-outlined"
                                >all_inclusive</span
                            >
                        </div>
                        <h3 class="font-bold text-black">La continuité</h3>
                        <p>
                            Un don, c'est bien. Une culture du don, c'est mieux.
                            Nous valorisons les entreprises qui inscrivent leur
                            engagement dans la durée, et qui reviennent année
                            après année
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <!-- Comment ça marche ? -->
        <section class="bg-light-grey py-20">
            <div
                class="max-w-7xl mx-auto px-8 grid grid-cols-2 gap-16 items-center"
            >
                <div>
                    <h2 class="font-bold mb-5 text-black">
                        Comment ça marche ?
                    </h2>
                    <p class="mb-5">
                        Le trophée est ouvert à toutes les entreprises
                        labellisées CTS. Pas de formulaire complexe, pas de
                        candidature fastidieuse, une entreprise peut, à chaque
                        nouvelle collecte, inscrire son entreprise. Chaque
                        candidature sera prise en compte et nous désignerons un
                        lauréat lors d’une cérémonie annuelle.
                    </p>
                    <RouterLink
                        to="/login"
                        class="btn btn-filled-red"
                        :style="
                            isCobrand
                                ? {
                                      background: brandColor,
                                      borderColor: brandColor,
                                      color: cobrand.textOnBrand,
                                  }
                                : null
                        "
                    >
                        Inscrire mon entreprise
                    </RouterLink>
                </div>
                <div
                    class="aspect-video w-full rounded-md overflow-hidden rounded-lg"
                >
                    <img
                        :src="'/images/thumbnail_howitworks_trophy.webp'"
                        alt="Deux personnes discutant devant un écran"
                        class="object-cover"
                    />
                </div>
            </div>
        </section>
        <!-- Footer -->
        <Footer :slug="route.params.slug" />
    </div>
</template>
