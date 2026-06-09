<script setup>
import { onMounted } from "vue";
import HugNavbar from "../components/HugNavbar.vue";
import Footer from "../components/Footer.vue";

onMounted(() => {
    document.title = "Entreprises partenaires — HUG";
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
            <div class="relative max-w-7xl mx-auto px-8 w-full z-10">
                <h1
                    class="font-bold text-white leading-tight mb-5"
                    style="max-width: 640px"
                >
                    Elles s'engagent pour le don du sang
                </h1>
                <p
                    class="text-white mb-8"
                    style="opacity: 0.9; line-height: 1.6; max-width: 620px"
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

        <!-- CTA gradient -->
        <section
            class="py-20 text-center bg-gradient"
        >
            <div class="max-w-2xl mx-auto px-8">
                <h2
                    class="font-bold mb-6 text-black"
                    style="line-height: 1.3"
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
        <Footer />
    </div>
</template>
