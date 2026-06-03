<script setup>
import { ref, onMounted } from "vue";
import { useCoinEntrepriseLink } from "../composables/useCoinEntrepriseLink";

const { coinEntrepriseLink } = useCoinEntrepriseLink();
const useful = ref(null);

onMounted(() => {
    document.title = "Contact — HUG Don du sang";
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
                        :to="coinEntrepriseLink"
                        style="color: #2c4140; text-decoration: none"
                        class="hover:opacity-60 transition"
                        >Coin entreprise</RouterLink
                    >
                    <RouterLink
                        to="/contact"
                        style="
                            color: #e60f48;
                            font-weight: 700;
                            text-decoration: none;
                        "
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
        <section style="background: linear-gradient(135deg, #65c6c1, #93cfa9); padding: 72px 0 56px">
            <div class="max-w-4xl mx-auto px-8">
                <p
                    class="font-semibold mb-3 uppercase tracking-widest"
                    style="font-size: 13px; color: #2c4140; opacity: 0.65"
                >
                    Hôpitaux universitaires de Genève
                </p>
                <h1
                    class="font-bold"
                    style="font-size: 48px; line-height: 1.2; color: #2c4140"
                >
                    Contacter les HUG
                </h1>
            </div>
        </section>

        <!-- Contenu principal -->
        <section class="py-16">
            <div class="max-w-4xl mx-auto px-8">
                <div class="grid grid-cols-3 gap-14">
                    <!-- Colonne principale -->
                    <div class="col-span-2 flex flex-col gap-12">
                        <!-- Adresse + téléphone -->
                        <div
                            style="
                                border-top: 2px solid #e60f48;
                                padding-top: 1.5rem;
                            "
                        >
                            <h2
                                class="font-bold mb-6"
                                style="font-size: 22px; color: #2c4140"
                            >
                                Coordonnées
                            </h2>
                            <div class="flex flex-col gap-5">
                                <div>
                                    <p
                                        class="font-semibold mb-1"
                                        style="font-size: 14px; color: #2c4140"
                                    >
                                        Adresse
                                    </p>
                                    <p
                                        style="
                                            font-size: 15px;
                                            color: #497371;
                                            line-height: 1.7;
                                        "
                                    >
                                        Rue Gabrielle-Perret-Gentil 4<br />
                                        1205 Genève<br />
                                        1211 Genève 14
                                    </p>
                                </div>
                                <div>
                                    <p
                                        class="font-semibold mb-1"
                                        style="font-size: 14px; color: #2c4140"
                                    >
                                        Centrale téléphonique
                                    </p>
                                    <a
                                        href="tel:+41223723311"
                                        style="
                                            font-size: 18px;
                                            color: #e60f48;
                                            font-weight: 600;
                                            text-decoration: none;
                                        "
                                        class="hover:opacity-75 transition"
                                        >+41 (0)22 372 33 11</a
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Infos médicales -->
                        <div
                            style="
                                border-top: 2px solid #f2f4f3;
                                padding-top: 1.5rem;
                            "
                        >
                            <h2
                                class="font-bold mb-4"
                                style="font-size: 22px; color: #2c4140"
                            >
                                Questions médicales
                            </h2>
                            <p
                                style="
                                    font-size: 15px;
                                    color: #497371;
                                    line-height: 1.8;
                                "
                            >
                                Pour toute question médicale ou information
                                personnelle relative à la santé, nous vous
                                invitons à contacter votre médecin ou le corps
                                médical concerné. Vous trouverez la liste
                                complète de nos structures médicales ainsi que
                                leurs coordonnées sur la page suivante :
                            </p>
                            <a
                                href="https://www.hug.ch/liste-structures-medicales"
                                target="_blank"
                                rel="noopener"
                                style="
                                    font-size: 15px;
                                    color: #e60f48;
                                    text-decoration: none;
                                    font-weight: 500;
                                "
                                class="inline-block mt-3 hover:opacity-75 transition"
                                >www.hug.ch/liste-structures-medicales →</a
                            >
                        </div>

                        <!-- Annuaire -->
                        <div
                            style="
                                border-top: 2px solid #f2f4f3;
                                padding-top: 1.5rem;
                            "
                        >
                            <h2
                                class="font-bold mb-2"
                                style="font-size: 22px; color: #2c4140"
                            >
                                Trouver un professionnel ou une professionnelle
                            </h2>
                            <p
                                class="mb-6"
                                style="
                                    font-size: 15px;
                                    color: #497371;
                                    line-height: 1.8;
                                "
                            >
                                Consultez l'annuaire des HUG pour trouver les
                                coordonnées de nos professionnels de santé.
                            </p>
                            <a
                                href="https://www.hug.ch/annuaire"
                                target="_blank"
                                rel="noopener"
                                class="inline-block text-white font-semibold rounded-full px-7 py-3 transition hover:opacity-80 uppercase tracking-wider"
                                style="
                                    background: #2c4140;
                                    font-size: 13px;
                                    text-decoration: none;
                                "
                                >Accéder à l'annuaire des HUG →</a
                            >
                        </div>

                        <!-- Urgences -->
                        <div
                            style="
                                background: #fef3c7;
                                border-left: 3px solid #f59e0b;
                                padding: 1.25rem 1.5rem;
                                border-radius: 0 8px 8px 0;
                            "
                        >
                            <p
                                style="
                                    font-size: 14px;
                                    color: #92400e;
                                    line-height: 1.75;
                                "
                            >
                                <strong>En cas d'urgence vitale</strong>, nous
                                vous encourageons à composer le
                                <strong>144</strong>.<br />
                                Pour les situations non vitales ou en cas
                                d'indisponibilité de votre médecin traitant,
                                vous pouvez appeler la Centrale Santé Genève
                                (CeSaGe) au
                                <strong>0800 116 117</strong>.
                            </p>
                        </div>

                        <!-- Ce contenu vous a-t-il été utile ? -->
                        <div
                            style="
                                border-top: 1px solid #dde4e3;
                                padding-top: 1.5rem;
                            "
                        >
                            <p
                                class="font-semibold mb-4"
                                style="font-size: 15px; color: #2c4140"
                            >
                                Ce contenu vous a-t-il été utile ?
                            </p>
                            <div class="flex gap-3">
                                <button
                                    @click="useful = true"
                                    class="rounded-full px-6 py-2 font-semibold transition"
                                    :style="
                                        useful === true
                                            ? 'background:#2c4140;color:white;border:2px solid #2c4140'
                                            : 'background:white;color:#2c4140;border:2px solid #2c4140'
                                    "
                                    style="font-size: 14px; cursor: pointer"
                                >
                                    Oui
                                </button>
                                <button
                                    @click="useful = false"
                                    class="rounded-full px-6 py-2 font-semibold transition"
                                    :style="
                                        useful === false
                                            ? 'background:#2c4140;color:white;border:2px solid #2c4140'
                                            : 'background:white;color:#2c4140;border:2px solid #dde4e3'
                                    "
                                    style="font-size: 14px; cursor: pointer"
                                >
                                    Non
                                </button>
                                <span
                                    v-if="useful !== null"
                                    style="
                                        font-size: 14px;
                                        color: #497371;
                                        align-self: center;
                                    "
                                    >Merci pour votre retour.</span
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Colonne latérale -->
                    <div class="flex flex-col gap-6">
                        <div
                            style="
                                background: #f2f4f3;
                                border-radius: 12px;
                                padding: 1.5rem;
                            "
                        >
                            <p
                                class="font-bold mb-4"
                                style="font-size: 16px; color: #2c4140"
                            >
                                Don du sang — CTS
                            </p>
                            <p
                                class="mb-4"
                                style="
                                    font-size: 14px;
                                    color: #497371;
                                    line-height: 1.7;
                                "
                            >
                                Pour toute question relative aux collectes en
                                entreprise et au programme Trophée de la
                                Générosité.
                            </p>
                            <RouterLink
                                to="/login"
                                class="inline-block font-semibold rounded-full px-5 py-2 transition hover:opacity-80"
                                style="
                                    background: #e60f48;
                                    color: white;
                                    font-size: 14px;
                                    text-decoration: none;
                                "
                                >Créer un compte →</RouterLink
                            >
                        </div>
                        <div
                            style="
                                background: #f2f4f3;
                                border-radius: 12px;
                                padding: 1.5rem;
                            "
                        >
                            <p
                                class="font-bold mb-3"
                                style="font-size: 16px; color: #2c4140"
                            >
                                Site officiel
                            </p>
                            <a
                                href="https://www.hug.ch"
                                target="_blank"
                                rel="noopener"
                                style="
                                    font-size: 14px;
                                    color: #e60f48;
                                    text-decoration: none;
                                    font-weight: 500;
                                "
                                class="hover:opacity-75 transition"
                                >www.hug.ch →</a
                            >
                        </div>
                    </div>
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
                                <a
                                    href="/#faq"
                                    class="text-white hover:opacity-70 transition"
                                    style="
                                        font-size: 16px;
                                        text-decoration: none;
                                    "
                                    >FAQ</a
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
                        © {{ new Date().getFullYear() }} Hôpitaux Universitaires
                        Genève. Tous droits réservés.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
