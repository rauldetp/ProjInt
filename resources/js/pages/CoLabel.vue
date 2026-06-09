<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import { useCobrandStore } from "../stores/cobrand";
import CoNavbar from "../components/CoNavbar.vue";
import Footer from "../components/Footer.vue";

const route = useRoute();
const cobrand = useCobrandStore();

const entreprise = ref(null);
const collecte = ref(null);
const loading = ref(true);

const brandColor = computed(() => cobrand.couleurPrimaire || "var(--color-default-red)");
const sectionGradient = computed(() => `linear-gradient(135deg, ${brandColor.value}, #ffffff)`);

onMounted(async () => {
    try {
        const res = await fetch(`/api/entreprises/${route.params.slug}`);
        if (res.ok) {
            const data = await res.json();
            entreprise.value = data.entreprise;
            collecte.value = data.collecte ?? null;
            if (data.entreprise) cobrand.set(data.entreprise);
        }
    } catch {}
    finally { loading.value = false; }
    document.title = `Label CTS — ${cobrand.nom || 'HUG'}`;
});
</script>

<template>
    <div class="min-h-screen bg-white">

        <!-- Navbar -->
        <CoNavbar :collecte="collecte" />

        <!-- Hero -->
        <section class="relative flex items-center overflow-hidden" style="height: 512px; background-image: url('/images/Hero_labelCTS.webp'); background-size: cover; background-position: center;">
            <div class="absolute inset-0" style="background: rgba(0,0,0,0.42); z-index: 1"></div>
            <div class="relative w-full" style="z-index: 2; max-width: 1280px; margin: 0 auto; padding: 0 2rem; width: 100%">
                <h1 class="font-bold leading-tight mb-4" style="color: white">
                    Notre Label CTS
                </h1>
                <p class="mb-6 max-w-lg" style="color: rgba(255,255,255,0.85); line-height: 1.6">
                    Le label CTS des HUG récompense les entreprises qui s'engagent concrètement pour le don du sang et la solidarité.
                </p>
            </div>
        </section>

        <!-- Ce qu'il représente -->
        <section id="ce-quil-represente" class="bg-white py-20">
            <div class="max-w-7xl mx-auto px-8 grid grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="font-bold mb-5 text-black">Ce qu'il représente</h2>
                    <p style="color: var(--default-text); line-height: 1.75">
                        Le Label CTS est une certification honorifique conçue par les Hôpitaux Universitaires de Genève pour valoriser l'engagement citoyen de toutes les organisations partenaires. Contrairement au Trophée de la Générosité qui met en avant un palmarès restreint, ce label RSE certifie l'effort collectif et la responsabilité sociale de chaque entreprise qui organise une collecte de sang et soutient activement la santé publique genevoise.
                    </p>
                </div>
                <div class="flex flex-col items-center justify-center gap-4 rounded-2xl border p-12" style="border-color: var(--light-grey)">
                    <div class="rounded-full border-4 flex items-center justify-center" style="width: 80px; height: 80px" :style="{ borderColor: brandColor }">
                        <span style="font-size: 32px" :style="{ color: brandColor }">♥</span>
                    </div>
                    <div class="text-center">
                        <p class="font-black" style="color: var(--default-titles); letter-spacing: -0.5px">CTS</p>
                        <p class="font-bold uppercase tracking-widest" style="color: var(--default-text)">Collecte solidaire</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Les bénéfices -->
        <section class="bg-white py-16 border-t" style="border-color: var(--light-grey)">
            <div class="max-w-7xl mx-auto px-8">
                <h2 class="font-bold text-center mb-12 text-black">Les bénéfices du label</h2>
                <div class="grid grid-cols-3 gap-8">
                    <div class="flex flex-col items-center text-center gap-4">
                        <div class="rounded-full flex items-center justify-center" style="width: 64px; height: 64px; background: var(--light-grey)">
                            <span class="material-symbols-outlined" style="font-size: 28px; color: var(--default-text)">group</span>
                        </div>
                        <h3 class="font-bold text-black">Rejoignez une communauté d'entreprises</h3>
                        <p style="color: var(--default-text); line-height: 1.6">Afficher votre engagement société auprès de vos collaborateurs, partenaires et clients.</p>
                    </div>
                    <div class="flex flex-col items-center text-center gap-4">
                        <div class="rounded-full flex items-center justify-center" style="width: 64px; height: 64px; background: var(--light-grey)">
                            <span class="material-symbols-outlined" style="font-size: 28px; color: var(--default-text)">emoji_events</span>
                        </div>
                        <h3 class="font-bold text-black">Affichez votre engagement</h3>
                        <p style="color: var(--default-text); line-height: 1.6">Associez votre entreprise à une démarche citoyenne et solidaire reconnue par les HUG.</p>
                    </div>
                    <div class="flex flex-col items-center text-center gap-4">
                        <div class="rounded-full flex items-center justify-center" style="width: 64px; height: 64px; background: var(--light-grey)">
                            <span class="material-symbols-outlined" style="font-size: 28px; color: var(--default-text)">favorite</span>
                        </div>
                        <h3 class="font-bold text-black">Un impact réel et mesurable</h3>
                        <p style="color: var(--default-text); line-height: 1.6">Votre mobilisation contribue directement à sauver des vies en Suisse.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Quote gradient co-brandé -->
        <section class="py-20 text-center" :style="{ background: sectionGradient }">
            <div class="max-w-3xl mx-auto px-8">
                <blockquote class="font-bold mb-4" :style="{ fontSize: '28px', color: cobrand.textOnBrand, lineHeight: '1.45', fontStyle: 'italic' }">
                    « Une initiative simple, qui a fédéré toute notre équipe autour d'une cause qui compte vraiment. »
                </blockquote>
                <p class="font-semibold" :style="{ fontSize: '14px', color: cobrand.textOnBrand, opacity: 0.75 }">Sophie M., Responsable RH, Nestlé SA</p>
            </div>
        </section>

        <!-- Comment ça marche -->
        <section class="bg-white py-20">
            <div class="max-w-7xl mx-auto px-8 grid grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="font-bold mb-5 text-black">Comment ça marche ?</h2>
                    <p class="mb-8" style="color: var(--default-text); line-height: 1.75">
                        Obtenir le Label CTS est un processus simple, transparent et entièrement accompagné par nos équipes médicales. Après avoir soumis votre demande d'organisation, le CTS valide avec vous le mode opératoire et la période de collecte. Vous disposez ensuite de tous nos outils numériques et kits de communication pour mobiliser vos équipes. Le label vous est officiellement et automatiquement décerné dès la finalisation de votre événement pour une validité d'un an.
                    </p>
                    <RouterLink
                        v-if="collecte"
                        :to="`/entreprise/${route.params.slug}/inscription`"
                        class="inline-block font-semibold rounded-full px-6 py-3 transition hover:opacity-80"
                        :style="{ background: brandColor, color: cobrand.textOnBrand, fontSize: '16px', textDecoration: 'none' }"
                    >
                        S'inscrire à la collecte
                    </RouterLink>
                </div>
                <div class="relative rounded-2xl overflow-hidden" style="height: 300px; background: var(--light-grey)">
                    <img :src="'/images/thumbnail_commentcamarche.webp'" alt="" class="absolute inset-0 w-full h-full object-cover" />
                </div>
            </div>
        </section>

        <!-- Rejoignez le mouvement -->
        <section class="py-20 bg-light-grey">
            <div class="max-w-7xl mx-auto px-8 grid grid-cols-2 gap-16 items-center">
                <div class="relative rounded-2xl overflow-hidden" style="height: 300px; background: #e2e8e8">
                    <img :src="'/images/thumbnail_mouvement.webp'" alt="" class="absolute inset-0 w-full h-full object-cover" />
                </div>
                <div>
                    <h2 class="font-bold mb-5 text-black">Rejoignez le mouvement</h2>
                    <p class="mb-8" style="color: var(--default-text); line-height: 1.75">
                        De la PME locale aux grands groupes bancaires et horlogers, de nombreuses entreprises du bassin genevois ont déjà intégré le don de sang dans leur culture d'entreprise. Parcourez notre annuaire public pour découvrir les organisations labellisées, visualiser leur historique de participation et mesurer l'impact concret de cette mobilisation collective.
                    </p>
                    <RouterLink
                        to="/label"
                        class="inline-block font-semibold rounded-full px-6 py-3 transition hover:opacity-80"
                        :style="{ background: brandColor, color: cobrand.textOnBrand, fontSize: '16px', textDecoration: 'none' }"
                    >
                        Découvrez nos entreprises partenaires
                    </RouterLink>
                </div>
            </div>
        </section>

        <!-- Footer HUG -->
        <Footer :slug="route.params.slug" />
    </div>
</template>
