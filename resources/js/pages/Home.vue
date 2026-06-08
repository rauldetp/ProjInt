<script setup>
import { ref, onMounted } from "vue";
import HugNavbar from "../components/HugNavbar.vue";

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
        <HugNavbar />
        <div
            v-if="bannerVisible"
            class="banner flex items-center justify-between"
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
            class="relative flex items-end pb-16 lg:items-center lg:pb-0"
            style="height: 512px; background: var(--default-titles)"
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
            <div class="relative max-w-6xl mx-auto px-8 w-full z-10">
                <h1
                    class="font-bold text-white leading-tight max-w-xl mb-6"
                    style="font-size: 48px"
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
                    <RouterLink
                        to="/quiz"
                        class="inline-block text-white text-sm font-semibold px-6 py-3 rounded-full transition hover:opacity-80"
                        style="background: transparent; border: 2px solid white"
                    >
                        Quiz d'éligibilité →
                    </RouterLink>
                </div>
            </div>
        </section>

        <!-- Stats -->
        <section class="bg-white" style="padding: 40px 0; position: relative; z-index: 1; margin-top: -2px">
            <div class="max-w-6xl mx-auto px-8 grid grid-cols-3 gap-4">
                <div class="rounded-xl px-5 py-4" style="background: var(--light-grey)">
                    <p class="font-bold mb-1" style="font-size: 24px; color: var(--default-titles)">100+</p>
                    <p class="captions" style="color: var(--default-text)">Entreprises partenaires</p>
                </div>
                <div class="rounded-xl px-5 py-4" style="background: var(--light-grey)">
                    <p class="font-bold mb-1" style="font-size: 24px; color: var(--default-titles)">653</p>
                    <p class="captions" style="color: var(--default-text)">Collectes organisées</p>
                </div>
                <div class="rounded-xl px-5 py-4" style="background: var(--light-grey)">
                    <p class="font-bold mb-1" style="font-size: 24px; color: var(--default-titles)">1000+</p>
                    <p class="captions" style="color: var(--default-text)">Patients bénéficiaires</p>
                </div>
            </div>
        </section>

        <!-- Partners strip (gradient) -->
        <section
            style="
                background: linear-gradient(90deg, var(--color-default-blue-59), var(--color-default-green));
                padding: 48px 0;
                position: relative;
                z-index: 1;
                margin-top: -2px;
            "
        >
            <div class="max-w-6xl mx-auto px-8">
                <p
                    class="text-center font-semibold mb-8"
                    style="color: var(--default-titles)"
                >
                    Nos partenaires
                </p>
                <div class="flex items-center justify-center gap-12 flex-wrap">
                    <span
                        class="font-bold text-lg"
                        style="color: var(--default-titles); opacity: 0.65"
                        >ROLEX</span
                    >
                    <span
                        class="font-bold text-lg"
                        style="color: var(--default-titles); opacity: 0.65"
                        >BCGE</span
                    >
                    <span
                        class="font-bold text-lg"
                        style="color: var(--default-titles); opacity: 0.65"
                        >Nestlé</span
                    >
                    <span
                        class="font-bold text-lg"
                        style="color: var(--default-titles); opacity: 0.65"
                        >logitech</span
                    >
                    <span
                        class="font-bold text-lg"
                        style="color: var(--default-titles); opacity: 0.65"
                        >ROLEX</span
                    >
                    <span
                        class="font-bold text-lg"
                        style="color: var(--default-titles); opacity: 0.65"
                        >BCGE</span
                    >
                    <span
                        class="font-bold text-lg"
                        style="color: var(--default-titles); opacity: 0.65"
                        >Nestlé</span
                    >
                    <span
                        class="font-bold text-lg"
                        style="color: var(--default-titles); opacity: 0.65"
                        >logitech</span
                    >
                </div>
            </div>
        </section>

        <!-- 3 steps -->
        <section style="background: var(--light-grey); padding: 72px 0; position: relative; z-index: 1; margin-top: -2px">
            <div class="max-w-6xl mx-auto px-8">
                <h2
                    class="text-center font-bold mb-12"
                    style="font-size: 32px; color: var(--default-titles)"
                >
                    La démarche en trois étapes
                </h2>
                <div class="grid grid-cols-3 gap-8">
                    <div class="bg-white rounded-2xl p-8">
                        <div
                            class="w-9 h-9 rounded-full flex items-center justify-center mb-5 font-bold"
                            style="
                                background: #fde8ef;
                                color: var(--color-default-red);
                                "
                        >
                            1
                        </div>
                        <h3
                            class="font-bold mb-3"
                            style="font-size: 24px; color: var(--default-titles)"
                        >
                            Prise de contact
                        </h3>
                        <p
                            style="
                                color: var(--default-text);
                                line-height: 1.6;
                            "
                        >
                            Notre équipe vous accompagne pour planifier votre
                            projet selon vos besoins.
                        </p>
                    </div>
                    <div class="bg-white rounded-2xl p-8">
                        <div
                            class="w-9 h-9 rounded-full flex items-center justify-center mb-5 font-bold"
                            style="
                                background: #fde8ef;
                                color: var(--color-default-red);
                                "
                        >
                            2
                        </div>
                        <h3
                            class="font-bold mb-3"
                            style="font-size: 24px; color: var(--default-titles)"
                        >
                            Organisation de la collecte
                        </h3>
                        <p
                            style="
                                color: var(--default-text);
                                line-height: 1.6;
                            "
                        >
                            Nous planifions ensemble la collecte dans vos locaux
                            et toute sécurité.
                        </p>
                    </div>
                    <div class="bg-white rounded-2xl p-8">
                        <div
                            class="w-9 h-9 rounded-full flex items-center justify-center mb-5 font-bold"
                            style="
                                background: #fde8ef;
                                color: var(--color-default-red);
                                "
                        >
                            3
                        </div>
                        <h3
                            class="font-bold mb-3"
                            style="font-size: 24px; color: var(--default-titles)"
                        >
                            Label & Impact
                        </h3>
                        <p
                            style="
                                color: var(--default-text);
                                line-height: 1.6;
                            "
                        >
                            Nous valorisons ensemble la collecte dans vos locaux
                            et toute sécurité.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Label CTS -->
        <section id="label" class="bg-white" style="padding: 72px 0; position: relative; z-index: 1; margin-top: -2px">
            <div
                class="max-w-6xl mx-auto px-8 grid grid-cols-2 gap-16 items-center"
            >
                <div>
                    <h2
                        class="font-bold mb-4"
                        style="font-size: 32px; color: var(--default-titles)"
                    >
                        Label CTS
                    </h2>
                    <p
                        class="mb-8 max-w-md"
                        style="
                            color: var(--default-text);
                            line-height: 1.7;
                        "
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
        <section id="trophee" style="background: var(--light-grey); padding: 72px 0; position: relative; z-index: 1; margin-top: -2px">
            <div
                class="max-w-6xl mx-auto px-8 grid grid-cols-2 gap-16 items-center"
            >
                <div
                    class="relative rounded-2xl overflow-hidden"
                    style="height: 320px; background: var(--default-titles)"
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
                        class="font-bold mb-4"
                        style="font-size: 32px; color: var(--default-titles)"
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

        <!-- Moins de 500 salariés -->
        <section id="petites-entreprises" class="bg-white" style="padding: 72px 0; position: relative; z-index: 1; margin-top: -2px">
            <div class="max-w-6xl mx-auto px-8 grid grid-cols-2 gap-16 items-center">
                <div>
                    <p
                        class="captions uppercase mb-3"
                        style="letter-spacing: 2px; color: var(--color-default-red)"
                    >
                        Toutes les entreprises peuvent agir
                    </p>
                    <h2
                        class="font-bold mb-4"
                        style="font-size: 32px; color: var(--default-titles); line-height: 1.2"
                    >
                        Votre entreprise compte<br />moins de 500 salariés ?
                    </h2>
                    <p
                        class="mb-6 max-w-md"
                        style="color: var(--default-text); line-height: 1.7"
                    >
                        Découvrez nos solutions adaptées pour organiser le don du sang, même sans espace dédié dans vos locaux.
                    </p>
                    <ul class="mb-8" style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 16px">
                        <li style="display: flex; align-items: flex-start; gap: 12px">
                            <span class="material-symbols-outlined" style="color: var(--color-default-red); font-size: 22px; flex-shrink: 0; margin-top: 2px">local_hospital</span>
                            <div>
                                <p class="font-semibold" style="color: var(--default-titles); margin: 0 0 4px">Aux HUG</p>
                                <p style="font-size: 14px; color: var(--default-text); margin: 0; line-height: 1.6">Vos collaborateurs se rendent directement au Centre de Transfusion Sanguine des HUG. Simple, encadré et sans organisation logistique.</p>
                            </div>
                        </li>
                        <li style="display: flex; align-items: flex-start; gap: 12px">
                            <span class="material-symbols-outlined" style="color: var(--color-default-red); font-size: 22px; flex-shrink: 0; margin-top: 2px">location_city</span>
                            <div>
                                <p class="font-semibold" style="color: var(--default-titles); margin: 0 0 4px">Dans votre commune</p>
                                <p style="font-size: 14px; color: var(--default-text); margin: 0; line-height: 1.6">Rejoignez une collecte mobile organisée dans votre région. Nous vous informons des prochaines dates à proximité.</p>
                            </div>
                        </li>
                    </ul>
                    <a
                        href="#contact"
                        class="inline-block font-semibold px-6 py-3 rounded-full transition hover:opacity-80"
                        style="border: 2px solid var(--color-default-red); color: var(--color-default-red); font-size: 15px; background: transparent"
                    >
                        Découvrir les aspects réglementaires
                    </a>
                </div>
                <div
                    class="relative rounded-2xl overflow-hidden"
                    style="height: 380px; background: var(--light-grey)"
                >
                    <img
                        :src="'/images/thumbnail_mouvement.webp'"
                        alt=""
                        class="absolute inset-0 w-full h-full object-cover"
                    />
                </div>
            </div>
        </section>

        <!-- Témoignages -->
        <section id="temoignages" class="bg-white" style="padding: 72px 0; position: relative; z-index: 1; margin-top: -2px">
            <div
                class="max-w-6xl mx-auto px-8 grid grid-cols-2 gap-16 items-center"
            >
                <div>
                    <h2
                        class="font-bold mb-8"
                        style="font-size: 32px; color: var(--default-titles)"
                    >
                        Témoignages
                    </h2>
                    <blockquote
                        class="mb-4"
                        style="
                            color: var(--default-text);
                            line-height: 1.7;
                            font-style: italic;
                        "
                    >
                        « Mettre en place la campagne a été bien plus simple que
                        ce qu'on imaginait. En quelques clics, nos employés
                        avaient accès à toutes les informations et pouvaient
                        s'inscrire directement. C'est le genre d'initiative qui
                        renforce vraiment la cohésion d'équipe tout en ayant un
                        impact concret. On recommande sans hésiter. »
                    </blockquote>
                    <p
                        class="font-semibold"
                        style="font-size: 14px; color: var(--default-titles)"
                    >
                        Sophia M., Responsable RH, groupe industriel de 1 200
                        collaborateurs
                    </p>
                </div>
                <div
                    class="relative rounded-2xl overflow-hidden"
                    style="height: 340px; background: var(--light-grey)"
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
        <section id="faq" style="background: var(--light-grey); padding: 72px 0; position: relative; z-index: 1; margin-top: -2px">
            <div class="max-w-3xl mx-auto px-8">
                <h2
                    class="text-center font-bold mb-10"
                    style="font-size: 32px; color: var(--default-titles)"
                >
                    Des questions ?
                </h2>
                <div class="space-y-3 mb-10">
                    <div
                        v-for="(item, i) in faqItems"
                        :key="i"
                        class="bg-white rounded-xl overflow-hidden"
                    >
                        <button
                            @click="toggleFaq(i)"
                            class="w-full flex items-center justify-between px-6 py-4 text-left font-medium transition hover:bg-[#f9fafa]"
                            style="color: var(--default-titles)"
                        >
                            {{ item.q }}
                            <span
                                class="ml-4 font-bold text-xl"
                                style="color: var(--color-default-red)"
                                >{{ openFaq === i ? "−" : "+" }}</span
                            >
                        </button>
                        <div
                            v-if="openFaq === i"
                            class="px-6 pb-4"
                            style="
                                color: var(--default-text);
                                line-height: 1.6;
                            "
                        >
                            {{ item.a }}
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
            style="
                background: linear-gradient(90deg, var(--color-default-blue-59), var(--color-default-green));
                padding: 72px 0;
                position: relative;
                z-index: 1;
                margin-top: -2px;
            "
        >
            <div class="max-w-2xl mx-auto px-8 text-center">
                <h2
                    class="font-bold mb-4"
                    style="font-size: 32px; color: var(--default-titles)"
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

        <!-- Footer -->
        <footer id="contact" style="background: var(--default-titles); padding: 72px 0 40px">
            <div class="max-w-6xl mx-auto px-8">
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
                            style="font-size: 24px"
                        >
                            Pages
                        </p>
                        <ul class="space-y-3">
                            <li>
                                <li><RouterLink to="/label" class="text-white hover:opacity-70 transition" style="text-decoration:none;">Label CTS</RouterLink></li>
                            </li>
                            <li>
                                <a
                                    href="#trophee"
                                    class="text-white hover:opacity-70 transition"
                                    style="font-size: 16px"
                                    >Trophée de la générosité</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#temoignages"
                                    class="text-white hover:opacity-70 transition"
                                    style="font-size: 16px"
                                    >Témoignages</a
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
                                    style="text-decoration: none"
                                    >FAQ</RouterLink
                                >
                            </li>
                            <li>
                                <RouterLink
                                    to="/contact"
                                    class="text-white hover:opacity-70 transition"
                                    style="text-decoration: none"
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
                                    style="font-size: 16px"
                                    >Politique de confidentialité</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="text-white hover:opacity-70 transition"
                                    style="font-size: 16px"
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
