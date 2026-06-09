<script setup>
import { ref, onMounted } from "vue";
import HugNavbar from "../components/HugNavbar.vue";
import Footer from "../components/Footer.vue";

const useful = ref(null);

onMounted(() => {
    document.title = "Contact — HUG Don du sang";
});
</script>

<template>
    <div class="min-h-screen bg-white">
        <HugNavbar />

        <!-- Hero -->
        <section class="bg-gradient py-20">
            <div class="max-w-4xl mx-auto px-8">
                <p
                    class="font-semibold mb-3 uppercase tracking-widest"
                    style="color: var(--default-titles); opacity: 0.65"
                >
                    Hôpitaux universitaires de Genève
                </p>
                <h1
                    class="font-bold text-black"
                    style="line-height: 1.2"
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
                                border-top: 2px solid var(--color-default-red);
                                padding-top: 1.5rem;
                            "
                        >
                            <h2
                                class="font-bold mb-6 text-black"
                            >
                                Coordonnées
                            </h2>
                            <div class="flex flex-col gap-5">
                                <div>
                                    <p
                                        class="font-semibold mb-1"
                                        style="color: var(--default-titles)"
                                    >
                                        Adresse
                                    </p>
                                    <p
                                        style="
                                            color: var(--default-text);
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
                                        style="color: var(--default-titles)"
                                    >
                                        Centrale téléphonique
                                    </p>
                                    <a
                                        href="tel:+41223723311"
                                        style="color: var(--color-default-red); font-weight: 600; text-decoration: none"
                                        class="hover:opacity-75 transition"
                                        >+41 (0)22 372 33 11</a
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Infos médicales -->
                        <div
                            style="
                                border-top: 2px solid var(--light-grey);
                                padding-top: 1.5rem;
                            "
                        >
                            <h2
                                class="font-bold mb-4 text-black"
                            >
                                Questions médicales
                            </h2>
                            <p
                                style="
                                    color: var(--default-text);
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
                                    color: var(--color-default-red);
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
                                border-top: 2px solid var(--light-grey);
                                padding-top: 1.5rem;
                            "
                        >
                            <h2
                                class="font-bold mb-2 text-black"
                            >
                                Trouver un professionnel ou une professionnelle
                            </h2>
                            <p
                                class="mb-6"
                                style="
                                    color: var(--default-text);
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
                                style="background: var(--default-titles); text-decoration: none"
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
                                style="color: #92400e; line-height: 1.75"
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
                                style="color: var(--default-titles)"
                            >
                                Ce contenu vous a-t-il été utile ?
                            </p>
                            <div class="flex gap-3">
                                <button
                                    @click="useful = true"
                                    class="rounded-full px-6 py-2 font-semibold transition"
                                    :style="
                                        useful === true
                                            ? 'background:var(--default-titles);color:white;border:2px solid var(--default-titles)'
                                            : 'background:white;color:var(--default-titles);border:2px solid var(--default-titles)'
                                    "
                                    style="cursor: pointer"
                                >
                                    Oui
                                </button>
                                <button
                                    @click="useful = false"
                                    class="rounded-full px-6 py-2 font-semibold transition"
                                    :style="
                                        useful === false
                                            ? 'background:var(--default-titles);color:white;border:2px solid var(--default-titles)'
                                            : 'background:white;color:var(--default-titles);border:2px solid #dde4e3'
                                    "
                                    style="cursor: pointer"
                                >
                                    Non
                                </button>
                                <span
                                    v-if="useful !== null"
                                    style="
                                        font-size: 14px;
                                        color: var(--default-text);
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
                                background: var(--light-grey);
                                border-radius: 12px;
                                padding: 1.5rem;
                            "
                        >
                            <p
                                class="font-bold mb-4"
                                style="color: var(--default-titles)"
                            >
                                Don du sang — CTS
                            </p>
                            <p
                                class="mb-4"
                                style="color: var(--default-text); line-height: 1.7"
                            >
                                Pour toute question relative aux collectes en
                                entreprise et au programme Trophée de la
                                Générosité.
                            </p>
                            <RouterLink
                                to="/login"
                                class="btn btn-filled-red"
                                >Créer un compte →</RouterLink
                            >
                        </div>
                        <div
                            style="
                                background: var(--light-grey);
                                border-radius: 12px;
                                padding: 1.5rem;
                            "
                        >
                            <p
                                class="font-bold mb-3"
                                style="color: var(--default-titles)"
                            >
                                Site officiel
                            </p>
                            <a
                                href="https://www.hug.ch"
                                target="_blank"
                                rel="noopener"
                                style="color: var(--color-default-red); text-decoration: none; font-weight: 500"
                                class="hover:opacity-75 transition"
                                >www.hug.ch →</a
                            >
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <Footer />
    </div>
</template>
