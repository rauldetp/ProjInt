<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";

const auth = useAuthStore();
const router = useRouter();

const mode = ref("login"); // 'login' | 'register' | 'admin' | 'success'
const step = ref(1); // 1 | 2 | 3

// --- Succès inscription ---
const newSlug          = ref("");
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
        if (!name.value.trim() || !emailReg.value.trim() || !passwordReg.value.trim()) {
            errorMsg.value = "Veuillez remplir tous les champs obligatoires.";
            return;
        }
        if (passwordReg.value.length < 8) {
            errorMsg.value = "Le mot de passe doit contenir au moins 8 caractères.";
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
            router.push(slug ? `/entreprise/${slug}` : "/coordinateur");
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
        formData.append("name",             name.value);
        formData.append("email",            emailReg.value);
        formData.append("password",         passwordReg.value);
        formData.append("entreprise",       entreprise.value);
        if (telephone.value)    formData.append("telephone",        telephone.value);
        if (adresse.value)      formData.append("adresse",          adresse.value);
        if (ville.value)        formData.append("ville",            ville.value);
        if (npa.value)          formData.append("npa",              npa.value);
        if (domaine.value)      formData.append("domaine",          domaine.value);
        if (nbEmployes.value)   formData.append("nb_employes",      nbEmployes.value);
        if (poste.value)        formData.append("poste",            poste.value);
        if (couleurPrimaire.value) formData.append("couleur_primaire", couleurPrimaire.value);
        if (logoFile.value)     formData.append("logo",             logoFile.value);

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
        newSlug.value           = data.slug;
        newEntrepriseName.value = entreprise.value;
        mode.value = "success";
    } catch (e) {
        errorMsg.value = e.message;
    } finally {
        loading.value = false;
    }
}

const inputStyle = `
    border: 1px solid #dde4e3;
    border-radius: 8px;
    font-size: 15px;
    color: var(--default-titles);
    background: white;
    outline: none;
    width: 100%;
    padding: 10px 14px;
    font-family: inherit;
    transition: border-color 0.15s;
`;

const selectStyle = inputStyle + `
    appearance: none;
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 32px;
`;
</script>

<template>
    <div
        class="min-h-screen flex"
        style="background: var(--light-grey)"
    >
        <!-- Panneau gauche -->
        <div
            class="hidden lg:flex flex-col justify-between px-14 py-12"
            style="width: 42%; background: linear-gradient(135deg, var(--color-default-blue-59), var(--color-default-green)); flex-shrink: 0"
        >
            <div>
                <RouterLink
                    to="/"
                    style="text-decoration: none"
                    class="flex items-center gap-2"
                >
                    <span class="font-extrabold text-xl" style="color: var(--default-titles)">HUG</span>
                    <div class="w-px h-5 mx-1" style="background: rgba(44,65,64,0.3)"></div>
                    <span class="text-base font-semibold" style="color: var(--default-titles)">Don du sang</span>
                </RouterLink>
            </div>

            <div>
                <template v-if="mode === 'login'">
                    <p class="font-black mb-4" style="font-size: 40px; color: var(--default-titles); line-height: 1.2">
                        Ensemble,<br />sauvons des vies.
                    </p>
                    <p style="color: rgba(44,65,64,0.7); line-height: 1.7; max-width: 340px">
                        Rejoignez les entreprises genevoises engagées pour le don du sang et donnez un impact concret à votre politique RSE.
                    </p>
                </template>
                <template v-else-if="mode === 'admin'">
                    <p class="font-black mb-4" style="font-size: 36px; color: var(--default-titles); line-height: 1.2">
                        Administration<br />HUG — CTS
                    </p>
                    <p style="font-size: 15px; color: rgba(44,65,64,0.65); line-height: 1.7; max-width: 300px">
                        Accès réservé à l'équipe du Centre de Transfusion Sanguine des Hôpitaux Universitaires de Genève.
                    </p>
                </template>
                <template v-else-if="mode === 'success'">
                    <div style="display: flex; flex-direction: column; gap: 1.5rem; max-width: 320px">
                        <div style="display: flex; flex-direction: column; gap: 0.5rem">
                            <div v-for="item in [
                                { icon: 'check_circle', text: 'Espace co-brandé créé' },
                                { icon: 'palette', text: 'Logo &amp; couleurs configurés' },
                                { icon: 'group', text: 'Page quiz personnalisée' },
                                { icon: 'emoji_events', text: 'Trophée de la générosité activé' },
                            ]" :key="item.text" style="display: flex; align-items: center; gap: 0.75rem">
                                <span class="material-symbols-outlined" style="font-size: 20px; color: var(--default-titles)">{{ item.icon }}</span>
                                <span style="font-size: 15px; color: var(--default-titles); font-weight: 600" v-html="item.text"></span>
                            </div>
                        </div>
                        <p style="font-size: 14px; color: rgba(44,65,64,0.65); line-height: 1.6">
                            Notre équipe du CTS vous recontactera sous 48h pour organiser votre première campagne de collecte.
                        </p>
                    </div>
                </template>
                <template v-else>
                    <!-- Étapes -->
                    <div class="flex flex-col gap-5 mb-10">
                        <div
                            v-for="s in [
                                { num: 1, label: 'Votre entreprise' },
                                { num: 2, label: 'Votre profil' },
                                { num: 3, label: 'Apparence' },
                            ]"
                            :key="s.num"
                            class="flex items-center gap-4"
                        >
                            <div
                                class="flex items-center justify-center rounded-full font-bold flex-shrink-0"
                                style="width: 32px; height: 32px; font-size: 14px"
                                :style="
                                    step === s.num
                                        ? 'background: var(--color-default-red); color: white'
                                        : step > s.num
                                        ? 'background: var(--default-titles); color: white'
                                        : 'background: rgba(44,65,64,0.15); color: rgba(44,65,64,0.4)'
                                "
                            >{{ s.num }}</div>
                            <span
                                class="font-semibold"
                                style="font-size: 15px"
                                :style="
                                    step === s.num
                                        ? 'color: var(--default-titles)'
                                        : step > s.num
                                        ? 'color: var(--default-titles)'
                                        : 'color: rgba(44,65,64,0.4)'
                                "
                            >{{ s.label }}</span>
                        </div>
                    </div>
                    <p style="font-size: 14px; color: rgba(44,65,64,0.65); line-height: 1.6; max-width: 300px">
                        Remplissez ce formulaire et notre équipe vous recontacte sous 48h pour organiser votre première campagne.
                    </p>
                </template>
            </div>

            <div>
                <p style="font-size: 13px; color: rgba(44,65,64,0.45)">
                    © {{ new Date().getFullYear() }} Hôpitaux Universitaires Genève
                </p>
            </div>
        </div>

        <!-- Panneau droit -->
        <div class="flex-1 flex items-center justify-center px-6 py-12">
            <div class="w-full" style="max-width: 480px">

                <!-- Logo mobile -->
                <RouterLink
                    to="/"
                    class="lg:hidden flex items-center gap-2 mb-10"
                    style="text-decoration: none"
                >
                    <span class="font-extrabold text-xl" style="color: var(--default-titles)">HUG</span>
                    <div class="w-px h-5 mx-1" style="background: rgba(44,65,64,0.3)"></div>
                    <span class="text-base font-semibold" style="color: var(--color-default-red)">Don du sang</span>
                </RouterLink>

                <!-- Erreur -->
                <div
                    v-if="errorMsg && mode !== 'success'"
                    class="mb-5 px-4 py-3 rounded-lg"
                    style="background: #fee2e2; color: #991b1b; font-size: 14px"
                >
                    {{ errorMsg }}
                </div>

                <!-- ══════════════════ CONNEXION ══════════════════ -->
                <template v-if="mode === 'login'">
                    <h1 class="font-bold mb-1" style="font-size: 26px; color: var(--default-titles)">
                        Connexion
                    </h1>
                    <p class="mb-8" style="font-size: 15px; color: var(--default-text)">
                        Accédez à votre espace entreprise.
                    </p>

                    <form @submit.prevent="handleLogin" class="flex flex-col gap-5">
                        <div>
                            <label class="block font-semibold mb-1" style="font-size: 14px; color: var(--default-titles)">
                                Adresse email
                            </label>
                            <input
                                v-model="email"
                                type="email"
                                required
                                placeholder="vous@entreprise.com"
                                :style="inputStyle"
                            />
                        </div>
                        <div>
                            <label class="block font-semibold mb-1" style="font-size: 14px; color: var(--default-titles)">
                                Mot de passe
                            </label>
                            <input
                                v-model="password"
                                type="password"
                                required
                                placeholder="••••••••"
                                :style="inputStyle"
                            />
                        </div>
                        <button
                            type="submit"
                            :disabled="loading"
                            class="btn btn-filled-red w-full"
                            style="border: none; cursor: pointer; margin-top: 4px"
                            :style="loading ? 'opacity:0.6;cursor:not-allowed' : ''"
                        >
                            {{ loading ? "Connexion…" : "Se connecter" }}
                        </button>
                    </form>

                    <!-- Séparateur -->
                    <div class="flex items-center gap-4 my-7">
                        <div class="flex-1" style="height: 1px; background: #dde4e3"></div>
                        <span style="font-size: 13px; color: #c0cac9; white-space: nowrap">Pas encore partenaire ?</span>
                        <div class="flex-1" style="height: 1px; background: #dde4e3"></div>
                    </div>

                    <button
                        @click="startRegister"
                        class="w-full font-semibold rounded-full py-3 transition hover:opacity-75"
                        style="
                            background: white;
                            border: 2px solid var(--default-titles);
                            color: var(--default-titles);
                            cursor: pointer;
                        "
                    >
                        Rejoindre le programme →
                    </button>

                    <!-- Lien admin discret -->
                    <div class="text-center mt-8">
                        <button
                            @click="switchToAdmin"
                            class="captions hover:opacity-70 transition"
                            style="background: none; border: none; cursor: pointer; color: #c0cac9; text-decoration: underline; text-underline-offset: 3px"
                        >
                            Accès administrateur
                        </button>
                    </div>
                </template>

                <!-- ══════════════════ ADMIN ══════════════════ -->
                <template v-if="mode === 'admin'">
                    <button
                        @click="backToLogin"
                        class="flex items-center gap-2 mb-6 transition hover:opacity-60"
                        style="background: none; border: none; cursor: pointer; padding: 0; color: var(--default-text); font-size: 14px"
                    >
                        ← Retour
                    </button>

                    <h1 class="font-bold mb-1" style="font-size: 26px; color: var(--default-titles)">
                        Administration
                    </h1>
                    <p class="mb-8" style="font-size: 15px; color: var(--default-text)">
                        Espace réservé à l'équipe CTS des HUG.
                    </p>

                    <form @submit.prevent="handleLogin" class="flex flex-col gap-5">
                        <div>
                            <label class="block font-semibold mb-1" style="font-size: 14px; color: var(--default-titles)">
                                Adresse email
                            </label>
                            <input
                                v-model="email"
                                type="email"
                                required
                                placeholder="admin@cts-hug.ch"
                                :style="inputStyle"
                            />
                        </div>
                        <div>
                            <label class="block font-semibold mb-1" style="font-size: 14px; color: var(--default-titles)">
                                Mot de passe
                            </label>
                            <input
                                v-model="password"
                                type="password"
                                required
                                placeholder="••••••••"
                                :style="inputStyle"
                            />
                        </div>
                        <button
                            type="submit"
                            :disabled="loading"
                            class="w-full text-white font-semibold rounded-full py-3 transition hover:opacity-80"
                            style="background: var(--default-titles); border: none; cursor: pointer; margin-top: 4px"
                            :style="loading ? 'opacity:0.6;cursor:not-allowed' : ''"
                        >
                            {{ loading ? "Connexion…" : "Accéder au panneau" }}
                        </button>
                    </form>
                </template>

                <!-- ══════════════════ INSCRIPTION ══════════════════ -->
                <template v-else-if="mode === 'register'">
                    <!-- Header étape -->
                    <button
                        @click="step > 1 ? step-- : backToLogin()"
                        class="flex items-center gap-2 mb-6 transition hover:opacity-60"
                        style="background: none; border: none; cursor: pointer; padding: 0; color: var(--default-text); font-size: 14px"
                    >
                        ← {{ step > 1 ? 'Étape précédente' : 'Retour à la connexion' }}
                    </button>

                    <!-- Indicateur d'étapes mobile -->
                    <div class="lg:hidden flex items-center gap-2 mb-6">
                        <div
                            v-for="s in 3"
                            :key="s"
                            class="h-1 rounded-full flex-1 transition-all"
                            :style="s <= step ? 'background: var(--color-default-red)' : 'background: #dde4e3'"
                        ></div>
                    </div>

                    <!-- ── Étape 1 : Votre entreprise ── -->
                    <template v-if="step === 1">
                        <h1 class="font-bold mb-1" style="font-size: 24px; color: var(--default-titles)">
                            Parlez-nous de votre entreprise
                        </h1>
                        <p class="mb-7" style="font-size: 14px; color: var(--default-text); line-height: 1.6">
                            Ces informations permettent au CTS de configurer la page de votre future entreprise.
                        </p>

                        <div class="flex flex-col gap-4">
                            <!-- Nom + Taille -->
                            <div class="grid grid-cols-2 gap-3">
                                <div class="col-span-2">
                                    <label class="block font-semibold mb-1" style="font-size: 13px; color: var(--default-titles)">
                                        Nom de l'entreprise <span style="color:var(--color-default-red)">*</span>
                                    </label>
                                    <input v-model="entreprise" type="text" required placeholder="Ma Société SA" :style="inputStyle" />
                                </div>
                                <div>
                                    <label class="block font-semibold mb-1" style="font-size: 13px; color: var(--default-titles)">
                                        Secteur d'activité
                                    </label>
                                    <select
                                        v-model="domaine"
                                        :style="selectStyle"
                                    >
                                        <option value="">Choisir…</option>
                                        <option v-for="opt in domaineOptions" :key="opt.value" :value="opt.value">
                                            {{ opt.label }}
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-semibold mb-1" style="font-size: 13px; color: var(--default-titles)">
                                        Nombre d'employés
                                    </label>
                                    <input v-model="nbEmployes" type="number" min="1" placeholder="ex. 250" :style="inputStyle" />
                                </div>
                            </div>

                            <!-- Adresse -->
                            <div>
                                <label class="block font-semibold mb-1" style="font-size: 13px; color: var(--default-titles)">
                                    Adresse
                                </label>
                                <input v-model="adresse" type="text" placeholder="Rue de la Paix 12" :style="inputStyle" />
                            </div>

                            <!-- Ville + NPA -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-semibold mb-1" style="font-size: 13px; color: var(--default-titles)">
                                        Ville
                                    </label>
                                    <input v-model="ville" type="text" placeholder="Genève" :style="inputStyle" />
                                </div>
                                <div>
                                    <label class="block font-semibold mb-1" style="font-size: 13px; color: var(--default-titles)">
                                        Code postal
                                    </label>
                                    <input v-model="npa" type="text" placeholder="1200" :style="inputStyle" />
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
                        <h1 class="font-bold mb-1" style="font-size: 24px; color: var(--default-titles)">
                            Parlez-nous de vous
                        </h1>
                        <p class="mb-7" style="font-size: 14px; color: var(--default-text); line-height: 1.6">
                            En tant que coordinateur principal, vous ferez le lien entre les HUG et votre entreprise.
                        </p>

                        <div class="flex flex-col gap-4">
                            <div class="grid grid-cols-2 gap-3">
                                <div class="col-span-2">
                                    <label class="block font-semibold mb-1" style="font-size: 13px; color: var(--default-titles)">
                                        Nom et prénom <span style="color:var(--color-default-red)">*</span>
                                    </label>
                                    <input v-model="name" type="text" required placeholder="Prénom Nom" :style="inputStyle" />
                                </div>
                                <div>
                                    <label class="block font-semibold mb-1" style="font-size: 13px; color: var(--default-titles)">
                                        Poste
                                    </label>
                                    <input v-model="poste" type="text" placeholder="Responsable RH" :style="inputStyle" />
                                </div>
                                <div>
                                    <label class="block font-semibold mb-1" style="font-size: 13px; color: var(--default-titles)">
                                        Téléphone
                                    </label>
                                    <input v-model="telephone" type="tel" placeholder="+41 79 000 00 00" :style="inputStyle" />
                                </div>
                                <div class="col-span-2">
                                    <label class="block font-semibold mb-1" style="font-size: 13px; color: var(--default-titles)">
                                        Email professionnel <span style="color:var(--color-default-red)">*</span>
                                    </label>
                                    <input v-model="emailReg" type="email" required placeholder="vous@entreprise.com" :style="inputStyle" />
                                </div>
                                <div class="col-span-2">
                                    <label class="block font-semibold mb-1" style="font-size: 13px; color: var(--default-titles)">
                                        Mot de passe <span style="color:var(--color-default-red)">*</span>
                                    </label>
                                    <input v-model="passwordReg" type="password" required placeholder="8 caractères minimum" :style="inputStyle" />
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
                        <h1 class="font-bold mb-1" style="font-size: 24px; color: var(--default-titles)">
                            Informations graphiques
                        </h1>
                        <p class="mb-7" style="font-size: 14px; color: var(--default-text); line-height: 1.6">
                            Ces éléments visuels personnalisent votre interface et vos kits de communication. <span style="color: #c0cac9">(optionnel)</span>
                        </p>

                        <div class="flex flex-col gap-5">
                            <!-- Logo -->
                            <div>
                                <label class="block font-semibold mb-1" style="font-size: 13px; color: var(--default-titles)">
                                    Logo
                                </label>
                                <div
                                    class="flex items-center gap-3"
                                    style="border: 1px solid #dde4e3; border-radius: 8px; padding: 10px 14px; background: white"
                                >
                                    <span style="font-size: 14px; color: #c0cac9; flex: 1">
                                        {{ logoFile ? logoFile.name : '.jpg, .png, .svg' }}
                                    </span>
                                    <label
                                        class="font-semibold rounded-full px-4 py-1 transition hover:opacity-75 cursor-pointer"
                                        style="background: var(--light-grey); color: var(--default-titles); font-size: 13px; white-space: nowrap"
                                    >
                                        Parcourir
                                        <input
                                            type="file"
                                            accept=".jpg,.jpeg,.png,.svg"
                                            class="hidden"
                                            @change="e => logoFile = e.target.files[0]"
                                        />
                                    </label>
                                </div>
                            </div>

                            <!-- Couleur principale -->
                            <div>
                                <label class="block font-semibold mb-1" style="font-size: 13px; color: var(--default-titles)">
                                    Couleur principale
                                </label>
                                <div
                                    class="flex items-center gap-3"
                                    style="border: 1px solid #dde4e3; border-radius: 8px; padding: 10px 14px; background: white"
                                >
                                    <input
                                        v-model="couleurPrimaire"
                                        type="color"
                                        style="width: 28px; height: 28px; border: none; padding: 0; cursor: pointer; background: none; border-radius: 4px"
                                    />
                                    <input
                                        v-model="couleurPrimaire"
                                        type="text"
                                        placeholder="var(--color-default-red)"
                                        style="border: none; outline: none; font-size: 15px; color: var(--default-titles); flex: 1; font-family: monospace"
                                    />
                                </div>
                            </div>
                        </div>

                        <button
                            @click="handleRegister"
                            :disabled="loading"
                            class="btn btn-filled-red w-full mt-7"
                            style="border: none; cursor: pointer"
                            :style="loading ? 'opacity:0.6;cursor:not-allowed' : ''"
                        >
                            {{ loading ? "Création de votre espace…" : "Créer mon espace entreprise" }}
                        </button>

                        <p class="text-center mt-5" style="font-size: 13px; color: #c0cac9; line-height: 1.6">
                            En créant un compte vous acceptez que vos données soient utilisées dans le cadre du programme de don du sang des HUG.
                        </p>
                    </template>
                </template>

                <!-- ══════════════════ SUCCÈS ══════════════════ -->
                <template v-else-if="mode === 'success'">
                    <div style="text-align: center">
                        <!-- Icône succès -->
                        <div
                            style="
                                width: 72px; height: 72px; border-radius: 50%;
                                background: #d1fae5; display: flex; align-items: center;
                                justify-content: center; margin: 0 auto 1.5rem;
                            "
                        >
                            <span class="material-symbols-outlined" style="font-size: 36px; color: #16a34a">check_circle</span>
                        </div>

                        <h1 class="font-bold mb-2" style="font-size: 24px; color: var(--default-titles); line-height: 1.3">
                            Votre espace a bien été créé !
                        </h1>
                        <p style="font-size: 15px; color: var(--default-text); line-height: 1.65; margin-bottom: 2rem">
                            Bienvenue dans le programme <strong>Trophée de la générosité</strong>.<br />
                            Votre site co-brandé
                            <strong style="color: var(--default-titles)">{{ newEntrepriseName }}</strong>
                            est prêt.
                        </p>

                        <!-- Récap -->
                        <div
                            style="
                                background: var(--light-grey); border-radius: 14px; padding: 1.25rem 1.5rem;
                                margin-bottom: 2rem; text-align: left; display: flex; flex-direction: column; gap: 0.75rem
                            "
                        >
                            <div v-for="item in [
                                { icon: 'language',      text: 'Site co-brandé avec vos couleurs' },
                                { icon: 'quiz',          text: 'Quiz d\'éligibilité personnalisé' },
                                { icon: 'emoji_events',  text: 'Trophée de la générosité activé' },
                                { icon: 'support_agent', text: 'Équipe CTS notifiée sous 48h' },
                            ]" :key="item.text"
                                style="display: flex; align-items: center; gap: 0.75rem"
                            >
                                <span class="material-symbols-outlined" style="font-size: 18px; color: var(--color-default-blue-59)">{{ item.icon }}</span>
                                <span style="font-size: 14px; color: var(--default-titles); font-weight: 500">{{ item.text }}</span>
                            </div>
                        </div>

                        <button
                            @click="router.push(`/entreprise/${newSlug}`)"
                            class="btn btn-filled-red w-full"
                            style="border: none; cursor: pointer; margin-bottom: 1rem"
                        >
                            Accéder à mon espace
                            <span class="material-symbols-outlined" style="font-size: 18px; vertical-align: middle; margin-left: 4px">arrow_forward</span>
                        </button>

                        <p style="font-size: 13px; color: #c0cac9">
                            Vous pouvez fermer cette page et y revenir à tout moment depuis<br />
                            <span style="color: var(--default-text); font-weight: 600">hug.ch/entreprise/{{ newSlug }}</span>
                        </p>
                    </div>
                </template>

            </div>
        </div>
    </div>
</template>
