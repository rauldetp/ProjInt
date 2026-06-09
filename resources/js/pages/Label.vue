<script setup>
import { ref, computed, onMounted } from "vue";
import HugNavbar from "../components/HugNavbar.vue";
import Footer from "../components/Footer.vue";

const entreprises = ref([]);
const loading = ref(true);
const showAll = ref(false);

const visibles = computed(() =>
    showAll.value ? entreprises.value : entreprises.value.slice(0, 18),
);

onMounted(async () => {
    document.title = "Label CTS — HUG";
    try {
        const res = await fetch("/api/entreprises");
        if (res.ok) entreprises.value = await res.json();
    } catch {
        // silent fail
    } finally {
        loading.value = false;
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
                :src="'/images/Hero_labelCTS.webp'"
                alt=""
                class="absolute inset-0 w-full h-full object-cover"
                style="opacity: 0.6"
            />
            <div
                class="absolute inset-0"
                style="background: rgba(44, 65, 64, 0.45)"
            ></div>
            <div class="relative max-w-7xl mx-auto px-8 w-full z-10">
                <div class="max-w-4xl">
                    <h1 class="font-bold text-white leading-tight mb-4">
                        Notre Label CTS
                    </h1>
                    <p class="text-white mb-6 max-w-lg">
                        Le label CTS des HUG récompense les entreprises qui
                        s'engagent concrètement pour le don du sang et la
                        solidarité.
                    </p>
                </div>
            </div>
        </section>

        <!-- Ce qu'il représente -->
        <section id="ce-quil-represente" class="bg-light-grey py-20">
            <div
                class="max-w-7xl mx-auto px-8 grid grid-cols-2 gap-16 items-center"
            >
                <div>
                    <h2 class="font-bold mb-5 text-black">
                        Ce qu'il représente
                    </h2>
                    <p>
                        Le Label CTS est une certification honorifique conçue
                        par les Hôpitaux Universitaires de Genève pour valoriser
                        l'engagement citoyen de toutes les organisations
                        partenaires. Contrairement au Trophée de la Générosité
                        qui met en avant un palmarès restreint, ce label RSE
                        certifie l'effort collectif et la responsabilité sociale
                        de chaque entreprise qui organise une collecte de sang
                        et soutient activement la santé publique genevoise.
                    </p>
                </div>
                <div
                    class="aspect-video bg-white flex flex-col items-center justify-center gap-4 rounded-2xl p-12"
                >
                    <img
                        :src="'/images/logo_labelCTS_red.png'"
                        alt="Label CTS"
                    />
                </div>
            </div>
        </section>

        <!-- Les bénéfices -->
        <section
            class="bg-white py-20 border-t"
            style="border-color: var(--light-grey)"
        >
            <div class="max-w-7xl mx-auto px-8">
                <h2 class="font-bold text-center mb-12 text-black">
                    Les bénéfices du label
                </h2>
                <div class="grid grid-cols-3 gap-8">
                    <div class="flex flex-col items-center text-center gap-4">
                        <div
                            class="circle-icon rounded-full bg-light-grey flex items-center justify-center"
                        >
                            <span class="material-symbols-outlined">group</span>
                        </div>
                        <h3 class="font-bold text-black">
                            Rejoignez une communauté d'entreprises
                        </h3>
                        <p>
                            Afficher votre engagement société auprès de vos
                            collaborateurs, partenaires et clients.
                        </p>
                    </div>
                    <div class="flex flex-col items-center text-center gap-4">
                        <div
                            class="circle-icon rounded-full bg-light-grey flex items-center justify-center"
                        >
                            <span class="material-symbols-outlined"
                                >emoji_events</span
                            >
                        </div>
                        <h3 class="font-bold text-black">
                            Affichez votre engagement
                        </h3>
                        <p>
                            Associez votre entreprise à une démarche citoyenne
                            et solidaire reconnue par les HUG.
                        </p>
                    </div>
                    <div class="flex flex-col items-center text-center gap-4">
                        <div
                            class="circle-icon rounded-full bg-light-grey flex items-center justify-center"
                        >
                            <span class="material-symbols-outlined"
                                >favorite</span
                            >
                        </div>
                        <h3 class="font-bold text-black">
                            Un impact réel et mesurable
                        </h3>
                        <p>
                            Votre mobilisation contribue directement à sauver
                            des vies en Suisse.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Quote gradient -->
        <section class="py-20 text-center bg-gradient">
            <div class="max-w-7xl mx-auto px-8">
                <blockquote class="font-bold mb-4 text-black">
                    « Une initiative simple, qui a fédéré toute notre équipe
                    autour d'une cause qui compte vraiment. »
                </blockquote>
                <p>Sophie M., Responsable RH, Nestlé SA</p>
            </div>
        </section>

        <!-- Comment ça marche -->
        <section class="bg-white py-20">
            <div
                class="max-w-7xl mx-auto px-8 grid grid-cols-2 gap-16 items-center"
            >
                <div>
                    <h2 class="font-bold mb-5 text-black">
                        Comment ça marche ?
                    </h2>
                    <p class="mb-8">
                        Obtenir le Label CTS est un processus simple,
                        transparent et entièrement accompagné par nos équipes
                        médicales. Après avoir soumis votre demande
                        d'organisation, le CTS valide avec vous le mode
                        opératoire et la période de collecte. Vous disposez
                        ensuite de tous nos outils numériques et kits de
                        communication pour mobiliser vos équipes. Le label vous
                        est officiellement et automatiquement décerné dès la
                        finalisation de votre événement pour une validité d'un
                        an.
                    </p>
                    <RouterLink to="/login" class="btn btn-filled-red">
                        Inscrire mon entreprise
                    </RouterLink>
                </div>
                <div
                    class="aspect-video w-full rounded-md overflow-hidden rounded-lg"
                >
                    <img
                        :src="'/images/thumbnail_commentcamarche.webp'"
                        alt=""
                        class="object-cover"
                    />
                </div>
            </div>
        </section>

        <!-- Rejoignez le mouvement -->
        <section class="py-20 bg-light-grey">
            <div
                class="max-w-7xl mx-auto px-8 grid grid-cols-2 gap-16 items-center"
            >
                <div
                    class="aspect-video w-full rounded-md overflow-hidden rounded-lg"
                >
                    <img
                        :src="'/images/thumbnail_mouvement.webp'"
                        alt=""
                        class="object-cover"
                    />
                </div>
                <div>
                    <h2 class="font-bold mb-5 text-black">
                        Rejoignez le mouvement
                    </h2>
                    <p class="mb-8">
                        De la PME locale aux grands groupes bancaires et
                        horlogers, de nombreuses entreprises du bassin genevois
                        ont déjà intégré le don de sang dans leur culture
                        d'entreprise. Parcourez notre annuaire public pour
                        découvrir les organisations labellisées, visualiser leur
                        historique de participation et mesurer l'impact concret
                        de cette mobilisation collective.
                    </p>
                </div>
            </div>
        </section>

        <!-- Grille des entreprises -->
        <section class="bg-white py-20">
            <div class="max-w-7xl mx-auto px-8">
                <h2 class="font-bold text-center mb-12 text-black">
                    Liste des entreprises partenaires
                </h2>

                <!-- Chargement -->
                <div
                    v-if="loading"
                    class="text-center py-16"
                    style="color: var(--default-text)"
                >
                    Chargement...
                </div>

                <template v-else>
                    <!-- Grille -->
                    <div
                        class="grid gap-6 mb-10"
                        style="grid-template-columns: repeat(6, 1fr)"
                    >
                        <div v-for="e in visibles" :key="e.id" class="block">
                            <div
                                class="relative overflow-hidden"
                                style="
                                    border-radius: 12px;
                                    background: var(--light-grey);
                                    aspect-ratio: 288 / 210;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                "
                            >
                                <img
                                    v-if="e.logo"
                                    :src="e.logo"
                                    :alt="e.nom"
                                    class="w-full h-full object-contain p-4"
                                />
                                <div v-else class="text-center p-4">
                                    <div
                                        class="font-black mx-auto mb-2 flex items-center justify-center rounded-full"
                                        style="width: 52px; height: 52px; background: white; color: var(--default-titles)"
                                    >
                                        {{ e.nom.charAt(0) }}
                                    </div>
                                    <p
                                        class="captions leading-tight" style="color: var(--default-titles)"
                                    >
                                        {{ e.nom }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bouton voir tout -->
                    <div
                        v-if="!showAll && entreprises.length > 18"
                        class="flex justify-center"
                    >
                        <button @click="showAll = true" class="btn btn-outlined-red">
                            Voir toutes les entreprises
                        </button>
                    </div>

                    <!-- Aucune entreprise -->
                    <div
                        v-if="!loading && entreprises.length === 0"
                        class="text-center py-16"
                        style="color: var(--default-text)"
                    >
                        Aucune entreprise partenaire pour le moment.
                    </div>
                </template>
            </div>
        </section>

        <!-- Footer -->
        <Footer />
    </div>
</template>
