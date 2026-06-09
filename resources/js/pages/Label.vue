<script setup>
import { onMounted } from "vue";
import HugNavbar from "../components/HugNavbar.vue";
import Footer from "../components/Footer.vue";

onMounted(() => {
    document.title = "Label CTS — HUG";
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
                    <p
                        class="text-white mb-6 max-w-lg"
                        style="opacity: 0.9; line-height: 1.6"
                    >
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
                    <p style="color: var(--default-text); line-height: 1.75">
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
                    class="bg-white flex flex-col items-center justify-center gap-4 rounded-2xl p-12"
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
                    <p
                        class="mb-8"
                        style="color: var(--default-text); line-height: 1.75"
                    >
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
                    class="relative rounded-2xl overflow-hidden"
                    style="height: 300px; background: var(--light-grey)"
                >
                    <img
                        :src="'/images/thumbnail_commentcamarche.webp'"
                        alt=""
                        class="absolute inset-0 w-full h-full object-cover"
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
                    class="relative rounded-2xl overflow-hidden"
                    style="height: 300px; background: #e2e8e8"
                >
                    <img
                        :src="'/images/thumbnail_mouvement.webp'"
                        alt=""
                        class="absolute inset-0 w-full h-full object-cover"
                    />
                </div>
                <div>
                    <h2 class="font-bold mb-5 text-black">
                        Rejoignez le mouvement
                    </h2>
                    <p
                        class="mb-8"
                        style="color: var(--default-text); line-height: 1.75"
                    >
                        De la PME locale aux grands groupes bancaires et
                        horlogers, de nombreuses entreprises du bassin genevois
                        ont déjà intégré le don de sang dans leur culture
                        d'entreprise. Parcourez notre annuaire public pour
                        découvrir les organisations labellisées, visualiser leur
                        historique de participation et mesurer l'impact concret
                        de cette mobilisation collective.
                    </p>
                    <RouterLink to="/entreprises" class="btn btn-filled-red">
                        Découvrez nos entreprises partenaires
                    </RouterLink>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <Footer />
    </div>
</template>
