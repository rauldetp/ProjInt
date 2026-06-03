<script setup>
import { ref, onMounted } from "vue";

const openFaq = ref(null);

function toggle(id) {
    openFaq.value = openFaq.value === id ? null : id;
}

const categories = [
    {
        id: "don",
        title: "Don du sang — Questions générales",
        items: [
            {
                q: "Qui peut donner du sang ?",
                a: "Toute personne en bonne santé, âgée de 18 à 75 ans, pesant au moins 50 kg peut donner du sang. Les personnes de plus de 65 ans peuvent donner sous réserve de l'accord du médecin CTS. Une carte d'identité ou un passeport est obligatoire pour le premier don.",
            },
            {
                q: "Quelles sont les contre-indications temporaires les plus fréquentes ?",
                a: "Les contre-indications temporaires incluent notamment : une prise d'antibiotiques (attendre 7 jours après la fin du traitement), une grossesse ou un accouchement récent (attendre 6 mois), un tatouage ou piercing récent (attendre 4 mois), un séjour dans certains pays à risque (délai variable), ou une grippe ou rhume récent (attendre la guérison complète).",
            },
            {
                q: "Combien de temps dure un don de sang total ?",
                a: "Le don de sang total dure environ 10 minutes. Avec l'accueil, le questionnaire médical, la collation post-don et le repos, comptez 45 à 60 minutes au total. Un don de plaquettes ou de plasma peut prendre jusqu'à 90 minutes.",
            },
            {
                q: "À quelle fréquence peut-on donner du sang ?",
                a: "Les hommes peuvent donner jusqu'à 4 fois par an, avec un délai minimum de 8 semaines entre deux dons. Les femmes peuvent donner jusqu'à 3 fois par an. Pour les dons de plaquettes, la fréquence peut être plus élevée (jusqu'à 24 fois par an).",
            },
            {
                q: "Le don de sang est-il rémunéré en Suisse ?",
                a: "Non. Le don de sang en Suisse est entièrement bénévole et volontaire. Aucune rémunération financière n'est versée. Les donneurs reçoivent en revanche une collation après le don.",
            },
            {
                q: "Quels types de dons sont possibles ?",
                a: "Le CTS des HUG collecte plusieurs types de dons : le sang total (le plus courant), les plaquettes (aphérèse), le plasma, et les granulocytes. Le médecin CTS oriente chaque donneur vers le don le plus adapté à son profil.",
            },
            {
                q: "Le don de sang est-il dangereux pour la santé ?",
                a: "Non. Tout le matériel utilisé est stérile et à usage unique. Il est impossible de contracter une maladie en donnant son sang. Après le don, le corps reconstitue le volume sanguin en quelques heures, et les globules rouges en 4 à 6 semaines.",
            },
            {
                q: "Puis-je donner si je prends des médicaments ?",
                a: "Cela dépend du médicament. Les anticoagulants, certains antibiotiques ou traitements dermatologiques sont des contre-indications temporaires ou permanentes. Le questionnaire médical pré-don et le médecin CTS évaluent chaque cas individuellement.",
            },
        ],
    },
    {
        id: "entreprise",
        title: "Don du sang en entreprise",
        items: [
            {
                q: "Comment organiser une collecte de sang dans mon entreprise ?",
                a: "Il suffit de créer un compte sur cette plateforme et de contacter l'équipe CTS des HUG. Un coordinateur interne est désigné dans votre entreprise. L'équipe mobile CTS se charge de tout le reste : logistique, matériel, personnel soignant. Vous n'avez qu'à communiquer en interne et gérer les inscriptions.",
            },
            {
                q: "Combien de temps faut-il pour mettre en place une collecte ?",
                a: "En général, 4 à 6 semaines entre la première prise de contact et le jour de la collecte. Ce délai permet de planifier la date, organiser l'espace, préparer la communication interne et ouvrir les créneaux d'inscription.",
            },
            {
                q: "Quelle surface faut-il prévoir pour accueillir une collecte ?",
                a: "Un espace d'environ 50 m² minimum est nécessaire, avec une bonne aération et une accessibilité pour les donneurs. Idéalement une salle de conférence ou un espace polyvalent avec tables et chaises. L'équipe CTS apporte tout son équipement.",
            },
            {
                q: "Combien de donneurs peut-on accueillir lors d'une collecte ?",
                a: "En règle générale, l'équipe CTS peut accueillir entre 20 et 50 donneurs par jour selon la taille du dispositif mis en place. Un créneau de 30 minutes par donneur est à prévoir, avec une collecte possible sur une ou deux journées.",
            },
            {
                q: "Y a-t-il un coût pour l'entreprise ?",
                a: "Non. La collecte est entièrement organisée et financée par les HUG. L'entreprise met à disposition l'espace et libère du temps à ses collaborateurs. Il n'y a aucun frais de participation.",
            },
            {
                q: "Qui s'occupe de la communication interne ?",
                a: "Le coordinateur interne de l'entreprise — généralement un responsable RH ou RSE — gère la communication auprès des collaborateurs. Le CTS des HUG met à disposition des modèles de communication (emails, affiches, etc.) prêts à l'emploi.",
            },
            {
                q: "Les données de nos employés sont-elles protégées ?",
                a: "Oui. Seules les données nécessaires à la gestion des inscriptions (nom, prénom, créneau) sont utilisées. Aucune donnée médicale n'est transmise à l'employeur. Le traitement des données est conforme au RGPD et à la LPD suisse.",
            },
            {
                q: "Quels sont les critères pour obtenir le label CTS ?",
                a: "Le label CTS est attribué automatiquement à toute entreprise ayant organisé au moins une collecte dans l'année. Il certifie votre engagement citoyen pour le don du sang et est valable 1 an, renouvelable chaque année si une nouvelle collecte est organisée.",
            },
            {
                q: "Comment fonctionne le Trophée de la générosité ?",
                a: "Le Trophée est attribué une fois par an, en décembre, par un jury HUG. Il récompense les entreprises les plus engagées selon trois critères : le taux de participation (donneurs / effectif), la régularité de l'engagement sur plusieurs années, et la qualité de la mobilisation interne. Les entreprises souhaitant candidater doivent soumettre un dossier avant le 30 novembre.",
            },
            {
                q: "Est-ce qu'une PME peut participer au programme ?",
                a: "Absolument. Le programme est ouvert à toutes les entreprises genevoises, quelle que soit leur taille. Une PME de 20 collaborateurs peut obtenir le label CTS et même candidater au Trophée. Le taux de participation est calculé au prorata des effectifs, ce qui valorise l'engagement de toutes les structures.",
            },
        ],
    },
];

onMounted(() => {
    document.title = "FAQ — HUG Don du sang";
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
                        style="color: #2c4140; text-decoration: none"
                        class="hover:opacity-60 transition"
                        >Trophée de la générosité</RouterLink
                    >
                    <RouterLink
                        to="/coordinateur"
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
        <section style="background: #2c4140; padding: 64px 0 52px">
            <div class="max-w-4xl mx-auto px-8">
                <p
                    class="font-semibold mb-3 uppercase tracking-widest"
                    style="font-size: 13px; color: #93cfa9"
                >
                    Aide & support
                </p>
                <h1
                    class="font-bold text-white mb-4"
                    style="font-size: 48px; line-height: 1.2"
                >
                    Foire aux questions
                </h1>
                <p
                    style="
                        font-size: 17px;
                        color: rgba(255, 255, 255, 0.7);
                        line-height: 1.6;
                        max-width: 520px;
                    "
                >
                    Tout ce que vous devez savoir sur le don du sang et sur
                    l'organisation de collectes en entreprise.
                </p>
            </div>
        </section>

        <!-- FAQ contenu -->
        <section class="py-16" style="background: #f2f4f3">
            <div class="max-w-3xl mx-auto px-8 flex flex-col gap-14">
                <div v-for="cat in categories" :key="cat.id">
                    <!-- Titre catégorie -->
                    <div
                        class="mb-6"
                        style="
                            border-top: 2px solid #e60f48;
                            padding-top: 1.25rem;
                        "
                    >
                        <h2
                            class="font-bold"
                            style="font-size: 22px; color: #2c4140"
                        >
                            {{ cat.title }}
                        </h2>
                    </div>

                    <!-- Items -->
                    <div class="space-y-3">
                        <div
                            v-for="(item, i) in cat.items"
                            :key="cat.id + i"
                            class="bg-white rounded-xl overflow-hidden"
                        >
                            <button
                                @click="toggle(cat.id + i)"
                                class="w-full flex items-center justify-between px-6 py-4 text-left font-medium transition hover:bg-[#f9fafa]"
                                style="font-size: 16px; color: #2c4140"
                            >
                                {{ item.q }}
                                <span
                                    class="ml-4 font-bold text-xl flex-shrink-0"
                                    style="color: #e60f48"
                                    >{{ openFaq === cat.id + i ? "−" : "+" }}</span
                                >
                            </button>
                            <div
                                v-if="openFaq === cat.id + i"
                                class="px-6 pb-5"
                                style="
                                    font-size: 15px;
                                    color: #497371;
                                    line-height: 1.75;
                                "
                            >
                                {{ item.a }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA contact -->
        <section
            class="py-16 text-center"
            style="background: linear-gradient(135deg, #65c6c1, #93cfa9)"
        >
            <div class="max-w-xl mx-auto px-8">
                <h2
                    class="font-bold mb-3"
                    style="font-size: 28px; color: #2c4140"
                >
                    Vous n'avez pas trouvé votre réponse ?
                </h2>
                <p
                    class="mb-8"
                    style="font-size: 16px; color: #2c4140; opacity: 0.8"
                >
                    Notre équipe répond à toutes vos questions.
                </p>
                <RouterLink
                    to="/contact"
                    class="inline-block text-white font-semibold rounded-full px-7 py-3 transition hover:opacity-80"
                    style="
                        background: #2c4140;
                        font-size: 16px;
                        text-decoration: none;
                    "
                >
                    Nous contacter →
                </RouterLink>
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
                                    style="font-size: 16px; text-decoration: none"
                                    >Label CTS</RouterLink
                                >
                            </li>
                            <li>
                                <RouterLink
                                    to="/trophee"
                                    class="text-white hover:opacity-70 transition"
                                    style="font-size: 16px; text-decoration: none"
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
                                    style="font-size: 16px; text-decoration: none"
                                    >FAQ</RouterLink
                                >
                            </li>
                            <li>
                                <RouterLink
                                    to="/contact"
                                    class="text-white hover:opacity-70 transition"
                                    style="font-size: 16px; text-decoration: none"
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
                                    style="font-size: 16px; text-decoration: none"
                                    >Politique de confidentialité</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="text-white hover:opacity-70 transition"
                                    style="font-size: 16px; text-decoration: none"
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
                        © {{ new Date().getFullYear() }} Hôpitaux Universitaires
                        Genève. Tous droits réservés.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
