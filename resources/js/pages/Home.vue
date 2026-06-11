<script setup>
import { ref, onMounted } from "vue";
import AppNavbar from "../components/AppNavbar.vue";
import Footer from "../components/Footer.vue";

const bannerVisible = ref(true);
const openFaq = ref(null);

const faqItems = [
    {
        q: "Quels sont les critères pour obtenir le label CTS ?",
        a: "Le label CTS est attribué à toute entreprise ayant organisé au moins une collecte de don du sang dans l'année. Il est décerné automatiquement à l'issue de la collecte par les HUG, sans candidature particulière à déposer.",
    },
    {
        q: "Comment fonctionne la sélection pour le trophée annuel ?",
        a: "Le Trophée de la Générosité est attribué par un jury HUG chaque décembre. Trois critères sont évalués : le taux de participation (donneurs présentés / effectif total), la régularité de l'engagement sur plusieurs années, et la qualité de la mobilisation interne.",
    },
    {
        q: "Combien de temps faut-il pour mettre en place une collecte ?",
        a: "En général, 4 à 6 semaines suffisent entre la première prise de contact et le jour J. L'équipe CTS des HUG vous accompagne pour toute la logistique : matériel, personnel soignant, gestion des créneaux.",
    },
    {
        q: "Qui organise la collecte dans nos locaux ?",
        a: "L'équipe mobile du Centre de Transfusion Sanguine (CTS) des HUG se déplace directement dans vos locaux avec tout le matériel nécessaire. Votre coordinateur interne s'occupe de la communication et de la gestion des inscriptions via la plateforme.",
    },
    {
        q: "Y a-t-il un coût pour l'entreprise ?",
        a: "Non. L'organisation de la collecte est entièrement prise en charge par les HUG. L'entreprise met simplement à disposition un espace adapté (environ 50 m² minimum) et libère du temps à ses collaborateurs pour le don.",
    },
    {
        q: "Les données de nos employés sont-elles protégées ?",
        a: "Oui. Seules les données strictement nécessaires à la gestion des inscriptions sont collectées, conformément au RGPD et à la loi suisse sur la protection des données (LPD). Aucune donnée médicale n'est transmise à l'entreprise.",
    },
];

function toggleFaq(i) {
    openFaq.value = openFaq.value === i ? null : i;
}

onMounted(() => {
    document.title = "Trophée de la générosité — HUG";
});
</script>

<template>
    <div class="min-h-screen bg-white">
        <AppNavbar />  
        <div
            v-if="bannerVisible"
            class="banner bg-gradient px-3 md:px-6 py-2 flex items-center justify-between shadow-light"
         >
            <span class="captions">
                Ce site est un projet étudiant réalisé dans un cadre
                académique. Il ne représente pas une organisation
                officielle. Retrouvez le site officiel à
                <a href="https://www.hug.ch/don-du-sang" class="underline">cette adresse</a>.
            </span>
            <button
                @click="bannerVisible = false"
                class="leading-none hover:opacity-70 px-2 py-2"
            >
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Hero -->
        <section
            class="section-hero relative flex items-end pb-16 lg:items-center lg:pb-0"
            style="background: var(--default-titles)"
        >
            <img
                :src="'/images/Hero_homepage.webp'"
                alt=""
                class="absolute inset-0 w-full h-full object-cover"
                style="opacity: 0.6"
            />
            <div
                class="absolute inset-0"
                style="background: rgba(44, 65, 64, 0.4)"
            ></div>
            <div class="relative max-w-7xl mx-auto px-4 md:px-8 w-full z-10">
                <h1
                    class="font-bold text-white leading-tight max-w-xl mb-6"
                >
                    Engagez votre entreprise.<br />Obtenez le label CTS.
                </h1>
                <div class="flex items-center gap-4 flex-wrap">
                    <RouterLink
                        to="/label"
                        class="btn btn-filled-red"
                    >
                        En savoir plus
                    </RouterLink>
                </div>
            </div>
        </section>

        <!-- Stats -->
        <section class="bg-white py-10">
            <div class="max-w-4xl mx-auto px-4 md:px-8 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="rounded-xl px-5 py-4 bg-light-grey">
                    <h2 class="font-bold mb-1">100+</h2>
                    <p class="captions">Entreprises partenaires</p>
                </div>
                <div class="rounded-xl px-5 py-4 bg-light-grey">
                    <h2 class="font-bold mb-1">653</h2>
                    <p class="captions">Collectes organisées</p>
                </div>
                <div class="rounded-xl px-5 py-4 bg-light-grey">
                    <h2 class="font-bold mb-1">1000+</h2>
                    <p class="captions">Patients bénéficiaires</p>
                </div>
            </div>
        </section>

        <!-- Partners strip (gradient) -->
        <section
        class="bg-gradient py-12 md:py-20"
        >
            <div class="mx-auto">
                <h2
                    class="text-center mb-8 text-black"
                >
                    Nos partenaires
            </h2>
                <div class="marquee-track">
                    <div class="marquee-inner">
                        <div class="marquee-group">
                            <img src="/images/marquee/logo_bcge.png" class="img-marquee" alt="BCGE">
                            <img src="/images/marquee/rolex-logo.png" class="img-marquee" alt="Rolex">
                            <img src="/images/marquee/logo_UBS.png" class="img-marquee" alt="UBS">
                            <img src="/images/marquee/logo_Pictet.png" class="img-marquee" alt="Banque Pictet">
                            <img src="/images/marquee/logo_Lombard.png" class="img-marquee" alt="Lombard Odier">
                            <img src="/images/marquee/logo_Logitech.png" class="img-marquee" alt="Logitech">
                            <img src="/images/marquee/logo_Patek.png" class="img-marquee" alt="Patek Philippe">
                            <img src="/images/marquee/logo_Genevatrading.png" class="img-marquee" alt="Geneva Trading">
                            <img src="/images/marquee/logo_SIG.png" class="img-marquee" alt="SIG">
                            <img src="/images/marquee/La-Vie-Black-Logo-1.png" class="img-marquee" alt="La Vie">
                            <img src="/images/marquee/Logo_Nestle.svg.png" class="img-marquee" alt="Nestlé">
                        </div>
                        <div class="marquee-group" aria-hidden="true">
                            <img src="/images/marquee/logo_bcge.png" class="img-marquee" alt="BCGE">
                            <img src="/images/marquee/rolex-logo.png" class="img-marquee" alt="Rolex">
                            <img src="/images/marquee/logo_UBS.png" class="img-marquee" alt="UBS">
                            <img src="/images/marquee/logo_Pictet.png" class="img-marquee" alt="Banque Pictet">
                            <img src="/images/marquee/logo_Lombard.png" class="img-marquee" alt="Lombard Odier">
                            <img src="/images/marquee/logo_Logitech.png" class="img-marquee" alt="Logitech">
                            <img src="/images/marquee/logo_Patek.png" class="img-marquee" alt="Patek Philippe">
                            <img src="/images/marquee/logo_Genevatrading.png" class="img-marquee" alt="Geneva Trading">
                            <img src="/images/marquee/logo_SIG.png" class="img-marquee" alt="SIG">
                            <img src="/images/marquee/La-Vie-Black-Logo-1.png" class="img-marquee" alt="La Vie">
                            <img src="/images/marquee/Logo_Nestle.svg.png" class="img-marquee" alt="Nestlé">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3 steps -->
        <section class="bg-light-grey py-12 md:py-20">
            <div class="max-w-7xl mx-auto px-4 md:px-8">
                <h2
                    class="text-center font-bold mb-12 text-black"
                >
                    La démarche en trois étapes
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white rounded-xl p-8 shadow-light">
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="w-11 h-11 rounded-full flex items-center justify-center font-bold flex-shrink-0"
                                style="border: 1px solid var(--color-default-red); color: var(--color-default-red); background: white;"
                            >1</div>
                            <div class="flex-1 h-px" style="background: var(--light-grey)"></div>
                        </div>
                        <h3 class="font-bold mb-3 text-black">Prise de contact</h3>
                        <p>Notre équipe vous accompagne pour planifier votre projet selon vos besoins.</p>
                    </div>
                    <div class="bg-white rounded-xl p-8 shadow-light">
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="w-11 h-11 rounded-full flex items-center justify-center font-bold flex-shrink-0"
                                style="border: 1px solid var(--color-default-red); color: var(--color-default-red); background: white;"
                            >2</div>
                            <div class="flex-1 h-px" style="background: var(--light-grey)"></div>
                        </div>
                        <h3 class="font-bold mb-3 text-black">Organisation de la collecte</h3>
                        <p>Nous planifions ensemble la collecte dans vos locaux et toute sécurité.</p>
                    </div>
                    <div class="bg-white rounded-xl p-8 shadow-light">
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="w-11 h-11 rounded-full flex items-center justify-center font-bold flex-shrink-0"
                                style="border: 1px solid var(--color-default-red); color: var(--color-default-red); background: white;"
                            >3</div>
                            <div class="flex-1 h-px" style="background: var(--light-grey)"></div>
                        </div>
                        <h3 class="font-bold mb-3 text-black">Label & Impact</h3>
                        <p>Nous valorisons ensemble la collecte dans vos locaux et toute sécurité.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Label CTS -->
        <section id="label" class="bg-white py-12 md:py-20" >
            <div
                class="max-w-7xl mx-auto px-4 md:px-8 grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-16 items-center"
            >
                <div>
                    <h2
                        class="font-bold mb-4 text-black"
                    >
                        Label CTS
                    </h2>
                    <p
                        class="mb-8 max-w-md"
                    >
                        Une reconnaissance officielle décernée annuellement par
                        les HUG pour valoriser l'ensemble des organisations
                        généreuses qui agissent pour le don du sang. Rejoignez
                        notre annuaire public et affichez fièrement vos valeurs
                        de solidarité.
                    </p>
                    <a
                        href="#contact"
                        class="btn btn-filled-red"
                    >
                        En savoir plus
                    </a>
                </div>
                <div
                    class="flex flex-col items-center justify-center gap-4 rounded-2xl p-12"
                    style="background-color: var(--light-grey)"
                >
                    <img
                        :src="'/images/logo_labelCTS_red.png'"
                        alt="Label CTS"
                    />
                </div>
            </div>
        </section>

        <!-- Trophy -->
        <section id="trophee" class="bg-light-grey py-12 md:py-20">
            <div
                class="max-w-7xl mx-auto px-4 md:px-8 grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-16 items-center"
            >
                <div
                    class="media-block relative rounded-2xl overflow-hidden"
                    style="background: var(--default-titles)"
                >
                    <img
                        :src="'/images/HomePage_TropheeGenerosite.webp'"
                        alt=""
                        class="absolute inset-0 w-full h-full object-cover"
                        style="opacity: 0.7"
                    />
                    <div
                        class="absolute inset-0"
                        style="
                            background: linear-gradient(
                                to top,
                                rgba(44, 65, 64, 0.6),
                                transparent
                            );
                        "
                    ></div>
                </div>
                <div>
                    <h2
                        class="font-bold mb-4 text-black"
                    >
                        Trophée de la générosité
                    </h2>
                    <p
                        class="mb-8 max-w-md"
                        style="
                            color: var(--default-text);
                            line-height: 1.7;
                        "
                    >
                        Prêts à relever le défi ? Ce prix historique récompense
                        la mobilisation collective et couronne les entreprises
                        enregistrant les meilleurs taux de participation au
                        prorata de leurs effectifs.
                    </p>
                    <a
                        href="#"
                        class="btn btn-filled-red"
                    >
                        Découvrir le trophée
                    </a>
                </div>
            </div>
        </section>

        <!-- Témoignages -->
        <section id="temoignages" class="bg-white py-12 md:py-20">
            <div
                class="max-w-7xl mx-auto px-4 md:px-8 grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-16 items-center"
            >
                <div>
                    <h2
                        class="font-bold mb-8 text-black"
                    >
                        Témoignages
                    </h2>
                    <p
                    >
                        « Mettre en place la campagne a été bien plus simple que
                        ce qu'on imaginait. En quelques clics, nos employés
                        avaient accès à toutes les informations et pouvaient
                        s'inscrire directement. C'est le genre d'initiative qui
                        renforce vraiment la cohésion d'équipe tout en ayant un
                        impact concret. On recommande sans hésiter. »
                </p>
            </br>
        </br>
                    <p>
                        Sophia M., Responsable RH, groupe industriel de 1 200
                        collaborateurs
                    </p>
                </div>
                <div
                    class="media-block relative rounded-2xl overflow-hidden"
                    style="background: var(--light-grey)"
                >
                    <img
                        :src="'/images/thumbnail_temoignage.webp'"
                        alt=""
                        class="absolute inset-0 w-full h-full object-cover"
                    />
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section id="faq" class="bg-light-grey py-12 md:py-20">
            <div class="max-w-3xl mx-auto px-4 md:px-8">
                <h2
                    class="text-center font-bold mb-10 text-black"
                >
                    Des questions ?
                </h2>
                <div class="space-y-3 mb-10">
                    <div
                        v-for="(item, i) in faqItems"
                        :key="i"
                        class="bg-white rounded-xl shadow-light"
                    >
                        <button class="faq-btn" @click="toggleFaq(i)">
                            {{ item.q }}
                            <div class="faq-btn-circle">
                                <span class="faq-btn-icon material-symbols-outlined">
                                    {{ openFaq === i ? 'expand_less' : 'expand_more' }}
                                </span>
                            </div>
                        </button>
                        <div class="faq-answer" :class="{ open: openFaq === i }">
                            <div class="faq-answer-inner">
                                <div class="mx-6" style="height: 1px; background: var(--light-grey)"></div>
                                <div class="px-6 py-5" style="color: var(--default-text)">
                                    {{ item.a }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <RouterLink
                        to="/faq"
                        class="btn btn-filled-red"
                    >
                        Vers la FAQ complète →
                    </RouterLink>
                </div>
            </div>
        </section>

        <!-- CTA bottom (gradient) -->
        <section
        class="bg-gradient py-12 md:py-20"
        >
            <div class="max-w-2xl mx-auto px-4 md:px-8 text-center">
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
            to="/label"
            class="btn btn-filled-red"
        >
            Découvrez nos entreprises partenaires →
        </RouterLink>            </div>
        </section>

        <!-- Footer -->
        <Footer />
    </div>
</template>
