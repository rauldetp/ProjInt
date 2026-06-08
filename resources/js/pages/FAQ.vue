<script setup>
import { ref, onMounted } from "vue";
import HugNavbar from "../components/HugNavbar.vue";
const openFaq = ref(null);

function toggle(id) {
    openFaq.value = openFaq.value === id ? null : id;
}

const categories = [
    {
        id: "avant",
        title: "Avant le don",
        insight: "Le don dure généralement moins de 10 minutes.",
        items: [
            {
                q: "Puis-je donner mon sang ?",
                a: "La plupart des personnes en bonne santé peuvent donner leur sang. Certaines situations peuvent cependant empêcher un don temporairement : maladie récente, tatouage récent, voyage, opération, certains médicaments, etc. Pour le savoir rapidement, notre quiz simplifié vous guide avant la prise de rendez-vous.",
            },
            {
                q: "Dois-je manger avant de donner mon sang ?",
                a: "Oui. Il est recommandé de manger normalement et de bien boire avant le don afin d'éviter les malaises ou la fatigue. Ne venez jamais à jeun.",
            },
            {
                q: "Est-ce que donner son sang fait mal ?",
                a: "Le don est généralement peu douloureux. Vous ressentez principalement une petite piqûre au début, comme une prise de sang classique.",
            },
            {
                q: "Combien de temps dure un don ?",
                a: "Le prélèvement dure environ 10 minutes. Avec l'accueil, le questionnaire et le temps de repos, il faut compter environ 45 minutes au total.",
            },
            {
                q: "Puis-je donner mon sang si j'ai un tatouage ou un piercing ?",
                a: "Oui, mais un délai est généralement nécessaire après un tatouage ou un piercing avant de pouvoir donner. Le quiz vous aide à savoir rapidement si vous êtes concerné.",
            },
            {
                q: "Puis-je donner mon sang si je prends des médicaments ?",
                a: "Cela dépend du traitement. Certains médicaments n'empêchent pas le don, d'autres oui temporairement. Le personnel médical vérifiera toujours cela avant le don.",
            },
            {
                q: "Puis-je donner mon sang si je suis malade ?",
                a: "Non. Même un simple refroidissement peut empêcher temporairement un don. Il est important de venir uniquement en bonne santé.",
            },
            {
                q: "Faut-il être à jeun ?",
                a: "Non. Au contraire, il est conseillé de manger et de boire suffisamment avant le don.",
            },
        ],
    },
    {
        id: "pendant",
        title: "Pendant le don",
        insight: "Le personnel médical vous accompagne à chaque étape.",
        items: [
            {
                q: "Que se passe-t-il pendant le don ?",
                a: "Le personnel médical vous accueille, vérifie votre questionnaire, puis procède au prélèvement dans un espace sécurisé et encadré.",
            },
            {
                q: "Combien de sang est prélevé ?",
                a: "Environ 450 ml de sang sont prélevés lors d'un don standard.",
            },
            {
                q: "Le matériel est-il stérile ?",
                a: "Oui. Tout le matériel utilisé est stérile et à usage unique. Il est impossible de contracter une maladie en donnant son sang.",
            },
            {
                q: "Vais-je me sentir faible pendant le don ?",
                a: "La majorité des donneurs se sentent bien pendant le prélèvement. Certaines personnes peuvent ressentir une légère fatigue ou un petit vertige, mais le personnel est présent pour accompagner chaque étape.",
            },
        ],
    },
    {
        id: "apres",
        title: "Après le don",
        insight: "La majorité des donneurs reprennent leur journée normalement après le don.",
        items: [
            {
                q: "Puis-je retourner travailler après le don ?",
                a: "Oui, la majorité des donneurs reprennent leur journée normalement après avoir mangé, bu et pris un petit moment de repos.",
            },
            {
                q: "Puis-je faire du sport après un don ?",
                a: "Il est conseillé d'éviter les efforts physiques intenses le jour même du don.",
            },
            {
                q: "Que se passe-t-il après le prélèvement ?",
                a: "Après le don, une collation est proposée afin de récupérer tranquillement avant de repartir.",
            },
            {
                q: "Mon corps récupère-t-il rapidement ?",
                a: "Oui. Le plasma est remplacé rapidement par le corps. Il est important de bien s'hydrater et de manger normalement après le don.",
            },
        ],
    },
    {
        id: "entreprise",
        title: "Collectes en entreprise",
        insight: "Vous n'êtes pas seul : votre entreprise participe avec vous.",
        items: [
            {
                q: "Comment fonctionne une collecte en entreprise ?",
                a: "Le Centre de Transfusion Sanguine se déplace directement dans l'entreprise afin de permettre aux collaborateurs de donner leur sang sur place.",
            },
            {
                q: "Qui peut participer à une collecte en entreprise ?",
                a: "Tous les collaborateurs répondant aux critères médicaux peuvent participer.",
            },
            {
                q: "Une entreprise doit-elle avoir plus de 1000 employés ?",
                a: "Les grandes entreprises peuvent accueillir une collecte directement dans leurs locaux. Les PME peuvent également participer via des dons de groupe organisés dans un centre de transfusion.",
            },
            {
                q: "Pourquoi organiser une collecte en entreprise ?",
                a: "Cela permet de faciliter le don pour les collaborateurs, de renforcer l'engagement collectif et de participer concrètement à une action utile localement.",
            },
            {
                q: "Une entreprise reçoit-elle une reconnaissance ?",
                a: "Oui. Les entreprises participantes peuvent obtenir un Label CTS et participer au Trophée de la Générosité, récompensant chaque année les entreprises les plus engagées.",
            },
            {
                q: "Y a-t-il un coût pour l'entreprise ?",
                a: "Non. La collecte est entièrement organisée et financée par les HUG. L'entreprise met à disposition l'espace et libère du temps à ses collaborateurs.",
            },
            {
                q: "Les données de nos employés sont-elles protégées ?",
                a: "Oui. Aucune donnée médicale n'est transmise à l'employeur. Le traitement des données est conforme au RGPD et à la LPD suisse.",
            },
        ],
    },
    {
        id: "securite",
        title: "Sécurité & rassurance",
        insight: "Même un premier don peut faire une vraie différence.",
        items: [
            {
                q: "Mon sang est-il testé ?",
                a: "Oui. Chaque don est analysé afin de garantir la sécurité des patients.",
            },
            {
                q: "À quoi sert mon don ?",
                a: "Un seul don peut contribuer à aider plusieurs patients ayant besoin de transfusions lors d'opérations, de traitements ou d'urgences médicales.",
            },
            {
                q: "Puis-je attraper une maladie en donnant mon sang ?",
                a: "Non. Le matériel utilisé est entièrement stérile et à usage unique.",
            },
            {
                q: "Comment fonctionne le Trophée de la générosité ?",
                a: "Le Trophée est attribué une fois par an par un jury HUG. Il récompense les entreprises les plus engagées selon leur taux de participation, leur régularité et la qualité de leur mobilisation interne.",
            },
            {
                q: "Quels sont les critères pour obtenir le Label CTS ?",
                a: "Le Label CTS est attribué à toute entreprise ayant organisé au moins une collecte dans l'année. Il certifie votre engagement citoyen et est valable 1 an, renouvelable.",
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
        <HugNavbar />

        <!-- Hero -->
        <section style="background: linear-gradient(135deg, var(--color-default-blue-59), var(--color-default-green)); padding: 64px 0 52px">
            <div class="max-w-4xl mx-auto px-8">
                <p
                    class="font-semibold mb-3 uppercase tracking-widest"
                    style="font-size: 13px; color: var(--default-titles); opacity: 0.65"
                >
                    Aide & support
                </p>
                <h1
                    class="font-bold mb-4"
                    style="font-size: 48px; line-height: 1.2; color: var(--default-titles)"
                >
                    Foire aux questions
                </h1>
                <p
                    style="
                        font-size: 17px;
                        color: var(--default-titles);
                        opacity: 0.75;
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
        <section class="py-16" style="background: var(--light-grey)">
            <div class="max-w-3xl mx-auto px-8 flex flex-col gap-14">
                <div v-for="cat in categories" :key="cat.id">
                    <!-- Titre catégorie -->
                    <div
                        class="mb-6"
                        style="
                            border-top: 2px solid var(--color-default-red);
                            padding-top: 1.25rem;
                        "
                    >
                        <h2
                            class="font-bold"
                            style="font-size: 22px; color: var(--default-titles)"
                        >
                            {{ cat.title }}
                        </h2>
                    </div>

                    <!-- Items -->
                    <div class="space-y-3">
                        <div
                            v-for="(item, i) in cat.items"
                            :key="cat.id + i"
                            class="bg-white rounded-xl shadow-sm"
                        >
                            <button class="faq-btn" @click="toggle(cat.id + i)">
                                {{ item.q }}
                                <div class="faq-btn-circle">
                                    <span class="faq-btn-icon material-symbols-outlined">
                                        {{ openFaq === cat.id + i ? 'expand_less' : 'expand_more' }}
                                    </span>
                                </div>
                            </button>
                            <template v-if="openFaq === cat.id + i">
                                <div class="mx-6" style="height: 1px; background: var(--light-grey)"></div>
                                <div class="px-6 py-5" style="color: var(--default-text)">
                                    {{ item.a }}
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA contact -->
        <section
            class="py-16 text-center"
            style="background: linear-gradient(135deg, var(--color-default-blue-59), var(--color-default-green))"
        >
            <div class="max-w-xl mx-auto px-8">
                <h2
                    class="font-bold mb-3"
                    style="font-size: 28px; color: var(--default-titles)"
                >
                    Vous n'avez pas trouvé votre réponse ?
                </h2>
                <p
                    class="mb-8"
                    style="color: var(--default-titles); opacity: 0.8"
                >
                    Notre équipe répond à toutes vos questions.
                </p>
                <RouterLink
                    to="/contact"
                    class="inline-block text-white font-semibold rounded-full px-7 py-3 transition hover:opacity-80"
                    style="
                        background: var(--default-titles);
                        text-decoration: none;
                    "
                >
                    Nous contacter →
                </RouterLink>
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
                            style="font-size: 24px"
                        >
                            Pages
                        </p>
                        <ul class="space-y-3">
                            <li>
                                <RouterLink
                                    to="/label"
                                    class="text-white hover:opacity-70 transition"
                                    style="text-decoration: none"
                                    >Label CTS</RouterLink
                                >
                            </li>
                            <li>
                                <RouterLink
                                    to="/trophee"
                                    class="text-white hover:opacity-70 transition"
                                    style="text-decoration: none"
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
                                    style="text-decoration: none"
                                    >Politique de confidentialité</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="text-white hover:opacity-70 transition"
                                    style="text-decoration: none"
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
                        © {{ new Date().getFullYear() }} Hôpitaux Universitaires
                        Genève. Tous droits réservés.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
