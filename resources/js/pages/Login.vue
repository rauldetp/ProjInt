<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";

const auth = useAuthStore();
const router = useRouter();

const mode = ref("login"); // 'login' | 'register'

// Connexion
const email = ref("");
const password = ref("");

// Inscription
const name = ref("");
const emailReg = ref("");
const passwordReg = ref("");
const entreprise = ref("");
const telephone = ref("");

const loading = ref(false);
const errorMsg = ref("");
const successMsg = ref("");

function switchMode(m) {
    mode.value = m;
    errorMsg.value = "";
    successMsg.value = "";
}

async function handleLogin() {
    loading.value = true;
    errorMsg.value = "";
    try {
        await auth.login(email.value, password.value);
        if (auth.isAdmin) router.push("/admin");
        else if (auth.isCoordinateur) router.push("/coordinateur");
        else router.push("/");
    } catch (e) {
        errorMsg.value = "Email ou mot de passe incorrect.";
    } finally {
        loading.value = false;
    }
}

async function handleRegister() {
    loading.value = true;
    errorMsg.value = "";
    successMsg.value = "";
    try {
        const res = await fetch("/api/register", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
            },
            body: JSON.stringify({
                name: name.value,
                email: emailReg.value,
                password: passwordReg.value,
                entreprise: entreprise.value,
                telephone: telephone.value,
            }),
        });
        const data = await res.json();
        if (!res.ok) {
            const msgs = data.errors
                ? Object.values(data.errors).flat().join(" ")
                : data.message || "Erreur lors de l'inscription.";
            throw new Error(msgs);
        }
        auth.token = data.token;
        auth.role = data.role;
        router.push("/coordinateur");
    } catch (e) {
        errorMsg.value = e.message;
    } finally {
        loading.value = false;
    }
}
</script>

<template>
    <div
        class="min-h-screen flex"
        style="
            background: #f2f4f3;
            font-family: &quot;Instrument Sans&quot;, sans-serif;
        "
    >
        <!-- Panneau gauche -->
        <div
            class="hidden lg:flex flex-col justify-between px-14 py-12"
            style="width: 42%; background: #2c4140; flex-shrink: 0"
        >
            <div>
                <RouterLink
                    to="/"
                    style="text-decoration: none"
                    class="flex items-center gap-2"
                >
                    <span class="font-extrabold text-xl" style="color: white"
                        >HUG</span
                    >
                    <div
                        class="w-px h-5 mx-1"
                        style="background: rgba(255, 255, 255, 0.3)"
                    ></div>
                    <span class="text-base font-semibold" style="color: #93cfa9"
                        >Don du sang</span
                    >
                </RouterLink>
            </div>
            <div>
                <p
                    class="font-black mb-4"
                    style="font-size: 40px; color: white; line-height: 1.2"
                >
                    Ensemble,<br />sauvons des vies.
                </p>
                <p
                    style="
                        font-size: 16px;
                        color: rgba(255, 255, 255, 0.65);
                        line-height: 1.7;
                        max-width: 340px;
                    "
                >
                    Rejoignez les entreprises genevoises engagées pour le don du
                    sang et donnez un impact concret à votre politique RSE.
                </p>
            </div>
            <div>
                <p style="font-size: 13px; color: rgba(255, 255, 255, 0.35)">
                    © {{ new Date().getFullYear() }} Hôpitaux Universitaires
                    Genève
                </p>
            </div>
        </div>

        <!-- Panneau droit -->
        <div class="flex-1 flex items-center justify-center px-6 py-12">
            <div class="w-full" style="max-width: 440px">
                <!-- Logo mobile -->
                <RouterLink
                    to="/"
                    class="lg:hidden flex items-center gap-2 mb-10"
                    style="text-decoration: none"
                >
                    <span class="font-extrabold text-xl" style="color: #2c4140"
                        >HUG</span
                    >
                    <div
                        class="w-px h-5 mx-1"
                        style="background: rgba(44, 65, 64, 0.3)"
                    ></div>
                    <span class="text-base font-semibold" style="color: #e60f48"
                        >Don du sang</span
                    >
                </RouterLink>

                <!-- Tabs -->
                <div class="flex mb-8" style="border-bottom: 1px solid #dde4e3">
                    <button
                        @click="switchMode('login')"
                        class="pb-3 mr-8 font-semibold transition"
                        style="
                            font-size: 16px;
                            background: none;
                            border: none;
                            cursor: pointer;
                            border-bottom: 2px solid transparent;
                            margin-bottom: -1px;
                        "
                        :style="
                            mode === 'login'
                                ? 'color: #2c4140; border-bottom-color: #e60f48'
                                : 'color: #497371'
                        "
                    >
                        Connexion
                    </button>
                    <button
                        @click="switchMode('register')"
                        class="pb-3 font-semibold transition"
                        style="
                            font-size: 16px;
                            background: none;
                            border: none;
                            cursor: pointer;
                            border-bottom: 2px solid transparent;
                            margin-bottom: -1px;
                        "
                        :style="
                            mode === 'register'
                                ? 'color: #2c4140; border-bottom-color: #e60f48'
                                : 'color: #497371'
                        "
                    >
                        Créer un compte
                    </button>
                </div>

                <!-- Message erreur / succès -->
                <div
                    v-if="errorMsg"
                    class="mb-5 px-4 py-3 rounded-lg"
                    style="background: #fee2e2; color: #991b1b; font-size: 14px"
                >
                    {{ errorMsg }}
                </div>

                <!-- FORMULAIRE CONNEXION -->
                <form
                    v-if="mode === 'login'"
                    @submit.prevent="handleLogin"
                    class="flex flex-col gap-5"
                >
                    <div>
                        <label
                            class="block font-semibold mb-1"
                            style="font-size: 14px; color: #2c4140"
                            >Adresse email</label
                        >
                        <input
                            v-model="email"
                            type="email"
                            required
                            placeholder="vous@entreprise.com"
                            class="w-full px-4 py-3 transition"
                            style="
                                border: 1px solid #dde4e3;
                                border-radius: 8px;
                                font-size: 15px;
                                color: #2c4140;
                                background: white;
                                outline: none;
                            "
                        />
                    </div>
                    <div>
                        <label
                            class="block font-semibold mb-1"
                            style="font-size: 14px; color: #2c4140"
                            >Mot de passe</label
                        >
                        <input
                            v-model="password"
                            type="password"
                            required
                            placeholder="••••••••"
                            class="w-full px-4 py-3 transition"
                            style="
                                border: 1px solid #dde4e3;
                                border-radius: 8px;
                                font-size: 15px;
                                color: #2c4140;
                                background: white;
                                outline: none;
                            "
                        />
                    </div>
                    <button
                        type="submit"
                        :disabled="loading"
                        class="w-full text-white font-semibold rounded-full py-3 transition hover:opacity-80"
                        style="
                            background: #e60f48;
                            font-size: 16px;
                            border: none;
                            cursor: pointer;
                            margin-top: 4px;
                        "
                        :style="
                            loading ? 'opacity: 0.6; cursor: not-allowed' : ''
                        "
                    >
                        {{ loading ? "Connexion…" : "Se connecter" }}
                    </button>
                </form>

                <!-- FORMULAIRE INSCRIPTION -->
                <form
                    v-else
                    @submit.prevent="handleRegister"
                    class="flex flex-col gap-5"
                >
                    <div>
                        <label
                            class="block font-semibold mb-1"
                            style="font-size: 14px; color: #2c4140"
                            >Nom complet</label
                        >
                        <input
                            v-model="name"
                            type="text"
                            required
                            placeholder="Prénom Nom"
                            class="w-full px-4 py-3 transition"
                            style="
                                border: 1px solid #dde4e3;
                                border-radius: 8px;
                                font-size: 15px;
                                color: #2c4140;
                                background: white;
                                outline: none;
                            "
                        />
                    </div>
                    <div>
                        <label
                            class="block font-semibold mb-1"
                            style="font-size: 14px; color: #2c4140"
                            >Nom de l'entreprise</label
                        >
                        <input
                            v-model="entreprise"
                            type="text"
                            required
                            placeholder="Ma Société SA"
                            class="w-full px-4 py-3 transition"
                            style="
                                border: 1px solid #dde4e3;
                                border-radius: 8px;
                                font-size: 15px;
                                color: #2c4140;
                                background: white;
                                outline: none;
                            "
                        />
                    </div>
                    <div>
                        <label
                            class="block font-semibold mb-1"
                            style="font-size: 14px; color: #2c4140"
                            >Adresse email</label
                        >
                        <input
                            v-model="emailReg"
                            type="email"
                            required
                            placeholder="vous@entreprise.com"
                            class="w-full px-4 py-3 transition"
                            style="
                                border: 1px solid #dde4e3;
                                border-radius: 8px;
                                font-size: 15px;
                                color: #2c4140;
                                background: white;
                                outline: none;
                            "
                        />
                    </div>
                    <div>
                        <label
                            class="block font-semibold mb-1"
                            style="font-size: 14px; color: #2c4140"
                            >Téléphone
                            <span style="color: #c0cac9; font-weight: 400"
                                >(optionnel)</span
                            ></label
                        >
                        <input
                            v-model="telephone"
                            type="tel"
                            placeholder="+41 79 000 00 00"
                            class="w-full px-4 py-3 transition"
                            style="
                                border: 1px solid #dde4e3;
                                border-radius: 8px;
                                font-size: 15px;
                                color: #2c4140;
                                background: white;
                                outline: none;
                            "
                        />
                    </div>
                    <div>
                        <label
                            class="block font-semibold mb-1"
                            style="font-size: 14px; color: #2c4140"
                            >Mot de passe</label
                        >
                        <input
                            v-model="passwordReg"
                            type="password"
                            required
                            placeholder="8 caractères minimum"
                            class="w-full px-4 py-3 transition"
                            style="
                                border: 1px solid #dde4e3;
                                border-radius: 8px;
                                font-size: 15px;
                                color: #2c4140;
                                background: white;
                                outline: none;
                            "
                        />
                    </div>
                    <button
                        type="submit"
                        :disabled="loading"
                        class="w-full text-white font-semibold rounded-full py-3 transition hover:opacity-80"
                        style="
                            background: #e60f48;
                            font-size: 16px;
                            border: none;
                            cursor: pointer;
                            margin-top: 4px;
                        "
                        :style="
                            loading ? 'opacity: 0.6; cursor: not-allowed' : ''
                        "
                    >
                        {{ loading ? "Création…" : "Créer mon compte" }}
                    </button>
                    <p
                        class="text-center"
                        style="
                            font-size: 13px;
                            color: #497371;
                            line-height: 1.6;
                        "
                    >
                        En créant un compte vous acceptez que vos données soient
                        utilisées dans le cadre du programme de don du sang des
                        HUG.
                    </p>
                </form>
            </div>
        </div>
    </div>
</template>
