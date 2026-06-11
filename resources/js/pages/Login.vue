<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";

const auth = useAuthStore();
const router = useRouter();

const mode = ref("login"); // 'login' | 'register' | 'admin' | 'success'
const step = ref(1); // 1 | 2 | 3

// --- Succès inscription ---
const newSlug = ref("");
const newEntrepriseName = ref("");

// --- Connexion ---
const email = ref("");
const password = ref("");

// --- Inscription étape 1 : Entreprise ---
const groupe = ref("");
const nbEmployes = ref("");
const entreprise = ref("");
const adresse = ref("");
const domaine = ref("");
const ville = ref("");
const npa = ref("");

// --- Inscription étape 2 : Profil ---
const name = ref("");
const telephone = ref("");
const emailReg = ref("");
const poste = ref("");
const passwordReg = ref("");

// --- Inscription étape 3 : Apparence ---
const couleurPrimaire = ref("var(--color-default-red)");
const logoFile = ref(null);

const loading = ref(false);
const errorMsg = ref("");

const domaineOptions = [
    { value: "horlogerie", label: "Horlogerie" },
    { value: "banque", label: "Banque & Finance" },
    { value: "assurance", label: "Assurance" },
    { value: "sante", label: "Santé" },
    { value: "industrie", label: "Industrie" },
    { value: "technologie", label: "Technologie" },
    { value: "autre", label: "Autre" },
];

function startRegister() {
    mode.value = "register";
    step.value = 1;
    errorMsg.value = "";
}

function backToLogin() {
    mode.value = "login";
    step.value = 1;
    errorMsg.value = "";
}

function switchToAdmin() {
    mode.value = "admin";
    errorMsg.value = "";
    email.value = "";
    password.value = "";
}

function nextStep() {
    errorMsg.value = "";
    if (step.value === 1) {
        if (!entreprise.value.trim()) {
            errorMsg.value = "Le nom de l'entreprise est obligatoire.";
            return;
        }
    }
    if (step.value === 2) {
        if (
            !name.value.trim() ||
            !emailReg.value.trim() ||
            !passwordReg.value.trim()
        ) {
            errorMsg.value = "Veuillez remplir tous les champs obligatoires.";
            return;
        }
        if (passwordReg.value.length < 8) {
            errorMsg.value =
                "Le mot de passe doit contenir au moins 8 caractères.";
            return;
        }
    }
    step.value++;
}

async function handleLogin() {
    loading.value = true;
    errorMsg.value = "";
    try {
        await auth.login(email.value, password.value);
        if (auth.isAdmin) router.push("/admin");
        else if (auth.isCoordinateur) {
            const slug = auth.entrepriseSlug;
            router.push(
                slug ? `/entreprise/${slug}/coordinateur` : "/coordinateur",
            );
        } else router.push("/");
    } catch (e) {
        errorMsg.value = "Email ou mot de passe incorrect.";
    } finally {
        loading.value = false;
    }
}

async function handleRegister() {
    loading.value = true;
    errorMsg.value = "";
    try {
        const formData = new FormData();
        formData.append("name", name.value);
        formData.append("email", emailReg.value);
        formData.append("password", passwordReg.value);
        formData.append("entreprise", entreprise.value);
        if (telephone.value) formData.append("telephone", telephone.value);
        if (adresse.value) formData.append("adresse", adresse.value);
        if (ville.value) formData.append("ville", ville.value);
        if (npa.value) formData.append("npa", npa.value);
        if (domaine.value) formData.append("domaine", domaine.value);
        if (nbEmployes.value) formData.append("nb_employes", nbEmployes.value);
        if (poste.value) formData.append("poste", poste.value);
        if (couleurPrimaire.value)
            formData.append("couleur_primaire", couleurPrimaire.value);
        if (logoFile.value) formData.append("logo", logoFile.value);

        const res = await fetch("/api/register", {
            method: "POST",
            headers: { Accept: "application/json" },
            body: formData,
        });
        const data = await res.json();
        if (!res.ok) {
            const msgs = data.errors
                ? Object.values(data.errors).flat().join(" ")
                : data.message || "Erreur lors de l'inscription.";
            throw new Error(msgs);
        }
        auth.token = data.token;
        localStorage.setItem("token", data.token);
        await auth.fetchMe();
        newSlug.value = data.slug;
        newEntrepriseName.value = entreprise.value;
        mode.value = "success";
    } catch (e) {
        errorMsg.value = e.message;
    } finally {
        loading.value = false;
    }
}

</script>

<template>
    <div class="min-h-screen flex" style="background: var(--light-grey)">
        <!-- Panneau gauche -->
        <div
            class="hidden lg:flex flex-col justify-between px-14 py-12"
            style="
                width: 42%;
                background: linear-gradient(
                    135deg,
                    var(--color-default-blue-59),
                    var(--color-default-green)
                );
                flex-shrink: 0;
            "
        >
            <div>
                <RouterLink
                    to="/"
                    style="text-decoration: none"
                    class="flex items-center gap-2"
                >
                    <div class="hug-brand">
                        <div class="brand-hug"
                            ><img
                                :src="'/images/LOG HUG_H_NEGATIF fond transparent.png'"
                                alt="Logo HUG"
                        /></div>
                    </div>
                    <div
                        class="w-px h-5 mx-1"
                        style="background: white"
                    ></div>
                    <span
                        class="text-base font-semibold"
                        style="color: white"
                        >Don du sang</span
                    >
                </RouterLink>
            </div>

            <div>
                <p style="color: white">
                    © {{ new Date().getFullYear() }} Hôpitaux Universitaires
                    Genève
                </p>
            </div>
        </div>

        <!-- Panneau droit -->
        <div class="flex-1 flex items-center justify-center px-4 sm:px-6 py-12">
            <div class="w-full" style="max-width: 480px">
                <!-- Logo mobile -->
                <RouterLink
                    to="/"
                    class="lg:hidden flex items-center gap-2 mb-10"
                    style="text-decoration: none"
                >
                    <span class="brand-hug">
                        <img
                            :src="'/images/logo_hug_h_quadri.png'"
                            alt="Logo HUG"
                        />
                    </span>
                    <div
                        class="w-px h-5 mx-1"
                        style="background: rgba(44, 65, 64, 0.3)"
                    ></div>
                    <span
                        class="text-base font-semibold"
                        style="color: var(--color-default-red)"
                        >Don du sang</span
                    >
                </RouterLink>

                <!-- Erreur -->
                <div
                    v-if="errorMsg && mode !== 'success'"
                    class="mb-5 px-4 py-3 rounded-lg"
                    style="background: #fee2e2; color: #991b1b"
                >
                    {{ errorMsg }}
                </div>

                <!-- ══════════════════ CONNEXION ══════════════════ -->
                <template v-if="mode === 'login'">
                    <h1 class="font-bold mb-1 text-black">Connexion</h1>
                    <p class="mb-8" style="color: var(--default-text)">
                        Accédez à votre espace entreprise.
                    </p>

                    <form
                        @submit.prevent="handleLogin"
                        class="flex flex-col gap-5"
                    >
                        <div>
                            <label
                                class="captions mb-3"
                            >
                                Adresse email
                            </label>
                            <input
                                v-model="email"
                                type="email"
                                required
                                placeholder="vous@entreprise.com"
                                class="form-input"
                            />
                        </div>
                        <div>
                            <label
                                class="captions mb-3"
                            >
                                Mot de passe
                            </label>
                            <input
                                v-model="password"
                                type="password"
                                required
                                placeholder="••••••••"
                                class="form-input"
                            />
                        </div>
                        <button
                            type="submit"
                            :disabled="loading"
                            class="btn btn-filled-red w-full"
                            style="
                                border: none;
                                cursor: pointer;
                                margin-top: 4px;
                            "
                            :style="
                                loading ? 'opacity:0.6;cursor:not-allowed' : ''
                            "
                        >
                            {{ loading ? "Connexion…" : "Se connecter" }}
                        </button>
                    </form>

                    <!-- Séparateur -->
                    <div class="flex items-center gap-4 my-7">
                        <div
                            class="flex-1"
                            style="height: 1px; background: #dde4e3"
                        ></div>
                        <span
                            style="
                                font-size: 13px;
                                color: #c0cac9;
                                white-space: nowrap;
                            "
                            >Pas encore partenaire ?</span
                        >
                        <div
                            class="flex-1"
                            style="height: 1px; background: #dde4e3"
                        ></div>
                    </div>

                    <button
                        @click="startRegister"
                        class="btn btn-outlined-red w-full"
                    >
                        Rejoindre le programme →
                    </button>

                    <!-- Lien admin discret -->
                    <div class="text-center mt-8">
                        <button
                            @click="switchToAdmin"
                            class="captions hover:opacity-70 transition"
                            style="
                                background: none;
                                border: none;
                                cursor: pointer;
                                color: #c0cac9;
                                text-decoration: underline;
                                text-underline-offset: 3px;
                            "
                        >
                            Accès administrateur
                        </button>
                    </div>
                </template>

                <!-- ══════════════════ ADMIN ══════════════════ -->
                <template v-if="mode === 'admin'">
                    <h1 class="font-bold mb-1 text-black">Administration</h1>
                    <p class="mb-8" style="color: var(--default-text)">
                        Espace réservé à l'équipe CTS des HUG.
                    </p>

                    <form
                        @submit.prevent="handleLogin"
                        class="flex flex-col gap-5"
                    >
                        <div>
                            <label
                                class="captions mb-3"
                            >
                                Adresse email
                            </label>
                            <input
                                v-model="email"
                                type="email"
                                required
                                placeholder="admin@cts-hug.ch"
                                class="form-input"
                            />
                        </div>
                        <div>
                            <label
                                class="captions mb-3"
                            >
                                Mot de passe
                            </label>
                            <input
                                v-model="password"
                                type="password"
                                required
                                placeholder="••••••••"
                                class="form-input"
                            />
                        </div>
                        <button
                            type="submit"
                            :disabled="loading"
                            class="btn btn-filled-blue w-full"
                        >
                            {{ loading ? "Connexion…" : "Accéder au panneau" }}
                        </button>
                    </form>

                    <!-- Bouton retour (bas) -->
                    <button
                        @click="backToLogin"
                        class="flex items-center gap-2 mt-6 transition hover:opacity-60"
                        style="
                            background: none;
                            border: none;
                            cursor: pointer;
                            padding: 0;
                            color: var(--default-text);
                        "
                    >
                        ← Retour
                    </button>
                </template>

                <!-- ══════════════════ INSCRIPTION ══════════════════ -->
                <template v-else-if="mode === 'register'">
                    <!-- Barre de progression (style quiz) -->
                    <div class="progress-steps mb-8">
                        <template v-for="s in 3" :key="s">
                            <div
                                class="step"
                                :class="{
                                    'step-done': s < step,
                                    'step-active': s === step,
                                }"
                            >
                                {{ s }}
                            </div>
                            <div
                                v-if="s < 3"
                                class="step-line"
                                :class="{ 'step-line-done': s < step }"
                            ></div>
                        </template>
                    </div>

                    <Transition name="slide" mode="out-in">
                    <div :key="step">

                    <!-- ── Étape 1 : Votre entreprise ── -->
                    <template v-if="step === 1">
                        <h1 class="font-bold mb-1 text-black">
                            Parlez-nous de votre entreprise
                        </h1>
                        <p
                            class="mb-7"
                            style="color: var(--default-text); line-height: 1.6"
                        >
                            Ces informations permettent au CTS de configurer la
                            page de votre future entreprise.
                        </p>

                        <div class="flex flex-col gap-4">
                            <!-- Nom + Taille -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="sm:col-span-2">
                                    <label
                                        class="captions mb-3"
                                    >
                                        Nom de l'entreprise
                                        <span
                                            style="
                                                color: var(--color-default-red);
                                            "
                                            >*</span
                                        >
                                    </label>
                                    <input
                                        v-model="entreprise"
                                        type="text"
                                        required
                                        placeholder="Ma Société SA"
                                        class="form-input"
                                    />
                                </div>
                                <div>
                                    <label
                                        class="captions mb-3"
                                    >
                                        Secteur d'activité
                                    </label>
                                    <select
                                        v-model="domaine"
                                        class="form-input form-select"
                                    >
                                        <option value="">Choisir…</option>
                                        <option
                                            v-for="opt in domaineOptions"
                                            :key="opt.value"
                                            :value="opt.value"
                                        >
                                            {{ opt.label }}
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label
                                        class="captions mb-3"
                                    >
                                        Nombre d'employés
                                    </label>
                                    <input
                                        v-model="nbEmployes"
                                        type="number"
                                        min="1"
                                        placeholder="ex. 250"
                                        class="form-input"
                                    />
                                </div>
                            </div>

                            <!-- Adresse -->
                            <div>
                                <label
                                    class="captions mb-3"
                                >
                                    Adresse
                                </label>
                                <input
                                    v-model="adresse"
                                    type="text"
                                    placeholder="Rue de la Paix 12"
                                    class="form-input"
                                />
                            </div>

                            <!-- Ville + NPA -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label
                                        class="captions mb-3"
                                    >
                                        Ville
                                    </label>
                                    <input
                                        v-model="ville"
                                        type="text"
                                        placeholder="Genève"
                                        class="form-input"
                                    />
                                </div>
                                <div>
                                    <label
                                        class="captions mb-3"
                                    >
                                        Code postal
                                    </label>
                                    <input
                                        v-model="npa"
                                        type="text"
                                        placeholder="1200"
                                        class="form-input"
                                    />
                                </div>
                            </div>
                        </div>

                        <button
                            @click="nextStep"
                            class="btn btn-filled-red w-full mt-7"
                            style="border: none; cursor: pointer"
                        >
                            Continuer vers la prochaine étape →
                        </button>
                    </template>

                    <!-- ── Étape 2 : Votre profil ── -->
                    <template v-if="step === 2">
                        <h1 class="font-bold mb-1 text-black">
                            Parlez-nous de vous
                        </h1>
                        <p
                            class="mb-7"
                            style="color: var(--default-text); line-height: 1.6"
                        >
                            En tant que coordinateur principal, vous ferez le
                            lien entre les HUG et votre entreprise.
                        </p>

                        <div class="flex flex-col gap-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="sm:col-span-2">
                                    <label
                                        class="captions mb-3"
                                    >
                                        Nom et prénom
                                        <span
                                            style="
                                                color: var(--color-default-red);
                                            "
                                            >*</span
                                        >
                                    </label>
                                    <input
                                        v-model="name"
                                        type="text"
                                        required
                                        placeholder="Prénom Nom"
                                        class="form-input"
                                    />
                                </div>
                                <div>
                                    <label
                                        class="captions mb-3"
                                    >
                                        Poste
                                    </label>
                                    <input
                                        v-model="poste"
                                        type="text"
                                        placeholder="Responsable RH"
                                        class="form-input"
                                    />
                                </div>
                                <div>
                                    <label
                                        class="captions mb-3"
                                    >
                                        Téléphone
                                    </label>
                                    <input
                                        v-model="telephone"
                                        type="tel"
                                        placeholder="+41 79 000 00 00"
                                        class="form-input"
                                    />
                                </div>
                                <div class="sm:col-span-2">
                                    <label
                                        class="captions mb-3"
                                    >
                                        Email professionnel
                                        <span
                                            style="
                                                color: var(--color-default-red);
                                            "
                                            >*</span
                                        >
                                    </label>
                                    <input
                                        v-model="emailReg"
                                        type="email"
                                        required
                                        placeholder="vous@entreprise.com"
                                        class="form-input"
                                    />
                                </div>
                                <div class="sm:col-span-2">
                                    <label
                                        class="captions mb-3"
                                    >
                                        Mot de passe
                                        <span
                                            style="
                                                color: var(--color-default-red);
                                            "
                                            >*</span
                                        >
                                    </label>
                                    <input
                                        v-model="passwordReg"
                                        type="password"
                                        required
                                        placeholder="8 caractères minimum"
                                        class="form-input"
                                    />
                                </div>
                            </div>
                        </div>

                        <button
                            @click="nextStep"
                            class="btn btn-filled-red w-full mt-7"
                            style="border: none; cursor: pointer"
                        >
                            Continuer vers la prochaine étape →
                        </button>
                    </template>

                    <!-- ── Étape 3 : Apparence ── -->
                    <template v-if="step === 3">
                        <h1 class="font-bold mb-1 text-black">
                            Informations graphiques
                        </h1>
                        <p
                            class="mb-7"
                            style="color: var(--default-text); line-height: 1.6"
                        >
                            Ces éléments visuels personnalisent votre interface
                            et vos kits de communication.
                            <span style="color: #c0cac9">(optionnel)</span>
                        </p>

                        <div class="flex flex-col gap-5">
                            <!-- Logo -->
                            <div>
                                <label
                                    class="captions mb-3"
                                >
                                    Logo
                                </label>
                                <div
                                    class="flex items-center gap-3"
                                    style="
                                        border: 1px solid #dde4e3;
                                        border-radius: 8px;
                                        padding: 10px 14px;
                                        background: white;
                                    "
                                >
                                    <span
                                        style="
                                            font-size: 14px;
                                            color: #c0cac9;
                                            flex: 1;
                                        "
                                    >
                                        {{
                                            logoFile
                                                ? logoFile.name
                                                : ".jpg, .png, .svg"
                                        }}
                                    </span>
                                    <label
                                        class="font-semibold rounded-full px-4 py-1 transition hover:opacity-75 cursor-pointer"
                                        style="
                                            background: var(--light-grey);
                                            color: var(--default-titles);
                                            white-space: nowrap;
                                        "
                                    >
                                        Parcourir
                                        <input
                                            type="file"
                                            accept=".jpg,.jpeg,.png,.svg"
                                            class="hidden"
                                            @change="
                                                (e) =>
                                                    (logoFile =
                                                        e.target.files[0])
                                            "
                                        />
                                    </label>
                                </div>
                            </div>

                            <!-- Couleur principale -->
                            <div>
                                <label
                                    class="captions mb-3"
                                >
                                    Couleur principale
                                </label>
                                <div
                                    class="flex items-center gap-3"
                                    style="
                                        border: 1px solid #dde4e3;
                                        border-radius: 8px;
                                        padding: 10px 14px;
                                        background: white;
                                    "
                                >
                                    <input
                                        v-model="couleurPrimaire"
                                        type="color"
                                        style="
                                            width: 28px;
                                            height: 28px;
                                            border: none;
                                            padding: 0;
                                            cursor: pointer;
                                            background: none;
                                            border-radius: 4px;
                                        "
                                    />
                                    <input
                                        v-model="couleurPrimaire"
                                        type="text"
                                        placeholder="var(--color-default-red)"
                                        style="
                                            border: none;
                                            outline: none;
                                            color: var(--default-titles);
                                            flex: 1;
                                            font-family: monospace;
                                        "
                                    />
                                </div>
                            </div>
                        </div>

                        <button
                            @click="handleRegister"
                            :disabled="loading"
                            class="btn btn-filled-red w-full mt-7"
                            style="border: none; cursor: pointer"
                            :style="
                                loading ? 'opacity:0.6;cursor:not-allowed' : ''
                            "
                        >
                            {{
                                loading
                                    ? "Création de votre espace…"
                                    : "Créer mon espace entreprise"
                            }}
                        </button>

                        <p
                            class="text-center mt-5"
                            style="color: #c0cac9; line-height: 1.6"
                        >
                            En créant un compte vous acceptez que vos données
                            soient utilisées dans le cadre du programme de don
                            du sang des HUG.
                        </p>
                    </template>

                    </div>
                    </Transition>

                    <!-- Bouton retour (bas) -->
                    <button
                        @click="step > 1 ? step-- : backToLogin()"
                        class="flex items-center gap-2 mt-6 transition hover:opacity-60"
                        style="
                            background: none;
                            border: none;
                            cursor: pointer;
                            padding: 0;
                            color: var(--default-text);
                        "
                    >
                        ←
                        {{
                            step > 1
                                ? "Étape précédente"
                                : "Retour à la connexion"
                        }}
                    </button>
                </template>

                <!-- ══════════════════ SUCCÈS ══════════════════ -->
                <template v-else-if="mode === 'success'">
                    <div style="text-align: center">
                        <!-- Icône succès -->
                        <div
                            style="
                                width: 72px;
                                height: 72px;
                                border-radius: 50%;
                                background: #d1fae5;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                margin: 0 auto 1.5rem;
                            "
                        >
                            <span
                                class="material-symbols-outlined"
                                style="font-size: 36px; color: #16a34a"
                                >check_circle</span
                            >
                        </div>

                        <h1
                            class="font-bold mb-2 text-black"
                            style="line-height: 1.3"
                        >
                            Votre espace a bien été créé !
                        </h1>
                        <p
                            style="
                                color: var(--default-text);
                                line-height: 1.65;
                                margin-bottom: 2rem;
                            "
                        >
                            Bienvenue dans le programme
                            <strong>Trophée de la générosité</strong>.<br />
                            Votre site co-brandé
                            <strong style="color: var(--default-titles)">{{
                                newEntrepriseName
                            }}</strong>
                            est prêt.
                        </p>

                        <!-- Récap -->
                        <div
                            style="
                                background: var(--light-grey);
                                border-radius: 14px;
                                padding: 1.25rem 1.5rem;
                                margin-bottom: 2rem;
                                text-align: left;
                                display: flex;
                                flex-direction: column;
                                gap: 0.75rem;
                            "
                        >
                            <div
                                v-for="item in [
                                    {
                                        icon: 'language',
                                        text: 'Site co-brandé avec vos couleurs',
                                    },
                                    {
                                        icon: 'quiz',
                                        text: 'Quiz d\'éligibilité personnalisé',
                                    },
                                    {
                                        icon: 'emoji_events',
                                        text: 'Trophée de la générosité activé',
                                    },
                                    {
                                        icon: 'support_agent',
                                        text: 'Équipe CTS notifiée sous 48h',
                                    },
                                ]"
                                :key="item.text"
                                style="
                                    display: flex;
                                    align-items: center;
                                    gap: 0.75rem;
                                "
                            >
                                <span
                                    class="material-symbols-outlined"
                                    style="
                                        font-size: 18px;
                                        color: var(--color-default-blue-59);
                                    "
                                    >{{ item.icon }}</span
                                >
                                <span
                                    style="
                                        font-size: 14px;
                                        color: var(--default-titles);
                                        font-weight: 500;
                                    "
                                    >{{ item.text }}</span
                                >
                            </div>
                        </div>

                        <button
                            @click="router.push(`/entreprise/${newSlug}`)"
                            class="btn btn-filled-red w-full"
                            style="
                                border: none;
                                cursor: pointer;
                                margin-bottom: 1rem;
                            "
                        >
                            Accéder à mon espace
                            <span
                                class="material-symbols-outlined"
                                style="
                                    font-size: 18px;
                                    vertical-align: middle;
                                    margin-left: 4px;
                                "
                                >arrow_forward</span
                            >
                        </button>

                        <p style="color: #c0cac9">
                            Vous pouvez fermer cette page et y revenir à tout
                            moment depuis<br />
                            <span
                                style="
                                    color: var(--default-text);
                                    font-weight: 600;
                                "
                                >hug.ch/entreprise/{{ newSlug }}</span
                            >
                        </p>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<style scoped>
.hug-brand {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-shrink: 0;
}
.brand-hug {
    font-weight: 800;
    font-size: 1.25rem;
    color: var(--default-titles);
    text-decoration: none;
}
.brand-hug img {
    max-height: 2.75rem;
    width: auto;
}

/* Barre de progression (style quiz) */
/* Styles des steps centralisés dans app.css (.progress-steps, .step, .step-line…) */

/* Transition slide entre étapes (comme le quiz) */
.slide-enter-active,
.slide-leave-active {
    transition:
        opacity 0.25s ease,
        transform 0.25s ease;
}
.slide-enter-from {
    opacity: 0;
    transform: translateX(30px);
}
.slide-leave-to {
    opacity: 0;
    transform: translateX(-30px);
}
</style>
