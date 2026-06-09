<script setup>
import { computed } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useCobrandStore } from "../stores/cobrand";
import { useAuthStore } from "../stores/auth";

const props = defineProps({
    collecte: { type: Object, default: null },
});

const route = useRoute();
const router = useRouter();
const cobrand = useCobrandStore();
const auth = useAuthStore();

function handleLogout() {
    auth.logout();
    router.push("/");
}

const slug = computed(() => route.params.slug);
const brandColor = computed(
    () => cobrand.couleurPrimaire || "var(--color-default-red)",
);

// Depuis le site coobrandé, "Espace entreprise" = espace propre à cette entreprise
const coinEntrepriseLink = computed(() => {
    if (auth.isAdmin) return "/admin";
    if (auth.isCoordinateur) return `/entreprise/${slug.value}/espace`;
    return "/login";
});

function isActive(path) {
    return route.path === path;
}

function linkStyle(path) {
    const active = isActive(path);
    return {
        color: active ? brandColor.value : "var(--default-titles)",
        fontWeight: active ? "700" : "400",
        textDecoration: "none",
    };
}
</script>

<template>
    <header class="co-navbar">
        <div class="co-navbar-inner max-w-7xl px-8">
            <!-- Brand -->
            <div class="co-brand">
                <RouterLink to="/" class="brand-hug"
                    ><img :src="'/images/logo_hug_h_quadri.png'" alt="Logo HUG"
                /></RouterLink>
                <span class="brand-sep">X</span>
                <span class="brand-company" :style="{ color: brandColor }">
                    <img
                        v-if="cobrand.logo"
                        :src="cobrand.logo"
                        :alt="cobrand.nom"
                        class="brand-logo"
                    />
                    <template v-else>{{ cobrand.nom }}</template>
                </span>
            </div>

            <!-- Links -->
            <nav class="co-nav-links">
                <RouterLink
                    :to="`/entreprise/${slug}/label`"
                    :style="linkStyle(`/entreprise/${slug}/label`)"
                    >Label CTS</RouterLink
                >
                <RouterLink
                    :to="`/entreprise/${slug}/trophee`"
                    :style="linkStyle(`/entreprise/${slug}/trophee`)"
                    >Trophée de la Générosité</RouterLink
                >
                <RouterLink
                    :to="coinEntrepriseLink"
                    :style="linkStyle(coinEntrepriseLink)"
                    >Espace entreprise</RouterLink
                >
                <RouterLink
                    :to="`/entreprise/${slug}/quiz`"
                    :style="linkStyle(`/entreprise/${slug}/quiz`)"
                    >Quiz d'éligibilité</RouterLink
                >
            </nav>

            <!-- CTA -->
            <div class="co-navbar-actions">
                <RouterLink
                    v-if="collecte?.active"
                    :to="`/entreprise/${slug}/inscription`"
                    class="btn"
                    :style="{ color: brandColor, borderColor: brandColor }"
                >
                    S'inscrire à la collecte
                </RouterLink>
                <button
                    class="btn"
                    :style="{ color: brandColor, borderColor: brandColor }"
                    @click="handleLogout"
                >
                    Déconnexion
                </button>
            </div>
        </div>
    </header>
</template>

<style scoped>
.co-navbar {
    background: white;
    border-bottom: 1px solid var(--light-grey);
    height: 76px;
    position: sticky;
    top: 0;
    z-index: 50;
    flex-shrink: 0;
}
.co-navbar-inner {
    margin: 0 auto;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
}

/* Brand */
.co-brand {
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

.brand-hug img, .brand-logo {
    max-height: 2.75rem;
    width: auto;
}

.brand-sep {
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
.co-nav-links {
    display: flex;
    align-items: center;
    gap: 1.75rem;
    margin-left: auto;
}
.co-nav-links a {
    font-size: 1rem;
    white-space: nowrap;
    transition: opacity 0.15s;
}
.co-nav-links a:hover {
    opacity: 0.65;
}

/* CTA */
.co-navbar-actions {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    flex-shrink: 0;
}
.co-navbar-actions .btn:hover {
    opacity: 0.85;
}

@media (max-width: 960px) {
    .co-nav-links {
        display: none;
    }
}
</style>
