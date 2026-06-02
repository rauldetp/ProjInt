<script setup>
import { ref, onMounted } from "vue";

const bannerVisible = ref(true);
const openFaq = ref(null);

const faqItems = [
    { q: "Quels sont les critères pour obtenir le label CTS ?" },
    { q: "Comment fonctionne la sélection pour le trophée annuel ?" },
    { q: "Combien de temps faut-il pour mettre en place une campagne ?" },
    { q: "Qui organise la collecte dans nos locaux ?" },
    { q: "Y a-t-il un coût pour l'entreprise ?" },
    { q: "Les données de nos employés sont-elles protégées ?" },
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
        <!-- Sticky header = banner + navbar -->
        <div class="sticky top-0 z-50">
            <!-- Announcement banner (gradient) -->
            <div
                v-if="bannerVisible"
                class="flex items-center justify-between px-8 py-2 text-xs"
                style="
                    background: linear-gradient(90deg, #65c6c1, #93cfa9);
                    color: #2c4140;
                "
            >
                <span>
                    Ce site est un projet étudiant réalisé dans un cadre
                    académique. Il ne représente pas une organisation
                    officielle. Retrouvez le site officiel à
                    <a href="#" class="underline font-semibold">cette adresse</a
                    >.
                </span>
                <button
                    @click="bannerVisible = false"
                    class="ml-6 font-bold text-sm leading-none hover:opacity-70"
                >
                    ✕
                </button>
            </div>

            <!-- Main navbar -->
            <header
                class="bg-white border-b border-[#f2f4f3]"
                style="height: 76px"
            >
                <div
                    class="max-w-7xl mx-auto px-8 h-full flex items-center justify-between"
                >
                    <!-- Logo -->
                    <div class="flex items-center gap-2">
                        <span
                            class="font-extrabold text-xl tracking-tight"
                            style="color: #2c4140"
                            >HUG</span
                        >
                        <div
                            class="w-px h-5 mx-2"
                            style="background: #2c4140; opacity: 0.3"
                        ></div>
                        <span
                            class="text-base font-semibold"
                            style="color: #e60f48"
                            >Don du sang</span
                        >
                    </div>
                    <!-- Nav links -->
                    <nav class="hidden md:flex items-center gap-7 text-base font-medium">
                        <RouterLink to="/label" class="hover:opacity-70 transition" style="color:#2c4140; text-decoration:none;">Label CTS</RouterLink>
                        <RouterLink to="/trophee" class="hover:opacity-70 transition" style="color:#2c4140; text-decoration:none;">Trophée de la générosité</RouterLink>
                        <RouterLink to="/coordinateur" class="hover:opacity-70 transition" style="color:#2c4140; text-decoration:none;">Coin entreprise</RouterLink>
                        <RouterLink to="/contact" style="color: #2c4140; text-decoration: none" class="hover:opacity-60 transition">Contact</RouterLink>                    </nav>
                    <!-- CTA button -->
                    <RouterLink
                        to="/login"
                        class="text-base font-semibold px-6 py-2 rounded-full border-2 transition hover:opacity-80"
                        style="color: #e60f48; border-color: #e60f48; text-decoration: none"
                    >
                        Participer
                    </RouterLink>                </div>
            </header>
        </div>

        <!-- Hero -->
        <section
            class="relative flex items-end pb-16 overflow-hidden"
            style="height: 512px; background: #2c4140"
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
            <div class="relative max-w-7xl mx-auto px-8">
                <h1
                    class="font-bold text-white leading-tight max-w-xl mb-6"
                    style="font-size: 48px"
                >
                    Engagez votre entreprise.<br />Obtenez le label CTS.
                </h1>
                <RouterLink
                    to="/label"
                    class="inline-block bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold px-6 py-3 rounded-full transition"
                    style="background: #e60f48"
                >
                    En savoir plus
                </RouterLink>
            </div>
        </section>

        <!-- Stats -->
        <section class="bg-white" style="padding: 40px 0">
            <div
                class="max-w-5xl mx-auto px-8 grid grid-cols-3 gap-8 text-center"
            >
                <div>
                    <p
                        class="font-bold"
                        style="font-size: 24px; color: #2c4140"
                    >
                        100+
                    </p>
                    <p
                        style="
                            font-size: 12px;
                            color: #2c4140;
                            font-weight: 600;
                            margin-top: 4px;
                        "
                    >
                        Entreprises partenaires
                    </p>
                </div>
                <div>
                    <p
                        class="font-bold"
                        style="font-size: 24px; color: #2c4140"
                    >
                        653
                    </p>
                    <p
                        style="
                            font-size: 12px;
                            color: #2c4140;
                            font-weight: 600;
                            margin-top: 4px;
                        "
                    >
                        Collectes organisées
                    </p>
                </div>
                <div>
                    <p
                        class="font-bold"
                        style="font-size: 24px; color: #2c4140"
                    >
                        1000+
                    </p>
                    <p
                        style="
                            font-size: 12px;
                            color: #2c4140;
                            font-weight: 600;
                            margin-top: 4px;
                        "
                    >
                        Patients bénéficiaires
                    </p>
                </div>
            </div>
        </section>

        <!-- Partners strip (gradient) -->
        <section
            style="
                background: linear-gradient(90deg, #65c6c1, #93cfa9);
                padding: 48px 0;
            "
        >
            <div class="max-w-6xl mx-auto px-8">
                <p
                    class="text-center font-semibold mb-8"
                    style="color: #2c4140; font-size: 16px"
                >
                    Nos partenaires
                </p>
                <div class="flex items-center justify-center gap-12 flex-wrap">
                    <span
                        class="font-bold text-lg"
                        style="color: #2c4140; opacity: 0.65"
                        >ROLEX</span
                    >
                    <span
                        class="font-bold text-lg"
                        style="color: #2c4140; opacity: 0.65"
                        >BCGE</span
                    >
                    <span
                        class="font-bold text-lg"
                        style="color: #2c4140; opacity: 0.65"
                        >Nestlé</span
                    >
                    <span
                        class="font-bold text-lg"
                        style="color: #2c4140; opacity: 0.65"
                        >logitech</span
                    >
                    <span
                        class="font-bold text-lg"
                        style="color: #2c4140; opacity: 0.65"
                        >ROLEX</span
                    >
                    <span
                        class="font-bold text-lg"
                        style="color: #2c4140; opacity: 0.65"
                        >BCGE</span
                    >
                    <span
                        class="font-bold text-lg"
                        style="color: #2c4140; opacity: 0.65"
                        >Nestlé</span
                    >
                    <span
                        class="font-bold text-lg"
                        style="color: #2c4140; opacity: 0.65"
                        >logitech</span
                    >
                </div>
            </div>
        </section>

        <!-- 3 steps -->
        <section style="background: #f2f4f3; padding: 72px 0">
            <div class="max-w-6xl mx-auto px-8">
                <h2
                    class="text-center font-bold mb-12"
                    style="font-size: 32px; color: #2c4140"
                >
                    La démarche en trois étapes
                </h2>
                <div class="grid grid-cols-3 gap-8">
                    <div class="bg-white rounded-2xl p-8">
                        <div
                            class="w-9 h-9 rounded-full flex items-center justify-center mb-5 font-bold"
                            style="
                                background: #fde8ef;
                                color: #e60f48;
                                font-size: 16px;
                            "
                        >
                            1
                        </div>
                        <h3
                            class="font-bold mb-3"
                            style="font-size: 24px; color: #2c4140"
                        >
                            Prise de contact
                        </h3>
                        <p
                            style="
                                font-size: 16px;
                                color: #497371;
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
                                color: #e60f48;
                                font-size: 16px;
                            "
                        >
                            2
                        </div>
                        <h3
                            class="font-bold mb-3"
                            style="font-size: 24px; color: #2c4140"
                        >
                            Organisation de la collecte
                        </h3>
                        <p
                            style="
                                font-size: 16px;
                                color: #497371;
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
                                color: #e60f48;
                                font-size: 16px;
                            "
                        >
                            3
                        </div>
                        <h3
                            class="font-bold mb-3"
                            style="font-size: 24px; color: #2c4140"
                        >
                            Label & Impact
                        </h3>
                        <p
                            style="
                                font-size: 16px;
                                color: #497371;
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
        <section id="label" class="bg-white" style="padding: 72px 0">
            <div
                class="max-w-6xl mx-auto px-8 grid grid-cols-2 gap-16 items-center"
            >
                <div>
                    <h2
                        class="font-bold mb-4"
                        style="font-size: 32px; color: #2c4140"
                    >
                        Label CTS
                    </h2>
                    <p
                        class="mb-8 max-w-md"
                        style="
                            font-size: 16px;
                            color: #497371;
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
                        class="inline-block text-white font-semibold px-6 py-3 rounded-full transition hover:opacity-80"
                        style="background: #e60f48; font-size: 16px"
                    >
                        En savoir plus
                    </a>
                </div>
                <div
                    class="flex flex-col items-center justify-center gap-4 rounded-2xl border p-12"
                    style="border-color: #f2f4f3"
                >
                    <div
                        class="rounded-full border-4 flex items-center justify-center"
                        style="width: 80px; height: 80px; border-color: #e60f48"
                    >
                        <span style="color: #e60f48; font-size: 32px">♥</span>
                    </div>
                    <div class="text-center">
                        <p
                            class="font-black"
                            style="
                                font-size: 24px;
                                color: #2c4140;
                                letter-spacing: -0.5px;
                            "
                        >
                            CTS
                        </p>
                        <p
                            class="font-bold uppercase tracking-widest"
                            style="font-size: 11px; color: #497371"
                        >
                            Collecte solidaire
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Trophy -->
        <section id="trophee" style="background: #f2f4f3; padding: 72px 0">
            <div
                class="max-w-6xl mx-auto px-8 grid grid-cols-2 gap-16 items-center"
            >
                <div
                    class="relative rounded-2xl overflow-hidden"
                    style="height: 320px; background: #2c4140"
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
                        style="font-size: 32px; color: #2c4140"
                    >
                        Trophée de la générosité
                    </h2>
                    <p
                        class="mb-8 max-w-md"
                        style="
                            font-size: 16px;
                            color: #497371;
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
                        class="inline-block text-white font-semibold px-6 py-3 rounded-full transition hover:opacity-80"
                        style="background: #e60f48; font-size: 16px"
                    >
                        Découvrir le trophée
                    </a>
                </div>
            </div>
        </section>

        <!-- Témoignages -->
        <section id="temoignages" class="bg-white" style="padding: 72px 0">
            <div
                class="max-w-6xl mx-auto px-8 grid grid-cols-2 gap-16 items-center"
            >
                <div>
                    <h2
                        class="font-bold mb-8"
                        style="font-size: 32px; color: #2c4140"
                    >
                        Témoignages
                    </h2>
                    <blockquote
                        class="mb-4"
                        style="
                            font-size: 16px;
                            color: #497371;
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
                        style="font-size: 14px; color: #2c4140"
                    >
                        Sophia M., Responsable RH, groupe industriel de 1 200
                        collaborateurs
                    </p>
                </div>
                <div
                    class="relative rounded-2xl overflow-hidden"
                    style="height: 340px; background: #f2f4f3"
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
        <section id="faq" style="background: #f2f4f3; padding: 72px 0">
            <div class="max-w-3xl mx-auto px-8">
                <h2
                    class="text-center font-bold mb-10"
                    style="font-size: 32px; color: #2c4140"
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
                            style="font-size: 16px; color: #2c4140"
                        >
                            {{ item.q }}
                            <span
                                class="ml-4 font-bold text-xl"
                                style="color: #e60f48"
                                >{{ openFaq === i ? "−" : "+" }}</span
                            >
                        </button>
                        <div
                            v-if="openFaq === i"
                            class="px-6 pb-4"
                            style="
                                font-size: 16px;
                                color: #497371;
                                line-height: 1.6;
                            "
                        >
                            La réponse à cette question sera disponible
                            prochainement.
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <a
                        href="#contact"
                        class="inline-block text-white font-semibold px-6 py-3 rounded-full transition hover:opacity-80"
                        style="background: #e60f48; font-size: 16px"
                    >
                        Vers la FAQ complète
                    </a>
                </div>
            </div>
        </section>

        <!-- CTA bottom (gradient) -->
        <section
            style="
                background: linear-gradient(90deg, #65c6c1, #93cfa9);
                padding: 72px 0;
            "
        >
            <div class="max-w-2xl mx-auto px-8 text-center">
                <h2
                    class="font-bold mb-4"
                    style="font-size: 32px; color: #2c4140"
                >
                    Rejoignez les entreprises partenaires
                </h2>
                <p
                    class="mb-8"
                    style="
                        font-size: 16px;
                        color: #2c4140;
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
            class="inline-flex items-center gap-2 text-white font-semibold px-7 py-3 rounded-full transition hover:opacity-80"
            style="background: #e60f48; font-size: 16px; text-decoration: none"
        >
            Découvrez nos entreprises partenaires →
        </RouterLink>            </div>
        </section>

        <!-- Footer -->
        <footer id="contact" style="background: #2c4140; padding: 72px 0 40px">
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
                                <li><RouterLink to="/label" class="text-white hover:opacity-70 transition" style="font-size:16px; text-decoration:none;">Label CTS</RouterLink></li>
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
                                <a
                                    href="#faq"
                                    class="text-white hover:opacity-70 transition"
                                    style="font-size: 16px"
                                    >FAQ</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="text-white hover:opacity-70 transition"
                                    style="font-size: 16px"
                                    >Contact</a
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
