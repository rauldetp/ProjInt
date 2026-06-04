<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import { useCobrandStore } from '../stores/cobrand';
import { useAuthStore } from '../stores/auth';

const props = defineProps({
    collecte: { type: Object, default: null },
});

const route = useRoute();
const cobrand = useCobrandStore();
const auth = useAuthStore();

const slug = computed(() => route.params.slug);
const brandColor = computed(() => cobrand.couleurPrimaire || '#e60f48');

// Depuis le site coobrandé, "Coin entreprise" = espace propre à cette entreprise
const coinEntrepriseLink = computed(() => {
    if (auth.isAdmin) return '/entreprises';
    if (auth.isCoordinateur) return `/entreprise/${slug.value}/espace`;
    return '/login';
});

function isActive(path) {
    return route.path === path;
}

function linkStyle(path) {
    const active = isActive(path);
    return {
        color: active ? brandColor.value : '#2c4140',
        fontWeight: active ? '700' : '500',
        textDecoration: 'none',
    };
}
</script>

<template>
    <header class="co-navbar">
        <div class="co-navbar-inner">

            <!-- Brand -->
            <div class="co-brand">
                <RouterLink to="/" class="brand-hug">HUG</RouterLink>
                <span class="brand-sep">|</span>
                <span class="brand-subtitle">Don du sang</span>
                <span class="brand-cross">×</span>
                <span class="brand-company" :style="{ color: brandColor }">
                    <img v-if="cobrand.logo" :src="cobrand.logo" :alt="cobrand.nom" class="brand-logo" />
                    <span v-else>{{ cobrand.nom }}</span>
                </span>
            </div>

            <!-- Links -->
            <nav class="co-nav-links">
                <RouterLink :to="`/entreprise/${slug}`" :style="linkStyle(`/entreprise/${slug}`)">Accueil</RouterLink>
                <RouterLink :to="`/entreprise/${slug}/label`" :style="linkStyle(`/entreprise/${slug}/label`)">Label CTS</RouterLink>
                <RouterLink :to="`/entreprise/${slug}/trophee`" :style="linkStyle(`/entreprise/${slug}/trophee`)">Trophée de la générosité</RouterLink>
                <RouterLink :to="coinEntrepriseLink" :style="{ color: '#2c4140', fontWeight: '500', textDecoration: 'none' }">Coin entreprise</RouterLink>
                <RouterLink :to="`/entreprise/${slug}/quiz`" :style="linkStyle(`/entreprise/${slug}/quiz`)">Quiz d'éligibilité</RouterLink>
            </nav>

            <!-- CTA -->
            <RouterLink
                v-if="collecte?.active"
                :to="`/entreprise/${slug}/inscription`"
                class="co-navbar-cta"
                :style="{ color: brandColor, borderColor: brandColor }"
            >
                S'inscrire à la collecte
            </RouterLink>
            <div v-else class="co-navbar-cta-placeholder"></div>

        </div>
    </header>
</template>

<style scoped>
.co-navbar {
    background: white;
    border-bottom: 1px solid #f2f4f3;
    height: 76px;
    position: sticky;
    top: 0;
    z-index: 50;
    flex-shrink: 0;
}
.co-navbar-inner {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 2rem;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 2rem;
}

/* Brand */
.co-brand {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    flex-shrink: 0;
}
.brand-hug {
    font-weight: 800;
    font-size: 1.2rem;
    color: #2c4140;
    text-decoration: none;
}
.brand-sep {
    color: rgba(44, 65, 64, 0.3);
    font-size: 1.1rem;
    margin: 0 0.2rem;
}
.brand-subtitle {
    font-size: 0.9rem;
    font-weight: 600;
    color: #497371;
}
.brand-cross {
    color: rgba(44, 65, 64, 0.3);
    font-size: 1.1rem;
    margin: 0 0.2rem;
}
.brand-company {
    font-weight: 700;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
}
.brand-logo {
    max-height: 28px;
    object-fit: contain;
}

/* Nav links */
.co-nav-links {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-left: auto;
}
.co-nav-links a {
    font-size: 0.9rem;
    white-space: nowrap;
    transition: opacity 0.15s;
}
.co-nav-links a:hover {
    opacity: 0.65;
}

/* CTA */
.co-navbar-cta {
    font-size: 0.85rem;
    font-weight: 600;
    border: 2px solid;
    border-radius: 9999px;
    padding: 0.4rem 1.1rem;
    text-decoration: none;
    transition: opacity 0.15s;
    flex-shrink: 0;
    white-space: nowrap;
}
.co-navbar-cta:hover { opacity: 0.75; }
.co-navbar-cta-placeholder {
    width: 160px;
    flex-shrink: 0;
}

@media (max-width: 960px) {
    .co-nav-links { display: none; }
    .co-navbar-cta-placeholder { display: none; }
}
</style>
