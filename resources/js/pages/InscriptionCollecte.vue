<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import { useCobrandStore } from "../stores/cobrand";
import { useCoinEntrepriseLink } from "../composables/useCoinEntrepriseLink";

const route = useRoute();
const cobrand = useCobrandStore();
const { coinEntrepriseLink } = useCoinEntrepriseLink();

const entreprise = ref(null);
const collecte = ref(null);
const loading = ref(true);

const brandColor = computed(() => cobrand.couleurPrimaire || "#e60f48");
const heroGradient = computed(() => `linear-gradient(135deg, ${brandColor.value}, #ffffff)`);

onMounted(async () => {
    try {
        const res = await fetch(`/api/entreprises/${route.params.slug}`);
        if (res.ok) {
            const data = await res.json();
            entreprise.value = data.entreprise;
            collecte.value = data.collecte ?? null;
            if (data.entreprise) {
                cobrand.set(data.entreprise);
            }
        }
    } catch {}
    finally { loading.value = false; }
    document.title = `S'inscrire à la collecte — HUG`;
});
</script>

<template>
    <div class="min-h-screen bg-white" style="font-family: 'Instrument Sans', sans-serif">
        <!-- Navbar -->
        <header class="bg-white sticky top-0 z-50" style="height: 76px; border-bottom: 1px solid #f2f4f3">
            <div class="max-w-7xl mx-auto px-8 h-full flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <RouterLink to="/" style="text-decoration: none; font-weight: 800; font-size: 20px; color: #2c4140">HUG</RouterLink>
                    <span style="color: rgba(44,65,64,0.3); font-size: 18px">|</span>
                    <span style="font-size: 15px; font-weight: 600; color: #497371">Don du sang</span>
                    <template v-if="entreprise">
                        <span style="color: rgba(44,65,64,0.3); font-size: 18px">×</span>
                        <span style="font-size: 15px; font-weight: 700" :style="{ color: brandColor }">{{ entreprise.nom }}</span>
                    </template>
                </div>
                <nav class="hidden md:flex items-center gap-7 text-base font-medium">
                    <RouterLink :to="`/entreprise/${route.params.slug}`" style="color: #2c4140; text-decoration: none" class="hover:opacity-60 transition">Accueil</RouterLink>
                    <RouterLink :to="`/entreprise/${route.params.slug}/label`" style="color: #2c4140; text-decoration: none" class="hover:opacity-60 transition">Label CTS</RouterLink>
                    <RouterLink :to="`/entreprise/${route.params.slug}/trophee`" style="color: #2c4140; text-decoration: none" class="hover:opacity-60 transition">Trophée de la générosité</RouterLink>
                    <RouterLink :to="coinEntrepriseLink" style="color: #2c4140; text-decoration: none" class="hover:opacity-60 transition">Coin entreprise</RouterLink>
                </nav>
                <RouterLink
                    v-if="collecte"
                    :to="`/entreprise/${route.params.slug}/inscription`"
                    class="border-2 rounded-full px-5 py-2 text-base font-semibold transition hover:opacity-75"
                    :style="{ color: brandColor, borderColor: brandColor, textDecoration: 'none' }"
                >
                    S'inscrire à la collecte
                </RouterLink>
            </div>
        </header>

        <!-- Hero -->
        <section class="py-16 text-center" :style="{ background: heroGradient }">
            <div class="max-w-2xl mx-auto px-8">
                <p class="font-semibold mb-3 uppercase tracking-widest" :style="{ fontSize: '13px', color: cobrand.textOnBrand, opacity: 0.75 }">
                    {{ entreprise?.nom ?? 'Entreprise' }} × HUG
                </p>
                <h1 class="font-bold mb-4" :style="{ color: cobrand.textOnBrand, fontSize: '42px', lineHeight: '1.2' }">
                    S'inscrire à la collecte de sang
                </h1>
                <p :style="{ fontSize: '17px', color: cobrand.textOnBrand, opacity: 0.85, lineHeight: '1.6' }">
                    Remplissez le dossier d'inscription pour réserver votre créneau.
                </p>
            </div>
        </section>

        <!-- Contenu -->
        <section class="py-20" style="background: #f2f4f3">
            <div class="max-w-xl mx-auto px-8 text-center">
                <div style="background: white; border-radius: 16px; padding: 3rem 2.5rem">
                    <div
                        class="flex items-center justify-center rounded-full mx-auto mb-6"
                        style="width: 64px; height: 64px"
                        :style="{ background: brandColor + '15' }"
                    >
                        <span style="font-size: 28px">📋</span>
                    </div>
                    <h2 class="font-bold mb-3" style="font-size: 22px; color: #2c4140">
                        Dossier d'inscription
                    </h2>
                    <p class="mb-8" style="font-size: 15px; color: #497371; line-height: 1.7">
                        Pour vous inscrire à la collecte de sang, veuillez remplir le formulaire d'inscription officiel des HUG. Ce document nous permet de préparer votre accueil dans les meilleures conditions.
                    </p>
                    <a
                        href="https://www.hug.ch/don-du-sang"
                        target="_blank"
                        rel="noopener"
                        class="inline-block text-white font-semibold rounded-full px-8 py-3 transition hover:opacity-80"
                        :style="{ background: brandColor, textDecoration: 'none', fontSize: '16px' }"
                    >
                        Accéder au formulaire →
                    </a>
                    <p class="mt-5" style="font-size: 12px; color: #c0cac9">
                        Vous serez redirigé vers le site officiel des HUG.
                    </p>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer style="background: #2c4140; padding: 3rem 0 2rem">
            <div class="max-w-6xl mx-auto px-8 flex items-center justify-between">
                <div>
                    <div class="font-extrabold text-xl text-white">HUG</div>
                    <div style="font-size: 12px; color: #93cfa9">Hôpitaux Universitaires Genève</div>
                </div>
                <p style="font-size: 13px; color: rgba(242,244,243,0.4)">
                    © {{ new Date().getFullYear() }} Hôpitaux Universitaires Genève
                </p>
            </div>
        </footer>
    </div>
</template>
