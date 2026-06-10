<script setup>
import { ref, onMounted } from "vue";
import AppNavbar from "../components/AppNavbar.vue";
import Footer from "../components/Footer.vue";

const form = ref({
    nom: "",
    prenom: "",
    entreprise: "",
    email: "",
    sujet: "",
    message: "",
});
const loading = ref(false);
const sent = ref(false);
const errorMsg = ref("");

async function submit() {
    loading.value = true;
    errorMsg.value = "";
    try {
        const res = await fetch("/api/contact", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
            },
            body: JSON.stringify(form.value),
        });
        const data = await res.json().catch(() => ({}));
        if (!res.ok) {
            const msgs = data.errors
                ? Object.values(data.errors).flat().join(" ")
                : data.message || "Une erreur est survenue lors de l'envoi.";
            throw new Error(msgs);
        }
        sent.value = true;
    } catch (e) {
        errorMsg.value = e.message;
    } finally {
        loading.value = false;
    }
}

function scrollToForm() {
    document.getElementById("form")?.scrollIntoView({ behavior: "smooth" });
}

onMounted(() => {
    document.title = "Contact — HUG Don du sang";
});
</script>

<template>
    <div class="min-h-screen bg-white">
        <AppNavbar />

        <!-- Hero -->
        <section
            class="section-hero relative flex items-end pb-16 overflow-hidden"
        >
            <img
                :src="'/images/hero_contact.webp'"
                alt=""
                class="absolute inset-0 w-full h-full object-cover"
            />
            <div
                class="absolute inset-0"
                style="background: rgba(44, 65, 64, 0.55)"
            ></div>
            <div class="relative max-w-7xl mx-auto px-8 w-full z-10">
                <h1 class="font-bold text-white mb-4">Contactez-nous</h1>
                <p class="text-white mb-2 max-w-xl">
                    Une question sur le Label CTS, le trophée de la Générosité ou
                    l'organisation d'une collecte ?
                </p>
                <p class="text-white mb-8 max-w-xl">
                    Notre équipe est là pour vous accompagner.
                </p>
                <button
                    type="button"
                    class="btn btn-filled-red"
                    @click="scrollToForm"
                >
                    Nous écrire
                    <span class="material-symbols-outlined">arrow_forward</span>
                </button>
            </div>
        </section>

        <!-- Formulaire -->
        <section id="form" class="bg-light-grey py-20">
            <div class="max-w-3xl mx-auto px-8">
                <h2 class="font-bold mb-10 text-black">
                    Envoyez-nous un message
                </h2>

                <!-- Confirmation -->
                <div
                    v-if="sent"
                    class="bg-white rounded-2xl p-10 text-center shadow-light"
                >
                    <span
                        class="material-symbols-outlined mb-4"
                        style="font-size: 48px; color: var(--color-default-green-39)"
                        >check_circle</span
                    >
                    <h3 class="font-bold mb-2 text-black">Message envoyé !</h3>
                    <p style="color: var(--default-text)">
                        Merci, notre équipe vous recontactera dans les meilleurs
                        délais.
                    </p>
                </div>

                <!-- Formulaire -->
                <form v-else @submit.prevent="submit">
                    <div
                        v-if="errorMsg"
                        class="mb-6 px-4 py-3 rounded-lg"
                        style="background: #fee2e2; color: #991b1b"
                    >
                        {{ errorMsg }}
                    </div>

                    <div class="grid grid-cols-2 gap-x-6 gap-y-5 mb-5">
                        <div>
                            <label class="captions block mb-2">Nom</label>
                            <input
                                v-model="form.nom"
                                type="text"
                                class="form-input"
                            />
                        </div>
                        <div>
                            <label class="captions block mb-2">
                                Prénom
                                <span style="color: var(--color-default-red)"
                                    >*</span
                                >
                            </label>
                            <input
                                v-model="form.prenom"
                                type="text"
                                required
                                class="form-input"
                            />
                        </div>
                        <div>
                            <label class="captions block mb-2">Entreprise</label>
                            <input
                                v-model="form.entreprise"
                                type="text"
                                class="form-input"
                            />
                        </div>
                        <div>
                            <label class="captions block mb-2">
                                Email
                                <span style="color: var(--color-default-red)"
                                    >*</span
                                >
                            </label>
                            <input
                                v-model="form.email"
                                type="email"
                                required
                                class="form-input"
                            />
                        </div>
                        <div>
                            <label class="captions block mb-2">
                                Sujet
                                <span style="color: var(--color-default-red)"
                                    >*</span
                                >
                            </label>
                            <select
                                v-model="form.sujet"
                                required
                                class="form-input form-select"
                            >
                                <option value="">Choisir…</option>
                                <option>Label CTS</option>
                                <option>Trophée de la Générosité</option>
                                <option>Organisation d'une collecte</option>
                                <option>Autre</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-8">
                        <label class="captions block mb-2">Message</label>
                        <textarea
                            v-model="form.message"
                            rows="6"
                            required
                            class="form-input"
                        ></textarea>
                    </div>

                    <button
                        type="submit"
                        :disabled="loading"
                        class="btn btn-filled-red"
                    >
                        {{ loading ? "Envoi…" : "Envoyer le message" }}
                        <span class="material-symbols-outlined"
                            >arrow_forward</span
                        >
                    </button>
                </form>
            </div>
        </section>

        <Footer />
    </div>
</template>
