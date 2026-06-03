<script setup>
import { onMounted } from "vue";
import { useCoinEntrepriseLink } from "../composables/useCoinEntrepriseLink";

const { coinEntrepriseLink } = useCoinEntrepriseLink();

onMounted(() => {
    document.title = "Label CTS — HUG";
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
                        style="
                            color: #e60f48;
                            font-weight: 700;
                            text-decoration: none;
                        "
                        >Label CTS</RouterLink
                    >
                    <RouterLink
                        to="/trophee"
                        style="color: #2c4140; text-decoration: none"
                        class="hover:opacity-60 transition"
                        >Trophée de la générosité</RouterLink
                    >
                    <RouterLink
                        :to="coinEntrepriseLink"
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
        <section
            class="relative flex items-end pb-14 overflow-hidden"
            style="height: 420px; background: #2c4140"
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
                <h1
                    class="font-bold text-white leading-tight mb-4"
                    style="font-size: 48px"
                >
                    Notre Label CTS
                </h1>
                <p
                    class="text-white mb-6 max-w-lg"
                    style="font-size: 18px; opacity: 0.9; line-height: 1.6"
                >
                    Le label CTS des HUG récompense les entreprises qui
                    s'engagent concrètement pour le don du sang et la
                    solidarité.
                </p>
                <a
                    href="#ce-quil-represente"
                    class="inline-block text-white font-semibold rounded-full px-6 py-3 transition hover:opacity-80"
                    style="background: #e60f48; font-size: 16px"
                >
                    Découvrir le Label
                </a>
            </div>
        </section>

        <!-- Ce qu'il représente -->
        <section id="ce-quil-represente" class="bg-white py-20">
            <div
                class="max-w-6xl mx-auto px-8 grid grid-cols-2 gap-16 items-center"
            >
                <div>
                    <h2
                        class="font-bold mb-5"
                        style="font-size: 32px; color: #2c4140"
                    >
                        Ce qu'il représente
                    </h2>
                    <p
                        style="
                            font-size: 16px;
                            color: #497371;
                            line-height: 1.75;
                        "
                    >
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

        <!-- Les bénéfices -->
        <section class="bg-white py-16 border-t" style="border-color: #f2f4f3">
            <div class="max-w-6xl mx-auto px-8">
                <h2
                    class="font-bold text-center mb-12"
                    style="font-size: 32px; color: #2c4140"
                >
                    Les bénéfices du label
                </h2>
                <div class="grid grid-cols-3 gap-8">
                    <div class="flex flex-col items-center text-center gap-4">
                        <div
                            class="rounded-full flex items-center justify-center"
                            style="
                                width: 64px;
                                height: 64px;
                                background: #f2f4f3;
                            "
                        >
                            <span style="font-size: 24px">👥</span>
                        </div>
                        <h3
                            class="font-bold"
                            style="font-size: 16px; color: #2c4140"
                        >
                            Rejoignez une communauté d'entreprises
                        </h3>
                        <p
                            style="
                                font-size: 14px;
                                color: #497371;
                                line-height: 1.6;
                            "
                        >
                            Afficher votre engagement société auprès de vos
                            collaborateurs, partenaires et clients.
                        </p>
                    </div>
                    <div class="flex flex-col items-center text-center gap-4">
                        <div
                            class="rounded-full flex items-center justify-center"
                            style="
                                width: 64px;
                                height: 64px;
                                background: #f2f4f3;
                            "
                        >
                            <span style="font-size: 24px">🏆</span>
                        </div>
                        <h3
                            class="font-bold"
                            style="font-size: 16px; color: #2c4140"
                        >
                            Affichez votre engagement
                        </h3>
                        <p
                            style="
                                font-size: 14px;
                                color: #497371;
                                line-height: 1.6;
                            "
                        >
                            Associez votre entreprise à une démarche citoyenne
                            et solidaire reconnue par les HUG.
                        </p>
                    </div>
                    <div class="flex flex-col items-center text-center gap-4">
                        <div
                            class="rounded-full flex items-center justify-center"
                            style="
                                width: 64px;
                                height: 64px;
                                background: #f2f4f3;
                            "
                        >
                            <span style="font-size: 24px">❤️</span>
                        </div>
                        <h3
                            class="font-bold"
                            style="font-size: 16px; color: #2c4140"
                        >
                            Un impact réel et mesurable
                        </h3>
                        <p
                            style="
                                font-size: 14px;
                                color: #497371;
                                line-height: 1.6;
                            "
                        >
                            Votre mobilisation contribue directement à sauver
                            des vies en Suisse.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Quote gradient -->
        <section
            class="py-20 text-center"
            style="background: linear-gradient(135deg, #65c6c1, #93cfa9)"
        >
            <div class="max-w-3xl mx-auto px-8">
                <blockquote
                    class="font-bold mb-4"
                    style="
                        font-size: 28px;
                        color: #2c4140;
                        line-height: 1.45;
                        font-style: italic;
                    "
                >
                    « Une initiative simple, qui a fédéré toute notre équipe
                    autour d'une cause qui compte vraiment. »
                </blockquote>
                <p
                    class="font-semibold"
                    style="font-size: 14px; color: #2c4140; opacity: 0.75"
                >
                    Sophie M., Responsable RH, Nestlé SA
                </p>
            </div>
        </section>

        <!-- Comment ça marche -->
        <section class="bg-white py-20">
            <div
                class="max-w-6xl mx-auto px-8 grid grid-cols-2 gap-16 items-center"
            >
                <div>
                    <h2
                        class="font-bold mb-5"
                        style="font-size: 32px; color: #2c4140"
                    >
                        Comment ça marche ?
                    </h2>
                    <p
                        class="mb-8"
                        style="
                            font-size: 16px;
                            color: #497371;
                            line-height: 1.75;
                        "
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
                    <RouterLink
                        to="/login"
                        class="inline-block text-white font-semibold rounded-full px-6 py-3 transition hover:opacity-80"
                        style="
                            background: #e60f48;
                            font-size: 16px;
                            text-decoration: none;
                        "
                    >
                        Inscrire mon entreprise
                    </RouterLink>
                </div>
                <div
                    class="relative rounded-2xl overflow-hidden"
                    style="height: 300px; background: #f2f4f3"
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
        <section class="py-20" style="background: #f2f4f3">
            <div
                class="max-w-6xl mx-auto px-8 grid grid-cols-2 gap-16 items-center"
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
                    <h2
                        class="font-bold mb-5"
                        style="font-size: 32px; color: #2c4140"
                    >
                        Rejoignez le mouvement
                    </h2>
                    <p
                        class="mb-8"
                        style="
                            font-size: 16px;
                            color: #497371;
                            line-height: 1.75;
                        "
                    >
                        De la PME locale aux grands groupes bancaires et
                        horlogers, de nombreuses entreprises du bassin genevois
                        ont déjà intégré le don de sang dans leur culture
                        d'entreprise. Parcourez notre annuaire public pour
                        découvrir les organisations labellisées, visualiser leur
                        historique de participation et mesurer l'impact concret
                        de cette mobilisation collective.
                    </p>
                    <RouterLink
                        to="/entreprises"
                        class="inline-block text-white font-semibold rounded-full px-6 py-3 transition hover:opacity-80"
                        style="
                            background: #e60f48;
                            font-size: 16px;
                            text-decoration: none;
                        "
                    >
                        Découvrez nos entreprises partenaires
                    </RouterLink>
                </div>
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
                                    style="
                                        font-size: 16px;
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
                                        font-size: 16px;
                                        text-decoration: none;
                                    "
                                    >Trophée de la générosité</RouterLink
                                >
                            </li>
                            <li>
                                <a
                                    href="/#temoignages"
                                    class="text-white hover:opacity-70 transition"
                                    style="
                                        font-size: 16px;
                                        text-decoration: none;
                                    "
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
                                <RouterLink
                                    to="/faq"
                                    class="text-white hover:opacity-70 transition"
                                    style="
                                        font-size: 16px;
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
                                        font-size: 16px;
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
                                        font-size: 16px;
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
                                        font-size: 16px;
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
