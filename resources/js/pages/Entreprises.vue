<script setup>
import { ref, computed, onMounted } from "vue";
import HugNavbar from "../components/HugNavbar.vue";

const entreprises = ref([]);
const loading = ref(true);
const showAll = ref(false);

const visibles = computed(() =>
    showAll.value ? entreprises.value : entreprises.value.slice(0, 18),
);

onMounted(async () => {
    document.title = "Entreprises partenaires — HUG";
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
            class="relative flex items-end pb-14 overflow-hidden"
            style="height: 512px; background: var(--default-titles)"
        >
            <img
                :src="'/images/Hero_Cobrand.webp'"
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
                    class="font-bold text-white leading-tight mb-5"
                    style="font-size: 48px; max-width: 640px"
                >
                    Elles s'engagent pour le don du sang
                </h1>
                <p
                    class="text-white mb-8"
                    style="
                        font-size: 20px;
                        opacity: 0.9;
                        line-height: 1.6;
                        max-width: 620px;
                    "
                >
                    Grâce à l'engagement de ces entreprises et à la mobilisation
                    de leurs collaborateurs, le Centre de Transfusion Sanguine
                    des HUG peut répondre durablement aux besoins hospitaliers
                    de notre canton.
                </p>
                <RouterLink
                    to="/login"
                    class="btn btn-filled-red"
                >
                    Inscrire mon entreprise →
                </RouterLink>
            </div>
        </section>

        <!-- Grille des entreprises -->
        <section class="bg-white py-20">
            <div class="max-w-6xl mx-auto px-8">
                <h2
                    class="font-bold text-center mb-12"
                    style="font-size: 24px; color: var(--default-titles)"
                >
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
                                        style="
                                            width: 52px;
                                            height: 52px;
                                            background: white;
                                            font-size: 22px;
                                            color: var(--default-titles);
                                        "
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
                        <button
                            @click="showAll = true"
                            class="border-2 rounded-full px-7 py-2 font-semibold transition hover:opacity-75"
                            style="
                                color: var(--color-default-red);
                                border-color: var(--color-default-red);
                                background: white;
                                cursor: pointer;
                            "
                        >
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

        <!-- CTA gradient -->
        <section
            class="py-20 text-center"
            style="background: linear-gradient(135deg, var(--color-default-blue-59), var(--color-default-green))"
        >
            <div class="max-w-2xl mx-auto px-8">
                <h2
                    class="font-bold mb-6"
                    style="font-size: 32px; color: var(--default-titles); line-height: 1.3"
                >
                    Rejoignez les entreprises partenaires
                </h2>
                <p
                    class="mb-10"
                    style="
                        color: var(--default-titles);
                        opacity: 0.85;
                        line-height: 1.7;
                    "
                >
                    Vous souhaitez fédérer vos équipes autour d'un projet
                    solidaire fort et donner un impact concret à la politique
                    RSE de votre organisation ? Le CTS s'occupe de toute la
                    logistique médicale directement dans vos murs ou au centre
                    de transfusion.
                </p>
                <RouterLink
                    to="/login"
                    class="btn btn-filled-red"
                >
                    Participer dès maintenant →
                </RouterLink>
            </div>
        </section>

        <!-- Footer -->
        <footer style="background: var(--default-titles); padding: 3.5rem 0 2.5rem">
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
