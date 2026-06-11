<script setup>
import { computed, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useCobrandStore } from "../stores/cobrand";
import { useAuthStore } from "../stores/auth";

const props = defineProps({
    showLogout: { type: Boolean, default: false },
});

const route = useRoute();
const router = useRouter();
const cobrand = useCobrandStore();
const auth = useAuthStore();

// Mode cobrandé déterminé par la route (présence d'un slug d'entreprise),
// pas par l'état persisté du store : on reste générique sur /label, /trophee, etc.
const isCobrand = computed(() => !!route.params.slug);
const slug = computed(() => route.params.slug);

// Couleur d'accent : marque de l'entreprise en cobrandé, rouge HUG sinon.
const accent = computed(() =>
    isCobrand.value
        ? cobrand.couleurPrimaire || "var(--color-default-red)"
        : "var(--color-default-red)",
);

const labelLink = computed(() =>
    isCobrand.value ? `/entreprise/${slug.value}/label` : "/label",
);
const tropheeLink = computed(() =>
    isCobrand.value ? `/entreprise/${slug.value}/trophee` : "/trophee",
);
const quizLink = computed(() =>
    isCobrand.value ? `/entreprise/${slug.value}/quiz` : "/quiz",
);
const contactLink = computed(() =>
    isCobrand.value ? `/entreprise/${slug.value}/contact` : "/contact",
);

const espaceLink = computed(() => {
    if (auth.isAdmin) return "/admin";
    if (auth.isCoordinateur) {
        if (isCobrand.value) return `/entreprise/${slug.value}/coordinateur`;
        return auth.entrepriseSlug
            ? `/entreprise/${auth.entrepriseSlug}/coordinateur`
            : "/coordinateur";
    }
    return "/login";
});

function isActive(path) {
    return route.path === path;
}

function linkStyle(path) {
    return {
        color: isActive(path) ? accent.value : "var(--default-titles)",
        fontWeight: "700",
        textDecoration: "none",
    };
}

// Menu mobile (hamburger)
const mobileOpen = ref(false);
function closeMobile() {
    mobileOpen.value = false;
}

function handleLogout() {
    closeMobile();
    auth.logout();
    router.push(isCobrand.value ? "/" : "/login");
}
</script>

<template>
    <header class="app-navbar">
        <div class="app-navbar-inner max-w-7xl px-4 md:px-8">
            <!-- Brand -->
            <RouterLink
                :to="isCobrand ? `/entreprise/${slug}` : '/'"
                class="app-brand"
            >
                <span class="brand-hug">
                    <img
                        :src="'/images/logo_hug_h_quadri.png'"
                        alt="Logo HUG"
                    />
                </span>

                <!-- Cobrandé : séparateur "X" + entreprise -->
                <template v-if="isCobrand">
                    <span class="brand-sep-x">X</span>
                    <span class="brand-company" :style="{ color: accent }">
                        <img
                            v-if="cobrand.logo"
                            :src="cobrand.logo"
                            :alt="cobrand.nom"
                            class="brand-logo"
                        />
                        <template v-else>{{ cobrand.nom }}</template>
                    </span>
                </template>

                <!-- Générique : séparateur + "Don du sang" -->
                <template v-else>
                    <span class="brand-sep"></span>
                    <span style="color: var(--color-default-red)"
                        >Don du sang</span
                    >
                </template>
            </RouterLink>

            <!-- Links -->
            <nav class="app-nav-links">
                <RouterLink :to="labelLink" :style="linkStyle(labelLink)"
                    >Label CTS</RouterLink
                >
                <RouterLink :to="tropheeLink" :style="linkStyle(tropheeLink)"
                    >Trophée de la Générosité</RouterLink
                >
                <RouterLink :to="quizLink" :style="linkStyle(quizLink)"
                    >Quiz d'éligibilité</RouterLink
                >
                <RouterLink :to="espaceLink" :style="linkStyle(espaceLink)"
                    >Espace entreprise</RouterLink
                >
                <RouterLink :to="contactLink" :style="linkStyle(contactLink)"
                    >Contact</RouterLink
                >
            </nav>

            <!-- CTA -->
            <div class="app-navbar-actions">
                <button
                    v-if="(showLogout || isCobrand) && auth.isLoggedIn"
                    class="btn"
                    :style="{ color: accent, borderColor: accent }"
                    @click="handleLogout"
                >
                    Déconnexion
                </button>
                <button
                    class="app-burger"
                    aria-label="Menu"
                    @click="mobileOpen = !mobileOpen"
                >
                    <span class="material-symbols-outlined">{{
                        mobileOpen ? "close" : "menu"
                    }}</span>
                </button>
            </div>
        </div>

        <!-- Menu mobile -->
        <nav v-if="mobileOpen" class="app-mobile-menu">
            <RouterLink :to="labelLink" :style="linkStyle(labelLink)" @click="closeMobile"
                >Label CTS</RouterLink
            >
            <RouterLink :to="tropheeLink" :style="linkStyle(tropheeLink)" @click="closeMobile"
                >Trophée de la Générosité</RouterLink
            >
            <RouterLink :to="quizLink" :style="linkStyle(quizLink)" @click="closeMobile"
                >Quiz d'éligibilité</RouterLink
            >
            <RouterLink :to="espaceLink" :style="linkStyle(espaceLink)" @click="closeMobile"
                >Espace entreprise</RouterLink
            >
            <RouterLink :to="contactLink" :style="linkStyle(contactLink)" @click="closeMobile"
                >Contact</RouterLink
            >
            <button
                v-if="(showLogout || isCobrand) && auth.isLoggedIn"
                class="btn btn-outlined-red"
                style="margin-top: 0.75rem"
                @click="handleLogout"
            >
                Déconnexion
            </button>
        </nav>
    </header>
</template>

<style scoped>
.app-navbar {
    background: white;
    border-bottom: 1px solid var(--light-grey);
    height: 76px;
    position: sticky;
    top: 0;
    z-index: 50;
    flex-shrink: 0;
}
.app-navbar-inner {
    margin: 0 auto;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
}

/* Brand */
.app-brand {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-shrink: 0;
    text-decoration: none;
}
.brand-hug {
    font-weight: 800;
    font-size: 1.25rem;
    color: var(--default-titles);
    text-decoration: none;
}
.brand-hug img,
.brand-logo {
    max-height: 2.75rem;
    width: auto;
}
.brand-sep {
    width: 1px;
    height: 20px;
    background: var(--default-text);
    margin: 0 0.2rem;
}
.brand-sep-x {
    color: var(--default-text);
    font-size: 1.1rem;
    margin: 0 0.2rem;
}
.brand-company {
    font-weight: 700;
    font-size: 1rem;
    display: flex;
    align-items: center;
}

/* Nav links */
.app-nav-links {
    display: flex;
    align-items: center;
    gap: 1.75rem;
    margin-left: auto;
}
.app-nav-links a {
    font-size: 1rem;
    white-space: nowrap;
    transition: opacity 0.15s;
}
.app-nav-links a:hover {
    opacity: 0.65;
}

/* CTA */
.app-navbar-actions {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    flex-shrink: 0;
}
.app-navbar-actions .btn:hover {
    opacity: 0.85;
}

/* Hamburger (visible en mobile uniquement) */
.app-burger {
    display: none;
    align-items: center;
    justify-content: center;
    background: none;
    border: none;
    cursor: pointer;
    color: var(--default-titles);
    padding: 0.25rem;
}
.app-mobile-menu {
    display: none;
}

@media (max-width: 960px) {
    .app-nav-links {
        display: none;
    }
    .app-burger {
        display: inline-flex;
    }
    /* Pas de bouton Déconnexion dans la barre mobile (cf. maquettes) */
    .app-navbar-actions .btn {
        display: none;
    }
    .app-mobile-menu {
        display: flex;
        flex-direction: column;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border-bottom: 1px solid var(--light-grey);
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.08);
        padding: 0.5rem 2rem 1rem;
    }
    .app-mobile-menu a {
        padding: 0.7rem 0;
        font-size: 1rem;
        border-top: 1px solid var(--light-grey);
    }
    .app-mobile-menu a:first-child {
        border-top: none;
    }
}
</style>
