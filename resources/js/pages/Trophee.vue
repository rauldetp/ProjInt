<script setup>
import { ref, onMounted } from "vue";
import { useCoinEntrepriseLink } from "../composables/useCoinEntrepriseLink";

const { coinEntrepriseLink } = useCoinEntrepriseLink();

const palmares = ref([]);
const palmaresLoading = ref(true);

const temoignages = [
    {
        quote: "Recevoir le Trophée de la Générosité a été un moment de fierté collective pour toute notre équipe. Ça a renforcé notre culture d'entreprise autour de valeurs concrètes.",
        author: "Marc D., Directeur RH",
        company: "Banque Pictet & Cie",
        annee: "Lauréat 2010",
    },
    {
        quote: "Nous organisons des collectes depuis 2009. Le Trophée nous a donné une visibilité supplémentaire et a motivé encore plus de collaborateurs à franchir le pas.",
        author: "Christine L., Responsable RSE",
        company: "Centrale de Compensation",
        annee: "Lauréate 2009 et 2010",
    },
    {
        quote: "Ce trophée, c'est la preuve que des gestes simples peuvent avoir un impact énorme. 1 don = jusqu'à 3 vies sauvées. Notre équipe en est fière chaque jour.",
        author: "Antoine R., CEO",
        company: "Groupe horloger genevois",
        annee: "Lauréat 2024",
    },
];

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
        <!-- Navbar -->
        <header
            class="bg-white border-b border-[#f2f4f3] sticky top-0 z-50"
            style="height: 76px"
        >
            <div
                class="max-w-7xl mx-auto px-8 h-full flex items-center justify-between"
            >
                <div class="flex items-center gap-2">
                    <RouterLink
                        to="/"
                        class="font-extrabold text-xl tracking-tight"
                        style="color: #2c4140; text-decoration: none"
                        >HUG</RouterLink
                    >
                    <div
                        class="w-px h-5 mx-2"
                        style="background: rgba(44, 65, 64, 0.3)"
                    ></div>
                    <span class="text-base font-semibold" style="color: #e60f48"
                        >Don du sang</span
                    >
                </div>
                <nav
                    class="hidden md:flex items-center gap-7 text-base font-medium"
                >
                    <RouterLink
                        to="/label"
                        style="color: #2c4140; text-decoration: none"
                        class="hover:opacity-60 transition"
                        >Label CTS</RouterLink
                    >
                    <RouterLink
                        to="/trophee"
                        style="
                            color: #e60f48;
                            font-weight: 700;
                            text-decoration: none;
                        "
                        >Trophée de la générosité</RouterLink
                    >
                    <RouterLink
                        :to="coinEntrepriseLink"
                        style="color: #2c4140; text-decoration: none"
                        class="hover:opacity-60 transition"
                        >Coin entreprise</RouterLink
                    >
                    <RouterLink
                        to="/contact"
                        style="color: #2c4140; text-decoration: none"
                        class="hover:opacity-60 transition"
                        >Contact</RouterLink
                    >
                </nav>
                <RouterLink
                    to="/login"
                    class="border-2 rounded-full px-5 py-2 text-base font-semibold transition hover:opacity-75"
                    style="
                        color: #e60f48;
                        border-color: #e60f48;
                        text-decoration: none;
                    "
                >
                    Participer
                </RouterLink>
            </div>
        </header>

        <!-- Hero -->
        <section
            class="relative flex items-end pb-14 overflow-hidden"
            style="height: 460px; background: #2c4140"
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
                <p
                    class="font-semibold mb-3 uppercase tracking-widest"
                    style="font-size: 13px; color: #93cfa9"
                >
                    Depuis 2008
                </p>
                <h1
                    class="font-bold text-white leading-tight mb-4"
                    style="font-size: 52px"
                >
                    Le Trophée<br />de la Générosité
                </h1>
                <p
                    class="text-white mb-8 max-w-xl"
                    style="font-size: 18px; opacity: 0.9; line-height: 1.6"
                >
                    La distinction annuelle qui récompense les entreprises
                    genevoises les plus engagées pour le don du sang.
                </p>
                <RouterLink
                    to="/login"
                    class="inline-block text-white font-semibold rounded-full px-7 py-3 transition hover:opacity-80"
                    style="background: #e60f48; font-size: 16px; text-decoration: none"
                >
                    Candidater pour 2026 →
                </RouterLink>
            </div>
        </section>

        <!-- Stats -->
        <section style="background: #f2f4f3" class="py-16">
            <div class="max-w-4xl mx-auto px-8">
                <div class="grid grid-cols-3 gap-8 text-center">
                    <div>
                        <p
                            class="font-black mb-2"
                            style="
                                font-size: 48px;
                                color: #2c4140;
                                line-height: 1;
                            "
                        >
                            2008
                        </p>
                        <p
                            style="
                                font-size: 14px;
                                color: #497371;
                                line-height: 1.5;
                            "
                        >
                            La distinction existe<br />depuis plus de 15 ans
                        </p>
                    </div>
                    <div>
                        <p
                            class="font-black mb-2"
                            style="
                                font-size: 48px;
                                color: #2c4140;
                                line-height: 1;
                            "
                        >
                            10%
                        </p>
                        <p
                            style="
                                font-size: 14px;
                                color: #497371;
                                line-height: 1.5;
                            "
                        >
                            des dons proviennent<br />des entreprises
                        </p>
                    </div>
                    <div>
                        <p
                            class="font-black mb-2"
                            style="
                                font-size: 48px;
                                color: #2c4140;
                                line-height: 1;
                            "
                        >
                            +30
                        </p>
                        <p
                            style="
                                font-size: 14px;
                                color: #497371;
                                line-height: 1.5;
                            "
                        >
                            entreprises candidates<br />chaque année
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Une distinction qui compte -->
        <section class="bg-white py-20">
            <div
                class="max-w-6xl mx-auto px-8 grid grid-cols-2 gap-16 items-center"
            >
                <div>
                    <h2
                        class="font-bold mb-5"
                        style="font-size: 32px; color: #2c4140"
                    >
                        Une distinction qui compte
                    </h2>
                    <p
                        class="mb-5"
                        style="
                            font-size: 16px;
                            color: #497371;
                            line-height: 1.75;
                        "
                    >
                        Créé en 2008 par les Hôpitaux Universitaires de Genève,
                        le Trophée de la Générosité est la plus haute
                        distinction décernée aux entreprises partenaires du
                        Centre de Transfusion Sanguine. Il récompense chaque
                        année les organisations qui se sont le plus distinguées
                        par leur taux de participation, leur mobilisation
                        interne et leur régularité d'engagement.
                    </p>
                    <p
                        style="
                            font-size: 16px;
                            color: #497371;
                            line-height: 1.75;
                        "
                    >
                        Attribué par un jury constitué de membres des HUG, le
                        Trophée n'est pas une simple récompense — c'est une
                        reconnaissance officielle de l'impact concret que votre
                        entreprise a eu sur la santé publique genevoise.
                    </p>
                </div>
                <div
                    class="relative rounded-2xl overflow-hidden"
                    style="height: 320px; background: #f2f4f3"
                >
                    <img
                        :src="'/images/thumbnail_trophee.webp'"
                        alt="Trophée de la générosité"
                        class="absolute inset-0 w-full h-full object-cover"
                    />
                </div>
            </div>
        </section>

        <!-- Label vs Trophée -->
        <section style="background: #f2f4f3" class="py-20">
            <div class="max-w-5xl mx-auto px-8">
                <h2
                    class="font-bold text-center mb-14"
                    style="font-size: 32px; color: #2c4140"
                >
                    Label CTS ou Trophée — quelle différence ?
                </h2>
                <div class="grid grid-cols-2 gap-16">
                    <!-- Label CTS -->
                    <div
                        style="
                            border-top: 2px solid #497371;
                            padding-top: 1.5rem;
                        "
                    >
                        <p
                            class="font-bold mb-1"
                            style="font-size: 20px; color: #2c4140"
                        >
                            Label CTS
                        </p>
                        <p
                            style="
                                font-size: 13px;
                                color: #497371;
                                margin-bottom: 2rem;
                            "
                        >
                            Certification de participation
                        </p>
                        <div class="flex flex-col gap-4">
                            <div class="flex items-start gap-3">
                                <span
                                    style="
                                        color: #c0cac9;
                                        flex-shrink: 0;
                                        margin-top: 2px;
                                        font-size: 14px;
                                    "
                                    >—</span
                                >
                                <span
                                    style="
                                        font-size: 15px;
                                        color: #497371;
                                        line-height: 1.55;
                                    "
                                    >Pour
                                    <strong style="color: #2c4140"
                                        >toutes les entreprises</strong
                                    >
                                    qui organisent une collecte</span
                                >
                            </div>
                            <div class="flex items-start gap-3">
                                <span
                                    style="
                                        color: #c0cac9;
                                        flex-shrink: 0;
                                        margin-top: 2px;
                                        font-size: 14px;
                                    "
                                    >—</span
                                >
                                <span
                                    style="
                                        font-size: 15px;
                                        color: #497371;
                                        line-height: 1.55;
                                    "
                                    ><strong style="color: #2c4140"
                                        >Automatique</strong
                                    >
                                    dès la fin de la collecte</span
                                >
                            </div>
                            <div class="flex items-start gap-3">
                                <span
                                    style="
                                        color: #c0cac9;
                                        flex-shrink: 0;
                                        margin-top: 2px;
                                        font-size: 14px;
                                    "
                                    >—</span
                                >
                                <span
                                    style="
                                        font-size: 15px;
                                        color: #497371;
                                        line-height: 1.55;
                                    "
                                    >Valide
                                    <strong style="color: #2c4140">1 an</strong
                                    >, renouvelable</span
                                >
                            </div>
                            <div class="flex items-start gap-3">
                                <span
                                    style="
                                        color: #c0cac9;
                                        flex-shrink: 0;
                                        margin-top: 2px;
                                        font-size: 14px;
                                    "
                                    >—</span
                                >
                                <span
                                    style="
                                        font-size: 15px;
                                        color: #497371;
                                        line-height: 1.55;
                                    "
                                    >Certifie votre
                                    <strong style="color: #2c4140"
                                        >engagement citoyen</strong
                                    ></span
                                >
                            </div>
                        </div>
                        <RouterLink
                            to="/label"
                            class="inline-block mt-8 font-semibold rounded-full border-2 px-6 py-2 transition hover:opacity-75"
                            style="
                                font-size: 15px;
                                color: #2c4140;
                                border-color: #2c4140;
                                text-decoration: none;
                            "
                            >Découvrir le Label →</RouterLink
                        >
                    </div>

                    <!-- Trophée -->
                    <div
                        style="
                            border-top: 2px solid #2c4140;
                            padding-top: 1.5rem;
                        "
                    >
                        <p
                            class="font-bold mb-1"
                            style="font-size: 20px; color: #2c4140"
                        >
                            Trophée de la Générosité
                        </p>
                        <p
                            style="
                                font-size: 13px;
                                color: #497371;
                                margin-bottom: 2rem;
                            "
                        >
                            Distinction d'excellence
                        </p>
                        <div class="flex flex-col gap-4">
                            <div class="flex items-start gap-3">
                                <span
                                    style="
                                        color: #c0cac9;
                                        flex-shrink: 0;
                                        margin-top: 2px;
                                        font-size: 14px;
                                    "
                                    >—</span
                                >
                                <span
                                    style="
                                        font-size: 15px;
                                        color: #497371;
                                        line-height: 1.55;
                                    "
                                    >Pour les entreprises
                                    <strong style="color: #2c4140"
                                        >les plus engagées</strong
                                    >
                                    de l'année</span
                                >
                            </div>
                            <div class="flex items-start gap-3">
                                <span
                                    style="
                                        color: #c0cac9;
                                        flex-shrink: 0;
                                        margin-top: 2px;
                                        font-size: 14px;
                                    "
                                    >—</span
                                >
                                <span
                                    style="
                                        font-size: 15px;
                                        color: #497371;
                                        line-height: 1.55;
                                    "
                                    >Attribué par
                                    <strong style="color: #2c4140"
                                        >jury HUG</strong
                                    >
                                    en décembre</span
                                >
                            </div>
                            <div class="flex items-start gap-3">
                                <span
                                    style="
                                        color: #c0cac9;
                                        flex-shrink: 0;
                                        margin-top: 2px;
                                        font-size: 14px;
                                    "
                                    >—</span
                                >
                                <span
                                    style="
                                        font-size: 15px;
                                        color: #497371;
                                        line-height: 1.55;
                                    "
                                    >Distinction
                                    <strong style="color: #2c4140"
                                        >permanente</strong
                                    >
                                    au palmarès</span
                                >
                            </div>
                            <div class="flex items-start gap-3">
                                <span
                                    style="
                                        color: #c0cac9;
                                        flex-shrink: 0;
                                        margin-top: 2px;
                                        font-size: 14px;
                                    "
                                    >—</span
                                >
                                <span
                                    style="
                                        font-size: 15px;
                                        color: #497371;
                                        line-height: 1.55;
                                    "
                                    >Récompense
                                    <strong style="color: #2c4140"
                                        >l'excellence</strong
                                    >
                                    et la régularité</span
                                >
                            </div>
                        </div>
                        <RouterLink
                            to="/login"
                            class="inline-block mt-8 text-white font-semibold rounded-full px-6 py-2 transition hover:opacity-80"
                            style="
                                font-size: 15px;
                                background: #e60f48;
                                text-decoration: none;
                            "
                            >Candidater au Trophée 2026 →</RouterLink
                        >
                    </div>
                </div>
            </div>
        </section>

        <!-- Critères d'attribution -->
        <section class="bg-white py-20">
            <div class="max-w-6xl mx-auto px-8">
                <h2
                    class="font-bold text-center mb-4"
                    style="font-size: 32px; color: #2c4140"
                >
                    Comment est-il attribué ?
                </h2>
                <p
                    class="text-center mb-16 max-w-xl mx-auto"
                    style="font-size: 16px; color: #497371; line-height: 1.6"
                >
                    Trois critères évalués par le jury HUG pour identifier les
                    entreprises les plus méritantes.
                </p>
                <div class="grid grid-cols-3 gap-12 mb-12">
                    <div
                        v-for="c in criteres"
                        :key="c.title"
                        class="flex flex-col gap-3"
                        style="
                            border-left: 2px solid #e60f48;
                            padding-left: 1.5rem;
                        "
                    >
                        <p
                            class="font-black"
                            style="
                                font-size: 28px;
                                color: #e60f48;
                                line-height: 1;
                            "
                        >
                            {{ c.num }}
                        </p>
                        <h3
                            class="font-bold"
                            style="font-size: 17px; color: #2c4140"
                        >
                            {{ c.title }}
                        </h3>
                        <p
                            style="
                                font-size: 14px;
                                color: #497371;
                                line-height: 1.7;
                            "
                        >
                            {{ c.desc }}
                        </p>
                    </div>
                </div>
                <p
                    class="text-center italic"
                    style="font-size: 14px; color: #497371"
                >
                    Le jury HUG se réunit chaque année en décembre pour
                    délibérer et annoncer le ou les lauréats lors d'une
                    cérémonie officielle.
                </p>
            </div>
        </section>

        <!-- Témoignages -->
        <section
            class="py-20"
            style="background: linear-gradient(135deg, #65c6c1, #93cfa9)"
        >
            <div class="max-w-6xl mx-auto px-8">
                <h2
                    class="font-bold text-center mb-12"
                    style="font-size: 32px; color: #2c4140"
                >
                    Ils ont reçu le Trophée
                </h2>
                <div class="grid grid-cols-3 gap-6">
                    <div
                        v-for="t in temoignages"
                        :key="t.author"
                        class="rounded-2xl bg-white flex flex-col gap-4"
                        style="padding: 2rem"
                    >
                        <p
                            class="italic flex-1"
                            style="
                                font-size: 15px;
                                color: #2c4140;
                                line-height: 1.7;
                            "
                        >
                            « {{ t.quote }} »
                        </p>
                        <div
                            class="pt-4 border-t"
                            style="border-color: #f2f4f3"
                        >
                            <p
                                class="font-semibold"
                                style="font-size: 14px; color: #2c4140"
                            >
                                {{ t.author }}
                            </p>
                            <p style="font-size: 13px; color: #497371">
                                {{ t.company }}
                            </p>
                            <p
                                class="font-semibold"
                                style="
                                    font-size: 12px;
                                    color: #93cfa9;
                                    margin-top: 6px;
                                "
                            >
                                {{ t.annee }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Palmarès -->
        <section class="bg-white py-20">
            <div class="max-w-4xl mx-auto px-8">
                <h2
                    class="font-bold text-center mb-4"
                    style="font-size: 32px; color: #2c4140"
                >
                    Palmarès des lauréats
                </h2>
                <p
                    class="text-center mb-12"
                    style="font-size: 16px; color: #497371"
                >
                    Depuis la création du Trophée, ces entreprises se sont
                    distinguées par leur engagement exceptionnel.
                </p>

                <div
                    v-if="palmaresLoading"
                    class="text-center py-8"
                    style="color: #497371"
                >
                    Chargement...
                </div>

                <template v-else>
                    <div
                        class="flex items-center gap-6 py-5 border-b"
                        style="border-color: #f2f4f3"
                    >
                        <span
                            class="font-black flex-shrink-0"
                            style="font-size: 20px; color: #2c4140; width: 56px"
                            >2026</span
                        >
                        <span
                            style="
                                font-size: 12px;
                                font-weight: 600;
                                background: #fef3c7;
                                color: #92400e;
                                padding: 2px 10px;
                            "
                            >À venir</span
                        >
                        <span style="font-size: 14px; color: #497371"
                            >Cérémonie décembre 2026</span
                        >
                    </div>

                    <template v-if="palmares.length > 0">
                        <div
                            v-for="annee in palmares"
                            :key="annee.annee"
                            class="flex items-center gap-6 py-5 border-b"
                            style="border-color: #f2f4f3"
                        >
                            <span
                                class="font-black flex-shrink-0"
                                style="
                                    font-size: 20px;
                                    color: #2c4140;
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
                                        background: #f2f4f3;
                                        color: #2c4140;
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
                            style="border-color: #f2f4f3"
                        >
                            <span
                                class="font-black flex-shrink-0"
                                style="
                                    font-size: 20px;
                                    color: #2c4140;
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

                <p
                    class="mt-8 text-center italic"
                    style="font-size: 13px; color: #c0cac9"
                >
                    * Les données historiques seront complétées en collaboration
                    avec le CTS des HUG.
                </p>
            </div>
        </section>

        <!-- Process candidature -->
        <section style="background: #f2f4f3" class="py-20">
            <div class="max-w-6xl mx-auto px-8">
                <h2
                    class="font-bold text-center mb-12"
                    style="font-size: 32px; color: #2c4140"
                >
                    Comment candidater ?
                </h2>
                <div class="grid grid-cols-4 gap-6">
                    <div
                        v-for="e in etapes"
                        :key="e.num"
                        class="flex flex-col gap-4"
                        style="
                            border-top: 2px solid #e60f48;
                            padding-top: 1.5rem;
                        "
                    >
                        <p
                            class="font-black"
                            style="
                                font-size: 32px;
                                color: #e60f48;
                                line-height: 1;
                            "
                        >
                            {{ e.num }}
                        </p>
                        <h3
                            class="font-bold"
                            style="font-size: 15px; color: #2c4140"
                        >
                            {{ e.title }}
                        </h3>
                        <p
                            style="
                                font-size: 14px;
                                color: #497371;
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
        <section
            class="py-20 text-center"
            style="background: linear-gradient(135deg, #65c6c1, #93cfa9)"
        >
            <div class="max-w-2xl mx-auto px-8">
                <h2
                    class="font-bold mb-4"
                    style="font-size: 36px; color: #2c4140; line-height: 1.25"
                >
                    Votre entreprise mérite<br />d'être reconnue.
                </h2>
                <p
                    class="mb-10"
                    style="
                        font-size: 17px;
                        color: #2c4140;
                        opacity: 0.8;
                        line-height: 1.6;
                    "
                >
                    Rejoignez les entreprises qui font la différence pour la
                    santé publique genevoise.
                </p>
                <div class="flex items-center justify-center gap-4 flex-wrap">
                    <RouterLink
                        to="/login"
                        class="inline-block text-white font-semibold rounded-full px-8 py-3 transition hover:opacity-80"
                        style="
                            background: #e60f48;
                            font-size: 16px;
                            text-decoration: none;
                        "
                    >
                        Candidater au Trophée 2026
                    </RouterLink>
                    <RouterLink
                        to="/label"
                        class="inline-block font-semibold rounded-full px-8 py-3 border-2 transition hover:opacity-75"
                        style="
                            color: #2c4140;
                            border-color: #2c4140;
                            font-size: 16px;
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
        <footer style="background: #2c4140; padding: 3.5rem 0 2.5rem">
            <div class="max-w-6xl mx-auto px-8">
                <div class="grid grid-cols-4 gap-10 mb-12">
                    <div>
                        <div class="font-extrabold text-2xl mb-1 text-white">
                            HUG
                        </div>
                        <div
                            style="
                                font-size: 12px;
                                color: #93cfa9;
                                line-height: 1.6;
                            "
                        >
                            Hôpitaux<br />Universitaires<br />Genève
                        </div>
                    </div>
                    <div>
                        <p
                            class="font-bold mb-5 text-white"
                            style="font-size: 24px"
                        >
                            Pages
                        </p>
                        <ul class="space-y-3">
                            <li>
                                <RouterLink
                                    to="/label"
                                    class="text-white hover:opacity-70 transition"
                                    style="
                                        font-size: 16px;
                                        text-decoration: none;
                                    "
                                    >Label CTS</RouterLink
                                >
                            </li>
                            <li>
                                <RouterLink
                                    to="/trophee"
                                    class="text-white hover:opacity-70 transition"
                                    style="
                                        font-size: 16px;
                                        text-decoration: none;
                                    "
                                    >Trophée de la générosité</RouterLink
                                >
                            </li>
                        </ul>
                    </div>
                    <div>
                        <p
                            class="font-bold mb-5 text-white"
                            style="font-size: 24px"
                        >
                            Support
                        </p>
                        <ul class="space-y-3">
                            <li>
                                <RouterLink
                                    to="/faq"
                                    class="text-white hover:opacity-70 transition"
                                    style="
                                        font-size: 16px;
                                        text-decoration: none;
                                    "
                                    >FAQ</RouterLink
                                >
                            </li>
                            <li>
                                <RouterLink
                                    to="/contact"
                                    class="text-white hover:opacity-70 transition"
                                    style="
                                        font-size: 16px;
                                        text-decoration: none;
                                    "
                                    >Contact</RouterLink
                                >
                            </li>
                        </ul>
                    </div>
                    <div>
                        <p
                            class="font-bold mb-5 text-white"
                            style="font-size: 24px"
                        >
                            Mentions légales
                        </p>
                        <ul class="space-y-3">
                            <li>
                                <a
                                    href="#"
                                    class="text-white hover:opacity-70 transition"
                                    style="
                                        font-size: 16px;
                                        text-decoration: none;
                                    "
                                    >Politique de confidentialité</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="text-white hover:opacity-70 transition"
                                    style="
                                        font-size: 16px;
                                        text-decoration: none;
                                    "
                                    >Conditions générales</a
                                >
                            </li>
                        </ul>
                    </div>
                </div>
                <div
                    class="border-t pt-6"
                    style="border-color: rgba(242, 244, 243, 0.15)"
                >
                    <p
                        class="text-center"
                        style="font-size: 16px; color: #f2f4f3"
                    >
                        © {{ new Date().getFullYear() }} Hôpitaux Universitaire
                        Genève. Tous droits réservés.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
