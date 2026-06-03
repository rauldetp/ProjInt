<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import { useCobrandStore } from "../stores/cobrand";
import { useCoinEntrepriseLink } from "../composables/useCoinEntrepriseLink";

const route = useRoute();
const cobrand = useCobrandStore();
const { coinEntrepriseLink } = useCoinEntrepriseLink();

const entreprise = ref(null);
const loading = ref(true);

const brandColor = computed(() => cobrand.couleurPrimaire || "#e60f48");
const heroGradient = computed(() => `linear-gradient(135deg, ${brandColor.value}, #ffffff)`);
const sectionGradient = computed(() => `linear-gradient(135deg, ${brandColor.value}, #ffffff)`);

onMounted(async () => {
    try {
        const res = await fetch(`/api/entreprises/${route.params.slug}`);
        if (res.ok) {
            const data = await res.json();
            entreprise.value = data.entreprise;
            if (data.entreprise) cobrand.set(data.entreprise);
        }
    } catch {}
    finally { loading.value = false; }
    document.title = `Label CTS — ${cobrand.nom || 'HUG'}`;
});
</script>

<template>
    <div class="min-h-screen bg-white" style="font-family: 'Instrument Sans', sans-serif">

        <!-- Navbar co-brandée -->
        <header class="bg-white sticky top-0 z-50" style="height: 76px; border-bottom: 1px solid #f2f4f3">
            <div class="max-w-7xl mx-auto px-8 h-full flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <RouterLink to="/" style="font-weight: 800; font-size: 20px; color: #2c4140; text-decoration: none">HUG</RouterLink>
                    <span style="color: rgba(44,65,64,0.3); font-size: 18px; margin: 0 4px">|</span>
                    <span style="font-size: 15px; font-weight: 600; color: #497371">Don du sang</span>
                    <span style="color: rgba(44,65,64,0.3); font-size: 18px; margin: 0 4px">×</span>
                    <span style="font-size: 15px; font-weight: 700" :style="{ color: brandColor }">
                        <img v-if="cobrand.logo" :src="cobrand.logo" :alt="cobrand.nom" style="max-height: 28px; object-fit: contain" />
                        <span v-else>{{ cobrand.nom || entreprise?.nom }}</span>
                    </span>
                </div>
                <nav class="hidden md:flex items-center gap-7 text-base font-medium">
                    <RouterLink :to="`/entreprise/${route.params.slug}`" style="color: #2c4140; text-decoration: none" class="hover:opacity-60 transition">Accueil</RouterLink>
                    <RouterLink :to="`/entreprise/${route.params.slug}/label`" style="font-weight: 700; text-decoration: none" :style="{ color: brandColor }">Label CTS</RouterLink>
                    <RouterLink :to="`/entreprise/${route.params.slug}/trophee`" style="color: #2c4140; text-decoration: none" class="hover:opacity-60 transition">Trophée de la générosité</RouterLink>
                    <RouterLink :to="coinEntrepriseLink" style="color: #2c4140; text-decoration: none" class="hover:opacity-60 transition">Coin entreprise</RouterLink>
                </nav>
                <RouterLink
                    :to="`/entreprise/${route.params.slug}/inscription`"
                    class="border-2 rounded-full px-5 py-2 text-sm font-semibold transition hover:opacity-75"
                    style="text-decoration: none; white-space: nowrap"
                    :style="{ color: brandColor, borderColor: brandColor }"
                >
                    S'inscrire à la collecte
                </RouterLink>
            </div>
        </header>

        <!-- Hero -->
        <section class="relative flex items-end pb-14 overflow-hidden" :style="{ height: '420px', background: heroGradient }">
            <div class="relative max-w-7xl mx-auto px-8 w-full z-10">
                <h1 class="font-bold leading-tight mb-4" style="font-size: 48px; color: #2c4140">
                    Notre Label CTS
                </h1>
                <p class="mb-6 max-w-lg" style="font-size: 18px; color: #2c4140; opacity: 0.8; line-height: 1.6">
                    Le label CTS des HUG récompense les entreprises qui s'engagent concrètement pour le don du sang et la solidarité.
                </p>
                <a
                    href="#ce-quil-represente"
                    class="inline-block text-white font-semibold rounded-full px-6 py-3 transition hover:opacity-80"
                    :style="{ background: brandColor, fontSize: '16px' }"
                >
                    Découvrir le Label
                </a>
            </div>
        </section>

        <!-- Ce qu'il représente -->
        <section id="ce-quil-represente" class="bg-white py-20">
            <div class="max-w-6xl mx-auto px-8 grid grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="font-bold mb-5" style="font-size: 32px; color: #2c4140">Ce qu'il représente</h2>
                    <p style="font-size: 16px; color: #497371; line-height: 1.75">
                        Le Label CTS est une certification honorifique conçue par les Hôpitaux Universitaires de Genève pour valoriser l'engagement citoyen de toutes les organisations partenaires. Contrairement au Trophée de la Générosité qui met en avant un palmarès restreint, ce label RSE certifie l'effort collectif et la responsabilité sociale de chaque entreprise qui organise une collecte de sang et soutient activement la santé publique genevoise.
                    </p>
                </div>
                <div class="flex flex-col items-center justify-center gap-4 rounded-2xl border p-12" style="border-color: #f2f4f3">
                    <div class="rounded-full border-4 flex items-center justify-center" style="width: 80px; height: 80px" :style="{ borderColor: brandColor }">
                        <span style="font-size: 32px" :style="{ color: brandColor }">♥</span>
                    </div>
                    <div class="text-center">
                        <p class="font-black" style="font-size: 24px; color: #2c4140; letter-spacing: -0.5px">CTS</p>
                        <p class="font-bold uppercase tracking-widest" style="font-size: 11px; color: #497371">Collecte solidaire</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Les bénéfices -->
        <section class="bg-white py-16 border-t" style="border-color: #f2f4f3">
            <div class="max-w-6xl mx-auto px-8">
                <h2 class="font-bold text-center mb-12" style="font-size: 32px; color: #2c4140">Les bénéfices du label</h2>
                <div class="grid grid-cols-3 gap-8">
                    <div class="flex flex-col items-center text-center gap-4">
                        <div class="rounded-full flex items-center justify-center" style="width: 64px; height: 64px; background: #f2f4f3">
                            <span style="font-size: 24px">👥</span>
                        </div>
                        <h3 class="font-bold" style="font-size: 16px; color: #2c4140">Rejoignez une communauté d'entreprises</h3>
                        <p style="font-size: 14px; color: #497371; line-height: 1.6">Afficher votre engagement société auprès de vos collaborateurs, partenaires et clients.</p>
                    </div>
                    <div class="flex flex-col items-center text-center gap-4">
                        <div class="rounded-full flex items-center justify-center" style="width: 64px; height: 64px; background: #f2f4f3">
                            <span style="font-size: 24px">🏆</span>
                        </div>
                        <h3 class="font-bold" style="font-size: 16px; color: #2c4140">Affichez votre engagement</h3>
                        <p style="font-size: 14px; color: #497371; line-height: 1.6">Associez votre entreprise à une démarche citoyenne et solidaire reconnue par les HUG.</p>
                    </div>
                    <div class="flex flex-col items-center text-center gap-4">
                        <div class="rounded-full flex items-center justify-center" style="width: 64px; height: 64px; background: #f2f4f3">
                            <span style="font-size: 24px">❤️</span>
                        </div>
                        <h3 class="font-bold" style="font-size: 16px; color: #2c4140">Un impact réel et mesurable</h3>
                        <p style="font-size: 14px; color: #497371; line-height: 1.6">Votre mobilisation contribue directement à sauver des vies en Suisse.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Quote gradient co-brandé -->
        <section class="py-20 text-center" :style="{ background: sectionGradient }">
            <div class="max-w-3xl mx-auto px-8">
                <blockquote class="font-bold mb-4" style="font-size: 28px; color: #2c4140; line-height: 1.45; font-style: italic">
                    « Une initiative simple, qui a fédéré toute notre équipe autour d'une cause qui compte vraiment. »
                </blockquote>
                <p class="font-semibold" style="font-size: 14px; color: #2c4140; opacity: 0.75">Sophie M., Responsable RH, Nestlé SA</p>
            </div>
        </section>

        <!-- Comment ça marche -->
        <section class="bg-white py-20">
            <div class="max-w-6xl mx-auto px-8 grid grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="font-bold mb-5" style="font-size: 32px; color: #2c4140">Comment ça marche ?</h2>
                    <p class="mb-8" style="font-size: 16px; color: #497371; line-height: 1.75">
                        Obtenir le Label CTS est un processus simple, transparent et entièrement accompagné par nos équipes médicales. Après avoir soumis votre demande d'organisation, le CTS valide avec vous le mode opératoire et la période de collecte. Vous disposez ensuite de tous nos outils numériques et kits de communication pour mobiliser vos équipes. Le label vous est officiellement et automatiquement décerné dès la finalisation de votre événement pour une validité d'un an.
                    </p>
                    <RouterLink
                        :to="`/entreprise/${route.params.slug}/inscription`"
                        class="inline-block text-white font-semibold rounded-full px-6 py-3 transition hover:opacity-80"
                        :style="{ background: brandColor, fontSize: '16px', textDecoration: 'none' }"
                    >
                        S'inscrire à la collecte
                    </RouterLink>
                </div>
                <div class="relative rounded-2xl overflow-hidden" style="height: 300px; background: #f2f4f3">
                    <img :src="'/images/thumbnail_commentcamarche.webp'" alt="" class="absolute inset-0 w-full h-full object-cover" />
                </div>
            </div>
        </section>

        <!-- Rejoignez le mouvement -->
        <section class="py-20" style="background: #f2f4f3">
            <div class="max-w-6xl mx-auto px-8 grid grid-cols-2 gap-16 items-center">
                <div class="relative rounded-2xl overflow-hidden" style="height: 300px; background: #e2e8e8">
                    <img :src="'/images/thumbnail_mouvement.webp'" alt="" class="absolute inset-0 w-full h-full object-cover" />
                </div>
                <div>
                    <h2 class="font-bold mb-5" style="font-size: 32px; color: #2c4140">Rejoignez le mouvement</h2>
                    <p class="mb-8" style="font-size: 16px; color: #497371; line-height: 1.75">
                        De la PME locale aux grands groupes bancaires et horlogers, de nombreuses entreprises du bassin genevois ont déjà intégré le don de sang dans leur culture d'entreprise. Parcourez notre annuaire public pour découvrir les organisations labellisées, visualiser leur historique de participation et mesurer l'impact concret de cette mobilisation collective.
                    </p>
                    <RouterLink
                        to="/entreprises"
                        class="inline-block text-white font-semibold rounded-full px-6 py-3 transition hover:opacity-80"
                        :style="{ background: brandColor, fontSize: '16px', textDecoration: 'none' }"
                    >
                        Découvrez nos entreprises partenaires
                    </RouterLink>
                </div>
            </div>
        </section>

        <!-- Footer HUG -->
        <footer style="background: #2c4140; padding: 3.5rem 0 2.5rem">
            <div class="max-w-6xl mx-auto px-8">
                <div class="grid grid-cols-4 gap-10 mb-12">
                    <div>
                        <div class="font-extrabold text-2xl mb-1 text-white">HUG</div>
                        <div style="font-size: 12px; color: #93cfa9; line-height: 1.6">Hôpitaux<br />Universitaires<br />Genève</div>
                    </div>
                    <div>
                        <p class="font-bold mb-5 text-white" style="font-size: 18px">Pages</p>
                        <ul class="space-y-3">
                            <li><RouterLink :to="`/entreprise/${route.params.slug}`" class="text-white hover:opacity-70 transition" style="font-size: 15px; text-decoration: none">Accueil collecte</RouterLink></li>
                            <li><RouterLink :to="`/entreprise/${route.params.slug}/label`" class="text-white hover:opacity-70 transition" style="font-size: 15px; text-decoration: none">Label CTS</RouterLink></li>
                            <li><RouterLink :to="`/entreprise/${route.params.slug}/trophee`" class="text-white hover:opacity-70 transition" style="font-size: 15px; text-decoration: none">Trophée de la générosité</RouterLink></li>
                        </ul>
                    </div>
                    <div>
                        <p class="font-bold mb-5 text-white" style="font-size: 18px">Support</p>
                        <ul class="space-y-3">
                            <li><RouterLink to="/faq" class="text-white hover:opacity-70 transition" style="font-size: 15px; text-decoration: none">FAQ</RouterLink></li>
                            <li><RouterLink to="/contact" class="text-white hover:opacity-70 transition" style="font-size: 15px; text-decoration: none">Contact</RouterLink></li>
                        </ul>
                    </div>
                    <div>
                        <p class="font-bold mb-5 text-white" style="font-size: 18px">Mentions légales</p>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-white hover:opacity-70 transition" style="font-size: 15px; text-decoration: none">Politique de confidentialité</a></li>
                            <li><a href="#" class="text-white hover:opacity-70 transition" style="font-size: 15px; text-decoration: none">Conditions générales</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t pt-6" style="border-color: rgba(242,244,243,0.15)">
                    <p class="text-center" style="font-size: 14px; color: rgba(242,244,243,0.5)">
                        © {{ new Date().getFullYear() }} Hôpitaux Universitaires Genève. Tous droits réservés.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
