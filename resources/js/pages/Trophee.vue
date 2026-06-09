<script setup>
import { ref, onMounted } from "vue";
import HugNavbar from "../components/HugNavbar.vue";

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
        <HugNavbar />

        <!-- Hero -->
        <section
            class="relative flex items-end pb-14 overflow-hidden"
            style="height: 460px; background: var(--default-titles)"
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
                    style="color: var(--color-default-green)"
                >
                    Depuis 2008
                </p>
                <h1
                    class="font-bold text-white leading-tight mb-4"
                >
                    Le Trophée<br />de la Générosité
                </h1>
                <p
                    class="text-white mb-8 max-w-xl"
                    style="opacity: 0.9; line-height: 1.6"
                >
                    La distinction annuelle qui récompense les entreprises
                    genevoises les plus engagées pour le don du sang.
                </p>
                <RouterLink
                    to="/login"
                    class="btn btn-filled-red"
                >
                    Candidater pour 2026 →
                </RouterLink>
            </div>
        </section>

        <!-- Stats -->
        <section class="bg-white py-16">
            <div class="max-w-4xl mx-auto px-8">
                <div class="grid grid-cols-3 gap-4">
                    <div class="rounded-xl px-5 py-4" style="background: var(--light-grey)">
                        <p class="font-black mb-2" style="color: var(--default-titles); line-height: 1">2008</p>
                        <p style="color: var(--default-text); line-height: 1.5">La distinction existe<br />depuis plus de 15 ans</p>
                    </div>
                    <div class="rounded-xl px-5 py-4" style="background: var(--light-grey)">
                        <p class="font-black mb-2" style="color: var(--default-titles); line-height: 1">10%</p>
                        <p style="color: var(--default-text); line-height: 1.5">des dons proviennent<br />des entreprises</p>
                    </div>
                    <div class="rounded-xl px-5 py-4" style="background: var(--light-grey)">
                        <p class="font-black mb-2" style="color: var(--default-titles); line-height: 1">+30</p>
                        <p style="color: var(--default-text); line-height: 1.5">
                            entreprises candidates<br />chaque année
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Une distinction qui compte -->
        <section class="bg-white py-20">
            <div
                class="max-w-7xl mx-auto px-8 grid grid-cols-2 gap-16 items-center"
            >
                <div>
                    <h2
                        class="font-bold mb-5 text-black"
                    >
                        Une distinction qui compte
                    </h2>
                    <p
                        class="mb-5"
                        style="
                            color: var(--default-text);
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
                            color: var(--default-text);
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

        <!-- Label vs Trophée -->
        <section class="py-20 bg-light-grey">
            <div class="max-w-5xl mx-auto px-8">
                <h2
                    class="font-bold text-center mb-14 text-black"
                >
                    Label CTS ou Trophée — quelle différence ?
                </h2>
                <div class="grid grid-cols-2 gap-16">
                    <!-- Label CTS -->
                    <div
                        style="
                            border-top: 2px solid var(--default-text);
                            padding-top: 1.5rem;
                        "
                    >
                        <p
                            class="font-bold mb-1"
                            style="color: var(--default-titles)"
                        >
                            Label CTS
                        </p>
                        <p
                            style="color: var(--default-text); margin-bottom: 2rem"
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
                                        color: var(--default-text);
                                        line-height: 1.55;
                                    "
                                    >Pour
                                    <strong style="color: var(--default-titles)"
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
                                        color: var(--default-text);
                                        line-height: 1.55;
                                    "
                                    ><strong style="color: var(--default-titles)"
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
                                        color: var(--default-text);
                                        line-height: 1.55;
                                    "
                                    >Valide
                                    <strong style="color: var(--default-titles)">1 an</strong
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
                                        color: var(--default-text);
                                        line-height: 1.55;
                                    "
                                    >Certifie votre
                                    <strong style="color: var(--default-titles)"
                                        >engagement citoyen</strong
                                    ></span
                                >
                            </div>
                        </div>
                        <RouterLink
                            to="/label"
                            class="inline-block mt-8 font-semibold rounded-full border-2 px-6 py-2 transition hover:opacity-75"
                            style="
                                color: var(--default-titles);
                                border-color: var(--default-titles);
                                text-decoration: none;
                            "
                            >Découvrir le Label →</RouterLink
                        >
                    </div>

                    <!-- Trophée -->
                    <div
                        style="
                            border-top: 2px solid var(--default-titles);
                            padding-top: 1.5rem;
                        "
                    >
                        <p
                            class="font-bold mb-1"
                            style="color: var(--default-titles)"
                        >
                            Trophée de la Générosité
                        </p>
                        <p
                            style="color: var(--default-text); margin-bottom: 2rem"
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
                                        color: var(--default-text);
                                        line-height: 1.55;
                                    "
                                    >Pour les entreprises
                                    <strong style="color: var(--default-titles)"
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
                                        color: var(--default-text);
                                        line-height: 1.55;
                                    "
                                    >Attribué par
                                    <strong style="color: var(--default-titles)"
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
                                        color: var(--default-text);
                                        line-height: 1.55;
                                    "
                                    >Distinction
                                    <strong style="color: var(--default-titles)"
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
                                        color: var(--default-text);
                                        line-height: 1.55;
                                    "
                                    >Récompense
                                    <strong style="color: var(--default-titles)"
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
                                background: var(--color-default-red);
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
            <div class="max-w-7xl mx-auto px-8">
                <h2
                    class="font-bold text-center mb-4 text-black"
                >
                    Comment est-il attribué ?
                </h2>
                <p
                    class="text-center mb-16 max-w-xl mx-auto"
                    style="color: var(--default-text); line-height: 1.6"
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
                            border-left: 2px solid var(--color-default-red);
                            padding-left: 1.5rem;
                        "
                    >
                        <p
                            class="font-black"
                            style="color: var(--color-default-red); line-height: 1"
                        >
                            {{ c.num }}
                        </p>
                        <h3
                            class="font-bold text-black"
                        >
                            {{ c.title }}
                        </h3>
                        <p
                            style="color: var(--default-text); line-height: 1.7"
                        >
                            {{ c.desc }}
                        </p>
                    </div>
                </div>
                <p
                    class="text-center italic"
                    style="color: var(--default-text)"
                >
                    Le jury HUG se réunit chaque année en décembre pour
                    délibérer et annoncer le ou les lauréats lors d'une
                    cérémonie officielle.
                </p>
            </div>
        </section>

        <!-- Témoignages -->
        <section
            class="py-20 bg-gradient"
        >
            <div class="max-w-7xl mx-auto px-8">
                <h2
                    class="font-bold text-center mb-12 text-black"
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
                                color: var(--default-titles);
                                line-height: 1.7;
                            "
                        >
                            « {{ t.quote }} »
                        </p>
                        <div
                            class="pt-4 border-t"
                            style="border-color: var(--light-grey)"
                        >
                            <p
                                class="font-semibold"
                                style="color: var(--default-titles)"
                            >
                                {{ t.author }}
                            </p>
                            <p style="color: var(--default-text)">
                                {{ t.company }}
                            </p>
                            <p class="captions" style="color: var(--color-default-green); margin-top: 6px">
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
                    class="font-bold text-center mb-4 text-black"
                >
                    Palmarès des lauréats
                </h2>
                <p
                    class="text-center mb-12"
                    style="color: var(--default-text)"
                >
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
                            style="font-size: 20px; color: var(--default-titles); width: 56px"
                            >2026</span
                        >
                        <span class="captions" style="background: #fef3c7; color: #92400e; padding: 2px 10px">À venir</span
                        >
                        <span style="font-size: 14px; color: var(--default-text)"
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

                <p
                    class="mt-8 text-center italic"
                    style="color: #c0cac9"
                >
                    * Les données historiques seront complétées en collaboration
                    avec le CTS des HUG.
                </p>
            </div>
        </section>

        <!-- Process candidature -->
        <section class="py-20 bg-light-grey">
            <div class="max-w-7xl mx-auto px-8">
                <h2
                    class="font-bold text-center mb-12 text-black"
                >
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
                            style="color: var(--color-default-red); line-height: 1"
                        >
                            {{ e.num }}
                        </p>
                        <h3
                            class="font-bold text-black"
                        >
                            {{ e.title }}
                        </h3>
                        <p
                            style="color: var(--default-text); line-height: 1.65"
                        >
                            {{ e.desc }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA final -->
        <section
            class="py-20 text-center bg-gradient"
        >
            <div class="max-w-2xl mx-auto px-8">
                <h2
                    class="font-bold mb-4 text-black"
                    style="line-height: 1.25"
                >
                    Votre entreprise mérite<br />d'être reconnue.
                </h2>
                <p
                    class="mb-10"
                    style="color: var(--default-titles); opacity: 0.8; line-height: 1.6"
                >
                    Rejoignez les entreprises qui font la différence pour la
                    santé publique genevoise.
                </p>
                <div class="flex items-center justify-center gap-4 flex-wrap">
                    <RouterLink
                        to="/login"
                        class="btn btn-filled-red"
                    >
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
        <footer style="background: var(--default-titles); padding: 3.5rem 0 2.5rem">
            <div class="max-w-7xl mx-auto px-8">
                <div class="grid grid-cols-4 gap-10 mb-12">
                    <div>
                        <div class="font-extrabold text-2xl mb-1 text-white">
                            HUG
                        </div>
                        <div class="captions" style="color: var(--color-default-green); line-height: 1.6">
                            Hôpitaux<br />Universitaires<br />Genève
                        </div>
                    </div>
                    <div>
                        <p
                            class="font-bold mb-5 text-white"
                        >
                            Pages
                        </p>
                        <ul class="space-y-3">
                            <li>
                                <RouterLink
                                    to="/label"
                                    class="text-white hover:opacity-70 transition"
                                    style="
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
                        >
                            Support
                        </p>
                        <ul class="space-y-3">
                            <li>
                                <RouterLink
                                    to="/faq"
                                    class="text-white hover:opacity-70 transition"
                                    style="
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
                        >
                            Mentions légales
                        </p>
                        <ul class="space-y-3">
                            <li>
                                <a
                                    href="#"
                                    class="text-white hover:opacity-70 transition"
                                    style="
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
                        style="color: var(--light-grey)"
                    >
                        © {{ new Date().getFullYear() }} Hôpitaux Universitaire
                        Genève. Tous droits réservés.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
